<?php

namespace App\Controllers\admin;

use App\Models\Mail_template;



class Mail_templates extends Admin
{
    public $validation, $mail_template;
    public function __construct()
    {
        parent::__construct();
        $this->validation = \Config\Services::validation();
        $this->mail_template = new Mail_template();
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['title'] = 'Email Templates | espeech';
            $this->data['main_page'] = 'email_templates';

            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function add_mail_template()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $template_model  = new  Mail_templates;
            // $this->validation->setRules(
            //     [
            //         'mail_type' => 'required',
            //         'mail_subject' => 'required',
            //         'mail_text' => 'required',
            //     ],
            //     [
            //         'mail_type' => [
            //             'required' => 'Mail type is required',
            //         ],
            //         'mail_subject' => [
            //             'required' => 'Mail Subject is required',
            //         ],
            //         'mail_text' => [
            //             'required' => 'Mail text is required',
            //         ],

            //     ]
            // );

            // if (!$this->validation->withRequest($this->request)->run()) {
            //     $errors = $this->validation->getErrors();
            //     $response['error'] = 'true';
            //     $response['message'] = $errors;
            //     $response['csrfName'] = csrf_token();
            //     $response['csrfHash'] = csrf_hash();
            //     $response['data'] = $_POST;
            //     return $this->response->setJSON($response);
            // }



            $type =  strtolower($this->request->getPost('mail_type'));
            $type =  preg_replace('/\s+/', '_', $type);
            $id = $this->request->getPost('id');


            $data['email_type']  = $type;
            $data['email_subject']  = $this->request->getPost('mail_subject');
            $data['email_text']  = json_encode($this->request->getPost('mail_text'));
            $data['status'] = ($this->request->getPost('template_status') == 'on') ? '1' : '0';
            $db      = \Config\Database::connect();
            $text = $this->request->getPost('mail_text');

            $exists =  fetch_details('email_template', ['email_type' => $type]);


            if (empty($exists)) {
                $result = $this->mail_template->insert($data);
            } else {
                $result = $db->table('email_template')->where('email_type', $type)->update($data);
            }


            if ($result) {
                $response['error'] = 'false';
                $response['message'] = 'Template Added successfully';
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = [];
                return $this->response->setJSON($response);
            } else {
                $response['error'] = 'true';
                $response['message'] = "Couldn't Add Template";
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = [];
                return $this->response->setJSON($response);
            }
        } else {
            return redirect('unauthorised');
        }
    }

    public function mail_template_list()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $limit = (isset($_GET['limit']) && !empty($_GET['limit'])) ? $_GET['limit'] : 10;
            $offset = (isset($_GET['offset']) && !empty($_GET['offset'])) ? $_GET['offset'] : 0;
            $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : 'id';
            $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : 'ASC';
            $search = (isset($_GET['search']) && !empty($_GET['search'])) ? $_GET['search'] : '';

            $data =  $this->mail_template->template_list($sort, $order, $limit, $offset, $search);
            return $data;
        } else {
            return redirect('unauthorised');
        }
    }

    public function delete_mail_template()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $db      = \Config\Database::connect();
            $builder = $db->table('email_template');
            $id = $this->request->getPost('id');
            $exists = exists(['id' => $id], 'email_template');
            if ($exists == false) {
                $response['error'] = 'true';
                $response['message'] = 'No Template Found';
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = [];
                return $this->response->setJSON($response);
            }
            $builder->where('id', $id);
            $data = $builder->delete();
            if ($data) {
                $response['error'] = 'false';
                $response['message'] = 'Template Deleted';
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = [];
                return $this->response->setJSON($response);
            } else {
                $response['error'] = 'true';
                $response['message'] = "Template Can't be Deleted";
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = $data;
                return $this->response->setJSON($response);
            }
        } else {
            return redirect('unauthorised');
        }
    }

    public function fetch_mail_type_data()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if ($this->isLoggedIn && $this->userIsAdmin) {
               //TODO: /**  for future reference, for improvements */
                $email_template_literals = [
                    'forget_password' => ["reset_link", "user_id", "first_name", "last_name"],
                    'activate_user' => ["activation_link", "user_id", "first_name", "last_name", "identity", "email", "phone"],
                    'activate_user' => ["activation_link", "user_id", "first_name", "last_name", "identity", "email", "phone"],
                ];

                $mail_type = $this->request->getPost('mail_type');
                $limit = (isset($_GET['limit']) && !empty($_GET['limit'])) ? $_GET['limit'] : 10;
                $offset = (isset($_GET['offset']) && !empty($_GET['offset'])) ? $_GET['offset'] : 0;
                $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : 'id';
                $order = (isset($_GET['order']) && !empty($_GET['order'])) ? $_GET['order'] : 'ASC';
                $search = (isset($_GET['search']) && !empty($_GET['search'])) ? $_GET['search'] : '';

                if ($mail_type == '0') {
                    $response['error'] = 'true';
                    $response['message'] = 'Please Select Specific type! This Is Not Allowed';
                    $response['csrfName'] = csrf_token();
                    $response['csrfHash'] = csrf_hash();
                    $response['data'] = [];
                    return $this->response->setJSON($response);
                }
                
                $data =  $this->mail_template->template_list($sort, $order, $limit, $offset, $search, $addition_data = ['email_type' => $mail_type]);

                $response['error'] = 'false';
                $response['message'] = 'Fetched successfully';
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = $data;
                return $this->response->setJSON($response);
            } else {
                return redirect('unauthorised');
            }
        } else {
            return redirect('unauthorised');
        }
    }
}
