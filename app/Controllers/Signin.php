<?php

namespace App\Controllers;
use App\Controllers\Frontend;


class Home extends Frontend
{
    public function __construct()
    {
        parent::__construct();
    }
	public function index()
	{
		
		if ($this->isLoggedIn && $this->userIsAdmin) {
			$data['admin'] = true;
		} else {
			$data['admin'] = false;
		}
		$data['title'] = "Login &mdash; $this->appName ";
		$data['main_page'] = "view_signin";
		$data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
		$data['meta_description'] = "All about $this->appName. $this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
	}
}
