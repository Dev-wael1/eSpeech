<?php

namespace App\Controllers\api;

use App\Controllers\BaseController;
use App\Libraries\IBM;
use App\Libraries\Google;
use App\Libraries\Azure;
use App\Libraries\Aws;
use App\Libraries\Paypal_lib;
use App\Libraries\Paystack;
use App\Libraries\Razorpay;
use App\Libraries\Stripe;
use App\Models\Tts_model;
use App\Models\Bank_transfers_model;
use App\Models\Subscription_model;
use App\Models\Bank_transfers;
use stdClass;
use App\Libraries\Paytm;

use function PHPUnit\Framework\isEmpty;

/* 
    -----------------------
    APIs 
    -----------------------
    1.  index()
    2.  register()
    3.  login()
    4.  verify_user()
    5.  languages()
    6.  voices()
    7.  synthesize()
    8.  update_fcm()
    9.  settings()
    10. predefined_tts()
    11. plans()
    12. subscriptions()
    13. add_subscription()
    14. add_transaction()
    15. get_transactions()
    16. forgot_password()
    17. update_user()
    18. change_password()
    19. predefined_voice() //disabled
    20. save_tts()
    21. delete_tts()
    22. available_settings()
    23. user_details()
    24. saved_tts()
    24. bank_transfers()
    25. get_tags()
    26. convert_active()
    27. bank_transfers()
    28. upload_receipts()
    29.  generate_paytm_checksum()
    30.  generate_paytm_txn_token()
    31.  validate_paytm_checksum()
    32.  
*/

class V1 extends BaseController
{

    protected $request;
    public $transfer, $paytm, $paypal_lib;
    function __construct()
    {
        helper('api');
        $this->request = \Config\Services::request();
        $this->transfer = new Bank_transfers();
        $this->paytm = new Paytm;
        $this->paypal_lib = new Paypal_lib;
    }
    private  $allowed_settings = ["general_settings", "terms_conditions", "privacy_policy", "about_us", 'payment_gateways_settings', 'tts_config'];
    private  $user_data = ['id', 'first_name', 'last_name', 'phone', 'email', 'fcm_id', 'image'];
    // 1.
    public function index()
    {
        $response = \Config\Services::response();
        helper("filesystem");
        $response->setHeader('content-type', 'Text');
        return $response->setBody(file_get_contents(base_url('apidoc.txt')));
    }

