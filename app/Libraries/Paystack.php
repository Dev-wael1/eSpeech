<?php
namespace App\Libraries;
/*
	PAYSTACK Library 

	Functions 
	-------------
	1. get_banks()
	2. create_transfer_receipt()
	3. transfer()
	4. finalize_transfer()
	5. disable_otp()
	6. disable_otp_finalize()
	7. verify_transfer()
	8. resolve_bank()
	9. curl_request()
*/


class Paystack{
	private $secret_key,$key_id,$curl,$currency;
	public $temp = array();
    function __construct()
    {
        $settings = get_settings('payment_gateways_settings',true); 
        $this->key_id = (isset($settings['paystack_key']))?$settings['paystack_key']:'';
        $this->secret_key = (isset($settings['paystack_secret']))?$settings['paystack_secret']:'';
        $this->currency = (isset($settings['paystack_currency']))?$settings['paystack_currency']:'NGN';
     
        $this->url = "https://api.paystack.co/";
    }
    public function get_credentials(){
        $this->temp['secret'] = $this->secret_key;
        $this->temp['key'] = $this->key_id;
        $this->temp['url'] = $this->url;
        $this->temp['currency'] = $this->currency;
        return $this->temp;
    }
	public function get_banks($slug=''){
		$end_point = $this->url."bank/".$slug;
		$method = "Get";
		$banks = $this->curl_request($end_point,$method);
		return $banks;
	}
	
	public function create_transfer_receipt($data){
		$end_point = $this->url."transferrecipient";
		$method = "post";

		
		$transfer = $this->curl_request($end_point,$method,$data);
		return $transfer;
	}
	
	public function transfer($data){
		$end_point = $this->url."transfer";
		$method = "post";

		$transfer = $this->curl_request($end_point,$method,$data);
		return $transfer;
	}
	
	public function finalize_transfer($data){
		$end_point = $this->url."transfer/finalize_transfer";
		$method = "post";

		$transfer = $this->curl_request($end_point,$method,$data);
		return $transfer;
	}
	
	public function list_transfers($transfer_code = ''){
		$end_point = $this->url."transfer";
		$end_point .= (!empty($transfer_code))?"/".$transfer_code:"";
		$method = "get";
		$transfer = $this->curl_request($end_point,$method);
		return $transfer;
	}
	
	public function disable_otp(){
		$end_point = $this->url."transfer/disable_otp";
		$method = "post";
		
		$transfer = $this->curl_request($end_point,$method);
		return $transfer;
	}
	
	public function disable_otp_finalize($data){
		$end_point = $this->url."transfer/disable_otp_finalize";
		$method = "post";
		$transfer = $this->curl_request($end_point,$method,$data);
		return $transfer;
	}
	
	public function verify_transfer($reference = ''){
		$end_point = $this->url."transfer/verify";
		$end_point .= (!empty($reference))?"/".$reference:"";
		$method = "get";
		$transfer = $this->curl_request($end_point,$method);
		return $transfer;
	}
	public function resolve_bank($data){
		$end_point = $this->url."bank/resolve";
		$end_point .=(!empty($data))?"?account_number=".$data['account_number']."&bank_code=".$data['bank_code']:"";
		$method = "get";
		$bank_details = $this->curl_request($end_point,$method);
        return $bank_details;
	}
    
    public function verify_transation($reference = ''){
		$end_point = $this->url."transaction/verify";
		$end_point .= (!empty($reference))?"/".$reference:"";
		$method = "get";
		$transfer = $this->curl_request($end_point,$method);
		return $transfer;
	}
	public function curl_request($end_point,$method,$data = array()){
		$this->curl = curl_init();

		curl_setopt_array($this->curl, array(
			CURLOPT_URL => $end_point,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => strtoupper($method),
			CURLOPT_POSTFIELDS => $data,   /* example array('test_key' => 'test_value_1') */
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer ".$this->secret_key
			),
		));
		
		$response = curl_exec($this->curl);
		
		curl_close($this->curl);
		return $response;
	}
}
?>