<?php

namespace App\Models;

use CodeIgniter\Model;

class Tenures extends Model
    {
        protected $DBGroup = 'default';
        protected $table = 'plans_tenures';
        protected $primaryKey = 'id';

        protected $useAutoIncrement = true;

        protected $returnType     = 'array';
        protected $useSoftDeletes = true;


        protected $allowedFields = ['plan_id', 'title','months','price','discounted_price'];

    }
?>