<?php

namespace App\Models;

use CodeIgniter\Model;

    class User extends Model
    {
        protected $DBGroup = 'default';
        protected $table = 'users';
        protected $primaryKey = 'id';

        protected $useAutoIncrement = true;

        protected $returnType     = 'array';
        protected $useSoftDeletes = true;

        protected $allowedFields = ['id', 'username','active', 'first_name', 'last_name'];

        protected $useTimestamps = true;
        protected $createdField  = 'created_on';
   
        
        public function get_records($select_field = '*', $where='')
        {
            $this->builder()->like("email", $where, 'before');
            $this->builder()->like("first_name", $where);
            $this->builder()->like("last_name", $where);
            $this->builder()->select($select_field);

            $data = [];

            foreach($this->builder()->get()->getResultArray() as $record)
            {
                $data[] = array("id" => $record['id'], "email" => $record['email']);
            }
            return$data;
        }    
    }
    
?>