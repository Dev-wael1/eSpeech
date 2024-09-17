<?php

namespace App\Controllers;

use App\Libraries\Aws;
use App\Libraries\Azure;
use App\Libraries\Google;
use App\Libraries\IBM;
use App\Controllers\Frontend;


class Home extends Frontend
{
    public function __construct()
    {
        parent::__construct();
    }
    // public function test()
    // {
    //     $config = config("IonAuth");
    //     echo "<pre>";
    //     print_r($config);
    // }
    public function index()
    {
        $plans_tenures_model = new \App\Models\Tenures();
        $plans_tenures_model->builder()->select();
        $data['tenure'] =  $plans_tenures_model->builder()->get()->getResultArray();
        $data['plans'] =  fetch_details('plans', [], [], null, "0", "row_order", "ASC");
        $data['blogs'] =  fetch_details('blogs', ['status' => 1], [], null);
        $data['reviews'] =  fetch_details('reviews', ['status' => 1], [], null);
        $data['title'] = "Home &mdash; $this->appName ";
        $data['main_page'] = "home";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "$this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
        $data['logged'] = false;
        if ($this->isLoggedIn) {
            $data['logged'] = true;
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $data['admin'] = true;
        } else {
            $data['admin'] = false;
        }
        $languages = fetch_details('tts_languages', ['status' => '1'],[],null, '0','id', 'ASC');
        // print_r($languages);
        $app_settings = fetch_details('settings', ['variable' => 'app_settings']);
        // if (!empty($language)) {
        //     $language = $language[0]['value'];
        // } else {
        //     $language = '[{}]';
        // }
        $data['languages'] = $languages;
        if (!empty($app_settings)) {
            $app_settings = $app_settings[0]['value'];
        } else {
            $app_settings = '[{}]';
        }
        $currency = get_settings('general_settings', true);
        $currency = (isset($currency['currency'])) ? $currency['currency'] : '₹';
        $data['currency'] =  $currency;
        $data['app_settings'] = json_decode($app_settings, true);

        return view('/frontend/' . config('Site')->theme . '/template', $data);
    }

