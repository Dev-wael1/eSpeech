<?php

namespace App\Controllers;
use App\Controllers\Frontend;

class Blog extends Frontend
{
    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        $data['blogs'] =  fetch_details('blogs', [], [], null);
        $data['logged'] = false;
        if($this->isLoggedIn ){
            $data['logged'] = true;
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
                $data['admin'] = true;
            } else {
                $data['admin'] = false;
            }
        $data['title'] = "Blogs &mdash; $this->appName ";
        $data['main_page'] = "blog";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "All about $this->appName. $this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
		
        return view('frontend/'.config('Site')->theme.'/template',$data);
	}

    public function show($slug){
        $data['blogs'] =  fetch_details('blogs', ['slug'=>$slug], [], null);
        
        $data['logged'] = false;
        if($this->isLoggedIn ){
            $data['logged'] = true;
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
                $data['admin'] = true;
            } else {
                $data['admin'] = false;
            }
        $data['title'] = "Blogs &mdash; $this->appName ";
        $data['main_page'] = "blog_show";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "All about $this->appName. $this->appName is one of the leading voice synthesis service provider, that offers text to speech services for over 300+ languages and 80+ voices.";
		
        return view('frontend/'.config('Site')->theme.'/template',$data);
    }
}
