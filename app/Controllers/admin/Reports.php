<?php
	namespace App\Controllers\admin;

	class Reports extends Admin
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function usage()
		{
			if($this->isLoggedIn && $this->userIsAdmin)
			{
				$this->data['title'] = 'Reports | Admin Panel';
				$this->data['main_page'] = 'reports';
				return view('backend/admin/template', $this->data);
			}
			else
			{
				return redirect('unauthorised');
			}
		}
	}
?>