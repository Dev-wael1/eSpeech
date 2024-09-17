<?php

namespace App\Controllers\user;

use App\Controllers\BaseController;
use App\Models\Transaction_model;

class Transactions extends User
{

	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		if ($this->isLoggedIn) {
			$this->data['title'] = 'Transactions | espeech';
			$this->data['main_page'] = 'transactions';
			return view('backend/user/template', $this->data);
		} else {
			return redirect('unauthorised');
		}
	}
	public function table()
	{
		$transactions = new Transaction_model;
		return print_r(json_encode($transactions->list_transactions()));
	}
}
