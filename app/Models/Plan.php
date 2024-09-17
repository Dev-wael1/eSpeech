<?php

namespace App\Models;

use \Config\Database;
use CodeIgniter\Model;

class Plan extends Model
    {
        protected $DBGroup = 'default';
        protected $table = 'plans';
        protected $primaryKey = 'id';

        protected $useAutoIncrement = true;

        protected $returnType     = 'array';
        protected $useSoftDeletes = true;

        protected $allowedFields = ['title', 'type','google','aws','ibm','azure','no_of_characters','status' , 'featured_text' , 'is_featured' ,'lottie'];

        protected $useTimestamps = true;
        protected $createdField  = 'created_on';
        protected $updatedField  = 'last_modified';
    }
?>