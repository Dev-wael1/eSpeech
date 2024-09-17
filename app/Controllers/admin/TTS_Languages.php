<?php

namespace App\Controllers\admin;

use App\Models\TTS_Languages_model;

class TTS_Languages extends Admin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            $this->data['tags'] = fetch_details('ssml_tags');
            $this->data['title'] = 'TTS_languages';
            $this->data['main_page'] = 'tts_languages';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function add_languages()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            $add_voices = new TTS_Languages_model();
            $languages = get_settings('languages', true, true);
            echo "<pre>";

            foreach ($languages as $key => $val) {
                // print_r(count($key));
                $lan_code = explode('-',$key);
                if(isset($lan_code[2])){
                    $flag_name = strtolower($lan_code[2] . "-".$lan_code[1]);
                }elseif (!isset($lan_code[2]) && isset($lan_code[1])) {
                    $flag_name = strtolower($lan_code[1]);
                }else{
                    $flag_name = strtolower($lan_code[0]);
                }

                $data = [
                    "language_code" => $key,
                    "language_name" => $val,
                    "flag" => "public/flags/" . $flag_name . ".svg"
                ];
                // print_r($data2);
                $add_voices->save($data);
            }
        } else {
            return redirect('unauthorised');
        }
    }

    public function show()
    {
        $request = \Config\Services::request();
        $tts_voices = new TTS_Languages_model();
        // $language = $request->getPost('language');
        if ($this->isLoggedIn && $this->userIsAdmin) {
            return print_r(json_encode($tts_voices->list_languages()));
        } else {
            return redirect('unauthorised');
        }
    }

    public function update_tts_language(){
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
            $request = \Config\Services::request();
            helper('function');

            $id = $request->getVar('id');

            $data = [
                'status' => $_POST['status'],
            ];
            // print_r($data);

            $status = update_details(
                $data,
                ['id' => $id],
                'tts_languages'
            );

            if ($status) {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => false,
                    'message' => "TTS Language updated successfully",
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
}
