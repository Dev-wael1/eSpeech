<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use app\Libraries\Key;


function generate_tokens($identity)
{
    $jwt = new App\Libraries\JWT();
    $db      = \Config\Database::connect();
    $user_id = $db->table('users')->select('id')->where(['username' => $identity])->get()->getResultArray()[0]['id'];
    $payload = [
        'iat' => time(), /* issued at time */
        'iss' => 'espeech',
        'exp' => time() + (30 * 60), 
        'sub' => 'espeech_authentication',
        'user_id' => $user_id
    ];
    $token = $jwt->encode($payload, API_SECRET);
    return $token;
}
function verify_tokens()
{
    $responses = \Config\Services::response();

    $jwt = new App\Libraries\JWT;
    $key = new App\Libraries\Key;


    try {
        $token = $jwt->getBearerToken();
    } catch (\Exception $e) {
        $response['error'] = true;
        $response['message'] = $e->getMessage();
        print_r(json_encode($response));
        return false;
    }

    if (!empty($token)) {
        $api_keys = API_SECRET;
        if (empty($api_keys)) {
            $response['error'] = true;
            $response['message'] = 'No Client(s) Data Found !';
            print_r(json_encode($response));
            return false;
        }
        App\Libraries\JWT::$leeway = 60000000000000;
        $flag = true; //For payload indication that it return some data or throws an expection.
        $error = true; //It will indicate that the payload had verified the signature and hash is valid or not.

        $message = '';
        $user_token = "";
        try {
            $user_id = $jwt->decode_unsafe($token)->user_id;
            $user_token = fetch_details('users', ['id' => $user_id])[0]['api_key'];
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        if ($user_token == $token) {
            try {
                $payload = $jwt->decode($token, new Key($api_keys, 'HS256'));
                // $payload = $jwt->decode($token, $api_keys, ['HS256']);

                if (isset($payload->iss) && $payload->iss == 'espeech') {

                    $error = false;
                    $flag = false;
                } else {
                    $error = true;
                    $flag = false;
                    $message = 'Invalid Hash';
                }
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $error = true;
            $flag = false;
            $message = 'Token expired. Please login again';
            
        }   

        if ($flag) {
            $response['error'] = true;
            $response['message'] = $message;
            print_r(json_encode($response));
            return false;
        } else {
            if ($error == true) {
                $response['error'] = true;
                $response['message'] = $message;
                $responses->setStatusCode(401);
                print_r(json_encode($response));
                return false;
            } else {
                return $payload->user_id;
            }
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Unauthorized access not allowed";

        print_r(json_encode($response));
        return false;
    }
}
