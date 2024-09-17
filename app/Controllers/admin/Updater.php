<?php

namespace App\Controllers\admin;

use CodeIgniter\Database\Query;
use ZipArchive;

class Updater extends Admin
{
    public function index()
    {
        $db = \Config\Database::connect();
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['title'] = 'Updater | Admin Panel';
            $this->data['main_page'] = 'updater';
            $db = \Config\Database::connect();
            $this->data['version'] = $db->table('updates')->select('*')->orderBy('id', 'DESC')->get(1)->getResult();

            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function is_dir_empty($dir)
    {
        if (!is_readable($dir)) {
            return null;
        }

        return (count(scandir($dir)) == 2);
    }
    public function upload_update_file()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            helper('filesystem');
            $db = \Config\Database::connect();
            $migrate = \Config\Services::migrations();
            if (!empty($_FILES['update_file']['name'][0])) {
                if (!file_exists(FCPATH . UPDATE_PATH)) {

                    mkdir(FCPATH . UPDATE_PATH, 0777, true);
                }
                $uploadData = $this->request->getFile('update_file.0');
                $ext = trim(strtolower($uploadData->getClientExtension()));
                if ($ext != "zip") {
                    $response = [
                        "error" => true,
                        "message" => "Please insert a valid Zip File.",
                        'data' => [],
                        "csrfName" => csrf_token(),
                        "csrfHash" => csrf_hash(),
                    ];
                    return $this->response->setJSON($response);
                    die();
                }
                if ($uploadData->move(FCPATH . UPDATE_PATH)) {

                    $filename = $uploadData->getName();
                    ## Extract the zip file ---- start
                    $zip = new ZipArchive();
                    $res = $zip->open(FCPATH . UPDATE_PATH . $filename);

                    if ($res === true) {
                        // Unzip path
                        $extractpath = FCPATH . UPDATE_PATH;
                        // Extract file
                        $zip->extractTo($extractpath);
                        $zip->close();
                        unlink(FCPATH . UPDATE_PATH . $filename);
                        if (file_exists(UPDATE_PATH . "package.json") || file_exists(UPDATE_PATH . "plugin/package.json")) {

                            /* Plugin / Module installer script */
                            $sub_directory = (file_exists(UPDATE_PATH . "plugin/package.json")) ? "plugin/" : "";
                            if (file_exists(UPDATE_PATH . $sub_directory . "package.json")) {
                                $package_data = file_get_contents(UPDATE_PATH . $sub_directory . "package.json");
                                $package_data = json_decode($package_data, true);

                                if (!empty($package_data)) {
                                    /* Folders Creation - check if folders.json is set if yes then create folders listed in that file */
                                    if (isset($package_data['folders']) && !empty($package_data['folders'])) {
                                        /* create folders in the destination as set in the file */
                                        if (file_exists(UPDATE_PATH . $sub_directory . $package_data['folders'])) {
                                            $lines_array = file_get_contents(UPDATE_PATH . $sub_directory . $package_data['folders']);
                                            if (!empty($lines_array)) {
                                                $lines_array = json_decode($lines_array);
                                                foreach ($lines_array as $key => $line) {
                                                    if (!is_dir($line) && !file_exists($line)) {
                                                        mkdir($line, 0777, true);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    /* Files Copy - check if files.json is set if yes then copy the files listed in that file */
                                    if (isset($package_data['files']) && !empty($package_data['files'])) {
                                        /* copy files from source to destination as set in the file */
                                        if (file_exists(UPDATE_PATH . $sub_directory . $package_data['files'])) {
                                            $lines_array = file_get_contents(UPDATE_PATH . $sub_directory . $package_data['files']);
                                            if (!empty($lines_array)) {
                                                $lines_array = json_decode($lines_array);
                                                foreach ($lines_array as $key => $line) {
                                                    copy($sub_directory . $key, $line);
                                                }
                                            }
                                        }
                                    }
                                    /* ZIP Extraction - check if archives.json is set if yes then extract the files on destination as mentioned */
                                    if (isset($package_data['archives']) && !empty($package_data['archives'])) {
                                        /* extract the archives in the destination folder as set in the file */
                                        if (file_exists(UPDATE_PATH . $sub_directory . $package_data['archives'])) {
                                            $lines_array = file_get_contents(UPDATE_PATH . $sub_directory . $package_data['archives']);
                                            if (!empty($lines_array)) {
                                                $lines_array = json_decode($lines_array);
                                                $zip = new ZipArchive;
                                                foreach ($lines_array as $source => $destination) {
                                                    $source = UPDATE_PATH . $sub_directory . $source;
                                                    $res = $zip->open($source);
                                                    if ($res === true) {
                                                        $destination = $source = $destination;
                                                        $zip->extractTo($destination);
                                                        $zip->close();
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if (is_dir(FCPATH . UPDATE_PATH . "\\app\\Database\\Migrations")
                                        && !$this->is_dir_empty(FCPATH . UPDATE_PATH . "\\app\\Database\\Migrations")
                                    ) {

                                        try {
                                            $migrate->latest();
                                            $check = false;
                                            if (isset($package_data['manual_queries']) && $package_data['manual_queries']) {
                                                if (isset($package_data['query_path']) && $package_data['query_path'] != "") {
                                                    $sql = file_get_contents(UPDATE_PATH . $sub_directory . $package_data['query_path']);
                                                    if ($sql != "") {
                                                        $db->query($sql);
                                                    }
                                                }
                                            }
                                        } catch (\Throwable$e) {
                                        }
                                    }

                                    delete_files(FCPATH . UPDATE_PATH, true);
                                    $response['error'] = false;
                                    $response['message'] = 'Congratulations! The ' . $package_data['name'] . ' is installed successfully on your system. ' . $package_data['message_on_success'];
                                    $response['csrfName'] = csrf_token();
                                    $response['csrfHash'] = csrf_hash();
                                    return $this->response->setJSON($response);
                                } else {
                                    $response['error'] = true;
                                    $response['message'] = 'Invalid plugin installer file!. No package data found / missing package data.';
                                    $response['csrfName'] = csrf_token();
                                    $response['csrfHash'] = csrf_hash();
                                    delete_files(FCPATH . UPDATE_PATH, true);
                                    return $this->response->setJSON($response);
                                }
                            } else {
                                $response['error'] = true;
                                $response['message'] = 'Invalid plugin installer file!. It seems like you are using some invalid file.';
                                $response['csrfName'] = csrf_token();
                                $response['csrfHash'] = csrf_hash();
                                delete_files(FCPATH . UPDATE_PATH, true);
                                return $this->response->setJSON($response);
                            }
                        } else if (file_exists(UPDATE_PATH . "folders.json") || file_exists(UPDATE_PATH . "update/folders.json")) {
                            /* System updater script starts here */

                            $system_info = get_system_update_info();

                            if (isset($system_info['is_updatable']) && $system_info['is_updatable'] == false) {
                                if (isset($system_info['db_current_version']) && $system_info['db_current_version'] == $system_info['file_current_version']) {
                                    $response['error'] = true;
                                    $response['message'] = 'Oops!. This version is already updated into your system. Try another one';
                                    $response['csrfName'] = csrf_token();
                                    $response['csrfHash'] = csrf_hash();
                                    delete_files(FCPATH . UPDATE_PATH, true);
                                    return $this->response->setJSON($response);
                                } else {
                                    if ($system_info['previous_error']) {
                                        $response['error'] = true;
                                        $response['message'] = 'Please update the system in a row.';
                                        $response['csrfName'] = csrf_token();
                                        $response['csrfHash'] = csrf_hash();
                                        delete_files(FCPATH . UPDATE_PATH, true);
                                        return $this->response->setJSON($response);
                                    }
                                    $response['error'] = true;
                                    $response['message'] = 'It seems like you are trying to update the system using wrong file.';
                                    $response['csrfName'] = csrf_token();
                                    $response['csrfHash'] = csrf_hash();
                                    delete_files(FCPATH . UPDATE_PATH, true);
                                    return $this->response->setJSON($response);
                                }
                            }

                            /**check if  folder.json is set if yes then create folders listed in there*/
                            $sub_directory = (file_exists(UPDATE_PATH . "update/folders.json")) ? "update/" : "";
                            if (file_exists(UPDATE_PATH . $sub_directory . "folders.json")) {
                                $lines_array = file_get_contents(UPDATE_PATH . $sub_directory . "folders.json");
                                $lines_array = json_decode($lines_array);
                                foreach ($lines_array as $key => $line) {
                                    if (!is_dir($line) && !file_exists($line)) {
                                        mkdir($line, 0777, true);
                                    }
                                }
                            }
                            /**this will check if files.json is set if yes thn copy listed files in that file */
                            if (file_exists(UPDATE_PATH . $sub_directory . "files.json")) {
                                $lines_array = file_get_contents(UPDATE_PATH . $sub_directory . "files.json");
                                $lines_array = json_decode($lines_array);
                                foreach ($lines_array as $key => $line) {
                                    copy($sub_directory . $key, $line);
                                }
                            }

                            /* ZIP Extraction - check if archives.json is set if yes then extract the files on destination as mentioned */
                            if (file_exists(UPDATE_PATH . $sub_directory . "archives.json")) {
                                /* extract the archives in the destination folder as set in the file */
                                $lines_array = file_get_contents(UPDATE_PATH . $sub_directory . "archives.json"); // $archive might create probs
                                if (!empty($lines_array)) {
                                    $lines_array = json_decode($lines_array);
                                    foreach ($lines_array as $source => $destination) {
                                        $source = UPDATE_PATH . $sub_directory . $source;
                                        $res = $zip->open($source);
                                        if ($res === true) {
                                            $zip->extractTo($destination);
                                            $zip->close();
                                        }
                                    }
                                }
                            }
                            $data = array('version' => $system_info['file_current_version']);
                            $data = escape_array($data);

                            $builder = $db->table('updates')->insert($data);
                            if (!$this->is_dir_empty(FCPATH . UPDATE_PATH . "\\app\\Database\\Migrations")) {
                                try {
                                    $migrate->latest();
                                    if (isset($system_info["query"]) && $system_info["query"]) {
                                        if ($system_info['query_path'] != '') {
                                            $sql = file_get_contents(UPDATE_PATH . $sub_directory . $system_info['query_path']);
                                            if ($sql != "") {
                                                $db->query($sql);
                                            }
                                        }
                                    }
                                } catch (\Throwable$e) {
                                    print_r($e);
                                }
                            }
                            delete_files(FCPATH . UPDATE_PATH, true);
                            $response['error'] = false;
                            $response['message'] = 'Congratulations! The system is updated From  version ' . $system_info['db_current_version'] . ' to ' . $system_info['file_current_version'] . ' version successfully';
                        } else {
                            $response['error'] = true;
                            $response['message'] = 'Invalid update file!. It seems like you are trying to update the system using wrong file.';
                            delete_files(FCPATH . UPDATE_PATH, true);
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Extraction failed";
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = $uploadData->getErrorString();
                }
            } else {
                $response['error'] = true;
                $response['message'] = 'You did not select a file to upload';
            }
            $response['csrfName'] = csrf_token();
            $response['csrfHash'] = csrf_hash();
            return $this->response->setJSON($response);
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
