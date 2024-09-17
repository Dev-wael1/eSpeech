<?php

namespace App\Controllers;

use App\Libraries\Aws;
use App\Libraries\Azure;
use App\Libraries\Google;
use App\Libraries\JWT;
use App\Libraries\IBM;
use App\Models\Tts_model;
use Config\Email;
use App\Libraries\Paypal_lib;
use App\Libraries\Razorpay;

use App\Models\Voices_model;

use GenderDetector\Exception\FileReadingException;
use GenderDetector\GenderDetector;

class Test extends BaseController
{
    private $voices_model;


    public function generate_token()
    {
        helper('function');
        generate_token();
    }
    public function index()
    {
        echo \CodeIgniter\CodeIgniter::CI_VERSION;
        // $data = fetch_details('users', ['id' => '1'])[0];
        // send_mail_with_template('subscription', $data);
        $settings = get_settings("aws_voices", true);

        foreach ($settings as $setting) {
            print_r($setting);
            $data = fetch_details('tts_voices', ['voice' => $setting['voice'], 'provider' => 'aws'], ['voice', 'language']);
            if (empty($data)) {
                $db = \Config\Database::connect();
                $builder = $db->table('tts_voices');
                $builder = $builder->insert([
                    'language' => $setting['language'],
                    'voice' => $setting['voice'],
                    'display_name' => $setting['display_name'],
                    'type' => $setting['type'],
                    'gender' => null,
                    'provider' => 'aws',
                    'icon' => null,
                    'status' => 1,
                ]);
            }
        }
    }
    public function voices()
    {
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        helper('function');
        $validation->setRules(
            [
                'language' => 'required'
            ],
            [
                'language' => [
                    'required' => 'Language code is required',
                ]
            ]
        );
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            $response = [
                'error' => true,
                'message' => $errors,
                'data' => [],
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];

            return print_r(json_encode($response));
        }
        $voices = get_voices($request->getPost('language'));

        $response = [
            'error' => false,
            'message' => '',
            'data' => $voices,
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash()
        ];
        return print_r(json_encode($response));
    }
    public function synthesize()
    {
        helper('function');
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [
                'provider' => 'required',
                'voice' => 'required',
                'text' => 'required',
            ],
            [
                'provider' => [
                    'required' => 'provider is required',
                ],
                'voice' => [
                    'required' => 'voice is required',
                ],
                'text' => [
                    'required' => 'text is required',
                ],
            ]
        );
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            $response = [
                'error' => true,
                'message' => $errors,
                'data' => []
            ];

            return print_r(json_encode($response));
        }
        $provider = strtolower($request->getPost('provider'));
        $voice = $request->getPost('voice');
        $text = $request->getPost('text');
        $language = $request->getPost('language');
        if ($provider == "ibm") {
            $ibm = new IBM;
            $base64 =  $ibm->synthesize($voice, $text, true);
        } elseif ($provider == "aws") {
            $aws = new Aws;
            $base64 =  $aws->systhesize($voice, $text, $language, true);
        } elseif ($provider == "azure") {
            $azure = new Azure;
            $base64 = $azure->systhesize($language, $voice, $text);
        } elseif ($provider == "google") {
            $google = new Google;
            $base64 = $google->systhesize($language, $voice, $text);
        } else {
            $response = [
                'error' => true,
                'message' => "Provider is not valid",
                'data' => []
            ];
            return print_r(json_encode($response));
        }
        $response = [
            'error' => false,
            'message' => '',
            'data' => $base64,
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash()
        ];
        return print_r(json_encode($response));
    }

    public function update_all_providers()
    {
        // to update all the providers
        $test = update_all_providers();
        print_r($test);
        return print_r('something went wrong');
    }
    function test()
    {
        helper('filesystem');
        delete_files('public/uploads/images/about.png');
    }
    public function test_azure()
    {
        $azure = new Azure;
        echo "<pre>";
        return print_r($azure->get_token());
    }
    public function send_mail()
    {

        $email_config = array(
            'charset' => 'utf-8',
            'mailType' => 'html'
        );

        $email = \Config\Services::email();
        $email->initialize($email_config);

        $email->setTo("jaysspspsp@gmail.com");
        $email->setSubject("Test message");
        $email->setMessage("Hello");

        if ($email->send()) {
            echo "Email sent!";
        } else {
            echo $email->printDebugger();
            return false;
        }
    }
    public function ibm()
    {
        $ibm = new IBM;
        echo $ibm->synthesize('de-DE_BirgitV2Voice', "hello");
    }

    public function gender()
    {
        $genderDetector = new GenderDetector();
        $add_icon = new Voices_model();

        $female = array("female1", "female2", "female3", "female4", "female5");
        $male = array("male1", "male2", "male3", "male4", "male5");

        $tts_voices = fetch_details('tts_voices', [], ['id', 'language', 'voice', 'display_name', 'type', 'gender', 'provider', 'icon']);

        foreach ($tts_voices as $value) {
            $voices = $genderDetector->detect($value['display_name']);
            if ($voices == "female" || $voices == "mostly_female" || $voices == "unisex") {
                $random = array_rand($female, 1);
                $value['icon'] =  "public/provider/$female[$random].jpg";
                $value['gender'] = "female";
            } else if ($voices == "male" || $voices == "mostly_male") {
                $random = array_rand($male, 1);
                $value['icon'] =  "public/provider/$male[$random].jpg";
                $value['gender'] = "male";
            } else {
                $value['icon'] =  null;
                $value['gender'] = null;
            }

            // print_r($value);
            $data = [
                'id' => $value['id'],
                'icon' => $value['icon'],
                'gender' => $value['gender'],
            ];
            // print_r($data);
            $add_icon->save($data);
        }
    }
}
