<?php

namespace App\Controllers\admin;

use App\Models\Voices_model;

class Voices extends Admin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            if (!$languages = get_settings('languages', true, true)) {
                update_supported_languages();
            }
            $languages = get_settings('languages', true, true);
            // print_r($languages);

            $this->data['tags'] = fetch_details('ssml_tags');
            $this->data['title'] = 'Voices';
            $this->data['main_page'] = 'voices';
            $this->data['languages'] = $languages;
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function show()
    {
        $request = \Config\Services::request();
        $tts_voices = new Voices_model();
        $language = $request->getPost('language');
        if ($this->isLoggedIn && $this->userIsAdmin) {
            return print_r(json_encode($tts_voices->list_voices($language)));
        } else {
            return redirect('unauthorised');
        }
    }

    public function update_voices()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $response = [
                    'error' => true,
                    'message' => DEMO_MODE_ERROR,
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => [],
                ];
                return $this->response->setJSON($response);
            }

            $validation = \Config\Services::validation();
            $request = \Config\Services::request();
            helper('function');
            $validation->setRules(
                [
                    'display_name' => 'required',
                    'image' => 'is_image[image]',
                ],
                [
                    'display_name' => [
                        'required' => 'Display Name is required',
                    ],
                    'image' => [
                        'is_image' => 'Please enter a valid Image',
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
                    'csrfHash' => csrf_hash(),
                ];

                return $this->response->setJSON($response);
            }

            $id = $request->getVar('id');

            $data = [
                'language' => $_POST['language'],
                'voice' => $_POST['voice'],
                'display_name' => $_POST['display_name'],
                'gender' => $_POST['gender'] == 'undefined' ? 'Null' : $_POST['gender'],
                'status' => $_POST['status'],
            ];

            $file = $request->getFile('image');
            if ($file->isValid()) {
                $imgRandom = $file->getRandomName();
                if ($file->move('public/uploads/voice_icons/', $imgRandom)) {
                    $data['icon'] = "public/uploads/voice_icons/" . $file->getName();

                    $voice_data = fetch_details("tts_voices", ['id' => $id], ['icon']);
                    if (!empty($voice_data[0]['icon'])  && !str_contains("provider", "")) {
                        unlink($voice_data[0]['icon']);
                    }
                }
            }

            $status = update_details(
                $data,
                ['id' => $id],
                'tts_voices'
            );

            if ($status) {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => false,
                    'message' => "Voice updated successfully",
                    "data" => $data,
                ]);
            } else {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => true,
                    'message' => "Something went wrong...",
                    "data" => [],
                ]);
            }
        } else {
            return redirect('unauthorised');
        }
    }

    public function update_all_voices()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $response = [
                'error' => true,
                'message' => DEMO_MODE_ERROR,
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'data' => [],
            ];
            return $this->response->setJSON($response);
        }
        $response = update_all_providers();
        if ($response != false) {
            return $this->response->setJSON([
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'error' => false,
                'message' => "Voice updated successfully",
            ]);
        } else {
            return $this->response->setJSON([
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'error' => true,
                'message' => $response,
            ]);
        }
    }
}
