<?php

namespace App\Models;

use CodeIgniter\Model;

class Transactions extends Model
    {
        protected $DBGroup = 'default';
        protected $table = 'transactions';
        protected $primaryKey = 'id';

        protected $useAutoIncrement = true;

        protected $returnType     = 'array';
        protected $useSoftDeletes = true;

        protected $allowedFields = ['id', 'user_id','subscription_id','payment_method','transaction_id','amount','currency_code','status','message',];

        protected $useTimestamps = true;
        protected $createdField  = 'created_on';
        protected $updatedField  = 'last_modified';
 
    }
?>