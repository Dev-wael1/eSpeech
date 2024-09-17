<?php

namespace App\Controllers\admin;

class Languages extends Admin
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $session = session();
            $lang = $session->get('lang');
            if (empty($lang)) {
                $lang = 'en';
            }
            $this->data['code'] = $lang;
            $this->data['title'] = 'Language';
            $this->data['main_page'] = 'languages';
            $this->data['languages'] = fetch_details('languages',[],[],null,'0','id','ASC');
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function change($lang)
    {
        $session = session();
        $session->remove('lang');
        $session->set('lang', $lang);
        return redirect()->to("admin/languages/");
    }
    public function set_labels()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                $_SESSION['toastMessageType']  = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/languages')->withCookies();
            }
            helper('files');
            helper('filesystem');
            $my_lang = trim($_POST['code']);
            $labels = $_POST;
            $langstr = "\$lang['label_language'] = \"$my_lang\";" . "\n";

            $langstr_final = "<?php 
/**
*
*
* Descriptions :  " . $my_lang . " language file for general labels
*
*/" . "\n\n\n" . $langstr;
            foreach ($labels as $key => $val) {
                $langstr_final .= "\$lang['$key'] = \"$val\";" . "\n";
            }
            $langstr_final .= 'return $lang;';
            if (!is_dir('./app/Language/' . $my_lang . '/')) {
                mkdir('./app/Language/' . $my_lang . '/', 0777, TRUE);
            }

            if (file_exists('./app/Language/' . $my_lang . '/Text.php')) {
                delete_files('./app/Language/' . $my_lang . '/Text.php');
                write_file('./app/Language/' . $my_lang . '/Text.php', $langstr_final);
            } else {
                write_file('./app/Language/' . $my_lang . '/Text.php', $langstr_final);
            }
            $_SESSION['toastMessage'] = 'Labels Updated successfully';
            $_SESSION['toastMessageType']  = 'success';
            $this->session->markAsFlashdata('toastMessage');
            $this->session->markAsFlashdata('toastMessageType');
            return redirect()->to("admin/languages/change/" . $my_lang)->withCookies();
        } else {
            return redirect('unauthorised');
        }
    }
    public function create()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                $_SESSION['toastMessageType']  = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/languages')->withCookies();
            }
            helper('files');
            helper('filesystem');

            $db      = \Config\Database::connect();
            $language = (trim($_POST['language']));
            $code = str_replace(' ', '-', strtolower(trim($_POST['code'])));
            $check = fetch_details('languages', ['code' => $code]);
            if (count($check) > 0) {
                $_SESSION['toastMessage'] = "Language code already exists.";
                $_SESSION['toastMessageType']  = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/languages')->withCookies();
            }
            $check = $db->table('languages')->insert($data = ['language' => $language, 'code' => $code]);
            $my_lang = $code;
            if ($check) {
                $langstr = "\$lang['label_language'] = \"$my_lang\";" . "\n";

                $langstr_final = "<?php 
/**
*
*
* Description:  " . $my_lang . " language file for general labels
*
*/" . "\n\n\n" . $langstr;
                $langstr_final .= 'return $lang;';

                if (!is_dir('./app/Language/' . $my_lang . '/')) {
                    mkdir('./app/Language/' . $my_lang . '/', 0777, TRUE);
                }

                if (file_exists('./app/Language/' . $my_lang . '/Text.php')) {
                    delete_files('./app/Language/' . $my_lang . '/Text.php');
                    write_file('./app/Language/' . $my_lang . '/Text.php', $langstr_final);
                } else {
                    write_file('./app/Language/' . $my_lang . '/Text.php', $langstr_final);
                }

                $_SESSION['toastMessage'] = "Language added..";
                $_SESSION['toastMessageType']  = 'success';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/languages')->withCookies();
            } else {
                $_SESSION['toastMessage'] = 'something went wrong..';
                $_SESSION['toastMessageType']  = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/languages')->withCookies();
            }
        } else {
            return redirect('unauthorised');
        }
    }
    public function remove()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) 
        {
            $db      = \Config\Database::connect();

            $id = $this->request->getVar('id');
            $builder = $db->table('languages');
            
            $builder->where('id', $id);
            $data = fetch_details("languages", ['id' => $id]);
            if (empty($data)) {
                return redirect('unauthorised');
            }
            $code = $data[0]['code'];
            if($code == "en"){
                $_SESSION['toastMessage'] = "Default language cannot be removed.";
                $_SESSION['toastMessageType']  = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/languages')->withCookies();
            }

            if ($builder->delete()) {
                delete_directory("app/Language/$code/");
                $_SESSION['toastMessage'] = "Language removed successfully.";
                $_SESSION['toastMessageType']  = 'success';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/languages')->withCookies();
            }
        } else {
            return redirect('unauthorised');
        }
    }
}
