<?php
namespace App\Libraries;
/**
* Code Igniter
*
* An open source application development framework for PHP 4.3.2 or newer
*
* @package     CodeIgniter
* @author      Rick Ellis
* @copyright   Copyright (c) 2006, pMachine, Inc.
* @license     http://www.codeignitor.com/user_guide/license.html
* @link        http://www.codeigniter.com
* @since       Version 1.0
* @filesource
*/
class Paypal_lib
{
    var $last_error;            // holds the last error encountered
    var $ipn_log;                // bool: log IPN results to text file?
    var $ipn_log_file;            // filename of the IPN log
    var $ipn_response;            // holds the IPN response from paypal
    var $ipn_data = array();    // array contains the POST values for IPN
    var $fields = array();        // array holds the fields to submit to paypal
    var $paypal_url = '';        // The path of the buttons
    var $submit_btn = '';        // Image/Form button
    var $button_path = '';        // The path of the buttons
    var $CI;
    function __construct()
    {
        helper('url');
        helper('form');
        helper('function');
        $pg_settings = get_settings('payment_gateways_settings', true);
        $this->paypal_url = (!empty($pg_settings) && $pg_settings['paypal_mode'] == 'test') ?
            'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
        $this->last_error = '';
        $this->ipn_response = '';
        // $this->ipn_log_file = $this->CI->config->item('paypal_lib_ipn_log_file');
        // $this->ipn_log = $this->CI->config->item('paypal_lib_ipn_log');
        // $this->button_path = $this->CI->config->item('paypal_lib_button_path');
        // populate $fields array with a few default values.  See the paypal
        // documentation for a list of fields and their data types. These defaul
        // values can be overwritten by the calling script.
        $businessEmail = isset($pg_settings['business_email']) ? $pg_settings['business_email'] : "seller@somedomain.com";
        // email used above shall be used for final product
        // $businessEmail = "harshadpatel1507@gmail.com";
        $this->add_field('payer_email', 'testing@infinitietech.com');
        $this->add_field('business', $pg_settings['paypal_mode'] != 'test' ? $businessEmail : "seller@somedomain.com");
        $this->add_field('rm', '2');              // Return method = POST
        $this->add_field('cmd', '_xclick');
        $this->add_field('currency_code',  isset($general_settings['currency']) ? $general_settings['currency'] : "USD");
        $this->add_field('quantity', '1');
        $this->button('Pay Now!');
    }
    public function get_credentials()
    {
        $settings = get_settings('payment_gateways_settings', true);
        $data['notification_url'] = (isset($settings['notification_url'])) ? $settings['notification_url'] : 'no data';
        $data['notification_url'] = (isset($settings['notification_url'])) ? $settings['notification_url'] : 'no data';
        $data['client_id'] = (isset($settings['paypal_client_id'])) ? $settings['paypal_client_id'] : 'no data';
        $data['paypal_client_secret'] = (isset($settings['paypal_client_secret'])) ? $settings['paypal_client_secret'] : "no data";
        $data['webhook_url'] = (isset($settings['webhook_url'])) ? $settings['webhook_url'] : "no data";
        $data['webhook_id'] = (isset($settings['webhook_id'])) ? $settings['webhook_id'] : "no data";
        $data['end_point_url'] = (isset($settings['end_point_url'])) ? $settings['end_point_url'] : "no data";
        $data['paypal_mode'] = (isset($settings['paypal_mode'])) ? $settings['paypal_mode'] : 'no data';
        $data['status'] = (isset($settings['paypal_status'])) ? $settings['paypal_status'] : 'no data';
        return $data;
    }
    public function generate_access_token()
    {
        $curl = curl_init();
        $cred = $this->get_credentials();
        $client_id = $cred['client_id'];
        $client_secret = $cred['paypal_client_secret'];
        // $url = ($cred['paypal_mode'] == 'test') ? 'https://api-m.sandbox.paypal.com/v1/oauth2/token' : 'https://api-m.paypal.com/v1/oauth2/token' ;
        $url = $cred['end_point_url'] . 'v1/oauth2/token';
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode($client_id . ":" . $client_secret)
            ),
        ));
        $result = curl_exec($curl);
        $response = (!empty($result)) ? json_decode($result, true) : "";
        curl_close($curl);
        $access_token = (isset($response['access_token'])) ? $response['access_token'] : "";
        return $access_token;
    }
    public function cUrl($captured_id)
    {
        $cred = $this->get_credentials();
        // $url = ($cred['paypal_mode'] == 'test') ? 'https://api-m.sandbox.paypal.com/v2/payments/captures/' : 'https://api-m.paypal.com/v2/payments/captures/' ;
        $url = $cred['end_point_url'] . 'v2/payments/captures/';
        // this may change
        $access_token = $this->generate_access_token();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $captured_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $access_token",
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    public function fetch_transaction($captured_id)
    {
        $data  = $this->cUrl($captured_id);
        return $data;
    }
    function add_field($field, $value)
    {
        // adds a key=>value pair to the fields array, which is what will be
        // sent to paypal as POST variables.  If the value is already in the
        // array, it will be overwritten.
        $this->fields[$field] = $value;
    }
    function button($value)
    {
        // changes the default caption of the submit button
        $this->submit_btn = form_submit('pp_submit', $value);
    }
    function image($file)
    {
        $this->submit_btn = '<input type="image" name="add" src="' . site_url($this->button_path . '/' . $file) . '" border="0" />';
    }
    function paypal_auto_form()
    {
        // this function actually generates an entire HTML page consisting of
        // a form with hidden elements which is submitted to paypal via the
        // BODY element's onLoad attribute.  We do this so that you can validate
        // any POST vars from you custom form before submitting to paypal.  So
        // basically, you'll have your own form which is submitted to your script
        // to validate the data, which in turn calls this function to create
        // another hidden form and submit to paypal.
        $this->button('Click here if you\'re not automatically redirected...');
        echo '<html>' . "\n";
        echo '<head><title>Processing Payment...</title></head>' . "\n";
        echo '<body style="text-align:center;" onLoad="document.forms[\'paypal_auto_form\'].submit();">' . "\n";
        echo '<p style="text-align:center;">Please wait, your order is being processed and you will be redirected to the paypal website.</p>' . "\n";
        echo $this->paypal_form('paypal_auto_form');
        echo '</body></html>';
    }
    function paypal_form($form_name = 'paypal_form')
    {
        $str = '';
        $str .= '<form method="post" action="' . $this->paypal_url . '" name="' . $form_name . '"/>' . "\n";
        // $str .= '<input type="hidden" name="payer_email" value="testing@infinitietech.com" />';
        foreach ($this->fields as $name => $value)
            $str .= form_hidden($name, $value) . "\n";
        $str .= '<p><img src="' . base_url('public/backend/assets/img/loader.gif') . '" alt="Please wait.. Loading" title="Please wait.. Loading.." width="140px" /></p>';
        $str .= '<p>' . $this->submit_btn . '</p>';
        $str .= form_close() . "\n";
        return $str;
    }
}