<?php

    namespace App\Models;

    use CodeIgniter\Model;

    class Subscription extends Model
    {
        protected $DBGroup = 'default';
        protected $table = 'subscriptions';
        protected $primaryKey = 'id';

        protected $useAutoIncrement = true;

        protected $returnType     = 'array';
        protected $useSoftDeletes = true;

        protected $allowedFields = ['id','plan_id','user_id','transaction_id','type','price','characters','google','aws','ibm','azure','remaining_characters','remaining_google','remaining_aws','remaining_azure','remaining_ibm','starts_from','tenure','expires_on','status'];

        protected $useTimestamps = true;
        protected $createdField  = 'created_on';
        protected $updatedField  = 'last_modified';
    }
?>