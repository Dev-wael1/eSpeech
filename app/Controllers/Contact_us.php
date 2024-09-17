<?php

namespace App\Controllers;
use App\Controllers\Frontend;


class Contact_us extends Frontend
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if ($this->isLoggedIn) {
            $data['logged'] = true;
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $data['admin'] = true;
        } else {
            $data['admin'] = false;
        }
        $data['title'] = "Contact us &mdash; $this->appName ";
        $data['main_page'] = "contact";
        $data['settings'] = get_settings('general_settings', true);
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "All about $this->appName. $this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";

        return view('frontend/'.config('Site')->theme.'/template', $data);
    }

    public function sendMail()
    {
       
        $setting = get_settings('general_settings',true);
        $mail = (isset($setting['support_email'])?$setting['support_email'] : "infinitietechnologies05@gmail.com");
      
        $email_config = array(
            'charset' => 'utf-8',
            'mailType' => 'html'
        );
  

        $template = "
        Contact - us Data </br>
        Name : ".$_POST['name']."</br>
        Email : ".$_POST['email']."</br>
        Subject : ".$_POST['subject']."</br>
        Message : ".$_POST['message']."</br>

        ";
        $email = \Config\Services::email();
        $email->initialize($email_config);

        $email->setTo(trim($mail));
        $email->setSubject($_POST['subject']);
        $email->setMessage($template);

        if ($email->send()) {
            return $this->response->setJSON([
                "error" => false,
                "message" => "Thank you for contacting us.",
                "data"=>[],
                "csrfName" =>csrf_token(),
                "csrfHash" => csrf_hash()
            ]);
        } else {
            return $this->response->setJSON([
                "error" => true,
                "message" => "Something went wrong Please try again after some time.",
                "data"=>[
                    'console' => "console.log(".$email->printDebugger().");"
                ],
                "csrfName" =>csrf_token(),
                "csrfHash" => csrf_hash()
            ]);
          
        }
        
    }
}
