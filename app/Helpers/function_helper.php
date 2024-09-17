<?php
/* 
    ------------------------------------------------------------------------------------
        espeech helpers
    ------------------------------------------------------------------------------------

    1.  function curl($url, $method = 'GET', $header = ['Content-Type: application/x-www-form-urlencoded'], $data = [], $authorization = NULL)
    2.  function generate_token()
    3.  function verify_token()
    4.  function xss_clean($data)
    5.  function get_settings($type = 'system_settings', $is_json = false)
    6.  function output_escaping($array)
    7.  function escape_array($array)
    8.  function update_details($set, $where, $table, $escape = true)
    9.  function fetch_details($table, $where = [], $fields = [])
    10. function exists($where, $table)
    11. function active_plan($user_id)
    12. function update_characters($length, $user_id, $provider = "")
    13. function user_characters($user_id)
    14. function active_plan_type($user_id)
    15. function verify_voice($language  ,$voice  , $provider )
    16. function get_plans($plan_id = null)
    17. function get_subscription($user_id, $active = false)
    18. function get_subscription($user_id, $active = false)
    19. function add_subscription($user_id, $plan_id, $tenure, $transaction_id, $price, $starts_from = '', $start_now = false)
    20. function slugify($text, $divider = '-')
    21. function verify_payment_transaction($txn_id, $payment_method, $additional_data = [])
    22. function add_transaction($transaction_id, $amount, $payment_method, $user_id, $status = 'pending', $subscription_id = '', $message = '')
    23. function upcoming_plans($user_id)
    24. function subscription_status($subscription_id)
    25. function valid_image($image)
    26. function move_file($file, $path = 'public/uploads/images/', $name = '', $replace = false, $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'])
    27. function formatOffset($offset)
    28. function get_timezone()
    29. function get_timezone_array()
    30. function check_exists($file)
    31. function has_upcoming($user_id)
    32. function convert_active($user_id)
    33. function upcoming_plan($user_id)
    34. function numbers_initials($num)
    35. function mail_error($subject, $message, $trace = "")
    36. function mask_email($email)
    37. function get_system_update_info()
    38. function labels($label, $alt = '')
    39. function create_label($variable , $title = '')
    40. function get_currency()
    41. function console_log($data)
    42. function delete_directory($dir) 
    43. function formate_number($number, $decimals = 0, $decimal_separator = '.', $thousand_separator = ',', $currency_symbol = '', $type = 'prefix')
    44. function email_sender($user_email, $subject, $message)

*/

use App\Libraries\Aws;
use App\Libraries\Azure;
use App\Libraries\Google;
use App\Libraries\IBM;
use App\Libraries\Paystack;
use App\Libraries\Paytm;
use App\Libraries\Razorpay;
use App\Libraries\Paypal_lib;

function curl($url, $method = 'GET', $header = ['Content-Type: application/x-www-form-urlencoded'], $data = [], $authorization = NULL)
{
    $ch = curl_init();
    $curl_options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => $header
    );

    if (strtolower($method) == 'post') {
        $curl_options[CURLOPT_POST] = 1;
        $curl_options[CURLOPT_POSTFIELDS] = http_build_query($data);
    } else {
        $curl_options[CURLOPT_CUSTOMREQUEST] = 'GET';
    }
    curl_setopt_array($ch, $curl_options);

    $result = array(
        'body' => curl_exec($ch),
        'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
    );
    return $result;
}
function generate_token()
{
    $jwt = new App\Libraries\JWT();
    $payload = [
        'iat' => time(), /* issued at time */
        'iss' => 'espeech',
        'exp' => time() + (30 * 60), /* expires after 1 minute */
        'sub' => 'espeech_authentication'
    ];
    $token = $jwt->encode($payload, "my_secret");
    print_r(json_encode($token));
}
function verify_token()
{
    $jwt = new App\Libraries\JWT;

    try {
        $token = $jwt->getBearerToken();
    } catch (\Exception $e) {
        $response['error'] = true;
        $response['message'] = $e->getMessage();
        print_r(json_encode($response));
        return false;
    }

    if (!empty($token)) {
        $api_keys = 'my_secret';
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
        try {
            $payload = $jwt->decode($token, $api_keys, ['HS256']);
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


        if ($flag) {
            $response['error'] = true;
            $response['message'] = $message;
            print_r(json_encode($response));
            return false;
        } else {
            if ($error == true) {
                $response['error'] = true;
                $response['message'] = $message;
                print_r(json_encode($response));
                return false;
            } else {
                return true;
            }
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Unauthorized access not allowed";
        print_r(json_encode($response));
        return false;
    }
}
function xss_clean($data)
{
    $data = trim($data);
    // Fix &entity\n;
    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
    do {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    } while ($old_data !== $data);
    // we are done...
    return $data;
}
function get_settings($type = 'system_settings', $is_json = false, $bool = false)
{
    $db      = \Config\Database::connect();
    $builder = $db->table('settings');
    $res = $builder->select(' * ')->where('variable', $type)->get()->getResultArray();
    if (!empty($res)) {
        if ($is_json) {
            return json_decode($res[0]['value'], true);
        } else {
            return $res[0]['value'];
        }
    } else {
        if ($bool) {

            return false;
        } else {
            return [];
        }
    }
}
function output_escaping($array)
{
    if (!empty($array)) {
        if (is_array($array)) {
            $data = array();
            foreach ($array as $key => $value) {
                if ($value != null) {
                    $data[$key] = stripcslashes($value);
                }
            }
            return $data;
        } else if (is_object($array)) {
            $data = new stdClass();
            foreach ($array as $key => $value) {
                $data->$key = stripcslashes($value);
            }
            return $data;
        } else {
            return stripcslashes($array);
        }
    }
}
function escape_array($array)
{
    $db      = \Config\Database::connect();
    $posts = array();
    if (!empty($array)) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $posts[$key] = $db->escapeString($value);
            }
        } else {
            return $db->escapeString($array);
        }
    }
    return $posts;
}
function update_details($set, $where, $table, $escape = true)
{
    $db      = \Config\Database::connect();
    $db->transStart();
    if ($escape) {
        $set = escape_array($set);
    }
    $db->table($table)->update($set, $where);
    $db->transComplete();
    $response = FALSE;
    if ($db->transStatus() === TRUE) {
        $response = TRUE;
    }
    return $response;
}
function fetch_details($table, $where = [], $fields = [], $limit = null, $offset = '0', $sort = 'id', $order = 'DESC', $where_in_key = '', $where_in_value = '')
{
    $db      = \Config\Database::connect();
    $builder = $db->table($table);
    if (!empty($fields)) {
        $builder = $builder->select($fields);
    }
    if (!empty($where)) {
        $builder = $builder->where($where)->select($fields);
    }
    if (!empty($where_in_key) && !empty($where_in_value)) {
        $builder = $builder->whereIn($where_in_key, $where_in_value);
    }

    if ($limit != null) {
        $builder = $builder->limit($limit, $offset);
    }
    $builder = $builder->orderBy($sort, $order);
    $res = $builder->get()->getResultArray();
    return $res;
}
function exists($where, $table)
{
    $db      = \Config\Database::connect();
    $builder = $db->table($table);
    $builder = $builder->where($where);
    $res = count($builder->get()->getResultArray());
    if ($res > 0) {
        return true;
    } else {
        return false;
    }
}




