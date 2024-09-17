<?php

namespace App\Controllers\user;

use App\Models\Bank_transfers_model;


class Bank_transfers extends User
{
    public $bank_model;
    public function __construct()
    {
        parent::__construct();
        $this->bank_model = new Bank_transfers_model;
    }

    public function index()
    {
        if ($this->isLoggedIn) {
            $this->data['title'] = 'Bank Transfer | espeech';
            $this->data['main_page'] = 'bank_transfers';

            return view('backend/user/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function table()
    {
        if ($this->isLoggedIn && !$this->userIsAdmin) {
            $transactions = $this->bank_model;
            $_GET['user_id'] = $this->userId;
            return print_r(json_encode($this->bank_model->list_transfers()));
        } else {
            return redirect('unauthorised');
        }
    }
}
