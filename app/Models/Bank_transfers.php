<?php
    namespace App\Models;
    use \Config\Database;
    use CodeIgniter\Model;

    class Bank_transfers extends Model
    {
        protected $DBGroup = 'default';
        protected $table = 'bank_transfers';
        protected $primaryKey = 'id';

        protected $useAutoIncrement = true;

        protected $returnType     = 'array';
        protected $useSoftDeletes = true;

        protected $allowedFields = ['subscription_id','user_id', 'attachments', 'status'];
        protected $useTimestamps = true;

        protected $createdField  = 'created_at';
        protected $updatedField  = 'updated_at';
        protected $deletedField  = 'deleted_at';
    }