function active_plan($user_id)
{
    $db      = \Config\Database::connect();
    $builder = $db->table('subscriptions');
    $builder = $builder->where(['user_id' => $user_id]);
    $result = $builder->get()->getResultArray();
    if (count($result) == 0) {
        return false;
    }
    $type = $db->table('subscriptions');
    $arr = $type->select(['type', 'starts_from', 'expires_on', 'tenure', 'id'])->where(['user_id' => $user_id, 'status' => 1])->get()->getResultArray();
    foreach ($arr as $row) {
        $status = subscription_status($row['id']);
        if ($status == 'active') {
            return $row['id'];
        }
    }
    return false;
}

function update_characters($length, $user_id, $provider = "") //to do
{
    $id = active_plan($user_id);
    if (!$id) {
        return false;
    }
    $db      = \Config\Database::connect();
    $builder = $db->table('subscriptions');
    $builder = $builder->where(['id' => $id]);
    $result = $builder->get()->getResultArray()[0];
    $type =  $result['type'];
    if ($type == 'general') {
        $col = "remaining_" . $provider;
        $provider_balance = (int)$result[$col];
        $remaining_character = (int)$result['remaining_characters'];
        $total_chatacters = $provider_balance + $remaining_character;
        if ($total_chatacters >= $length) {
            $builder = $db->table('subscriptions')->where(['id' => $id]);
            if ($length >= $provider_balance) {
                $data[$col] = 0;
                $length = $length - $provider_balance;
                $data['remaining_characters'] = $remaining_character - $length;
            } else {
                $data[$col] = $provider_balance - $length;
            }
            $builder->update($data);
            return true;
        } else {
            return false;
        }
    }
    if ($type == 'provider') {
        $builder = $db->table('subscriptions')->where(['id' => $id]);
        $remaining = 'remaining_characters';
        $remaining_characters = $result[$remaining];
        $total_chatacters = $remaining_characters + $length;
        switch (strtolower($provider)) {
            case "ibm":

                $remaining_ibm = (int)$result['remaining_ibm'];
                $total_chatacters = $remaining_characters + $remaining_ibm;
                if ($total_chatacters >= $length) {
                    if ($length >= $remaining_characters) {
                        $data[$remaining] = 0;
                        $length = $length - $remaining_characters;
                        $data['remaining_ibm'] = $remaining_ibm - $length;
                    } else {
                        $data[$remaining] = (int)$result[$remaining] - $length;
                    }
                    $builder->update($data);
                    return true;
                } else {
                    return false;
                }

                break;
            case "azure":
                $remaining_azure = (int)$result['remaining_azure'];
                $total_chatacters = $remaining_characters + $remaining_azure;
                if ($total_chatacters >= $length) {
                    if ($length >= $remaining_characters) {
                        $data[$remaining] = 0;
                        $length = $length - $remaining_characters;
                        $data['remaining_azure'] = $remaining_azure - $length;
                    } else {
                        $data[$remaining] = (int)$result[$remaining] - $length;
                    }
                    $builder->update($data);
                    return true;
                } else {
                    return false;
                }

                break;
            case "aws":
                $remaining_aws = (int)$result['remaining_aws'];
                $total_chatacters = $remaining_characters + $remaining_aws;
                if ($total_chatacters >= $length) {
                    if ($length >= $remaining_characters) {
                        $data[$remaining] = 0;
                        $length = $length - $remaining_characters;
                        $data['remaining_aws'] = $remaining_aws - $length;
                    } else {
                        $data[$remaining] = (int)$result[$remaining] - $length;
                    }
                    $builder->update($data);
                    return true;
                } else {
                    return false;
                }

                break;
            case "google":
                $remaining_google = (int)$result['remaining_google'];
                $total_chatacters = $remaining_characters + $remaining_google;

                if ($total_chatacters >= $length) {
                    if ($length >= $remaining_characters) {
                        $data[$remaining] = 0;
                        $length = $length - $remaining_characters;
                        $data['remaining_google'] = $remaining_google - $length;
                    } else {
                        $data[$remaining] = (int)$result[$remaining] - $length;
                    }
                    $builder->update($data);
                    return true;
                } else {
                    return false;
                }

                break;
            default:
                return false;
        }
    }
    return false;
}
function user_characters($user_id) //t0 do
{
    $id = active_plan($user_id);
    if (!$id) {
        return false;
    }
    $db      = \Config\Database::connect();
    $builder = $db->table('subscriptions');
    $builder = $builder->where(['id' => $id]);
    $result = $builder->get()->getResultArray()[0];
    $type =  $result['type'];
    if ($type == 'general') {
        $remaining_characters = (int)$result['remaining_characters'];
        return [
            'type' => $type,
            'remaining_characters' => $remaining_characters

        ];
    }
    $remaining_characters = (int)$result['remaining_characters'];
    $ibm = (int)$result['remaining_ibm'];
    $azure = (int)$result['remaining_azure'];
    $aws =  (int)$result['remaining_aws'];
    $google = (int)$result['remaining_google'];
    $arr = [
        'type' => $type,
        'ibm' => $ibm,
        'aws' => $aws,
        'azure' => $azure,
        'google' => $google,
        'remaining_characters' => $remaining_characters



    ];
    return $arr;
}
function active_plan_type($user_id)
{
    $id = active_plan($user_id);
    if (!$id) {
        return false;
    }
    $db      = \Config\Database::connect();
    $builder = $db->table('subscriptions');
    $builder = $builder->where(['id' => $id]);
    $result = $builder->get()->getResultArray()[0];
    return $result['type'];
}
function verify_voice($language, $voice, $provider)
{

    return true;
}
function get_plans($plan_id = null)
{
    if ($plan_id == null) {
        $plans = fetch_details('plans', ['status' => 1], [], null, "0", "row_order", "ASC");
    } else {
        $plans = fetch_details('plans', ['id' => $plan_id, 'status' => 1], [], null, "0", "row_order", "ASC");
    }

    foreach ($plans as $key => $val) {
        $plans[$key]['tenure'] = fetch_details('plans_tenures', ['plan_id' => $plans[$key]['id']], ['id', 'title', 'months', 'price', 'discounted_price']);
    }
    return  $plans;
}
function get_subscription($user_id, $active = false)
{
    $data = fetch_details('subscriptions', ['user_id' => $user_id]);
    if (empty($data)) {
        return false;
    }
    if ($active) {
        $id = active_plan($user_id);
        $data = fetch_details('subscriptions', ['user_id' => $user_id, "id" => $id]);
        if (!$id) {
            return false;
        }
        foreach ($data as $key => $val) {
            if ($data[$key]['id'] == $id) {
                $data[$key]['status'] = subscription_status($data[$key]['id']);

                return $data;
            }
        }
    } else {
        $id = active_plan($user_id);
        if (!$id) {
            $id = -1;
        }
        foreach ($data as $key => $val) {
            $data[$key]['status'] = subscription_status($data[$key]['id']);
        }
    }
    return $data;
}
function add_subscription($user_id, $plan_id, $tenure, $transaction_id, $price, $starts_from = '', $start_now = false, $is_bank = false)
{
    $id = active_plan($user_id);
    $db = \Config\Database::connect();
    $prev_characters = 0;
    $prev_aws = 0;
    $prev_azure = 0;
    $prev_google = 0;
    $prev_ibm = 0;
    if ($starts_from == '') {
        $starts_from = date('Y-m-d');
    }
    //  new date for bank payments
    if ($id) {
        $previous = fetch_details('subscriptions', ['id' => $id])[0];
        if ($start_now) {
            $expiry_date = strtotime($previous['starts_from']);
            $expiry_date = strtotime($previous['expires_on']);
            $seconds = $expiry_date - time();
            if ($seconds > 0) {
                $prev_aws = $previous['remaining_aws'];
                $prev_google = $previous['remaining_google'];
                $prev_ibm = $previous['remaining_ibm'];
                $prev_azure = $previous['remaining_azure'];
                $prev_characters = $previous['remaining_characters'];
            }
            update_details(['status' => 0], ['id' => $id], 'subscriptions');
        } else {
            if (!($starts_from == '')) {
                $starts_from = $previous['expires_on'];
            }
        }
    }
    $expiry_date = new \DateTime($starts_from);
    $expiry_date = $expiry_date->modify('+' . $tenure . ' months')->format('Y-m-d');
    $plan = $db->table('plans')->where(['id' => $plan_id]);
    $plan = $plan->get()->getResultArray()[0];
    if ($plan['type'] == 'provider') {
        $data = [
            'user_id' => $user_id,
            'plan_id' => $plan_id,
            'type' => $plan['type'],
            'plan_title' => $plan['title'],
            'google' => $plan['google'],
            'aws' => $plan['aws'],
            'ibm' => $plan['ibm'],
            'azure' => $plan['azure'],
            'remaining_google' => (int)$plan['google'] + $prev_google,
            'remaining_aws' => (int)$plan['aws'] + $prev_aws,
            'remaining_ibm' => (int)$plan['ibm'] + $prev_ibm,
            'remaining_azure' => (int)$plan['azure'] + $prev_azure,
            'remaining_characters' => (int)$plan['no_of_characters'] + $prev_characters,
            'transaction_id' => $transaction_id,
            'tenure' => $tenure,
            'price' => $price,
            'status' => (!$is_bank) ? '1' : '2',
            'starts_from' => (!$is_bank) ? $starts_from : "-",
            'expires_on' => (!$is_bank) ? $expiry_date : "-"
        ];
    } elseif ($plan['type'] == 'general') {
        $data = [
            'user_id' => $user_id,
            'plan_id' => $plan_id,
            'plan_title' => $plan['title'],
            'type' => $plan['type'],
            'characters' => $plan['no_of_characters'],
            'remaining_google' => $plan['google'] + $prev_google,
            'remaining_aws' => $plan['aws'] + $prev_aws,
            'remaining_ibm' => $plan['ibm'] + $prev_ibm,
            'remaining_azure' => $plan['azure'] + $prev_azure,
            'remaining_characters' => $plan['no_of_characters'] + $prev_characters,
            'transaction_id' => $transaction_id,
            'tenure' => $tenure,
            'price' => $price,
            'status' => (!$is_bank) ? '1' : '2',
            'starts_from' => (!$is_bank) ? $starts_from : "-",
            'expires_on' => (!$is_bank) ? $expiry_date : "-"
        ];
    } else {
        return false;
    }

    $builder = $db->table('subscriptions')->insert($data);
    if ($builder) {
        return $db->insertID();
    } else
        return false;
}
function slugify($text, $divider = '-')
{
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, $divider);
    $text = preg_replace('~-+~', $divider, $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}
