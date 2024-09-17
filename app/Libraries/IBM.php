<?php

namespace App\Libraries;

use Exception;

class IBM
{
    private $url;
    protected $key;
    public function __construct()
    {
        $data = json_decode(fetch_details('settings', ['variable' => 'tts_config'])[0]['value'], true);
        $this->url = $data['ibmEndPointUrl'];
        $this->key = $data['ibmApiKey'];
    }
    private function token()
    {
        return base64_encode('apikey:' . $this->key);
    }
    public function get_voices($language = "", $voice = "")
    {
        $key = $this->token();
        $url = $this->url . 'v1/voices';
        $url .= (!empty($voice)) ? "/$voice" : "";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(

                "Authorization: Basic $key",
            ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);

        $voices = json_decode($response, true);
        if (isset($voices['code']) && $voices['code'] == 401) {
        } else {
            for ($i = 0; $i < count($voices['voices']); $i++) {
                $voices['voices'][$i]['display_name'] = ltrim(substr($voices['voices'][$i]['name'], strpos($voices['voices'][$i]['name'], "_")), "_");
            }
            if ($language == "") {
                return $voices;
            } else {
                $resultarr = [];
                $arr = [];
                for ($i = 0; $i < count($voices['voices']); $i++) {
                    if ($voices['voices'][$i]['language'] == $language) {

                        $arr = [
                            'voice' => $voices['voices'][$i]['name'],
                            'display_name' => $voices['voices'][$i]['display_name'],
                            'language' => $language,
                            'provider' => 'ibm',
                        ];
                        array_push($resultarr, $arr);
                    }
                }
                return $resultarr;
            }
        }

    }

    public function get_languages()
    {
        $voices = $this->get_voices();
        return array_values(array_unique(array_column($voices['voices'], "language")));
    }

    public function synthesize($voice, $text, $is_base64 = false)
    {
        $status = 1;
        try {
            $curl = curl_init();
            $key = $this->token();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url . 'v1/synthesize?voice=' . $voice,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{"text":"' . $text . '"}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: audio/mp3',
                    'Authorization: Basic ' . $key,
                ),
            ));

            $result = curl_exec($curl);
            if (isset($check['code']) && $check['code'] == 401) {
                $settings = get_settings('tts_config');
                $settings = json_decode($settings, true);
                $settings['ibmStatus'] = 'disable';

                $val = json_encode($settings);
                // update_details(['value' => $val], ['variable' => 'tts_config'], "settings", false);
                mail_error("IBM key crashed please check api key", "IBM key crashed please check api key", json_encode($obj['body']));
                header('Content-Type: application/json');
                $arr = [
                    "error" => true,
                    "message" => ERROR_CONTACT_ADMIN,
                    "key_error" => true,
                    "data" => [],
                    "error_message" => $obj['body'],
                    "csrfName" => csrf_token(),
                    "csrfHash" => csrf_hash(),
                ];
                echo json_encode($arr);
                die();
            }
            if (isset($check['code']) && $check['code'] != 200) {
                $settings = get_settings('tts_config');
                $settings = json_decode($settings, true);
                $settings['ibmStatus'] = 'disable';

                $val = json_encode($settings);
                update_details(['value' => $val], ['variable' => 'tts_config'], "settings", false);
                mail_error("IBM key crashed please check api key", "IBM key crashed please check api key", json_encode($obj['body']));
                header('Content-Type: application/json');
                $arr = [
                    "error" => true,
                    "message" => ERROR_CONTACT_ADMIN,
                    "key_error" => true,
                    "data" => [],
                    "error_message" => $obj['body'],
                    "csrfName" => csrf_token(),
                    "csrfHash" => csrf_hash(),
                ];
                echo json_encode($arr);
                die();
            }
            if (curl_errno($curl)) {
                echo 'Error:' . curl_error($curl);
            }
            curl_close($curl);
            if ($is_base64) {
                return base64_encode($result);
            } else {

                return $result;
            }
        } catch (Exception $e) {

            return false;
        }
    }

}
