<?php
namespace App\Controllers\admin;

class Dashboard extends Admin
{
    public function __construct()
    {
        parent::__construct();
        $this->user_model = new \App\Models\User();
    }

    public function index()
    {
        if($this->isLoggedIn && $this->userIsAdmin)
        {
            $db = \Config\Database::connect();
            $total_users = $db->table('users u')->select('count(u.id) as `total`')->get()->getResultArray()[0]['total'];
            $total_active = $db->table('subscriptions s')->select('count(s.id) as `total`')->where(['status' => 1])->get()->getResultArray()[0]['total'];
            $total_expired = $db->table('subscriptions s')->select('count(s.id) as `total`')->where(['status' => 0])->get()->getResultArray()[0]['total'];
            $total_tts = $db->table('users_tts ut')->select('count(ut.id) as `total`')->get()->getResultArray()[0]['total'];
            $total_google = $db->table('users_tts ut')->select('SUM(used_characters) as `total`')->where(["provider" => 'google'])->get()->getResultArray()[0]['total'];
            $total_aws = $db->table('users_tts ut')->select('SUM(used_characters) as `total`')->where(["provider" => 'aws'])->get()->getResultArray()[0]['total'];
            $total_ibm = $db->table('users_tts ut')->select('SUM(used_characters) as `total`')->where(["provider" => 'ibm'])->get()->getResultArray()[0]['total'];
            $total_azure = $db->table('users_tts ut')->select('SUM(used_characters) as `total`')->where(["provider" => 'azure'])->get()->getResultArray()[0]['total'];
           

            $this->data['title'] = 'Admin Panel';
            $this->data['total_users'] = $total_users;
            $this->data['total_active'] = $total_active;
            $this->data['total_expired'] = $total_expired;
            $this->data['total_tts'] = $total_tts;
            $this->data['total_google'] = $total_google;
            $this->data['total_aws'] = $total_aws;
            $this->data['total_ibm'] = $total_ibm;
            $this->data['total_azure'] = $total_azure;
            $this->data['main_page'] = 'dashboard';
            return view('backend/admin/template', $this->data);
        } 
        else
        {
            return redirect('unauthorised');
        }
    }
}