    public function unauthorised()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $data['admin'] = true;
        } else {
            $data['admin'] = false;
        }

        $data['title'] = "Unauthorised Access | $this->appName - Voice Synthesis Services";
        $data['main_page'] = "unauthorised";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "$this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
        return view('/frontend/' . config('Site')->theme . '/template', $data);
    }

    public function payment_success()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $data['admin'] = true;
        } else {
            $data['admin'] = false;
        }
        if ($this->isLoggedIn) {
            $data['logged'] = true;
        }
        
        $data['title'] = "Payment Status | $this->appName - Voice Synthesis Services";
        $data['main_page'] = "payment_status";
        $data['status'] = true;
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "$this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
        return view('/frontend/' . config('Site')->theme . '/template', $data);
    }

    public function payment_failed()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $data['admin'] = true;
        } else {
            $data['admin'] = false;
        }
        if ($this->isLoggedIn) {
            $data['logged'] = true;
        }
        $data['title'] = "Payment Status | $this->appName - Voice Synthesis Services";
        $data['main_page'] = "payment_status";
        $data['status'] = false;
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "$this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
        return view('/frontend/' . config('Site')->theme . '/template', $data);
    }
    public function set_voices()
    {

        $request = \Config\Services::request();
        helper('function');
        $voices = (array)get_voices($request->getGet('language'), true);
        // $voices = (array)get_voices($request->getPost('language'));
        // print_r($voices);
        $length = count($voices);
        for ($i = 0; $i < $length; $i++) {
            if ($voices[$i]['icon'] != null) {
                $image = "" . $voices[$i]['icon'];
            } elseif ($voices[$i]['gender'] != null) {
                if ($voices[$i]['gender'] == "male") {
                    $image =  "public/provider/male.png";
                } elseif ($voices[$i]['gender'] == "female") {
                    $image =  "public/provider/female.png";
                }
            } else {
                if ($voices[$i]['provider'] == "azure") {
                    $image =  "public/provider/azure.svg";
                } elseif ($voices[$i]['provider'] == "aws") {
                    $image =  "public/provider/aws.svg";
                } elseif ($voices[$i]['provider'] == "google") {
                    $image =  "public/provider/google.svg";
                } elseif ($voices[$i]['provider'] == "ibm") {
                    $image =  "public/provider/ibm.svg";
                }
            }
            $voices[$i]['image'] = $image;
        }
        $response = [
            'error' => false,
            'message' => '',
            'data' => $voices,
        ];
        return $this->response->setJSON($response);
    }
    public function synthesize()
    {
        if ($tts_config = get_settings('tts_config', true)) {
            $data = "";
            $validation =  \Config\Services::validation();
            $request = \Config\Services::request();
            $validation->setRules(
                [
                    'provider' => 'required',
                    'voice' => 'required',
                    'text' => 'required',
                    'language' => 'required',
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
                    'language' => [
                        'required' => 'language is required',
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

                return $this->response->setJSON($response);
            }
            $provider = strtolower($request->getPost('provider'));
            // $disabled_providers = array('azure' => 'Azure');
            $disabled_providers = [];

            if (array_key_exists($provider, $disabled_providers)) {
                $response = [
                    'error' => true,
                    'message' => "In Demo mode we've temporarily disabled " . $disabled_providers[$provider] . " TTS services. You can synthesize using Google, AWS and IBM TTS Services as of now.",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }


            $voice = $request->getPost('voice');

            $text = $request->getPost('text');
            $text = strip_tags($text);
            $language = $request->getPost('language');
            if (!verify_voice($language, $voice, $provider)) {

                $response = [
                    'error' => true,
                    'message' => "voice with provider did not match",
                    'data' => [],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];
                return $this->response->setJSON($response);
            }
            if (strlen($text) <= (int)$tts_config['freeTierCharacterLimit']) {
                if ($provider == "ibm") {
                    if ($tts_config['ibmStatus'] != "enable") {
                        $response = [
                            'error' => true,
                            'message' => "IBM is disabled",
                            'data' => [],
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash()
                        ];
                        return $this->response->setJSON($response);
                    }
                    $ibm = new IBM;
                    if (!$data = $ibm->synthesize($voice, $text, true)) {
                        $response = [
                            'error' => true,
                            'message' => "please Input valid tags.",
                            'data' => [],
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash()
                        ];
                        return $this->response->setJSON($response);
                    }
                } elseif ($provider == "aws") {
                    if ($tts_config['amazonPollyStatus'] != "enable") {
                        $response = [
                            'error' => true,
                            'message' => "Amazon polly is disabled",
                            'data' => [],
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash()
                        ];
                        return $this->response->setJSON($response);
                    }
                    $aws = new Aws;
                    if (!$data = $aws->systhesize($voice, $text, $language, $_POST['engine'])) {
                        $response = [
                            'error' => true,
                            'message' => "please Input valid tags.",
                            'data' => [],
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash()
                        ];
                        return $this->response->setJSON($response);
                    }
                } elseif ($provider == "azure") {
                    if ($tts_config['azureStatus'] != "enable") {
                        $response = [
                            'error' => true,
                            'message' => "Amazon polly is disabled",
                            'data' => [],
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash()
                        ];
                        return $this->response->setJSON($response);
                    }
                    $azure = new Azure;
                    if ($data = $azure->systhesize($language, $voice, $text)) {
                        $data = $azure->systhesize($language, $voice, $text);
                        if ($data == '') {
                            $response = [
                                'error' => true,
                                'message' => "language and the text should match in azure.",
                                'data' => [],
                                'csrfName' => csrf_token(),
                                'csrfHash' => csrf_hash()
                            ];
                            return $this->response->setJSON($response);
                        }
                    } else {
                        $response = [
                            'error' => true,
                            'message' => "please Input valid tags.",
                            'data' => [],
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash()
                        ];
                        return $this->response->setJSON($response);
                    }
                } elseif ($provider == "google") {
                    if ($tts_config['gcpStatus'] != "enable") {
                        $response = [
                            'error' => true,
                            'message' => "Amazon polly is disabled",
                            'data' => [],
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash()
                        ];
                        return $this->response->setJSON($response);
                    }
                    $google = new Google;
                    $data = $google->systhesize($language, $voice, $text);
                } else {
                    $response = [
                        'error' => true,
                        'message' => "Provider is not valid",
                        'data' => [],
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash()
                    ];
                    return $this->response->setJSON($response);
                }
                if ($request->getPost('title')) {
                    $title = $request->getPost('title');
                } else {
                    $title = '';
                }

                $response = [
                    'error' => false,
                    'message' => "Synthesized successfully",
                    'data' => $data,
                    'text' => ($text),
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];
                return $this->response->setJSON($response);
            } else {
                return $this->response->setJSON([
                    'error' => true,
                    'message' => "Character limit exceeded you can use " . (int)$tts_config['freeTierCharacterLimit'] . " characters",
                    'data' => [],
                    'text' => ($text),
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ]);
            }
        } else {
            $text = "";
            return $this->response->setJSON([
                'error' => true,
                'message' => "something went wrong contact admin ASAP",
                'data' => [],
                'text' => ($text),
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ]);
        }
    }
    public function terms_condition()
    {

        $data['title'] = "Terms and Condition &mdash; $this->appName ";
        $data['main_page'] = "terms_condition";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "$this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
        $data['logged'] = false;
        if ($this->isLoggedIn) {
            $data['logged'] = true;
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $data['admin'] = true;
        } else {
            $data['admin'] = false;
        }
        $data['text'] = get_settings('terms_conditions', true)['terms_conditions'];
        return view('/frontend/' . config('Site')->theme . '/template', $data);
    }
    public function privacy_policy()
    {

        $data['title'] = "Privacy Policy &mdash; $this->appName ";
        $data['main_page'] = "privacy_policy";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "$this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
        $data['logged'] = false;
        if ($this->isLoggedIn) {
            $data['logged'] = true;
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $data['admin'] = true;
        } else {
            $data['admin'] = false;
        }
        $data['text'] = get_settings('privacy_policy', true)['privacy_policy'];
        return view('/frontend/' . config('Site')->theme . '/template', $data);
    }
    public function refund_policy()
    {

        $data['title'] = "Refund Policy &mdash; $this->appName ";
        $data['main_page'] = "refund_policy";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "$this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
        $data['logged'] = false;
        if ($this->isLoggedIn) {
            $data['logged'] = true;
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $data['admin'] = true;
        } else {
            $data['admin'] = false;
        }
        $data['text'] = get_settings('refund_policy', true)['refund_policy'];
        return view('/frontend/' . config('Site')->theme . '/template', $data);
    }
    public function send_mail()
    {
    }
}