function verify_payment_transaction($txn_id, $payment_method, $additional_data = [])
{
    $db      = \Config\Database::connect();

    if (empty(trim($txn_id))) {
        $response['error'] = true;
        $response['message'] = "Transaction ID is required";
        return $response;
    }
    $razorpay = new Razorpay;

    switch ($payment_method) {
        case 'razorpay':

            $payment = $razorpay->fetch_payments($txn_id);
            if (!empty($payment) && isset($payment['status'])) {
                if ($payment['status'] == 'authorized') {
                    $capture_response = $razorpay->capture_payment($payment['amount'], $txn_id, $payment['currency']);
                    if ($capture_response['status'] == 'captured') {
                        $response['error'] = false;
                        $response['message'] = "Payment captured successfully";
                        $response['amount'] = $capture_response['amount'] / 100;
                        $response['data'] = $capture_response;
                        $response['status'] = $payment['status'];
                        return $response;
                    } else if ($capture_response['status'] == 'refunded') {
                        $response['error'] = true;
                        $response['message'] = "Payment is refunded.";
                        $response['amount'] = $capture_response['amount'] / 100;
                        $response['data'] = $capture_response;
                        $response['status'] = $payment['status'];
                        return $response;
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Payment could not be captured.";
                        $response['amount'] = (isset($capture_response['amount'])) ? $capture_response['amount'] / 100 : 0;
                        $response['data'] = $capture_response;
                        $response['status'] = $payment['status'];
                        return $response;
                    }
                } else if ($payment['status'] == 'captured') {
                    $status = 'captured';
                    $response['error'] = false;
                    $response['message'] = "Payment captured successfully";
                    $response['amount'] = $payment['amount'] / 100;
                    $response['status'] = $payment['status'];
                    $response['data'] = $payment;
                    return $response;
                } else if ($payment['status'] == 'created') {
                    $status = 'created';
                    $response['error'] = true;
                    $response['message'] = "Payment is just created and yet not authorized / captured!";
                    $response['amount'] = $payment['amount'] / 100;
                    $response['data'] = $payment;
                    $response['status'] = $payment['status'];
                    return $response;
                } else {
                    $status = 'failed';
                    $response['error'] = true;
                    $response['message'] = "Payment is " . ucwords($payment['status']) . "! ";
                    $response['amount'] = (isset($payment['amount'])) ? $payment['amount'] / 100 : 0;
                    $response['status'] = $payment['status'];
                    $response['data'] = $payment;
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Payment not found by the transaction ID!";
                $response['amount'] = 0;
                $response['data'] = [];
                $response['status'] = 'failed';
                return $response;
            }
            break;
        case "paystack":
            $paystack = new Paystack;
            $payment = $paystack->verify_transation($txn_id);
            if (!empty($payment)) {
                $payment = json_decode($payment, true);
                if (isset($payment['data']['status']) && $payment['data']['status'] == 'success') {
                    $response['error'] = false;
                    $response['message'] = "Payment is successful";
                    $response['amount'] = (isset($payment['data']['amount'])) ? $payment['data']['amount'] / 100 : 0;
                    $response['data'] = $payment;
                    $response['status'] = $payment['data']['status'];
                    return $response;
                } elseif (isset($payment['data']['status']) && $payment['data']['status'] != 'success') {
                    $response['error'] = true;
                    $response['message'] = "Payment is " . ucwords($payment['data']['status']) . "! ";
                    $response['amount'] = (isset($payment['data']['amount'])) ? $payment['data']['amount'] / 100 : 0;
                    $response['data'] = $payment;
                    $response['status'] = $payment['data']['status'];

                    return $response;
                } else {
                    $response['error'] = true;
                    $response['message'] = "Payment is unsuccessful! ";
                    $response['amount'] = (isset($payment['data']['amount'])) ? $payment['data']['amount'] / 100 : 0;
                    $response['data'] = $payment;
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Payment not found by the transaction ID!";
                $response['amount'] = 0;
                $response['data'] = [];
                $response['status'] = 'failed';
                return $response;
            }
            break;
        case 'paytm':
            $paytm = new Paytm;
            $payment = $paytm->transaction_status($txn_id);
            if (!empty($payment)) {
                $payment = json_decode($payment, true);
                if (
                    isset($payment['body']['resultInfo']['resultCode'])
                    && ($payment['body']['resultInfo']['resultCode'] == '01' && $payment['body']['resultInfo']['resultStatus'] == 'TXN_SUCCESS')
                ) {
                    $response['error'] = false;
                    $response['message'] = "Payment is successful";
                    $response['amount'] = (isset($payment['body']['txnAmount'])) ? $payment['body']['txnAmount'] : 0;
                    $response['data'] = $payment;
                    return $response;
                } elseif (
                    isset($payment['body']['resultInfo']['resultCode'])
                    && ($payment['body']['resultInfo']['resultStatus'] == 'TXN_FAILURE')
                ) {
                    $response['error'] = true;
                    $response['message'] = $payment['body']['resultInfo']['resultMsg'];
                    $response['amount'] = (isset($payment['body']['txnAmount'])) ? $payment['body']['txnAmount'] : 0;
                    $response['data'] = $payment;
                    return $response;
                } else if (
                    isset($payment['body']['resultInfo']['resultCode'])
                    && ($payment['body']['resultInfo']['resultStatus'] == 'PENDING')
                ) {
                    $response['error'] = true;
                    $response['message'] = $payment['body']['resultInfo']['resultMsg'];
                    $response['amount'] = (isset($payment['body']['txnAmount'])) ? $payment['body']['txnAmount'] : 0;
                    $response['data'] = $payment;
                    return $response;
                } else {
                    $response['error'] = true;
                    $response['message'] = "Payment is unsuccessful!";
                    $response['amount'] = (isset($payment['body']['txnAmount'])) ? $payment['body']['txnAmount'] : 0;
                    $response['data'] = $payment;
                    return $response;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Payment not found by the Order ID!";
                $response['amount'] = 0;
                $response['data'] = [];
                return $response;
            }
            break;
        case 'paypal':
            $paypal = new Paypal_lib;
            $payment = json_decode($paypal->fetch_transaction($txn_id), true);
            if ($payment['status'] == 'COMPLETED') {
                $response['error'] = false;
                $response['message'] = "Payment is successful";
                $response['amount'] = $payment['amount']['value'];
                $response['data'] = $payment;
                return $response;
            } elseif ($payment['status'] == 'DECLINED') {
                $response['error'] = true;
                $response['message'] = $payment['status'];
                $response['amount'] = (isset($payment['amount']['value'])) ? $payment['amount']['value'] : 0;
                $response['data'] = $payment;
                return $response;
            } elseif ($payment['status'] == 'PENDING') {
                $response['error'] = true;
                $response['message'] = $payment['status'];
                $response['amount'] = (isset($payment['amount']['value'])) ? $payment['amount']['value'] : 0;
                $response['data'] = $payment;
                return $response;
            } else {
                $response['error'] = true;
                $response['message'] = 'Payment failed';
                $response['amount'] = (isset($payment['amount']['value'])) ? $payment['amount']['value'] : 0;
                $response['data'] = $payment;
                return $response;
            }
            break;
    }
}
function add_transaction($transaction_id, $amount, $payment_method, $user_id, $status = 'pending', $subscription_id = '', $message = '')
{
    $db      = \Config\Database::connect();
    $arr = [
        'user_id' => $user_id,
        'subscription_id' => $subscription_id,
        'payment_method' => $payment_method,
        'txn_id' => $transaction_id,
        'amount' => $amount,
        'message' => $message,
        'status' => $status
    ];
    $insert = $db->table('transactions')->insert($arr);
    if ($insert) {
        return $db->insertID();
    } else {
        return false;
    }
}
function upcoming_plans($user_id)
{
    $db      = \Config\Database::connect();
    $builder = $db->table('subscriptions');
    $builder = $builder->where(['user_id' => $user_id]);
    $result = $builder->get()->getResultArray();
    if (count($result) == 0) {
        return false;
    }
    $type = $db->table('subscriptions');
    $arr = $type->select(['type', 'starts_from', 'tenure', 'id'])->where(['user_id' => $user_id, 'status' => 1])->get()->getResultArray();
    $data = [];
    foreach ($arr as $row) {
        $row = output_escaping($row);
        $startsfrom = new \DateTime($row['starts_from']);
        $now = new \DateTime();
        $expiry_date = strtotime($row['starts_from']);
        $expiry_date = strtotime("+" . $row['tenure'] . " months", $expiry_date);
        $seconds = $expiry_date - time();
        if (!($startsfrom <= $now)) {
            $status = '0';
        }
    }
    return $data;
}
function subscription_status($subscription_id)
{
    $db      = \Config\Database::connect();
    $row = $db->table('subscriptions')->where(['id' => $subscription_id])->get()->getResultArray()[0];

    $starts_from = strtotime($row['starts_from']);
    $expiry_date = strtotime($row['expires_on']);
    $seconds = $expiry_date - time();

    $status = 'expired';

    if ($seconds > 0 || $row['status'] == 1) {
        $status = 'active';
    }
    if ($starts_from > time()) {
        $status = 'upcoming';
    }
    if ($row['status'] == 0) {
        $status = 'expired';
    }
    if ($row['status'] == 2) {
        $status = 'pending';
    }
    return $status;
}
function valid_image($image)
{
    helper(['form', 'url']);
    $request = \Config\Services::request();
    if ($request->getFile($image)) {
        $file = $request->getFile($image);
        if (!$file->isValid()) {
            return false;
        }
        $type = $file->getMimeType();
        if ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/jpg') {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function move_file($file, $path = 'public/uploads/images/', $name = '', $replace = false, $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'])
{
    $type = $file->getMimeType();
    $p = FCPATH . $path;
    if (in_array($type, $allowed_types)) {
        if ($name == '') {
            $name = $file->getName();
        }

        if ($name != '') {
            $name = explode($name, ".");
            unset($name[count($name) - 1]);
            $name = implode(".", $name);
            $ext =  $file->guessExtension();
            $name = strtolower($name . '.' . $ext);
        }
        if ($file->move($p, $name, $replace)) {
            $name = $file->getName();
            $response['error'] = false;
            $response['message'] = "File moved successfully";
            $response['file_name'] = $name;
            $response['extension'] = $ext;
            $response['file_size'] = $file->getSizeByUnit("kb");
            $response['path'] = $path;
            $response['full_path'] = $path . $name;
        } else {
            $response['error'] = true;
            $response['message'] = "File could not be moved!" . $file->getError();
            $response['file_name'] = $name;
            $response['extension'] = "";
            $response['file_size'] = "";
            $response['path'] = $path;
            $response['full_path'] = "";
        }

        return $response;
    } else {
        $response['error'] = true;
        $response['message'] = "File could not be moved! Invalid file type uploaded";
        return $response;
    }
}
function formatOffset($offset)
{
    $hours = $offset / 3600;
    $remainder = $offset % 3600;
    $sign = $hours > 0 ? '+' : '-';
    $hour = (int) abs($hours);
    $minutes = (int) abs($remainder / 60);
    if ($hour == 0 and $minutes == 0) {
        $sign = ' ';
    }
    return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0');
}
function get_timezone()
{
    $list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();
    $data = $offset = $added = array();
    foreach ($list as $abbr => $info) {
        foreach ($info as $zone) {
            if (
                !empty($zone['timezone_id'])
                and
                !in_array($zone['timezone_id'], $added)
                and
                in_array($zone['timezone_id'], $idents)
            ) {
                $z = new DateTimeZone($zone['timezone_id']);
                $c = new DateTime("", $z);
                $zone['time'] = $c->format('H:i a');
                $offset[] = $zone['offset'] = $z->getOffset($c);
                $data[] = $zone;
                $added[] = $zone['timezone_id'];
            }
        }
    }
    array_multisort($offset, SORT_ASC, $data);
    $options = array();
    foreach ($data as $key => $row) {
        $options[$row['timezone_id']] = $row['time'] . ' - '
            . formatOffset($row['offset'])
            . ' ' . $row['timezone_id'];
    }
    return $options;
}
function get_timezone_array()
{
    $list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();

    $data = $offset = $added = array();
    foreach ($list as $abbr => $info) {
        foreach ($info as $zone) {
            if (
                !empty($zone['timezone_id'])
                and
                !in_array($zone['timezone_id'], $added)
                and
                in_array($zone['timezone_id'], $idents)
            ) {
                $z = new DateTimeZone($zone['timezone_id']);
                $c = new DateTime("", $z);
                $zone['time'] = $c->format('h:i A');
                $offset[] = $zone['offset'] = $z->getOffset($c);
                $data[] = $zone;
                $added[] = $zone['timezone_id'];
            }
        }
    }

    array_multisort($offset, SORT_ASC, $data);
    $i = 0;
    $temp = array();
    foreach ($data as $key => $row) {
        $temp[0] = $row['time'];
        $temp[1] = formatOffset($row['offset']);
        $temp[2] = $row['timezone_id'];
        $options[$i++] = $temp;
    }

    return $options;
}
function check_exists($file)
{
    $file_headers = @get_headers($file);

    $target_path = FCPATH . $file;
    if (!file_exists($target_path)) {

        return true;
    } else {
        return false;
    }
}

function has_upcoming($user_id)
{
    $db      = \Config\Database::connect();
    $builder = $db->table('subscriptions');
    $builder = $builder->where(['user_id' => $user_id]);
    $result = $builder->get()->getResultArray();
    if (count($result) == 0) {
        return false;
    }
    $type = $db->table('subscriptions');
    $arr = $type->select(['type', 'starts_from', 'expires_on', 'tenure', 'id'])->where(['user_id' => $user_id, 'status' => 1])->get()->getResultArray();
    foreach ($arr as $row) {
        $status = subscription_status($row['id']);
        if ($status == 'upcoming') {
            return $row['id'];
        }
    }
    return false;
}


function convert_active($user_id)
{
    $id = active_plan($user_id);
    $db      = \Config\Database::connect();
    $prev_characters = 0;
    $prev_aws = 0;
    $prev_azure = 0;
    $prev_google = 0;
    $prev_ibm = 0;
    $starts_from = date('Y-m-d');
    $expiry_date = new \DateTime($starts_from);

    if ($id) {
        $previous = fetch_details('subscriptions', ['id' => $id])[0];

        $expiry_date = strtotime($previous['starts_from']);
        $expiry_date = strtotime($previous['expires_on']);
        $seconds = $expiry_date - time();
        if ($seconds > 0) {
            $prev_aws = $previous['remaining_aws'];
            $prev_google = $previous['remaining_google'];
            $prev_ibm = $previous['remaining_ibm'];
            $prev_azure = $previous['remaining_azure'];
            $prev_characters = $previous['remaining_characters'];
        }
        update_details(['status' => 0], ['id' => $id], 'subscriptions');
    }
    if (!$upcoming_id = has_upcoming($user_id)) {
        return false;
    }
    $upcoming = fetch_details('subscriptions', ['id' => $upcoming_id], ['plan_id', 'tenure'])[0];
    $plan = $db->table('plans')->where(['id' => $upcoming['plan_id']]);
    $plan = $plan->get()->getResultArray()[0];

    $expiry_date = date('Y-m-d', strtotime("+" . $upcoming['tenure'] . " months", strtotime($starts_from)));

    if ($plan['type'] == 'provider') {
        $data = [

            'remaining_google' => (int)$plan['google'] + $prev_google,
            'remaining_aws' => (int)$plan['aws'] + $prev_aws,
            'remaining_ibm' => (int)$plan['ibm'] + $prev_ibm,
            'remaining_azure' => (int)$plan['azure'] + $prev_azure,
            'remaining_characters' => (int)$plan['no_of_characters'] + $prev_characters,
            'status' => '1',
            'starts_from' => $starts_from,
            'expires_on' => $expiry_date
        ];
        if (update_details($data, ['id' => $upcoming_id], 'subscriptions')) {
            return true;
        }
        return false;
    } elseif ($plan['type'] == 'general') {
        $data = [

            'remaining_characters' => (int)$plan['no_of_characters'] + $prev_characters,
            'remaining_google' => $plan['google'] + $prev_google,
            'remaining_aws' => $plan['aws'] + $prev_aws,
            'remaining_ibm' => $plan['ibm'] + $prev_ibm,
            'remaining_azure' => $plan['azure'] + $prev_azure,
            'remaining_characters' => $plan['no_of_characters'] + $prev_characters,
            'status' => '1',
            'starts_from' => $starts_from,
            'expires_on' => $expiry_date
        ];
        if (update_details($data, ['id' => $upcoming_id], 'subscriptions')) {
            return true;
        }
        return false;
    } else {
        return false;
    }
}
function upcoming_plan($user_id)
{
    $db      = \Config\Database::connect();
    $builder = $db->table('subscriptions');
    $builder = $builder->where(['user_id' => $user_id, 'status' => 1]);
    $result = $builder->get()->getResultArray();

    if (count($result) == 0) {
        return null;
    }
    foreach ($result as $row) {
        $status = subscription_status($row['id']);
        if ($status == 'upcoming') {
            return $row;
        }
    }
    return null;
}
function numbers_initials($num)
{
    if ($num > 1000) {

        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('K', 'M', 'B', 'T');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];

        return $x_display;
    }

    return $num;
}
function mail_error($subject, $message, $trace = "")
{
}
function mask_email($email)
{

    if ($email != '') {
        $em   = explode("@", $email);

        $name = implode('@', array_slice($em, 0, count($em) - 1));
        $len  = floor(strlen($name) / 2);


        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
    } else {
        return "";
    }
}
function get_system_update_info()
{
    $check_query = false;
    $query_path = "";
    $data['previous_error'] = false;
    $sub_directory = (file_exists(UPDATE_PATH . "update/updater.json")) ? "update/" : "";
    if (file_exists(UPDATE_PATH . "updater.json") || file_exists(UPDATE_PATH . "update/updater.json")) {
        $lines_array = file_get_contents(UPDATE_PATH . $sub_directory . "updater.json");
        $lines_array = json_decode($lines_array, true);
        $file_version = $lines_array['version'];
        $file_previous = $lines_array['previous'];
        $check_query = $lines_array['manual_queries'];
        $query_path = $lines_array['query_path'];
    } else {
        print_r("no json exists");
        die();
    }
    $db_version_data =   fetch_details("updates");
    if (!empty($db_version_data) && isset($db_version_data[0]['version'])) {
        $db_current_version = $db_version_data[0]['version'];
    }
    if (!empty($db_current_version)) {
        $data['db_current_version'] = $db_current_version;
    } else {
        $data['db_current_version'] = $db_current_version = 1.0;
    }
    if ($db_current_version == $file_previous) {
        $data['file_current_version'] = $file_current_version = $file_version;
    } else {
        $data['previous_error'] = true;
        $data['file_current_version'] = $file_current_version = false;
    }

    if ($file_current_version != false && $file_current_version > $db_current_version) {

        $data['is_updatable'] =  true;
    } else {
        $data['is_updatable'] =  false;
    }
    $data['query'] =  $check_query;
    $data['query_path'] =  $query_path;
    return $data;
}

function labels($label, $alt = '')
{

    $label = trim($label);
    if (lang('Text.' . $label) != 'Text.' . $label) {
        if (lang('Text.' . $label) == '') {
            return $alt;
        }
        return trim(lang('Text.' . $label));
    } else {
        return trim($alt);
    }
}

function create_label($variable, $title = '')
{
    if ($title == '') {
        $title = $variable;
    }
    return '<div class="form-group col-md-6">
        <label>' . $title . '</label>
        <input type="text" name="' . $variable . '" value="' . labels($variable) . '" class="form-control">
    </div>';
}
function get_currency()
{
    try {
        $currency = get_settings('general_settings', true)['currency'];
        if ($currency == '') {
            $currency = '₹';
        }
    } catch (Exception $e) {
        $currency = '₹';
        console_log($e);
    }
    return $currency;
}


function console_log($data)
{
    if (is_array($data)) {
        $data = json_encode($data);
    } elseif (is_object($data)) {
        $data = json_encode($data);
    }
    echo "<script>console.log('$data')</script>";
}

function delete_directory($dir)
{

    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {

            if ($object != "." && $object != "..") {

                if (filetype($dir . "/" . $object) == "dir") {

                    // return 'this is folder';
                    $dir_sec = $dir . "/" . $object;
                    if (is_dir($dir_sec)) {
                        $objects_sec = scandir($dir_sec);
                        foreach ($objects_sec as $object_sec) {
                            if ($object_sec != "." && $object_sec != "..") {
                                if (filetype($dir_sec . "/" . $object_sec) == "dir")
                                    rmdir($dir_sec . "/" . $object_sec);
                                else
                                    unlink($dir_sec . "/" . $object_sec);
                            }
                        }
                        rmdir($dir_sec);
                    }
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        return rmdir($dir);
    }
}

function format_number($number, $decimals = 0, $decimal_separator = '.', $thousand_separator = ',', $currency_symbol = '', $type = 'prefix')
{
    $number = number_format($number, $decimals, $decimal_separator, $thousand_separator);
    $number = (!empty(trim($currency_symbol))) ? (($type == 'prefix') ? $currency_symbol . $number : $number . $currency_symbol) : $number;
    return $number;
}
function email_sender($user_email, $subject, $message)
{
    $email = \Config\Services::email();

    $email_settings = \get_settings('email_settings', true);
    $smtpUsername = $email_settings['smtpUsername'];
    $email_type = $email_settings['mailType'];

    $email->setFrom($smtpUsername, $email_type);
    $email->setTo($user_email);
    $email->setSubject($subject);
    $email->setMessage($message);
    if ($email->send()) {
        return true;
    } else {
        return false;
    }
}

function send_mail_with_template($type = '', $data = [], $external_link = "", $activation_mail = "", $extra_data = [])
{
    $mail_data =  fetch_details('email_template', ['email_type' => $type]);
    $generale_data =  get_settings('general_settings', true);
    if (empty($mail_data)) {
        return true;
    }
    $email = \Config\Services::email();
    $string = json_decode($mail_data[0]['email_text']);
    // print_r($string);
    if (!empty($data)) {
        if (strstr($string, '{user_name}')) {

            $string =  str_replace(
                '{user_name}',
                $data[0]['username'],
                $string
            );
        }
        if (strpos($string,  '{company_title}') !== false) {
            // print_r('yes');
            $string =  str_replace(
                '{company_title}',
                $generale_data['company_title'],
                $string
            );
        }
        if (strpos($string,  '{first_name}') !== false) {
            $string =  str_replace(
                '{first_name}',
                $data[0]['first_name'],
                $string
            );
        }
        if (strpos($string,  '{last_name}') !== false) {
            $string =  str_replace(
                '{last_name}',
                $data[0]['last_name'],
                $string
            );
        }
    } else {
        $string = json_decode($mail_data[0]['email_text']);
    }


    if (strpos($string,  '{link}') !== false) {
        $string =  str_replace(
            '{link}',
            $external_link,
            $string
        );
    }
    if (strpos($string,  '{support_email}') !== false) {
        $string =  str_replace(
            '{support_email}',
            $generale_data['support_email'],
            $string
        );
    }
    if (!empty($extra_data)) {
        if (isset($extra_data['amount']) && strpos($string,  '{amount}') !== false) {
            $string =  str_replace(
                '{amount}',
                $extra_data['amount'],
                $string
            );
        }
        if (isset($extra_data['transaction_id']) && strpos($string,  '{transaction_id}') !== false) {
            $string =  str_replace(
                '{transaction_id}',
                $extra_data['transaction_id'],
                $string
            );
        }
        if (isset($extra_data['message']) && strpos($string,  '{message}') !== false) {
            $string =  str_replace(
                '{message}',
                $extra_data['message'],
                $string
            );
        }
        if (isset($extra_data['start_date']) && strpos($string,  '{start_date}') !== false) {
            $string =  str_replace(
                '{start_date}',
                $extra_data['start_date'],
                $string
            );
        }
        if (isset($extra_data['month']) && strpos($string,  '{month}') !== false) {
            $string =  str_replace(
                '{month}',
                $extra_data['month'],
                $string
            );
        }
        if (isset($extra_data['expiry_date']) && strpos($string,  '{expiry_date}') !== false) {
            $string =  str_replace(
                '{expiry_date}',
                $extra_data['expiry_date'],
                $string
            );
        }
    }
    // echo "<pre>";
    // print_r($data);

    // die();

    $email_settings = get_settings('email_settings', true);
    $smtpUsername = $email_settings['smtpUsername'];
    $email_type = $email_settings['mailType'];
    $email->setFrom($smtpUsername, $generale_data['company_title']);

    $email->setTo(($data[0]['email'] != '') ? $data[0]['email'] : $activation_mail);
    $email->setSubject($mail_data[0]['email_subject']);
    $email->setMessage($string);
    if ($email->send()) {
        $response = [
            'error' => false,
            'message' => 'success',
            'data' => []
        ];
        return $response;
    } else {
        $response = [
            'error' => true,
            'message' => 'success',
            'data' => $email->printDebugger()
        ];
        return $response;
    }
}
