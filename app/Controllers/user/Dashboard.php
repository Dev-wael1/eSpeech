<?php

namespace App\Controllers\user;

class Dashboard extends User
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->isLoggedIn) {
            $db = \Config\Database::connect();
            $total_tts = $db->table('users_tts ut')->select('count(ut.id) as `total`')->get()->getResultArray()[0]['total'];
            $total_google = $db->table('users_tts ut')->select('SUM(used_characters) as `total`')->where(["provider" => 'google', 'user_id' =>  $this->userId ])->get()->getResultArray()[0]['total'];
            $total_aws = $db->table('users_tts ut')->select('SUM(used_characters) as `total`')->where(["provider" => 'aws','user_id' =>  $this->userId ])->get()->getResultArray()[0]['total'];
            $total_ibm = $db->table('users_tts ut')->select('SUM(used_characters) as `total`')->where(["provider" => 'ibm','user_id' =>  $this->userId ])->get()->getResultArray()[0]['total'];
            $total_azure = $db->table('users_tts ut')->select('SUM(used_characters) as `total`')->where(["provider" => 'azure','user_id' => $this->userId ])->get()->getResultArray()[0]['total'];

            $free_data = get_settings('tts_config',true);
            $this->data['total_tts'] = $total_tts;
            $this->data['total_google'] = $total_google;
            $this->data['total_aws'] = $total_aws;
            $this->data['total_ibm'] = $total_ibm;
            $this->data['total_azure'] = $total_azure;
            $this->data['free_data'] = $free_data;
            $this->data['title'] = 'User Panel';
            $this->data['main_page'] = 'dashboard';
            return view('backend/user/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
}
