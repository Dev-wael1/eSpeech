<?php

namespace App\Controllers\admin;

use App\Libraries\Aws;
use App\Libraries\Azure;
use App\Libraries\Google;
use App\Libraries\IBM;
use App\Models\Tts_model;

class Text_To_Speech extends Admin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {


            // if (!$languages = get_settings('languages', true, true)) {
            //     update_supported_languages();
            // }
            // $languages = get_settings('languages', true, true);
            $languages = get_languages();

            $this->data['tags'] = fetch_details('ssml_tags');
            $this->data['title'] = 'Text To Speech';
            $this->data['main_page'] = '../../text_to_speech';
            // $this->data['languages'] = $languages;
            $this->data['languages'] = $languages;
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function set_voices()
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

            return $this->response->setJSON($response);
        }
        $voices = (array)get_voices($request->getPost('language'));
        $length = count($voices);
        for ($i = 0; $i < $length; $i++) {
            if ($tts_config = get_settings('tts_config', true, true)) {
                if ($tts_config['showVoiceIcon'] == "voiceIcon") {
                    $image = "" . $voices[$i]['icon'];
                } else if ($tts_config['showVoiceIcon'] == "genderIcon") {
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
            }
            $voices[$i]['image'] = $image;
        }
        $response = [
            'error' => false,
            'message' => '',
            'data' => $voices,
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash()
        ];
        return $this->response->setJSON($response);
    }
    public function synthesize()
    {

        // return $this->response->setJSON([
        //     "error" => true,
        //     "message" => "This feature is not available in demo mode.",
        //     "data" => [],
        //     'csrfName' => csrf_token(),
        //     "csrfHash" => csrf_hash()
        // ]);
        if ($tts_config = get_settings('tts_config', true)) {
            $data = "";
            $this->ionAuth = new \IonAuth\Libraries\IonAuth();
            $user_id = $this->userId;
            $validation =  \Config\Services::validation();
            $request = \Config\Services::request();
            $validation->setRules(
                [
                    'provider' => 'required',
                    'voice' => 'required',
                    'text' => 'required',
                    'language' => 'required',
                    'title' => 'trim',
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
                    'data' => [],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];

                return $this->response->setJSON($response);
            }
            $text = $request->getPost('text');
            $provider = strtolower($request->getPost('provider'));

            // $disabled_providers = array('azure' => 'Azure');

            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {

                $settings = get_settings("tts_config", true);
                // if (array_key_exists($provider, $disabled_providers)) {
                //     $response = [
                //         'error' => true,
                //         'message' => "In Demo mode we've temporarily disabled " . $disabled_providers[$provider] . " TTS services. You can synthesize using Google, AWS and IBM TTS Services as of now.",
                //         'csrfName' => csrf_token(),
                //         'csrfHash' => csrf_hash(),
                //         'data' => $settings
                //     ];
                //     return $this->response->setJSON($response);
                // }

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
            $voice = $request->getPost('voice');
            $characters = user_characters($user_id);
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
                    'message' => "User do not have any active Plans",
                    'data' => [],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];
                return $this->response->setJSON($response);
            }
            if (($remaining > 0) && $remaining >= strlen($text)) {
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
                            'message' => "Something went wrong please try again.",
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
                            'message' => "azure is disabled",
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
                            'message' => "google is disabled",
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
                $model = new Tts_model;
                $id = $model->insert_tts(['user_id' => $user_id, 'language' => $language, 'voice' => $voice, 'title' => $title, 'provider' => $provider, 'text' => $text, 'is_saved' => 0, 'used_characters' => strlen($text)]);
                if (update_characters(strlen($text), $user_id, $provider)) {
                    $model = new Tts_model;
                    $id = $model->insert_tts(['user_id' => $user_id, 'language' => $language, 'voice' => $voice, 'title' => $title, 'provider' => $provider, 'text' => $text, 'is_saved' => 0, 'used_characters' => strlen($text)]);

                    $response = [
                        'error' => false,
                        'message' => "Synthesized successfully",
                        'data' => $data,
                        'id' => $id,
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash()
                    ];
                    return $this->response->setJSON($response);
                }
                $response = [
                    'error' => true,
                    'message' => "Something went wrong please try again.",
                    'data' => [],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];
                return $this->response->setJSON($response);
            } else {

                if (has_upcoming($user_id)) {
                    $response = [
                        'error' => true,
                        'message' => "You do not have sufficient characters. Do you want to start your upcoming plan from today?",
                        'data' => [
                            'upcoming' => true
                        ],
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash()
                    ];
                    return $this->response->setJSON($response);
                }
                $response = [
                    'error' => true,
                    'message' => "You do not have sufficient characters",
                    'data' => [
                        'upcoming' => false
                    ],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];
                return $this->response->setJSON($response);
            }
        } else {
            $response = [
                'error' => true,
                'message' => "Some thing went wrong contact admin ASAP.",
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];
            return $this->response->setJSON($response);
        }
    }
    public function save_tts()
    {
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [
                'tts_id' => 'required',
                'base64' => 'required'

            ],
            [
                'tts_id' => [
                    'required' => 'tts_id is required',
                ],
                'base64' => [
                    'required' => 'base64 is required',
                ],

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

            return $this->response->setJSON($response);
        }
        $tts_id = $request->getPost('tts_id');
        $base64 = $request->getPost('base64');
        $title = $request->getPost('title');
        $db      = \Config\Database::connect();
        $builder = $db->table('users_tts')->update(
            [
                'base_64' => $base64,
                'is_saved' => '1',
                'title' => $title
            ],
            [
                'id' => $tts_id
            ]
        );
        if ($builder) {
            $response = [
                'error' => false,
                'message' => 'Saved successfully',
                'data' => [],
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];

            return $this->response->setJSON($response);
        }
        $response = [
            'error' => true,
            'message' => 'something went wrong',
            'data' => [],
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash()
        ];

        return $this->response->setJSON($response);

        $response = [
            'error' => true,
            'message' => 'Some thing went Wrong. Please try after some time.',
            'data' => [],
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash()
        ];
        return $this->response->setJSON($response);
    }
    public function convert_active()
    {
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [
                'user_id' => 'required',


            ],
            [
                'user_id' => [
                    'required' => 'user_id is required',
                ],


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

            return $this->response->setJSON($response);
        }
        $user_id = $_POST['user_id'];

        if (convert_active($user_id)) {
            $response = [
                'error' => false,
                'message' => 'You can use your upcoming plan from today...',
                'data' => [],
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];

            return $this->response->setJSON($response);
        }
        $response = [
            'error' => true,
            'message' => 'something went wrong',
            'data' => [],
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash()
        ];
    }
    public function tts_list()
    {
        $tts = new Tts_model;
        $user_id = $this->userId;
        if ($this->data['user'] == 'admin' || $this->data['admin'] == '1') {
            // print_r('here');
            if ($this->data['admin']) {
                return print_r(json_encode($tts->list_tts()));
            }
        } else {
            if ($user_id == $_GET['user_id']) {
                return print_r(json_encode($tts->list_tts()));
            }
        }
    }
    public function delete_tts()
    {
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
                'data' => [],
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];

            return $this->response->setJSON($response);
        }
        $tts_id = $request->getPost('tts_id');
        $tts = new Tts_model;
        $db      = \Config\Database::connect();
        $builder = $db->table('users_tts')->delete(['id' => $tts_id]);
        if ($builder) {
            $response = [
                'error' => false,
                'message' => 'deleted successfully',
                'data' => [],
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];

            return $this->response->setJSON($response);
        }
        $response = [
            'error' => true,
            'message' => 'something went wrong..',
            'data' => [],
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash()
        ];

        return $this->response->setJSON($response);
    }
    public function save_predefined()
    {
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [
                'tts_id' => 'required',
                'base64' => 'required'

            ],
            [
                'tts_id' => [
                    'required' => 'tts_id is required',
                ],
                'base64' => [
                    'required' => 'base64 is required',
                ],

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

            return $this->response->setJSON($response);
        }
        $tts_id = $request->getPost('tts_id');
        $base64 = $request->getPost('base64');
        $model = new Tts_model;
        $status = $model->save_predefined($tts_id, $base64);

        if ($status) {
            $response = [
                'error' => false,
                'message' => 'Saved as predefined.',
                'data' => [],
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];

            return $this->response->setJSON($response);
        }
        $response = [
            'error' => true,
            'message' => 'Some thing went Wrong. Please try after some time.',
            'data' => [],
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash()
        ];
        return $this->response->setJSON($response);
    }
    public function set_predefined()
    {
        $tts = new Tts_model;
        $validation =  \Config\Services::validation();
        $request = \Config\Services::request();
        $validation->setRules(
            [
                'voice' => 'required',
                'language' => 'required'

            ],
            [
                'voice' => [
                    'required' => 'voice is required',
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
                'data' => [],
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];

            return $this->response->setJSON($response);
        }

        $voice = $request->getPost('voice');
        $language = $request->getPost('language');

        $data = $tts->get_predefined($voice, $language);
        if (!empty($data)) {
            $data = $data[0]['base_64'];
            $response = [
                'error' => false,
                'message' => 'Sample voice recieved.',
                'data' => $data,
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];
            return $this->response->setJSON($response);
        } else {
            $data = [];
            $response = [
                'error' => true,
                'message' => 'Sample voice not found.',
                'data' => $data,
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];
            return $this->response->setJSON($response);
        }
    }
}
