<?php

namespace App\Libraries;

class Google
{
    protected $key;
    public function __construct()
    {
        $data = json_decode(fetch_details('settings', ['variable' => 'tts_config'])[0]['value'], true);
        $this->key = $data['gcpApiKey'];
    }

    public function get_all_voices()
    {
        $key = $this->key;
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => "https://texttospeech.googleapis.com/v1/voices?key=$key",
                CURLOPT_RETURNTRANSFER => true
            )
        );
        $output = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $output;
    }
    public function get_voices($language = "")
    {
        $key = $this->key;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://texttospeech.googleapis.com/v1/voices?key=$key&languageCode=$language",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        $res = [];
        for ($i = 0; $i < count($response['voices']); $i++) {
            $arr = [
                'voice' => $response['voices'][$i]['name'],
                'display_name' => $response['voices'][$i]['name'],
                'language' => $language,
                'provider' => 'google'
            ];
            array_push($res, $arr);
        }

        return $res;
    }
    public function systhesize($language, $voice, $text)
    {
        $req_data = [
            'voice' => [
                'languageCode' => $language,
                'name' => $voice,
                // 'ssmlGender' => 'FEMALE'
            ],
            'input' => [

                'ssml' => '<speak>' . $text . '</speak>',
            ],
            'audioConfig' => [
                'audioEncoding' => "MP3",

            ],
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://texttospeech.googleapis.com/v1/text:synthesize?key=' . $this->key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($req_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=UTF-8',
        ));

        $output = json_decode(curl_exec($ch), true);
        if (isset($output['error'])) {
            $settings = get_settings('tts_config');
            $settings = json_decode($settings);
            $settings->gcpStatus = 'disable';
            $val = json_encode($settings);
            // update_details(['value' => $val], ['variable' => 'tts_config'], "settings", false);
            mail_error("google synthesize crashed please check api key", "google synthesize crashed please check api key", json_encode($output));
            header('Content-Type: application/json');
            echo json_encode([
                "error" => true,
                "message" => ERROR_CONTACT_ADMIN,
                "key_error" => true,
                "data" => [$output],
                "csrfName" => csrf_token(),
                "csrfHash" => csrf_hash()
            ]);
            die();
        }


        curl_close($ch);

        return $output['audioContent'];
    }
    public function get_languages()
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => 'https://texttospeech.googleapis.com/v1/voices?key=' . $this->key,
                CURLOPT_RETURNTRANSFER => true
            )
        );
        $output = json_decode(curl_exec($ch), true);
        curl_close($ch);
   
        $languages = array_values(array_unique(array_column(array_column($output['voices'], "languageCodes"), 0)));
        return $languages;
    }

 
}
