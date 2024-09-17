<?php

namespace App\Controllers\admin;

use App\Models\Subscription_model;
use Exception;

class Subscriptions extends Admin
{
    private $subscription_model, $user_model, $plan_model, $tenure_model;

    public function __construct()
    {
        parent::__construct();
        $this->subscription_model = new \App\Models\Subscription();
        $this->user_model = new \App\Models\User();
        $this->plan_model = new \App\Models\Plan();
        $this->tenure_model = new \App\Models\Tenures();
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['users'] = fetch_details('users', [], ["id", "username"]);
            $this->data['plans'] = fetch_details('plans', [], ["id", "title"]);

            $this->data['title'] = 'Subscriptions | Admin Panel';
            $this->data['main_page'] = 'subscription';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }



    public function get_plan_data()
    {
        $data = [];
        $response = [
            'error' => true,
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash(),
            'data' => []
        ];
        if ($this->request->getPost('plan_id') && $this->request->getPost('plan_id') != '') {
            $response['data']["tenures"] =  fetch_details('plans_tenures', ['plan_id' => $_POST['plan_id']], ['id', 'title']);
            $response['data']['type'] = fetch_details('plans', ['id' => $_POST['plan_id']], ['type'])[0]['type'];
            $response["error"] = false;
            return $this->response->setJSON($response);
        }
        return $this->response->setJSON($response);
    }


    public function get_username()
    {
        $user = fetch_details("users", ['id' => $_POST['user_id']], ['first_name', 'last_name']);
        if (!empty($user)) {
            $user = $user[0];
            $user = $user['first_name'] . " " . $user['last_name'];
            return $this->response->setJSON([
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                "data" => $user
            ]);
        }
        return $this->response->setJSON([
            "error" => true,
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash(),
            "data" => ''
        ]);
    }
    public function get_price()
    {
        $response = [
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash(),
            "error" => true,
            "data" => []
        ];
        if ($this->request->getPost('tenure_id')) {
            if ($data = fetch_details('plans_tenures', ['id' => $_POST['tenure_id']], ['price', 'months'])[0]) {
                $response['data'] = $data;
                $response['error'] = false;
            }
        }
        return $this->response->setJSON($response);
    }
    public function add_subscription()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $response = [
                'error' => true,
                'message' => DEMO_MODE_ERROR,
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
        try {
            $months = fetch_details('plans_tenures', ['id' => $_POST['tenure_id']], ['months'])[0]['months'];
            $price = fetch_details('plans_tenures', ['id' => $_POST['tenure_id']], ['price'])[0]['price'];
            if (add_subscription($_POST['user_id'], $_POST['plan_id'], $months, '0', $price, $_POST['starts_from'])) {
                $response = [
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_Hash(),
                    'error' => false,
                    'message' => "subscription added successfully...",
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }
            $response = [
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'error' => true,
                'message' => "Something Went wrong...",
                'data' => []
            ];
            return $this->response->setJSON($response);
        } catch (Exception $e) {
            $response = [
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'error' => true,
                'message' => $e->getMessage(),
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
    }
    public function get_users()
    {
        // Fetch users
        $search_term = '';
        if (isset($_GET['search'])) {
            $search_term = $_GET['search'];
        }
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('*');
        $builder->where("`email` like '%" . $search_term . "%'");
        $fetched_records = $builder->limit(5)->get();
        $users = $fetched_records->getResultArray();
        // Initialize Array with fetched data
        $data = array();
        foreach ($users as $user) {
            $data[] = array('id' => $user['id'], 'email' => $user['email'], "text" => $user['email']);
        }
        $result['result'] = $data;
        $result['pagination'] = array('more' => true);
        return $this->response->setJSON($result);
    }
}
