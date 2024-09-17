<?php

namespace App\Controllers\admin;

use App\Controllers\Auth;

class Users extends Admin
{
    private $user_model;
    public $admin_id;
    public function __construct()
    {
        parent::__construct();
        $this->user_model = new \App\Models\User();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->admin_id = ($this->ionAuth->isAdmin()) ? $this->ionAuth->user()->row()->id : 0;
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['title'] = 'User List | Admin Panel';
            $this->data['main_page'] = 'users';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function register_user()
    {
        // helper('form');
        if ($this->isLoggedIn && $this->userIsAdmin) {
            
            $this->data['title'] = 'Create User';
            $this->data['main_page'] = 'register_user';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function tts()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['title'] = 'Users TTS - Admin Panel | eSpeech';
            $this->data['main_page'] = 'users_tts';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function list_user()
    {
        $multipleWhere = '';
        $search = '';
        $limit = '';
        $sort = '';
        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        if (isset($_GET['sort'])) {
            if ($_GET['sort'] == 'id') {
                $sort = "id";
            } else {
                $sort = $_GET['sort'];
            }
        }
        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        }
        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['id' => $search, 'first_name' => $search, 'email' => $search, 'active' => $search];
        }

        $this->user_model->builder()->select(' COUNT(id) as `total` ');

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->user_model->builder()->orWhere($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $this->user_model->builder()->where($where);
        }

        $user_count = $this->user_model->builder()->get()->getResultArray();

        $total = $user_count[0]['total'];

        $this->user_model->builder()->select();
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $this->user_model->builder()->orLike($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $this->user_model->builder()->where($where);
        }
        $users_record = $this->user_model->builder()->orderBy($sort, "desc")->limit($limit, $offset)->get()->getResultArray();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($users_record as $row) {
            // $row = output_escaping($row);
            $email = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? mask_email($row['email']) : $row['email'];


            if ($row['image'] != '') {
                if (check_exists(base_url('public/backend/assets/profiles/' . $row['image']))) {
                    $profile = '<a  href="' . base_url('public/backend/assets/profiles/' . $row['image'])  . '" data-lightbox="image-1"><img height="80px" class="rounded-circle" src="' . base_url("public/backend/assets/profiles/" . $row['image']) . '" alt=""></a>';
                } else {
                    $profile = '
                        <a href="#" id="pop">
                            <img id="profile_picture" src="' . base_url('public/backend/assets/profiles/default.png') . '" height="80px" class="rounded-circle">
                        </a>';
                }
            } else {
                $profile = '<a href="#" id="pop">
                        <img id="profile_picture" src="' . base_url('public/backend/assets/profiles/default.png') . '" height="80px" class="rounded-circle">
                    </a>';
            }
            if ($row['active'] == 1) {
                $status = '<div class="badge badge-success projects-badge">Active</div>';
            } else {
                $status = '<div class="badge badge-warning projects-badge">Deactivated</div>';
            }
            $profile = '
            

            <li class="media p-2" >


    ' . $profile . '
    
    <div class="media-body">
        <div class="media-title mt-3">' .     $row['first_name'] . " " . $row['last_name'] . '</div>
        <div class="text-job text-muted">' . $email . '</div>
    </div>
</li>
            ';

            // Activate - Deactivate users
            $button = ($row['active'] == 1) ?
                '<button class="btn btn-danger"  title="Deactivate user" data-toggle="modal" data-target="#deactivate_user_modal" 
                data-uid="' . $row['id'] . '" onclick="deactivate_user(this)">
            <i class="fa-solid fa-ban"></i></button>' :
                '<button class="btn btn-success"  title="Active User" data-toggle="modal" data-target="#activate_user_modal"
                data-uid="' . $row['id'] . '" onclick="activate_user(this)">
            <i class="fa-solid fa-check"></i></button>';

            $tempRow['id'] = $row['id'];
            $tempRow['image'] = $profile;
            $tempRow['name'] = $row['first_name'] . " " . $row['last_name'];
            $tempRow['first_name'] = $row['first_name'];
            $tempRow['last_name'] = $row['last_name'];
            $tempRow['active'] = $status;
            if ($this->admin_id == $row['id']) {
                $tempRow['operations'] = '';
            } else {
                $tempRow['operations'] = $button;
            }

            $tempRow['email'] = $email;
            // $tempRow['phone'] = ($row['phone'] != '') ? $row['phone'] : '';
            if ($row['phone'] != '') {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $tempRow['phone']  = str_repeat("X", strlen($row['phone']) - 3) . substr($row['phone'], -3);
                } else {
                    $tempRow['phone'] = $row['phone'];
                }
            } else {
                $tempRow['phone'] = '';
            }
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        print_r(json_encode($bulkData));
    }
    public function deactivate()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            $id = $this->request->getVar('user_id');
            $user_data = fetch_details('users', ['id' => $id]);
            $user_f_name = $user_data[0]['first_name'];
            $user_l_name = $user_data[0]['last_name'];
            $user_full_name = $user_f_name . $user_l_name;

            // email data
            $user_email = $user_data[0]['email'];
            $subject = "Account suspension";
            $message = "dear" . $user_full_name . '<br>' .
                "We've observed that you have been vioalting our privacy policies and terms and conditions thus we've decided to" . '<br>' .
                "suspend you account for upcoming few days.";
            if ($this->admin_id == $id) {
                $response = [
                    'error' => true,
                    'message' => "Admin can niether be disabled or be activated",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }
            $operations = $this->ionAuth->deactivate($id);
            if ($operations) {
                send_mail_with_template('deactivate_user', $user_data);
                if ($operations) {
                    $response = [
                        'error' => false,
                        'message' => "User disabled",
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => []
                    ];
                    return $this->response->setJSON($response);
                } else {
                    $response = [
                        'error' => true,
                        'message' => "Unable to Deactivate User",
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => []
                    ];
                    return $this->response->setJSON($response);
                }
            } else {
                $response = [
                    'error' => true,
                    'message' => "Eroor may have occured",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }
        } else {
            return redirect('unauthorised');
        }
    }
    public function activate()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $id = $this->request->getVar('user_id');

            $id = $this->request->getVar('user_id');
            $user_data = fetch_details('users', ['id' => $id]);
            $user_f_name = $user_data[0]['first_name'];
            $user_l_name = $user_data[0]['last_name'];
            $user_full_name = $user_f_name . $user_l_name;

            // email data
            
            if ($this->admin_id == $id) {
                $response = [
                    'error' => true,
                    'message' => "Admin can niether be disabled or be activated",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }
            $operations =   $this->ionAuth->activate($id);
            if ($operations) {
                if ($operations) {
                    send_mail_with_template('activate_user', $user_data);
                    $response = [
                        'error' => false,
                        'message' => "Email sended to the user successfully and user have been disabled",
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => []
                    ];
                    return $this->response->setJSON($response);
                } else {
                    send_mail_with_template('activate_user', $user_data);
                    $response = [
                        'error' => true,
                        'message' => "Could not deactivate user",
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => []
                    ];
                    return $this->response->setJSON($response);
                }
            } else {
                $response = [
                    'error' => true,
                    'message' => "Eroor may have occured",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }

           
        } else {
            return redirect('unauthorised');
        }
    }
}
