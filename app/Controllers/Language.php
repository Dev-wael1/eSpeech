<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Language extends BaseController
{
    public function index($lang)
    {
        $session = session();
        $session->remove('lang');
        $session->set('lang', $lang);
        $url = base_url();
        return redirect()->back();
    }
}
