<?php

namespace App\Controllers\admin;

use App\Models\Social_model;


class Social_links extends Admin
{
    public $validation, $social_model;
    public function __construct()
    {
        parent::__construct();
        $this->validation = \Config\Services::validation();
        $this->social_model = new Social_model();
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['title'] = 'Add Social Link | espeech';
            $this->data['main_page'] = 'add_links';

            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function add_link()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->validation->setRules(
                [
                    'site_name' => 'required',
                    'site_url' => 'required|valid_url_strict[https]',
                ],
                [
                    'site_name' => [
                        'required' => 'Site Name type is required',
                    ],
                    'site_url' => [
                        'required' => 'Site URL is required',
                        'valid_url_strict' => 'Site URL is Invalid',
                    ],

                ]
            );
            if (!$this->validation->withRequest($this->request)->run()) {
                $errors = $this->validation->getErrors();
                $response['error'] = true;
                $response['message'] = $errors;
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = $_POST;
                return $this->response->setJSON($response);
            }
            $type = strtolower($this->request->getPost('site_name'));
            $type =  preg_replace('/\s+/', '_', $type);
            // print_r($type);
            $id = $this->request->getPost('id');

            $data['site_name'] = $type;
            if (!isset($id)) {
                $existing =  fetch_details('social_links', ['site_name' => $type]);
                if (!empty($existing)) {
                    $response['error'] = true;
                    $response['message'] = "Name Already exists";
                    $response['csrfName'] = csrf_token();
                    $response['csrfHash'] = csrf_hash();
                    $response['data'] = $_POST;
                    return $this->response->setJSON($response);
                }
            }
            $data['site_url'] = $this->request->getPost('site_url');
            if (isset($id) && $id != null) {
                $old_data = fetch_details('social_links', ['id' => $id]);
                $old_icon = (!empty($old_data)) ? $old_data[0]['site_icon'] : '';
            }

            $file = $this->request->getFile('site_logo');
            if ($file->getTempName() != '') {
                $file_name = $file->getRandomName();
                $file_og_name = $file->getName();
                $path  = 'public/backend/assets/site_icon';
                $name_to_upload  = 'public/backend/assets/site_icon/' . $file_name;
                $data['site_icon'] = $name_to_upload;
            } else if ($id != '') {
                $data['site_icon'] = $old_icon;
            } else {
                $data['site_icon'] = '';
            }
            $data['site_html'] = $this->request->getPost('site_html');

            $this->social_model->save($data);

            $site = (!isset($id) && $id == null) ? $this->social_model->insert($data) : $this->social_model->update($id, $data);
            if ($site) {
                $upload =  ($file->getTempName() != '') ? $file->move($path,  $file_name) : true;
                if ($upload) {
                    $response['error'] = false;
                    $response['message'] = 'Added Site Link with icon';
                    $response['csrfName'] = csrf_token();
                    $response['csrfHash'] = csrf_hash();
                    $response['data'] = $_POST;
                    return $this->response->setJSON($response);
                } else {
                    $response['error'] = false;
                    $response['message'] = 'Added Site Link but Image had some problems';
                    $response['csrfName'] = csrf_token();
                    $response['csrfHash'] = csrf_hash();
                    $response['data'] = $upload;
                    return $this->response->setJSON($response);
                }
            } else {
                $response['error'] = true;
                $response['message'] = 'Could not Add Site Link';
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = $_POST;
                return $this->response->setJSON($response);
            }
        } else {
            return redirect('unauthorised');
        }
    }

    public function list()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $limit = (isset($_GET['limit']) && !empty($_GET['limit'])) ? $_GET['limit'] : 10;
            $offset = (isset($_GET['offset']) && !empty($_GET['offset'])) ? $_GET['offset'] : 0;
            $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : 'id';
            $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : 'ASC';
            $search = (isset($_GET['search']) && !empty($_GET['search'])) ? $_GET['search'] : '';

            $data =  $this->social_model->list($sort, $order, $limit, $offset, $search);
            return $data;
        } else {
            return redirect('unauthorised');
        }
    }

    public function delete_link()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $db      = \Config\Database::connect();
            $id = $this->request->getPost('id');
            $builder = $db->table('social_links');

            $site_icon = fetch_details('social_links', ['id' => $id], ['site_icon'])[0]['site_icon'];


            $builder->where('id', $id);
            $data = $builder->delete();
            if ($data) {
                unlink($site_icon);
                $response['error'] = true;
                $response['message'] = "Deleted successfully";
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = $_POST;
                return $this->response->setJSON($response);
            } else {
                $response['error'] = true;
                $response['message'] = "Delete Unsuccessful";
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = $_POST;
                return $this->response->setJSON($response);
            }
        } else {
            return redirect('unauthorised');
        }
    }
}
