<?php

namespace App\Models;

use CodeIgniter\Model;

class Settings extends Model
    {
        protected $DBGroup = 'default';
        protected $table = 'settings';
        protected $primaryKey = 'id';

        protected $useAutoIncrement = true;

        protected $returnType     = 'array';
        protected $useSoftDeletes = true;

        protected $allowedFields = ['variable', 'value'];

    }
?>