<?php

namespace App\Controllers;
use App\Controllers\Frontend;

class Review extends Frontend
{
    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        $data['reviews'] =  fetch_details('reviews', ['status' => 1], [], null);

        // echo "<pre>";
        // print_r($data['reviews']);
        $data['logged'] = false;
        if($this->isLoggedIn ){
            $data['logged'] = true;
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
                $data['admin'] = true;
            } else {
                $data['admin'] = false;
            }
        $data['title'] = "Reviews &mdash; $this->appName ";
        $data['main_page'] = "reviews";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "All about $this->appName. $this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
		
        return view('frontend/'.config('Site')->theme.'/template',$data);
	}
}
