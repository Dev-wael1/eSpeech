<?php

namespace App\Controllers;
use App\Controllers\Frontend;

use App\Models\Plan;
use App\Models\Tenures;

class Pricing extends Frontend
{
    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        $plans_tenures_model = new \App\Models\Tenures();
        $plans_tenures_model->builder()->select();
        $data['tenure'] =  $plans_tenures_model->builder()->get()->getResultArray();
        $data['plans'] =  fetch_details('plans', [], [], null, "0", "row_order", "ASC");
        $data['logged'] = false;
        if($this->isLoggedIn ){
            $data['logged'] = true;
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
                $data['admin'] = true;
            } else {
                $data['admin'] = false;
            }
        $data['title'] = "Pricing &mdash; $this->appName ";
        $currency = get_settings('general_settings', true);
        $currency = (isset($currency['currency'])) ? $currency['currency'] : '₹';
        $data['currency'] =  $currency;
        $data['main_page'] = "price";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "All about $this->appName. $this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
		
        return view('frontend/'.config('Site')->theme.'/template',$data);
	}
}