    //2.
    public function register()
    {
        /*
        Post paramrter
        
        1. first_name
        2. last_name
        3. email
        5. password
        6. Phone (optional)
        */
        if (!isset($_POST)) {
            $response = [
                'error' => true,
                'message' => "Please use Post request",
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
        $ionAuth    = new \IonAuth\Libraries\IonAuth();
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $config = new \Config\IonAuth();
        $identity_column = $config->identity;
        $validation->setRules(
            [
                'email' => 'required|valid_email',
                'phone' => 'permit_empty',
                'first_name' => 'required',
                'last_name' => 'required',
                'password' => 'required|min_length[8]',
                'fcm_id' => 'permit_empty'
            ],
            [
                'first_name' => [
                    'required' => 'First Name is required',
                ],
                'last_name' => [
                    'required' => 'Last Name is required',
                ],
                'email' => [
                    'required' => 'Email is required',
                ],
                'password' => [
                    'required' => 'Password is required',
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
        $first_name = $request->getPost('first_name');
        $last_name = $request->getPost('last_name');
        $email = $request->getPost('email');
        $password = $request->getPost('password');
        if ($request->getPost('phone')) {
            $phone = $request->getPost('phone');
        } else {
            $phone = null;
        }
        $identity = ($identity_column == 'email') ? $email : $phone;

        $additional_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'active' => '1',
            'phone' => $phone,
        ];

        if ($request->getPost('fcm_id')) {
            $additional_data['fcm_id'] = $request->getPost('fcm_id');
        }

        if ($this->request->getPost() && $validation->withRequest($this->request)->run() && $user_id = $ionAuth->register($identity, $password, $email, $additional_data)) {
            $data = array();
            $token = generate_tokens($identity);
            update_details(['api_key' => $token], ['username' => $identity], "users");
            $data = fetch_details('users', ['id' => $user_id], $this->user_data)[0];
            $data['active_subscription'] = null;
            $data['upcoming_subscription'] = null;
            if ($subscription = get_subscription($data['id'], true)) {
                $data['active_subscription'] = $subscription[0];
            }

            $response = [
                'error' => false,
                'token' => $token,
                'message' => 'User Registered successfully',
                'data' => $data
            ];

            return $this->response->setJSON($response);
        } else {

            $msg = trim(preg_replace('/\r+/', '', preg_replace('/\n+/', '', preg_replace('/\t+/', ' ', strip_tags($ionAuth->errors())))));
            $response = [
                'error' => true,
                'message' => $msg,
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
    }
    //3.
    public function login()
    {


        $ionAuth = new \IonAuth\Libraries\IonAuth();
        $config = new \Config\IonAuth();
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $identity_column = $config->identity;
        // 

        if ($identity_column == 'phone') {
            $identity = $request->getPost('phone');
            $validation->setRule('phone', 'Phone', 'numeric|required');
        } elseif ($identity_column == 'email') {
            $identity = $request->getPost('email');
            $validation->setRule('email', 'Email', 'required|valid_email');
        } else {
            $validation->setRule('identity', 'Identity', 'required');
        }
        $validation->setRule('password', 'Password', 'required');
        $password = $request->getPost('password');
        if ($request->getPost('fcm_id')) {
            $validation->setRule('fcm_id', 'FCM ID', 'trim');
        }
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            $response = [
                'error' => true,
                'message' => $errors,
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
        $status = fetch_details('users', ['email' => $identity], ['active']);
        // print_r($status);

        $login = $ionAuth->login($identity, $password, false);
        if ($login) {
            // Login Success
            if (($request->getPost('fcm_id')) && !empty($request->getPost('fcm_id'))) {
                update_details(['fcm_id' => $request->getPost('fcm_id')], [$identity_column => $identity], 'users');
            }
            $data = array();
            array_push($this->user_data, "api_key");
            $data = fetch_details('users', [$identity_column => $identity], $this->user_data)[0];
            $data['image'] = base_url('public/backend/assets/profiles/' . $data['image']);
            $data['active_subscription'] = null;
            if ($subscription = get_subscription($data['id'], true)) {
                $data['active_subscription'] = $subscription[0];
            }
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                if ($identity == "user@espeech.in") {
                    $token = $data['api_key'];
                } else {
                    $token = generate_tokens($identity);
                    update_details(['api_key' => $token], ['username' => $identity], "users");
                }
            } else {
                $token = generate_tokens($identity);
                update_details(['api_key' => $token], ['username' => $identity], "users");
            }
            if (isset($data['api_key'])) {
                unset($data['api_key']);
            }
            $user_id = fetch_details('users', ['username' => $identity])[0]['id'];
            $data["upcoming_subscription"] = upcoming_plan($user_id);
            $response = [
                'error' => false,
                "token" => $token,
                'message' => 'User Logged successfully',
                'data' => $data
            ];
            return $this->response->setJSON($response);
        } elseif ($login && $status[0]['active'] == 0) {
            $response = [
                'error' => true,
                'message' => "You'r account has been suspend",
            ];
            return $this->response->setJSON($response);
        } else {
            // Login Failed
            if (!exists([$identity_column => $identity], 'users')) {
                $response = [
                    'error' => true,
                    'message' => 'User does not exists !',
                ];
                return $this->response->setJSON($response);
            }
            $response['error'] = true;
            $response['message'] = 'Incorrect password !';
            return $this->response->setJSON($response);
        }
    }
    //4.
    public function verify_user()
    {

        /* Parameters to be passed
            phone: 9874565478
                or
            email: test@gmail.com 
        */
        if (!verify_tokens()) {
            return false;
        }
        $request = \Config\Services::request();
        $config = new \Config\IonAuth();
        $identity_column = $config->identity;
        $validation =  \Config\Services::validation();
        if ($identity_column == 'email') {
            $validation->setRule('email', 'Email', 'valid_email|required');
            $identity = $request->getPost('email');
        } elseif ($identity_column == 'phone') {
            $validation->setRule('phone', 'Phone', 'required');
            $identity = $request->getPost('phone');
        }
        if (!$validation->withRequest($this->request)->run()) {
            $response['error'] = true;
            $response['message'] = $validation->getErrors();
            $response['data'] = array();
            return $this->response->setJSON($response);
        } else {
            if (($request->getPost('phone')) && exists([$identity_column => $identity], 'users')) {
                $response['error'] = true;
                $response['message'] = 'Phone Number is already registered.Please try again !';
                $response['data'] = array();
                return $this->response->setJSON($response);
            }
            if (($request->getPost('email')) && exists([$identity_column => $identity], 'users')) {
                $response['error'] = true;
                $response['message'] = 'Email is already registered.Please try again !';
                $response['data'] = array();
                print_r(json_encode($this->response));
                return $this->response->setJSON($response);
            }

            $response['error'] = false;
            $response['message'] = 'User does not exist.';
            $response['data'] = array();
            return $this->response->setJSON($response);
        }
    }

    public function languages()
    {
        //returns all the supported languages
        // no parameters required

        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $new = [];
        // $languages = json_decode(fetch_details('settings', ['variable' => 'languages'])[0]['value']);
        // $languages = fetch_details('tts_languages', ['status' => '1']);
        $languages = get_api_languages();
        // print_r($languages);
        
        $data = [
            'error' => (!empty($languages)) ? false : true,
            'message' => (!empty($languages)) ? "Languages recieved successfully" : "No languages found!",
            'data' => $languages,
        ];
        return $this->response->setJSON($data);
    }
    //6.
    public function voices()
    {

        /* Parameters to be passed
            language : {language-code}
        */

        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
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
                'data' => []
            ];
            return $this->response->setJSON($response);
        }

        $language = $request->getPost('language');
        $voices = get_api_voices($language);
        if (!empty($voices)) {
            $response = [
                'error' => false,
                'message' => 'Voices recieved successfully..',
                'data' => $voices
            ];
        } else {
            $response = [
                'error' => true,
                'message' => 'No voices found',
                'data' => []
            ];
        }
        return $this->response->setJSON($response);
    }
    //7.
    public function synthesize()
    {
        /* 
            
            Parameters to be passed

            provider: azure                 //  range { IBM , azure , aws , google }
            voice: en-US-JennyNeural        //  we can get it from voices api 
            text: hello, this is espeech    //  the text to be synthesise
            language:en-US                  //  pass language of the
     
            title: {title}                  //  title of the text to speech

        */

        $request = \Config\Services::request();

        $is_free_characters_allowed = $request->getPost('is_free_characters_allowed');
        $tts_settings = get_settings("tts_config", true);
        $validation =  \Config\Services::validation();
        $validation->setRules(
            [
                'provider' => 'required',
                'voice' => 'required',
                'text' => 'required',
                'language' => 'required',
                'is_free_characters_allowed' => 'required',
                'title' => 'permit_empty'
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
                    'required' => 'Language is required',
                ],
                'is_free_characters_allowed' => [
                    'required' => 'character specification is required',
                ],
            ]
        );
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            $response = [
                'error' => true,
                'message' => $errors,
                'upcoming' => false,
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
        $voice = $request->getPost('voice');
        $language = $request->getPost('language');
        $text = $request->getPost('text');

        $title = $request->getPost('title');
        $provider = strtolower($request->getPost('provider'));
        if ($is_free_characters_allowed == 'false') {
            $disabled_providers = array('azure' => 'Azure');

            if (!$user_token = verify_tokens()) {
                $status = $this->response->getStatusCode();
                return $this->response->setStatusCode($status);
            }
            $user_id = $user_token;
            $characters = user_characters($user_id);

            $upcomming = false;

            if (upcoming_plan($user_id) != null) {
                $upcomming = true;
            }

            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {

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
            }

            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $settings = get_settings("tts_config", true);
                if (strlen($text) > $settings["freeTierCharacterLimit"]) {
                    $response = [
                        'error' => true,
                        'message' => str_replace("<chars>", (string)$settings["freeTierCharacterLimit"], DEMO_MODE_CHARACTERS_ERROR),
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => $settings
                    ];
                    return $this->response->setJSON($response);
                }
            }
            if (!verify_voice($language, $voice, $provider)) {
                $response = [
                    'error' => true,
                    'upcoming' => $upcomming,
                    'message' => "voice with provider did not match",
                    'data' => []
                ];
                return print_r(json_encode($response));
            }

            if (active_plan_type($user_id) == 'general') {
                $remaining = $characters['remaining_characters'];
            } elseif (active_plan_type($user_id) == 'provider') {
                switch (strtolower($provider)) {
                    case "ibm":
                        $remaining = $characters['ibm'] + $characters['remaining_characters'];
                        break;
                    case "aws":
                        $remaining = $characters['aws'] + $characters['remaining_characters'];
                        break;
                    case "azure":
                        $remaining = $characters['azure'] + $characters['remaining_characters'];
                        break;
                    case "google":
                        $remaining = $characters['google'] + $characters['remaining_characters'];
                        break;
                }
            } else {
                $response = [
                    'error' => true,
                    'upcoming' => $upcomming,
                    'message' => "User do not have any active Plans",
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }


            if (($remaining > 0) && $remaining >= strlen($text)) {
                if ($provider == "ibm") {
                    $ibm = new IBM;
                    $data = $ibm->synthesize($voice, $text, true);
                } elseif ($provider == "aws") {
                    $aws = new Aws;
                    if (!$data = $aws->systhesize($voice, $text, $language, true)) {
                        $response = [
                            'error' => true,
                            'upcoming' => $upcomming,
                            'message' => "please Input valid tags.",
                            'data' => []
                        ];
                        return $this->response->setJSON($response);
                    }
                } elseif ($provider == "azure") {
                    $azure = new Azure;
                    if ($data = $azure->systhesize($language, $voice, $text)) {
                        $data = $azure->systhesize($language, $voice, $text);
                        if ($data == '') {
                            $response = [
                                'error' => true,
                                'upcoming' => $upcomming,
                                'message' => "language and the text should match in azure.",
                                'data' => []
                            ];
                            return $this->response->setJSON($response);
                        }
                    } else {
                        $response = [
                            'error' => true,
                            'upcoming' => $upcomming,
                            'message' => "please Input valid tags.",
                            'data' => []

                        ];
                        return $this->response->setJSON($response);
                    }
                } elseif ($provider == "google") {
                    $google = new Google;
                    $data = $google->systhesize($language, $voice, $text);
                } else {
                    $response = [
                        'error' => true,
                        'upcoming' => $upcomming,
                        'message' => "Provider is not valid",
                        'data' => []
                    ];
                    return print_r(json_encode($response));
                }

                if (update_characters(strlen($text), $user_id, $provider)) {
                    $model = new Tts_model;
                    $id = $model->insert_tts(['user_id' => $user_id, 'language' => $language, 'voice' => $voice, 'title' => $title, 'provider' => $provider, 'text' => $text, 'is_saved' => 0, 'used_characters' => strlen($text)]);
                    $row = [
                        'tts_id' => $id,
                        'base_b4' => $data
                    ];
                    $response = [
                        'error' => false,
                        'upcoming' => $upcomming,
                        'message' => "Synthesized successfully",
                        'data' => $row
                    ];
                    return $this->response->setJSON($response);
                }
                $response = [
                    'error' => true,
                    'upcoming' => $upcomming,
                    'message' => "Something went wrong please try again.",
                    'data' => []
                ];
                return $this->response->setJSON($response);
            } elseif ($tts_settings['isFreeTierAllows'] == "true" && $tts_settings['freeTierCharacterLimit'] <= strlen($text)) {
                if ($provider == "ibm") {
                    $ibm = new IBM;
                    $data = $ibm->synthesize($voice, $text, true);
                    $row = [
                        'base_b4' => $data
                    ];
                    $response = [
                        'error' => false,
                        'upcoming' => $upcomming,
                        'message' => "Synthesized successfully",
                        'data' => $row
                    ];
                    return $this->response->setJSON($response);
                } elseif ($provider == "aws") {
                    $aws = new Aws;
                    if (!$data = $aws->systhesize($voice, $text, $language, true)) {
                        $response = [
                            'error' => true,
                            'upcoming' => $upcomming,
                            'message' => "please Input valid tags.",
                            'data' => []
                        ];
                        return $this->response->setJSON($response);
                    }
                } elseif ($provider == "azure") {
                    $azure = new Azure;
                    if ($data = $azure->systhesize($language, $voice, $text)) {
                        $data = $azure->systhesize($language, $voice, $text);
                        if ($data == '') {
                            $response = [
                                'error' => true,
                                'upcoming' => $upcomming,
                                'message' => "language and the text should match in azure.",
                                'data' => []
                            ];
                            return $this->response->setJSON($response);
                        }
                    } else {
                        $response = [
                            'error' => true,
                            'upcoming' => $upcomming,
                            'message' => "please Input valid tags.",
                            'data' => []

                        ];
                        return $this->response->setJSON($response);
                    }
                } elseif ($provider == "google") {
                    $google = new Google;
                    $data = $google->systhesize($language, $voice, $text);
                } else {
                    $response = [
                        'error' => true,
                        'upcoming' => $upcomming,
                        'message' => "Provider is not valid",
                        'data' => []
                    ];
                    return print_r(json_encode($response));
                }
            } else {
                $upcomming = false;
                if (upcoming_plan($user_id) != null) {
                    $upcomming = true;
                }
                $response = [
                    'error' => true,
                    'upcoming' => $upcomming,
                    'message' => "You do not have sufficient characters",
                    'upcoming' => $upcomming,
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }
        } else if ($is_free_characters_allowed == 'true') {
            if ($tts_settings['isFreeTierAllows'] == "true" && $tts_settings['freeTierCharacterLimit'] >= strlen($text)) {

                if ($provider == "ibm") {
                    $ibm = new IBM;
                    $data = $ibm->synthesize($voice, $text, true);
                    $row = [
                        'base_b4' => $data
                    ];
                    $response = [
                        'error' => false,
                        'upcoming' => 'Free characters used',
                        'message' => "Synthesized successfully",
                        'data' => $row
                    ];
                    return $this->response->setJSON($response);
                } elseif ($provider == "aws") {
                    // echo"here";
                    // die();
                    $aws = new Aws;
                    $data = $aws->systhesize($voice, $text, $language, true);
                    $row = [
                        'base_b4' => $data
                    ];
                    if ($data == "") {
                        $response = [
                            'error' => true,
                            'upcoming' => 'Free Characters',
                            'message' => "please Input valid tags.",
                            'data' => []
                        ];
                        return $this->response->setJSON($response);
                    } else {
                        $response = [
                            'error' => false,
                            'upcoming' => 'Free characters used',
                            'message' => "Synthesized successfully",
                            'data' => $row
                        ];
                        return $this->response->setJSON($response);
                    }
                } elseif ($provider == "azure") {
                    $azure = new Azure;
                    if ($data = $azure->systhesize($language, $voice, $text)) {
                        $data = $azure->systhesize($language, $voice, $text);
                        if ($data == '') {
                            $response = [
                                'error' => true,
                                'upcoming' => 'Free Characters',
                                'message' => "language and the text should match in azure.",
                                'data' => []
                            ];
                            return $this->response->setJSON($response);
                        } else {
                            $row = [
                                'base_b4' => $data
                            ];
                            $response = [
                                'error' => false,
                                'upcoming' => 'Free characters used',
                                'message' => "Synthesized successfully",
                                'data' => $row
                            ];
                            return $this->response->setJSON($response);
                        }
                    } else {
                        $response = [
                            'error' => true,
                            'upcoming' => 'Free Characters',
                            'message' => "please Input valid tags.",
                            'data' => []

                        ];
                        return $this->response->setJSON($response);
                    }
                } elseif ($provider == "google") {
                    $google = new Google;
                    $data = $google->systhesize($language, $voice, $text);
                    $row = [
                        'base_b4' => $data
                    ];
                    $response = [
                        'error' => false,
                        'upcoming' => 'Free characters used',
                        'message' => "Synthesized successfully",
                        'data' => $row
                    ];
                    return $this->response->setJSON($response);
                } else {
                    $response = [
                        'error' => true,
                        'upcoming' => 'Free Characters',
                        'message' => "Provider is not valid",
                        'data' => []
                    ];
                    return  $this->response->setJSON($response);
                }
            }
        } else {
            $response = [
                'error' => true,
                'message' => "please select either true or false",
                'data' => []
            ];
            return  $this->response->setJSON($response);
        }
    }
    //8.
    public function update_fcm()
    {
        /* Parameters to be passed
          
            fcm_id: FCM_ID
        */

        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [

                'fcm_id' => 'required',
            ],
            [

                'fcm_id' => [
                    'required' => 'FCM ID is required',
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
        if ($request->getPost('fcm_id')) {
            $user_id = $user_token;
            $fcm_id = $request->getPost('fcm_id');
            if (($user_id && $fcm_id) != NULL && !empty($request->getPost('fcm_id'))) {
                $user_res = update_details(['fcm_id' => $fcm_id], ['id' => $user_id], 'users');
                if ($user_res) {
                    $response['error'] = false;
                    $response['message'] = 'Updated Successfully';
                    $response['data'] = array();
                    return $this->response->setJSON($response);
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Updation Failed !';
                    $response['data'] = array();
                    return $this->response->setJSON($response);
                }
            }
        }
    }
    //9.
    public function settings()
    {
        /* Parameters to be passed
            variable:{variable Name}
        */

        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();

        $validation->setRules(
            ['variable' => 'required',],
            ['variable' => ['required' => 'Please specify settings type']]
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
        $variable = $request->getPost('variable');
        if (in_array(trim($variable), $this->allowed_settings)) {
            $setting = array();
            if ($variable == "payment_gateways_settings") {
                $payments = get_settings($variable, true);

                $razorpay = new Razorpay;
                $paystack = new Paystack;
                $stripe = new Stripe;
                $paytm = new Paytm;
                $paypal = new Paypal_lib;

                $setting['razorpay'] = $razorpay->get_credentials();
                $setting['razorpay']['status'] = $payments['razorpayApiStatus'];
                $setting['razorpay']['mode'] = $payments['razorpay_mode'];


                $setting['paystack'] = $paystack->get_credentials();
                $setting['paystack']['status'] = $payments['paystack_status'];
                $setting['paystack']['mode'] = $payments['paystack_mode'];


                $setting['stripe'] = $stripe->get_credentials();
                $setting['stripe']['status'] = $payments['stripe_status'];
                $setting['stripe']['mode'] = $payments['stripe_mode'];

                $setting['bank']['status'] =  get_settings("payment_gateways_settings", true)["bank_status"];
                $setting['bank']['instructions'] =  get_settings("payment_gateways_settings", true)["bank_instruction"];
                $setting['bank']['account_details'] =  get_settings("payment_gateways_settings", true)["account_details"];
                $setting['bank']['extra_details'] =  get_settings("payment_gateways_settings", true)["extra_details"];


                $setting['paytm'] = $paytm->get_credentials();
                $setting['paytm']['status'] = $payments['paytm_status'];
                $setting['paytm']['mode'] = $payments['paytm_mode'];

                // paypal data 
                $setting['paypal'] = $paypal->get_credentials();
                $setting['paypal']['access_token_url'] = $paypal->get_credentials()['end_point_url'] . 'v1/oauth2/token';
                $setting['paypal']['payment_fetch_url'] = $paypal->get_credentials()['end_point_url'] . 'v2/payments/captures/';

                // it ends here

            } elseif ($variable == "general_settings" || $variable == 'privacy_policy') {
                $setting = get_settings($variable, true);
            } else {
                $setting = get_settings($variable, true);
            }
            isset($logo) && $logo != "" ? $settings['logo'] =  base_url("public/uploads/site/" . $logo) : $settings['logo'] = base_url('public/backend/assets/img/news/img01.jpg');
            if ($setting) {
                if (!empty($setting)) {
                    $response = [
                        'error' => false,
                        'message' => "setting recieved Successfully",
                        'data' => $setting
                    ];
                } else {
                    $response = [
                        'error' => true,
                        'message' => "No data found in setting",
                        'data' => $setting
                    ];
                }
                return $this->response->setJSON($response);
            } else {
                $response = [
                    'error' => true,
                    'message' => '`Invalid `setting',
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }
        } else {
            $response = [
                'error' => true,
                'message' => 'Invalid settings',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
    }
    //10.
    public function predefined_tts()
    {
        /* Parameters to be passed
            voice:en-US_LisaV3Voice      //required
            language:en-US               //required
            provider:ibm                 //required
          
        */
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [
                'provider' => 'required',
                'voice' => 'required',
                'language' => 'required',
            ],
            [
                'provider' => [
                    'required' => 'provider is required',
                ],
                'voice' => [
                    'required' => 'voice is required',
                ],
                'language' => [
                    'required' => 'Language is required',
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
        $voice = $request->getPost('voice');
        $language = $request->getPost('language');
        if (!verify_voice($language, $voice, $provider)) {
            $response = [
                'error' => true,
                'message' => "voice with provider or language with voice did not match",
                'data' => []
            ];
            return $this->response->setJSON($response);;
        }

        $data = fetch_details('predefined_tts', ['voice' => $voice, 'status' => 1]);
        if (!empty($data)) {
            $response = [
                'error' => false,
                'message' => "Recieved successfully.",
                'data' => $data[0]
            ];
        } else {
            $response = [
                'error' => true,
                'message' => "No voices found.",
                'data' => []
            ];
        }
        return $this->response->setJSON($response);
    }
    //11.
    public function plans()
    {

        /*  return all the plans available and if passed plan id it will return plan detials

            Parameters to be passed

            plan_id :1          //optional            
        */
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $request = \Config\Services::request();
        if ($request->getPost('plan_id')) {
            $validation =  \Config\Services::validation();
            $validation->setRules(
                [
                    'plan_id' => 'permit_empty',
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
            $plan_id = $request->getPost('plan_id');
            if (!empty(get_plans($plan_id))) {
                $response = [
                    'error' => false,
                    'message' => 'Plan Recieved Successfully.',
                    'data' => get_plans($plan_id)
                ];
            } else {
                $response = [
                    'error' => true,
                    'message' => 'No Plans Found with the given Plan Id.',
                    'data' => []
                ];
            }

            return $this->response->setJSON($response);
        } else {
            if (!empty(get_plans())) {
                $response = [
                    'error' => false,
                    'message' => 'Plans Recieved Successfully.',
                    'data' => get_plans()
                ];
            } else {
                $response = [
                    'error' => true,
                    'message' => 'No Plans Found ',
                    'data' => []
                ];
            }

            return $this->response->setJSON($response);
        }
    }
    //12. 
    public function subscriptions()
    {
        /*  
            returns all the subscriptions of the Given user.
            if passed {"active": "true"} then returns the active plan of the user.

            Parameters to be passed

                    
            active :bool        //optional            //range (true,false)
            limit:10            //optional              //default ( 25 )
            offset:0            //optional             //default ( 0 )
            sort:id             //optional            //range (id, created_on) //default ( id )
            order:DESC          //optional            //range (DESC, ASC) //default ( DESC )
        */
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $request = \Config\Services::request();
        $validation =  \Config\Services::validation();
        // $validation->setRules(
        //     [
        //         'active' => 'trim',

        //     ]
        // );
        // if (!$validation->withRequest($this->request)->run()) {
        //     $errors = $validation->getErrors();
        //     $response = [
        //         'error' => true,
        //         'message' => $errors,
        //         'data' => []
        //     ];
        //     return $this->response->setJSON($response);
        // }
        $user_id = $user_token;
        if ($request->getPost('active') && $request->getPost('active') == 'true') {
            $status = $request->getPost('active');
            if ($status == 'true') {
                $data = get_subscription($user_id, $status);
                if ($data) {

                    $response = [
                        'error' => false,
                        'message' => 'Data recieved.',
                        'data' => $data
                    ];
                } else {
                    $response = [
                        'error' => true,
                        'message' => 'No active Plans found',
                        'data' => []
                    ];
                }
                return $this->response->setJSON($response);
            }
        } else {
            $res = fetch_details('subscriptions', ['user_id' => $user_id]);
            $total = count($res);
            if (isset($_POST['limit']) && isset($_POST['offset'])) {
                $res = fetch_details('subscriptions', ['user_id' => $user_id], [], (int)$request->getPost('limit'), (int)$request->getPost('offset'));
            }
            if ($request->getPost('sort') && $request->getPost('order')) {
                $res = fetch_details('subscriptions', ['user_id' => $user_id], [], '200', 0, $request->getPost('sort'), $request->getPost('order'));
            }
            if (isset($_POST['limit']) && isset($_POST['offset']) && $request->getPost('sort') && $request->getPost('order')) {
                $res = fetch_details('subscriptions', ['user_id' => $user_id], [], $request->getPost('limit'), $request->getPost('offset'), $request->getPost('sort'), $request->getPost('order'));
            }
            foreach ($res as $key => $val) {
                $res[$key]['status'] = subscription_status($res[$key]['id']);
            }

            if (!empty($res)) {

                $response = [
                    'error' => false,
                    'message' => 'Data recieved.',
                    'total' => $total,
                    'data' => $res
                ];
                return $this->response->setJSON($response);
            } else {
                $response = [
                    'error' => true,
                    'message' => 'No Plans found',
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }
        }
    }
    //13.
    public function add_subscription()
    {
        /*
        Add subscription after the payment is successfull

        Post parameter :-

        provider:2               //required
        txn_id:2                //required      //transaction id recieved by payment gateway
        tenure_id:3             //required 
        plan_id:2               //required
      

        */

        // print_r($this->request->getPost('txn_id'));

        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        if (has_upcoming($user_token)) {
            return $this->response->setJSON([
                'error' => true,
                'message' => "user already have a upcoming plan",
                'data' => []
            ]);
        }
        $validation =  \Config\Services::validation();
        $validation->setRules(
            [
                'provider' => 'required',
                // 'txn_id' => '',
                'tenure_id' => 'required',
                'plan_id' => 'required',

            ],
            [

                'provider' => [
                    'required' => 'Payment provider id is required',
                ],
                'tenure_id' => [
                    'required' => 'tenure id is required',
                ],
                'plan_id' => [
                    'required' => 'Plan id is required',
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
        $id = $user_token;
        if (!exists(['id' => $id], 'users')) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'user not found',
                'data' => []
            ]);
        }


        if ($provider = $this->request->getPost('provider')) {
            $db = \Config\Database::connect();
            $tenure_id = $this->request->getPost('tenure_id');
            $plan_id = $this->request->getPost('plan_id');
            $tenure = fetch_details('plans_tenures', ['id' => $tenure_id, 'plan_id' => $plan_id], ['price', 'discounted_price', 'months']);


            if ($tenure == []) {
                $response['message'] = "details don't match up please confirm either plan_id or tenure_id is mismatched";
                $response['error'] = true;
                return $this->response->setJSON($response);
            }
            // print_r($tenure);
            $price = $tenure[0]['price'];
            $months = $tenure[0]['months'];
            $discounted_price = $tenure[0]['discounted_price'];

            if ($provider == 'stripe') {

                $stripe = new Stripe;
                $payload = [
                    'amount' => ($price * 100),
                    'metadata' => [
                        'user_id' => $id,
                        'amount' => $price,
                        'plan_id' => $plan_id,
                        'tenure' => $tenure_id
                    ]
                ];
                $order = $stripe->create_payment_intent($payload);
                $data['paymentIntent'] = $order['client_secret'];
                // $data['order_id'] = $order['id'];
                return $this->response->setJSON([
                    "error" => false,
                    "message" => "Stripe Payment initiated",
                    "data" => $order
                ]);
            }
            $txn_id = $this->request->getPost('txn_id');
            if (exists(['txn_id' => $txn_id, 'payment_method' => $provider], 'transactions')) {
                return $this->response->setJSON([
                    'error' => true,
                    'message' => 'Unauthorized',
                    'data' => []
                ]);
            }
            if (has_upcoming($id) || active_plan($id)) {
                return $this->response->setJSON([
                    'error' => true,
                    'message' => 'user already have an plane or upcoming plan ',
                    'data' => []
                ]);
            }

            $insert_id = add_transaction($txn_id, $price, $provider, $id);
            if ($provider == 'razorpay') {
                $razorpay = verify_payment_transaction($txn_id, 'razorpay', $insert_id);
                if ($razorpay['error']) {
                    $response['error'] = true;
                    $response['message'] = "Invalid Razorpay Payment Transaction.";
                    $response['data'] = [];
                    update_details([
                        'message' => $response['message'],
                        'status' => $razorpay['status'],
                        'amount' => $price
                    ], [
                        'id' => $insert_id
                    ], 'transactions');
                    return $this->response->setJSON($response);
                } elseif ($razorpay['amount'] >= $price) {
                    if ($sub_id = add_subscription($id, $plan_id, $tenure[0]['months'], $txn_id, $price)) {
                        $response['error'] = false;
                        $response['message'] = "Order Placed Successfully";
                        $response['data'] = $razorpay;
                        $response['plan'] = $plan_id;
                        update_details(
                            [
                                'message' => $response['message'],
                                'status' => $razorpay['status'],
                                'subscription_id' =>  $sub_id,
                                'amount' => $price
                            ],
                            [
                                'id' => $insert_id
                            ],
                            'transactions'
                        );
                        update_details(
                            [

                                'transaction_id' => $insert_id,
                            ],
                            [
                                'id' => $sub_id,

                            ],
                            'subscriptions'
                        );
                        return $this->response->setJSON($response);
                    }
                    $response['error'] = true;
                    $response['message'] = "something went wrong";
                    $response['data'] = $razorpay;

                    return $this->response->setJSON($response);
                } else {

                    return $this->response->setJSON([
                        'error' => true,
                        'message' => 'Transaction ammount and price of the plan did not match..',
                        'data' => []
                    ]);
                }
            } elseif ($provider == 'paystack') {
                $transfer = verify_payment_transaction($txn_id, 'paystack');
                if (isset($transfer['data']['status']) && $transfer['data']['status']) {
                    if (isset($transfer['data']['data']['status']) && $transfer['data']['data']['status'] != "success") {
                        $response['error'] = true;
                        $response['message'] = "Invalid Paystack Transaction.";
                        $response['data'] = array();
                        update_details(
                            [
                                'message' => $response['message'],
                                'status' => 'failed',
                                'amount' => $price
                            ],
                            [
                                'id' => $insert_id
                            ],
                            'transactions'
                        );

                        return $this->response->setJSON($response);
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Error While Fetching the Order Details.Please Contact Admin.";
                    $response['data'] = $transfer;

                    return $this->response->setJSON($response);
                }
                if ($transfer['amount'] >= $price) {

                    if ($sub_id = add_subscription($id, $plan_id, $tenure[0]['months'], $txn_id, $price)) {
                        $response['error'] = false;
                        $response['message'] = "Subscription added Successfully";
                        $response['data'] = $transfer['data'];
                        $response['plan'] = $plan_id;
                        update_details(
                            [
                                'message' => $response['message'],
                                'status' => $transfer['status'],
                                'subscription_id' =>  $sub_id,
                                'amount' => $price
                            ],
                            [
                                'id' => $insert_id
                            ],
                            'transactions'
                        );
                        update_details(
                            [

                                'transaction_id' => $insert_id,
                            ],
                            [
                                'id' => $sub_id,

                            ],
                            'subscriptions'
                        );
                        return $this->response->setJSON($response);
                    }
                    $response['error'] = true;
                    $response['message'] = "something went wrong";
                    $response['data'] = $transfer;
                    update_details(
                        [
                            'message' => $response['message'],
                            'status' => 'failed',
                            'amount' => $price
                        ],
                        [
                            'id' => $insert_id
                        ],
                        'transactions'
                    );

                    return $this->response->setJSON($response);
                } else {
                    return $this->response->setJSON([
                        'error' => true,
                        'message' => 'Transaction ammount and price of the plan did not match...',
                        'data' => []
                    ]);
                }
            } elseif ($provider == 'bank') {
                $method = 'bank_transfers';
                $message = "order placed successfully";
                $final_price = $price - $discounted_price;
                $txn_id = $tenure_id = $this->request->getPost('txn_id');
                $is_bank = true;


                if ($sub_id = add_subscription($id, $plan_id, $months, $insert_id, $discounted_price, "", $start_now = false, $is_bank)) {
                    $response['message'] = $message;
                    $response['error'] = false;
                    $response['plan'] = $plan_id;
                    update_details(
                        ['subscription_id' => $sub_id, 'message' => $message, 'amount'  => $final_price],
                        ['id' => $insert_id],
                        'transactions'
                    );
                    return $this->response->setJSON($response);
                } else {
                    $response['error'] = true;
                    $response['message'] = "failed";
                    return $this->response->setJSON($response);
                }
            } elseif ($provider == 'paytm') {
                $payment = verify_payment_transaction($txn_id, 'paytm');

                $status = ($payment['data']['body']['resultInfo']['resultStatus'] == "TXN_SUCCESS") ? "Success" : "Pending";

                if ($status == "Success") {
                    if ($payment['data']['body']['txnAmount'] >= $price) {


                        if ($sub_id = add_subscription($id, $plan_id, $tenure[0]['months'], $txn_id, $price)) {

                            $txn_id = $this->request->getPost('txn_id');
                            $response['error'] = false;
                            $response['message'] = "Order Placed Successfully";
                            $response['data'] = $payment;
                            $response['plan'] = $plan_id;

                            update_details(
                                [
                                    'message' => $response['message'],
                                    'status' => 'Success',
                                    'subscription_id' =>  $sub_id,
                                    'amount' => $price
                                ],
                                [
                                    'id' => $insert_id
                                ],
                                'transactions'
                            );
                            update_details(
                                [

                                    'transaction_id' => $insert_id,
                                ],
                                [
                                    'id' => $sub_id,

                                ],
                                'subscriptions'
                            );


                            return $this->response->setJSON($response);
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Something went wrong";
                        $response['data'] = '';

                        return $this->response->setJSON($response);
                    }
                } else if ($status == "Pending") {
                    $response = [
                        'error' => true,
                        'message' => "Your transaction is currently pending ",
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => [
                            'error' => true,
                        ],
                    ];
                    return $this->response->setJSON($response);
                } else {
                    $response = [
                        'error' => true,
                        'message' => "Your transaction may have failed due to some reason please try again later on",
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => [
                            'error' => true,
                        ],
                    ];
                    return $this->response->setJSON($response);
                }
            } elseif ($provider == 'paypal') {
                $payment = verify_payment_transaction($txn_id, 'paypal');
                if ($payment['data']['status'] == "COMPLETED" &&  $payment['amount'] >= $price) {
                    if ($sub_id = add_subscription($id, $plan_id, $tenure[0]['months'], $txn_id, $price)) {

                        $data = [
                            'error' => 'false',
                            'message' => 'payment completed successfully',
                            'data' => [
                                'error' => false,
                                'data' => $payment
                            ],
                        ];
                        update_details(
                            [
                                'message' => $data['message'],
                                'status' => $payment['data']['status'],
                                'subscription_id' =>  $sub_id,
                                'amount' => $price
                            ],
                            [
                                'id' => $insert_id
                            ],
                            'transactions'
                        );
                        update_details(
                            [

                                'transaction_id' => $insert_id,
                            ],
                            [
                                'id' => $sub_id,

                            ],
                            'subscriptions'
                        );
                        return $this->response->setJSON($data);
                    } else {
                        $data = [
                            'error' => 'true',
                            'message' => 'Error occurred while completing transaction',
                            'data' => [
                                'error' => true,
                                'data' => $payment,
                            ],
                        ];
                        return $this->response->setJSON($data);
                    }

                    // return $this->response->setJSON($data);
                }
            } else {
                $data['error'] = true;
                $data['message'] = "Invalid Provider.";
                $data['data'] = array();
                return $this->response->setJSON($data);
            }
        }
    }

    //15.
    public function get_transactions()
    {
        $db      = \Config\Database::connect();
        /*
            to fetch the transaction history by a specific user

            Post parameters :-

            
            ------------------------------------------
            optional server-side pagination parameters
            ------------------------------------------
            limit                   //optional
            offset                  //optional
            sort                    //optional
            order                   //optional
        */
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $request = \Config\Services::request();
        $validation =  \Config\Services::validation();
        // $validation->setRules(
        //     [

        //         'limit' => 'trim',
        //         'offset' => 'trim',
        //         'sort' => 'trim',
        //         'order' => 'trim',
        //     ]
        // );
        // if (!$validation->withRequest($this->request)->run()) {
        //     $errors = $validation->getErrors();
        //     $response = [
        //         'error' => true,
        //         'message' => $errors,
        //         'data' => []
        //     ];
        //     return $this->response->setJSON($response);
        // }
        $user_id = $user_token;
        if (!exists(['id' => $user_id], 'users')) {
            $response = [
                'error' => true,
                'message' => 'Invalid User Id.',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }

        $limit = (isset($_POST['limit']) && !empty($_POST['limit'])) ? $_POST['limit'] : 10;
        $offset = (isset($_POST['offset']) && !empty($_POST['offset'])) ? $_POST['offset'] : 0;
        $sort = (isset($_POST['sort']) && !empty($_POST['sort'])) ? $_POST['sort'] : 't.id';
        $order = (isset($_POST['order']) && !empty($_POST['order'])) ? $_POST['order'] : 'ASC';


        $builder = $db->table('transactions t')
            ->select('t.*')->where(['t.user_id' => $user_id])
            ->limit($limit, $offset)
            ->orderBy($sort, $order)
            ->get()->getResultArray();


        $t_counter = $db->table('transactions t')->where(['t.user_id' => $user_id]);
        $total = $t_counter->get()->getNumRows();
        $arr = [];

        $path = base_url('public/uploads/images/reciept/');
        if (!empty($builder)) {

            foreach ($builder as $row) {

                //check attachments
                $images = fetch_details('bank_transfers', ['subscription_id' => $row['subscription_id']], ['attachments']);
                $attachments = [];
                //if no empty
                if (!empty($images)) {
                    foreach ($images as $key => $image) {
                        //add path
                        $attachments[] = $path . '/' . $image['attachments'];
                    }
                }
                //add attachments
                $row['attachments'] = (!empty($attachments)) ? $attachments : [];
                $s = fetch_details('subscriptions', ['id' => $row['subscription_id']]);
                if (!empty($s)) {
                    $sub = $s[0];
                } else {
                    $sub = new stdClass();
                }
                unset($row['subscription_id']);

                $row['subscription'] = $sub;
                array_push($arr, $row);
            }


            $response = [
                'error' => false,
                'message' => 'Transactions recieved successfully.',
                'total' => $total,
                'data' => $arr
            ];
            return $this->response->setJSON($response);
        } else {
            $response = [
                'error' => true,
                'message' => 'No data found',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
    }
    //16.
    public function forgot_password()
    {
        /*
        
        //this api sents the reset password link to the registered email address

        email: email@domain.com //required

        */

        $request = \Config\Services::request();
        $validation =  \Config\Services::validation();
        $validation->setRules(
            [
                'email' => 'required|valid_email',
            ],
            [
                'email' => [
                    'required' => 'Email is required',
                ]
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
        $email = $request->getPost('email');
        if (!exists(['username' => $email], 'users')) {
            $response = [
                'error' => true,
                'message' => 'Email does not exits.',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
        $status = $this->ionAuth->forgottenPassword($email);
        if ($status) {
            $response = [
                'error' => false,
                'message' => 'Password reset link sent to your registered email address.',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
        $response = [
            'error' => true,
            'message' => 'Something went wrong.',
            'data' => []
        ];
        return $this->response->setJSON($response);
    }
    //17.
    public function update_user()
    {


        /*
            This api updates the user profile
    

            Post parameters :-

          
            first_name                 //required
            last_name                 //required
            phone                    //optional
            image                   //optional

        */
        helper(['form', 'url']);
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $request = \Config\Services::request();
        $validation =  \Config\Services::validation();
        $validation->setRules(
            [

                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'permit_empty',
            ],
            [
                'first_name' => [
                    'required' => 'first name is required',
                ],
                'last_name' => [
                    'required' => 'last name is required',
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
        $user_id = $user_token;
        if (!exists(['id' => $user_id], 'users')) {
            $response = [
                'error' => true,
                'message' => 'Invalid User Id',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
        $arr = array();
        if ($request->getPost('first_name')) {
            $arr['first_name'] = $request->getPost('first_name');
        }
        if ($request->getPost('last_name')) {
            $arr['last_name'] = $request->getPost('last_name');
        }
        if ($request->getPost('phone')) {
            $arr['phone'] = $request->getPost('phone');
        }
        if ($request->getFile('image')) {
            $file = $request->getFile('image');
            if (!$file->isValid()) {

                $response = [
                    'error' => true,
                    'message' => 'Something went wrong please try after some time.',
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }
            $type = $file->getMimeType();
            if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/jpg') {
                $path = FCPATH . 'public/backend/assets/profiles/';
                $image = $file->getName();
                $newName = $file->getRandomName();
                $file->move($path, $newName);
                $arr['image'] =  $newName;
            } else {
                $response = [
                    'error' => true,
                    'message' => 'Please attach a valid image file.',
                    'data' => [],
                ];
                return $this->response->setJSON($response);
            }
        }
        if (!empty($arr)) {
            $status = update_details($arr, ['id' => $user_id], 'users');
            if ($status) {
                $data = fetch_details('users', ['id' => $user_id], $this->user_data)[0];
                if ($data['image'] != null) {
                    $data['image'] = base_url('public/backend/assets/profiles/' . $data['image']);
                } else {
                    $data['image'] = base_url('public/backend/assets/profiles/default.png');
                }
                $response = [
                    'error' => false,
                    'message' => 'User updated successfully.',
                    'data' =>  $data,

                ];
                return $this->response->setJSON($response);
            }
        } else {

            $response = [
                'error' => true,
                'message' => 'Please insert any one field to update.',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
    }
    //18.
    public function change_password()
    {
        /*
            This api changes the password of the given registered email.

            Post parameters :-

            email:admin@domain.com              //required
            old_password:{old password}         //required
            new_password:{new password}         //required

        */
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $request = \Config\Services::request();
        $validation =  \Config\Services::validation();
        $validation->setRules(
            [
                'email' => 'required',
                'old_password' => 'required',
                'new_password' => 'required',
            ],
            [
                'email' => [
                    'required' => 'Email is required',
                ],
                'old_password' => [
                    'required' => 'Old password is required',
                ],
                'new_password' => [
                    'required' => 'New password is required',
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
        $identity = $request->getPost('email');
        if (!exists(['username' => $identity], 'users')) {
            $response = [
                'error' => true,
                'message' => 'Email does not Exists.',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
        $change = $this->ionAuth->changePassword($identity, $this->request->getPost('old_password'), $this->request->getPost('new_password'));
        if ($change) {
            //if the password was successfully changed
            $response = [
                'error' => false,
                'message' => "Password changed Successfully ",
                'data' => []
            ];
            return $this->response->setJSON($response);
        } else {
            $response = [
                'error' => true,
                'message' => 'Incorrect old Password.',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
    }

    //20.
    public function save_tts()
    {
        /*
        *
         *  This api saves the tts from given base64 and tts_id
         *  
         *  Post Parameters :-
         *  1. tts_id:2             //required      //recieved from synthesize success data
         *  2. base64:{base64}      //required      //recieved from synthesize success data
         * 
         */
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [
                'tts_id' => 'required|numeric|permit_empty|integer',
                'base64' => 'required'

            ],
            [
                'tts_id' => [
                    'required' => 'tts_id is required',
                ],
                'tts_id' => [
                    'numeric' => 'invalid input',
                ],
                'base64' => [
                    'required' => 'base64 is required',
                ],

            ]
        );
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            // $response = [
            //     'error' => true,
            //     'message' => $errors,
            //     'data' => [],
            // ];
            $response['error'] = true;
            foreach ($errors as $e) {
                $response['message'] = $e;
            }
            $response['data'] = 'nothing passed';

            return $this->response->setJSON($response);
        }
        $tts_id = $request->getPost('tts_id');
        $base64 = $request->getPost('base64');
        $db      = \Config\Database::connect();
        $builder = $db->table('users_tts')->update(
            [
                'base_64' => $base64,
                'is_saved' => 1,
            ],
            [
                'id' => $tts_id
            ]
        );
        if ($builder) {
            $response = [
                'error' => false,
                'message' => 'Saved successfully',
                'data' => []
            ];

            return $this->response->setJSON($response);
        }
        $response = [
            'error' => true,
            'message' => 'Some thing went Wrong. Please try after some time.',
            'data' => []
        ];
        return $this->response->setJSON($response);
    }
    //21.
    public function delete_tts()
    {
        /**
         *  //This api deletes the data from saved voice
         *  
         *  Post Parameters :-
         *  
         *  1. tts_id: 2            //required      // can be fetched from list_tts api
         * 
         */
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [
                'tts_id' => 'required'

            ],
            [
                'tts_id' => [
                    'required' => 'tts_id is required',
                ]
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
        $tts_id = $request->getPost('tts_id');
        if (update_details(['is_saved' => 0, 'base_64' => ''], ['id' => $tts_id], 'users_tts')) {

            $response = [
                'error' => false,
                'message' => 'deleted successfully',
                'data' => []

            ];

            return $this->response->setJSON($response);
        }
        $response = [
            'error' => true,
            'message' => 'something went wrong..',
            'data' => []
        ];

        return $this->response->setJSON($response);
    }
    //22.
    public function available_settings()
    {

        $response = [
            'error' => false,
            "message" => "recieved all available settings",
            'data' => $this->allowed_settings
        ];
        return $this->response->setJSON($response);
    }
    //23.
    public function user_details()
    {
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();

        $user_id = $user_token;

        if (exists(['id' => $user_id], 'users')) {
            $data = fetch_details('users', ['id' => $user_id], $this->user_data)[0];
            $data['active_subscription'] = null;
            if ($subscription = get_subscription($data['id'], true)) {
                $data['active_subscription'] = $subscription[0];
            }
            $data["upcoming_subscription"] = upcoming_plan($user_id);
            $data['image'] = base_url('public/backend/assets/profiles/' . $data['image']);
            $response = [
                'error' => false,
                'message' => 'user data recieved successfully !',
                'data' => $data
            ];
            return $this->response->setJSON($response);
        } else {
            $response = [
                'error' => true,
                'message' => 'user not found !',
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
    }
    // 24.
    public function saved_tts()
    {
        // limit,offset,sort,search,id
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();


        $user_id = $user_token;

        $res = fetch_details('users_tts', ['user_id' => $user_id, 'is_saved' => 1]);
        $total = count($res);
        for ($i = 0; $i < $total; $i++) {
            $res[$i]['text'] = strip_tags($res[$i]['text']);
        }
        if (isset($_POST['limit']) && isset($_POST['offset'])) {
            $res = fetch_details('users_tts', ['user_id' => $user_id, 'is_saved' => 1], [], (int)$request->getPost('limit'), (int)$request->getPost('offset'));
        }
        if ($request->getPost('sort') && $request->getPost('order')) {
            $res = fetch_details('users_tts', ['user_id' => $user_id, 'is_saved' => 1], [], '200', 0, $request->getPost('sort'), $request->getPost('order'));
        }
        if (isset($_POST['limit']) && isset($_POST['offset']) && $request->getPost('sort') && $request->getPost('order')) {
            $res = fetch_details('users_tts', ['user_id' => $user_id, 'is_saved' => 1], [], $request->getPost('limit'), $request->getPost('offset'), $request->getPost('sort'), $request->getPost('order'));
        }
        if (empty($res)) {
            $response = [
                'error' => true,
                'total' => $total,
                'message' => 'no Saved TTS found',
                'data' => $res
            ];
        } else {
            $response = [
                'error' => false,
                'total' => $total,
                'message' => 'saved TTS recieved successfully',
                'data' => $res

            ];
        }
        return $this->response->setJSON($response);
    }
    // 25.
    public function get_tags()
    {

        $fields = ["title", "start_tag", "end_tag"];
        $all = fetch_details('ssml_tags');
        try {
            $voice_effects = fetch_details('ssml_tags', ['type' => 'voice_effects'], $fields);
            $say_as = fetch_details('ssml_tags', ['type' => 'say_as'], $fields);
            $emphasis = fetch_details('ssml_tags', ['type' => 'emphasis'], $fields);
            $volume = fetch_details('ssml_tags', ['type' => 'volume'], $fields);
            $speed = fetch_details('ssml_tags', ['type' => 'speed'], $fields);
            $pitch = fetch_details('ssml_tags', ['type' => 'pitch'], $fields);
            $pauses = fetch_details('ssml_tags', ['type' => 'pauses'], $fields);

            $response['data']['aws'] = [
                'voice_effects' => $voice_effects,
                'say_as' => $say_as,
                'emphasis' => $emphasis,
                'volume' => $volume,
                'speed' => $speed,
                'pitch' => $pitch,
                'pauses' => $pauses,
            ];
            $response['data']['google'] = [
                'say_as' => $say_as,
                'emphasis' => $emphasis,
                'volume' => $volume,
                'speed' => $speed,
                'pitch' => $pitch,
                'pauses' => $pauses,
            ];
            $response['data']['azure'] = [
                'say_as' => $say_as,
                'emphasis' => $emphasis,
                'volume' => $volume,
                'speed' => $speed,
                'pitch' => $pitch,
                'pauses' => $pauses,
            ];
            $response['data']['ibm'] = [
                'speed' => $speed,
                'pitch' => $pitch,
                'pauses' => $pauses,
            ];
            if (isset($_POST['provider'])) {
                $provider = $_POST['provider'];
                $response['error'] = false;
                $response['message'] = 'tags recieved successfully';
                switch ($provider) {

                    case "aws":

                        $response['data'] = $response['data']['aws'];
                        break;
                    case "azure":
                        $response['data'] = $response['data']['azure'];
                        break;
                    case "google":
                        $response['data'] = $response['data']['google'];
                        break;
                    case "ibm":
                        $response['data'] = $response['data']['ibm'];
                        break;
                }
            }
        } catch (\Exception $e) {

            $response = [
                "error" => true,
                'message' => $e->getMessage(),
                "data" => []
            ];
        }
        $response = [
            'error' => false,
            'message' => "Tags recieved successfully.",
            'data' => $response['data']
        ];
        return $this->response->setJSON($response);
    }

    // 26.
    public function convert_active()
    {
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        if (convert_active($user_token)) {
            return $this->response->setJSON([
                'error' => false,
                'message' => 'your upcoming plan started from today',
                'data' => []
            ]);
        } else {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'something went wrong...',
                'data' => []
            ]);
        }
    }

    // 27.
    public function bank_transfers()
    {
        // limit,offset,sort,search,id
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }

        $_GET['user_id'] = $user_token;
        $_GET['subscription_id'] = (isset($_GET['subscription_id']) && !empty(trim($_GET['subscription_id'])) && is_numeric($_GET['subscription_id'])) ? trim($_GET['subscription_id']) : "";
        $_GET['sort'] = (isset($_GET['sort']) && !empty(trim($_GET['sort']))) ? trim($_GET['sort']) : "id";
        $_GET['order'] = (isset($_GET['order']) && !empty(trim($_GET['order']))) ? trim($_GET['order']) : "DESC";
        $_GET['offset'] = (isset($_GET['offset']) && !empty(trim($_GET['offset'])) && is_numeric($_GET['offset'])) ? trim($_GET['offset']) : "0";
        $_GET['limit'] = (isset($_GET['limit']) && !empty(trim($_GET['limit'])) && is_numeric($_GET['limit'])) ? trim($_GET['limit']) : "10";

        $bank_model = new Bank_transfers_model;
        return $this->response->setJSON($bank_model->bank_transfer_list());
    }

    // 28.
    public function  upload_receipts()
    {
        $validation =  \Config\Services::validation();
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $user_id = $user_token;

        $db = \Config\Database::connect();
        $validation->setRules(
            [

                'subscription_id' => 'required|numeric',
                'user_id' => 'required|numeric'
            ],
            [

                'subscription_id' => [
                    'required' => 'Subscription id is required',
                    'numeric' => 'invalid input',
                ],
                'user_id' => [
                    'required' => 'User id is required',
                    'numeric' => 'invalid input',
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

        $files = $this->request->getFiles('reciept');
        // print_r($files);
        // die();
        $path = 'public/uploads/images/reciept';

        foreach ($files['reciept'] as $rec) {

            $type = $rec->getMimeType();
            if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/jpg') {
                $builder = $db->table('bank_transfers');
                if (!$rec->hasMoved()) {

                    $path = FCPATH . 'public/uploads/images/reciept/';
                    $file_name =  $rec->getName();
                    $newName = $rec->getRandomName();
                    $rec->move($path, $newName);
                    $arr['image'] =  $newName;

                    $data = [
                        'user_id' => $user_id,
                        'subscription_id' => $this->request->getPost('subscription_id'),
                        'attachments' => $newName,
                        'status' => 0, /* 0:pending | 1:accepted | 2:rejected	 */
                    ];
                    // print_r($data);


                    if ($builder->insert($data)) {
                        // insert successully
                    } else {
                        $response = [
                            'error' => true,
                            'message' => 'Some error occured while uploading the image',
                            'data' => $data,
                        ];
                        return $this->response->setJSON($response);
                    }
                } else {
                    $response = [
                        'error' => true,
                        'message' => 'this recipt has been already uploaded',
                        'data' => [],
                    ];
                    return $this->response->setJSON($response);
                }
            } else {
                $response = [
                    'error' => true,
                    'message' => 'Please attach a valid image file.',
                    'data' => [],
                ];
                return $this->response->setJSON($response);
            }
        }
        $response = [
            'error' => false,
            'message' => 'Receipts Added successfully',
            'data' => [],
        ];
        return $this->response->setJSON($response);
    }

    // 29.
    public function generate_paytm_checksum()
    {
        $validation =  \Config\Services::validation();

        /*
            order_id:1001
            amount:1099
            user_id:73              //{ optional } 
            industry_type:Industry  //{ optional } 
            channel_id:WAP          //{ optional }
            website:website link    //{ optional }
        */


        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }

        $validation->setRules(
            [
                'order_id' => 'required',
                'amount' => 'required|numeric',
                'user_id' => 'required|numeric'
            ],
            [
                'user_id' => [
                    'required' => 'User id is required',
                ],
                'subscription_id' => [
                    'required' => 'User id is required',
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
        } else {
            $settings = get_settings('payment_gateways_settings', true);
            $credentials = $this->paytm->get_credentials();
            $paytm_params["MID"] = $settings['paytm_merchant_id'];

            $paytm_params["ORDER_ID"] = $this->request->getPost('order_id');
            $paytm_params["TXN_AMOUNT"] = $this->request->getPost('amount');
            $paytm_params["CUST_ID"] = $this->request->getPost('user_id');

            $paytm_params["WEBSITE"] = (($this->request->getPost('website', true) != null) && !empty($this->request->getPost('website'))) ? $this->request->getPost('website', true) : '';

            $paytm_params["CALLBACK_URL"] = $credentials['url'] . "theia/paytmCallback?ORDER_ID=" . $paytm_params["ORDER_ID"];

            $paytm_checksum = $this->paytm->generateSignature($paytm_params, $settings['paytm_merchant_key']);
            if (!empty($paytm_checksum)) {
                $response['error'] = false;
                $response['message'] = "Checksum created successfully";
                $response['order id'] = $paytm_params["ORDER_ID"];
                $response['data'] = $paytm_params;
                $response['signature'] = $paytm_checksum;
                return $this->response->setJSON($response);
            } else {
                $response['error'] = true;
                $response['message'] = "Data not found!";
                return $this->response->setJSON($response);
            }

            $data['error'] = true;
            $data['message'] = "checking if we're here";
            $data['data'] = $paytm_params;
            return $this->response->setJSON($data);
        }
    }

    // 30.
    public function generate_paytm_txn_token()
    {
        $validation =  \Config\Services::validation();

        /*
            amount:100.00
            order_id:102
            user_id:73
            industry_type:      //{optional}
            channel_id:      //{optional}
            website:      //{optional}
        */
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }

        $validation->setRules(
            [
                'order_id' => 'required',
                'amount' => 'required|numeric',
                'user_id' => 'required|numeric'
            ],
            [
                'user_id' => [
                    'required' => 'User id is required',
                ],
                'order_id' => [
                    'required' => 'order id is required',
                ],
                'amount' => [
                    'required' => 'amount id is required',
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
        } else {
            $credentials = $this->paytm->get_credentials();
            $order_id = $_POST['order_id'];
            $amount = $_POST['amount'];
            $user_id = $_POST['user_id'];
            $paytmParams = array();

            $paytmParams["body"] = array(
                "requestType"   => "Payment",
                "mid"           => $credentials['paytm_merchant_id'],
                "websiteName"   => "WEBSTAGING",
                "orderId"       => $order_id,
                "callbackUrl"   => $credentials['url'] . "theia/paytmCallback?ORDER_ID=" . $order_id,
                "txnAmount"     => array(
                    "value"     => $amount,
                    "currency"  => "INR",
                ),
                "userInfo"      => array(
                    "custId"    => $user_id,
                ),
            );

            $checksum = $this->paytm->generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $credentials['paytm_merchant_key']);
            $paytmParams["head"] = array(
                "signature"    => $checksum
            );
            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
            $url = $credentials['url'] . "/theia/api/v1/initiateTransaction?mid=" . $credentials['paytm_merchant_id'] . "&orderId=" . $order_id;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            $paytm_response = curl_exec($ch);
            if (!empty($paytm_response)) {
                $paytm_response = json_decode($paytm_response, true);
                if (isset($paytm_response['body']['resultInfo']['resultMsg']) && ($paytm_response['body']['resultInfo']['resultMsg'] == "Success" || $paytm_response['body']['resultInfo']['resultMsg'] == "Success Idempotent")) {
                    $response['error'] = false;
                    $response['message'] = "Transaction token generated successfully";
                    $response['txn_token'] = $paytm_response['body']['txnToken'];
                    $response['paytm_response'] = $paytm_response;
                    return $this->response->setJSON($response);
                } else {
                    $response['error'] = true;
                    $response['message'] = $paytm_response['body']['resultInfo']['resultMsg'];
                    $response['txn_token'] = "";
                    $response['paytm_response'] = $paytm_response;
                    return $this->response->setJSON($response);
                }
            } else {
                $response['error'] = true;
                $response['message'] = "coud not genereate txn token";
                $response['txn_token'] = "";
                $response['paytm_response'] = $paytm_response;
                return $this->response->setJSON($response);
            }
        }
    }

    // 31. 
    public function validate_paytm_checksum()
    {
        /**
         *  paytm_checksum:PAYTM_CHECKSUM
         *  order_id:1001
         *  amount:1099
         *  user_id:73              //{ optional } 
         *  industry_type:Industry  //{ optional } 
         *  channel_id:WAP          //{ optional }
         *  website:website link    //{ optional }
         */
        $validation =  \Config\Services::validation();

        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $validation->setRules(
            [
                'order_id' => 'required',
                'amount' => 'required|numeric',
                'user_id' => 'required|numeric',
                'paytm_checksum' => 'required'
            ],
            [
                'user_id' => [
                    'required' => 'User id is required',
                ],
                'order_id' => [
                    'required' => 'order id is required',
                ],
                'amount' => [
                    'required' => 'amount id is required',
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
        } else {
            $settings = get_settings('payment_gateways_settings', true);
            $paytm_checksum = $this->request->getPost('paytm_checksum');
            $paytm_params["MID"] = $settings['paytm_merchant_id'];
            $paytm_params["MKEY"] = $settings['paytm_merchant_key'];

            $paytm_params["ORDER_ID"] = $this->request->getPost('order_id');
            $paytm_params["TXN_AMOUNT"] = $this->request->getPost('amount');
            $paytm_params["CUST_ID"] = $this->request->getPost('user_id');

            $isVerifySignature = $this->paytm->verifySignature($paytm_params, $settings['paytm_merchant_key'], $paytm_checksum);

            if ($isVerifySignature) {
                $data['error'] = false;
                $data['message'] = "Checksum Matched";
                $data['data'] = $paytm_params;
                return $this->response->setJSON($data);
            } else {
                $data['error'] = true;
                $data['message'] = "Checksum Mismatched";
                $data['data'] =  $paytm_params;
                return $this->response->setJSON($data);
            }
        }
    }

    // 32. 
    public function get_paypal_link()
    {
        /**
         * plan_id : 1
         * tenure_id: 1
         */
        if (!$user_token = verify_tokens()) {
            $status = $this->response->getStatusCode();
            return $this->response->setStatusCode($status);
        }
        $user_id = $user_token;
        $validation =  \Config\Services::validation();
        $validation->setRules(
            [
                'plan_id' => 'required|numeric',
                'tenure_id' => 'required|numeric',
            ],
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

        $user_id = $user_id;
        $plan_id = $_POST['plan_id'];
        $tenure_id = $_POST['tenure_id'];

        $tenure = fetch_details('plans_tenures', ['id' => $tenure_id, 'plan_id' => $plan_id], ['price', 'discounted_price']);


        if ($tenure == []) {
            $response['error'] = true;
            $response['message'] = "Plan Does not exist!";
            return $this->response->setJSON($response);
        }

        $amount = (!empty($tenure[0]) && isset($tenure[0]['discounted_price']) && $tenure[0]['discounted_price'] > 0)
            ? $tenure[0]['discounted_price'] : $tenure[0]['price'];

        // print_r($amount);

        $response['error'] = false;
        $response['message'] = 'Order Detail Founded !';
        $response['data'] = base_url('/api/v1/paypal_web_view?' . 'user_id=' . $user_id . '&order_id=' . $tenure_id . '&amount=' . $amount);
        return $this->response->setJSON($response);
    }

    // 33.

    public function paypal_web_view()
    {

        /*
            user_id: 1
            order_id : 1
            amount : 1000
        */


        header("Content-Type: html");

        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();

        $validation->setRules(
            [
                'user_id' => 'required',
                'order_id' => 'required|numeric',
                'amount' => 'required|numeric|greater_than[0]',
            ],
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
        $user_id = $request->getGet('user_id');

        $user =  fetch_details('users', ['id' => $user_id]);
        if (empty($user)) {
            $response = [
                'error' => true,
                'message' => "No user Found",
                'data' => []
            ];
            return $this->response->setJSON($response);
        }


        $order_id = $request->getGet('order_id');
        $tenure = fetch_details('plans_tenures', ['id' => $order_id,], ['price', 'discounted_price']);

        $amount = (!empty($tenure[0]) && isset($tenure[0]['discounted_price']) && $tenure[0]['discounted_price'] > 0)
            ? $tenure[0]['discounted_price'] : $tenure[0]['price'];
        // $amount = $request->getGet('amount');

        $data['user'] = $user[0];
        $data['payment_type'] = "paypal";
        // Set variables for paypal form
        $returnURL = base_url() . 'api/v1/app_payment_status';
        $cancelURL = base_url() . 'api/v1/app_payment_status';
        $notifyURL = base_url() . 'api/v1/paypal_notification';
        // $notifyURL = 'https://webhook.site/cf892427-9c01-4fdd-a1f6-b3865b2e548c';
        $txn_id = time() . "-" . rand();
        // Get current user ID from the session
        $userID = $data['user']['id'];
        $order_id = $order_id;
        $payeremail = $data['user']['email'];

        // $this->paypal_lib->add_field('payer_email', $payeremail);
        $this->paypal_lib->add_field('return', $returnURL);
        $this->paypal_lib->add_field('cancel_return', $cancelURL);
        $this->paypal_lib->add_field('notify_url', $notifyURL);
        $this->paypal_lib->add_field('item_name', 'Online shopping');
        $this->paypal_lib->add_field('custom', $userID . '|' . $payeremail);
        $this->paypal_lib->add_field('item_number', $order_id);
        $this->paypal_lib->add_field('amount', $amount);
        // Render paypal form and auto submit on page load
        $this->paypal_lib->paypal_auto_form();
        // return "nothing";
    }

    // 34.
    public function app_payment_status()
    {
        $request = \Config\Services::request();

        $paypalInfo = $request->getGet();
        // print_r($_GET);
        if (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "completed") {
            $response['error'] = false;
            $response['message'] = "Payment Completed Successfully";
            $response['data'] = $paypalInfo;
        } elseif (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "authorized") {
            $response['error'] = false;
            $response['message'] = "Your payment is has been Authorized successfully. We will capture your transaction within 30 minutes, once we process your order. After successful capture coins wil be credited automatically.";
            $response['data'] = $paypalInfo;
        } elseif (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "Pending") {
            $response['error'] = false;
            $response['message'] = "Your payment is pending and is under process. We will notify you once the status is updated.";
            $response['data'] = $paypalInfo;
        } else {
            $response['error'] = true;
            $response['message'] = "Payment Cancelled / Declined ";
            $response['data'] = (isset($_GET)) ? $request->getGet() : "";
        }
        print_r(json_encode($response));
    }

    // 35.
    public function paypal_notification()
    {

        $payment_status = $_POST['payment_status'];

        if (strtolower($payment_status) == 'completed') {
            $user_details = $_POST['custom'];
            $user_details = explode('|', $user_details);
            $user_id = $user_details[0];
            $user_email = $user_details[1];
            $price = $_POST['payment_gross'];

            $tenure_id = $_POST['item_number'];
            $tenure_data =  fetch_details('plans_tenures', ['id' => $tenure_id]);

            $transaction_id = $_POST['txn_id'];
            // print_r($tenure_data);

            $id = add_transaction($transaction_id, $price, 'paypal', $user_id, $payment_status, "", "Payment Completed");

            $sub_id =  add_subscription($user_id, $tenure_data[0]['plan_id'], $tenure_data[0]['months'], $id, $price);

            $update =   update_details(['subscription_id' => $sub_id], ['id' => $id], 'transactions');
            if ($update) {
                $response['error'] = false;
                $response['message'] = "Your Subscription is added";
                return $this->response->setJSON($response);
            } else {
                $response['error'] = true;
                $response['message'] = "Could not add subscription";
                $data = $update;
                return $this->response->setJSON($response);
            }
        }

        // print_r($_POST);
    }

    // 36.
    public function test()
    {
        echo "<pre>";
        $txn_id = "pay_JR8dw8p2zHsNXI";
        $payment_data = verify_payment_transaction($txn_id, "razorpay");
        print_r($payment_data);
    }
}
