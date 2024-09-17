<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;

class Update_pg extends BaseController{
    public function update_payment_gateway()
    {
        return print_r($_POST);
    }
}