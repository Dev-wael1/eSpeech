<?php

namespace App\Libraries;


require_once 'vendor/autoload.php';

use Aws\Polly\PollyClient;
use Exception;

class Aws
{
    protected $region;
    protected $key;
    protected $secret;

    public function __construct()
    {
        $data = json_decode(fetch_details('settings', ['variable' => 'tts_config'])[0]['value'], true);
        $this->region = $data['awsRegion'];
        $this->key = $data['amazonPollyAccessKey'];
        $this->secret = $data['amazonPollySecretAccessKey'];
    }
    private function client()
    {
        $polly = new PollyClient([
            'version' => 'latest',
            'region' => $this->region, //region
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret,
            ]
        ]);
        return $polly;
    }
    public function systhesize($voice = '', $text = "", $lang = "", $engine = "")
    {

        try {
            $polly = $this->client();
            $data = [
                'OutputFormat' => 'mp3',
                'Text' => '<speak>' . $text . '</speak>',
                'TextType' => 'ssml',
                'VoiceId' => $voice,
                "LanguageCode" => $lang,
            ];
            if ($engine == 'neural') {
                $data['Engine'] = 'neural';
            } elseif ($engine == 'standard') {
                $data['Engine'] = 'standard';
            }
            $result = $polly->synthesizeSpeech($data);
            $newdata =  $result->get("AudioStream");
            return base64_encode($newdata);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            if ($msg == "InvalidSignatureException" || $msg == "UnrecognizedClientException") {
                $settings = get_settings('tts_config');
                $settings = json_decode($settings);
                $settings->amazonPollyStatus = 'disable';
                $val = json_encode($settings);
                update_details(['value' => $val], ['variable' => 'tts_config'], "settings", false);
                mail_error("Amazon synthesize crashed please check api key", "google synthesize crashed please check api key", json_encode($e->getMessage()));
                header('Content-Type: application/json');
                echo json_encode([
                    "error" => true,
                    "message" => ERROR_CONTACT_ADMIN,
                    "key_error" => true,
                    "data" => [],
                    "test_d"=> $msg,
                    "error_message" => $e->getMessage(),
                    "csrfName" => csrf_token(),
                    "csrfHash" => csrf_hash()
                ]);
                die();
            }
          
            return false;
            try {
                $polly = $this->client();
                $data = [
                    'OutputFormat' => 'mp3',
                    'Text' =>  $text,
                    'TextType' => 'text',
                    'VoiceId' => $voice,
                    "LanguageCode" => $lang,
                ];
                if ($engine == 'neural') {
                    $data['Engine'] = 'neural';
                }
                $result = $polly->synthesizeSpeech($data);
                $newdata =  $result->get("AudioStream");
                return base64_encode($newdata);
            } catch (Exception $e) {

                return false;
            }
        }
        return false;
    }
    public function get_voices($language, $engine = "")
    {
        $languages = $this->get_languages();
        $client = $this->client();
        
        if (in_array($language, $languages)) {
            $condition = [
                'LanguageCode' => $language,
            ];
            $result = $client->describeVoices($condition);
            return $result;
            if (!empty($result['Voices']) > 0) {
                for ($i = 0; $i < count($result['Voices']); $i++) {
                    $arr[$i] = [
                        'voice' => $result['Voices'][$i]['Id'],
                        'display_name' => $result['Voices'][$i]['Name'],
                        'language' => $language,
                        'provider' => 'aws'
                    ];
                }

                return $arr;
            } else {
                return [];
            }
        }
    }
    public function save_voices()
    {
        $languages = $this->get_languages();
        $client = $this->client();
        
        $condition = [];
        $result = $client->describeVoices($condition);
        return $result;
    }
    public function get_languages()
    {
        $languages = array('cmn-CN', 'cy-GB', 'da-DK', 'de-DE', 'en-AU', 'en-GB', 'en-GB-WLS', 'en-IN', 'en-US', 'es-ES', 'es-MX', 'es-US', 'fr-CA', 'fr-FR', 'is-IS', 'it-IT', 'ja-JP', 'hi-IN', 'ko-KR', 'nb-NO', 'nl-NL', 'pl-PL', 'pt-BR', 'pt-PT', 'ro-RO', 'ru-RU', 'sv-SE', 'tr-TR', 'en-NZ', 'en-ZA', "hi-IN");
        return $languages;
    }
}
