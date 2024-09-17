<?php

namespace App\Controllers\user;

use App\Libraries\Paystack;
use App\Libraries\Razorpay;
use App\Libraries\Stripe;

class Plans extends User
{
    private $plan_model, $plans_tenures_model;

    public function __construct()
    {
        parent::__construct();
        $this->plan_model = new \App\Models\Plan();
        $this->plans_tenures_model = new \App\Models\Tenures();
    }
    public function index()
    {
        if ($this->isLoggedIn) {
            $this->plan_model->builder()->select()->where('status', 1);
            $this->plans_tenures_model->builder()->select();
            $plans =  $this->plan_model->builder()->get()->getResultArray();
            $tenure =  $this->plans_tenures_model->builder()->get()->getResultArray();
            $this->data['plans'] =  fetch_details('plans', [], [], null, "0", "row_order", "ASC");
            $currency = get_settings('general_settings', true);
            $free_data = get_settings('tts_config',true);
            $this->data['free_data'] = $free_data;
            $currency = (isset($currency['currency'])) ? $currency['currency'] : '₹';
            $this->data['currency'] =  $currency;
            $this->data['tenure'] = $tenure;
            $this->data['title'] = 'Plan | User Panel';
            $this->data['main_page'] = 'plan';
            return view('backend/user/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function checkout()
    {
        if ($this->isLoggedIn && !$this->userIsAdmin) {
            if ($this->request->getGet('plan_id') && $this->request->getGet('tenure')) {

                $db = \Config\Database::connect();
                $plans = $db->table('plans')->where(['status' => 1, 'id' => $_GET['plan_id']])->get()->getResultArray()[0];
                $tenure = $db->table('plans_tenures')->where(['id' => $_GET['tenure']])->get()->getResultArray()[0];

                $this->data['plan'] = $plans;
                $this->data['tenure'] = $tenure;
                $this->data['logo'] = base_url('public/frontend/retro/img/site/logo.png');


                $razorpay = new Razorpay;
                $paystack = new Paystack;
                $stripe = new Stripe;

                $app_settings = get_settings('general_settings', true);
                $app_name = $app_settings['company_title'];
                $support_name = $app_settings['support_name'];
                $this->data['app_name'] = $app_name;
                $this->data['support_name'] = $support_name;
                $settings = get_settings('payment_gateways_settings', true);
                $this->data['stripe'] = false;
                $this->data['razorpay'] = false;
                $this->data['paystack'] = false;
                $this->data['bank'] = false;
                $this->data['paytm'] = false;
                $this->data['paypal'] = false;
                if ($settings['razorpayApiStatus'] == "enable") {
                    $this->data['razorpay'] = true;
                }
                $this->data['razorpay_key'] = $razorpay->get_credentials()['key'];
                $this->data['razorpay_currency'] = $razorpay->get_credentials()['currency'];
                if ($settings['paystack_status'] == "enable") {
                    $this->data['paystack'] = true;
                }
                $this->data['paystack_key'] = $paystack->get_credentials()['key'];
                $this->data['paystack_currency'] = $paystack->get_credentials()['currency'];
                if ($settings['stripe_status'] == "enable") {
                    $this->data['stripe'] = true;
                }
                $this->data['stripe_key'] = $stripe->get_credentials()['publishable_key'];

                // bank settings
                $settings = get_settings('payment_gateways_settings', true);
                if (isset($settings['bank_status']) && $settings['bank_status'] === "enable") {
                    $this->data['bank'] = true;
                    $this->data['bank_instruction'] = get_settings("payment_gateways_settings", true)["bank_instruction"];
                    $this->data['account_details'] = get_settings("payment_gateways_settings", true)["account_details"];
                    $this->data['extra_details'] = get_settings("payment_gateways_settings", true)["extra_details"];
                }

                // Paytm settings
                if (isset($settings['paytm_status']) && $settings['paytm_status'] == "enable") {
                    $this->data['paytm'] = true;
                    $this->data['paytm_mode'] = get_settings("payment_gateways_settings", true)['paytm_mode'];
                    $this->data['paytm_merchant_id'] = get_settings("payment_gateways_settings", true)["paytm_merchant_id"];
                }
                // Paytm ends


                // Paypal Starts 
                if (isset($settings['paypal_status']) && $settings['paypal_status'] == "enable") {
                    $this->data['paypal'] = true;
                    $this->data['paypal_mode'] = get_settings("payment_gateways_settings", true)['paypal_mode'];
                    $this->data['paypal_client_id'] = get_settings("payment_gateways_settings", true)["paypal_client_id"];
                }
                // Paypal ends here
                $currency = get_settings('general_settings', true);
                $currency = (isset($currency['currency'])) ? $currency['currency'] : '₹';
                $this->data['currency'] =  $currency;
                $this->data['title'] = 'Checkout';
                $this->data['main_page'] = 'checkout';

                return view('backend/user/template', $this->data);
            } else {
                $this->index();
            }
        } else {
            return redirect('unauthorised');
        }
    }
}
