<?php

namespace App\Libraries;

use Exception;

class Azure
{
    private $key;
    private $region;
    public function __construct()
    {
        $data = json_decode(fetch_details('settings', ['variable' => 'tts_config'])[0]['value'], true);
        $this->region = $data['azureRegion'];
        $this->key = $data['azureApiKey'];
    }

    public function get_token()
    {
        $region = $this->region;

        $url = "https://" . $this->region . ".api.cognitive.microsoft.com/sts/v1.0/issuetoken";

        $header = array(
            'Ocp-Apim-Subscription-Key: ' . $this->key . '',
        );
        $obj = curl($url, "post", $header);
        if ($obj['http_code'] == 401) {
            $settings = get_settings('tts_config');
            $settings = json_decode($settings, true);
            $settings['azureStatus'] = 'disable';

            $val = json_encode($settings);
            update_details(['value' => $val], ['variable' => 'tts_config'], "settings", false);
            mail_error("Azure key crashed please check api key", "Azure key crashed please check api key", json_encode($obj['body']));
            header('Content-Type: application/json');
            $arr =  [
                "error" => true,
                "message" => ERROR_CONTACT_ADMIN,
                "key_error" => true,
                "data" => [],
                "error_message" => $obj['body'],
                "csrfName" => csrf_token(),
                "csrfHash" => csrf_hash()
            ];

            echo json_encode($arr);
            die();
        } elseif ($obj['http_code'] == 200) {
            return array(
                'token' => $obj['body'],
                'http_code' => $obj['http_code'],
                'region' => $region
            );
        } else {
            $settings = get_settings('tts_config');
            $settings = json_decode($settings);
            $settings->azureStatus = 'disable';
            $val = json_encode($settings);

            update_details(['value' => $val], ['variable' => 'tts_config'], "settings", false);
            mail_error("Something went wrong", "Unknown error occured", json_encode($obj['body']));
            header('Content-Type: application/json');
            $arr =  [
                "error" => true,
                "message" => ERROR_CONTACT_ADMIN,
                "key_error" => true,
                "data" => [],
                "error_message" => $obj['body'],
                "csrfName" => csrf_token(),
                "csrfHash" => csrf_hash()
            ];
            echo json_encode($arr);
            die();
        }
    }
    public function get_all_voices($key = "", $region = "")
    {
        if ($key == "" && $region == "") {
            $data = $this->get_token();
            $token = $data['token'];
            $region = $data['region'];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://' . $region . '.tts.speech.microsoft.com/cognitiveservices/voices/list',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $token"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return json_decode($response);
        } elseif ($key != "") {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://' . $this->region . 'tts.speech.microsoft.com/cognitiveservices/voices/list',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Ocp-Apim-Subscription-Key: $key"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return json_decode($response);
        }
    }
    public function get_voices($language = "", $engine = "")
    {
        $arr = array();
        $voices = $this->get_all_voices();
        if ($language != "" && $engine == "") {
            for ($i = 0; $i < count($voices); $i++) {
                if (strpos($voices[$i]->Name, $language) !== false) {
                    $data = [
                        'voice' => $voices[$i]->ShortName,
                        'display_name' => $voices[$i]->DisplayName,
                        'language' => $language,
                        'provider' => 'azure'
                    ];
                    array_push($arr, $data);
                }
            }
            return $arr;
        } elseif ($language != "" && $engine != "") {
            for ($i = 0; $i < count($voices); $i++) {
                if ((strpos($voices[$i]->Name, $language) !== false) && ($voices[$i]->VoiceType == $engine)) {
                    array_push($arr, $voices[$i]);
                }
            }
            return $arr;
        }
    }
    public function get_languages()
    {
        $arr = array();
        $voices = $this->get_all_voices();
        for ($i = 0; $i < count($voices); $i++) {
            if (!in_array($voices[$i]->Locale, $arr)) {
                array_push($arr, $voices[$i]->Locale);
            }
        }
        return $arr;
    }
    public function systhesize($language, $shortname, $inputText)
    {
        $auth = $this->get_token();
        $access_token = $auth['token'];
        $region = $auth['region'];


        $ttsServiceUri = "https://" . $this->region . ".tts.speech.microsoft.com/cognitiveservices/v1";

        $SsmlTemplate = "<speak version='1.0' xml:lang='$language'><voice  name='$shortname'>$inputText</voice></speak>";

        try {
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/ssml+xml\r\n" .
                        "X-Microsoft-OutputFormat: audio-48khz-192kbitrate-mono-mp3\r\n" .
                        "Authorization: " . "Bearer " . $access_token . "\r\n" .
                        "X-Search-AppId: 07D3234E49CE426DAA29772419F436CA\r\n" .
                        "X-Search-ClientID: 1ECFAE91408841A480F00935DC390960\r\n" .
                        "User-Agent: TTSPHP\r\n" .
                        "content-length: " . strlen($SsmlTemplate) . "\r\n",
                    'method'  => 'POST',
                    'content' => $SsmlTemplate,
                ),
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($ttsServiceUri, false, $context);
            if (!$result) {
                return '';
            } else {

                return base64_encode($result);
            }
        } catch (Exception $e) {
            return false;
            try {
                $doc = new \DOMDocument();

                $root = $doc->createElement("speak");
                $root->setAttribute("version", "1.0");
                $root->setAttribute("xml:lang", "$language");

                $voice = $doc->createElement("voice");
                $voice->setAttribute("xml:lang", "$language");
           
                $voice->setAttribute("name", "$shortname"); // Short name for "Microsoft Server Speech Text to Speech Voice (en-US, Guy24KRUS)"

                $text = $doc->createTextNode($inputText);

                $voice->appendChild($text);
                $root->appendChild($voice);
                $doc->appendChild($root);
                $data = $doc->saveXML();
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/ssml+xml\r\n" .
                            "X-Microsoft-OutputFormat: audio-48khz-192kbitrate-mono-mp3\r\n" .
                            "Authorization: " . "Bearer " . $access_token . "\r\n" .
                            "X-Search-AppId: 07D3234E49CE426DAA29772419F436CA\r\n" .
                            "X-Search-ClientID: 1ECFAE91408841A480F00935DC390960\r\n" .
                            "User-Agent: TTSPHP\r\n" .
                            "content-length: " . strlen($data) . "\r\n",
                        'method'  => 'POST',
                        'content' => $data,
                    ),
                );
                $context  = stream_context_create($options);
                $result = file_get_contents($ttsServiceUri, false, $context);
                if (!$result) {
                    return '';
                } else {

                    return base64_encode($result);
                }
            } catch (Exception $e) {
                return false;
            }
        }
    }
    public function verify_key($key)
    {
        

        $url = "https://" . $this->region . ".api.cognitive.microsoft.com/sts/v1.0/issuetoken";

        $header = array(
            'Ocp-Apim-Subscription-Key: ' . $key . '',
        );
        $obj = curl($url, "post", $header);
        print_r($obj);
        die();
        if ($obj['http_code'] == 401) {
            
        } elseif ($obj['http_code'] == 200) {
            return true;
        } else {
            return false;
        }
    }
}
