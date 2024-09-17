<?php

namespace App\Models;

use CodeIgniter\Model;

class Mail_template extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'email_template';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['email_type', 'email_subject', 'email_text', 'status'];


    public function template_list($sort, $order, $limit, $offset, $search, $addition_data = [])
    {

        $multipleWhere = '';
        $db      = \Config\Database::connect();
        $builder = $db->table('email_template');

        $condition  = [];

        if (isset($search) and $search != '') {
            $multipleWhere = ['`id`' => $search, '`email_type`' => $search, '`email_subject`' => $search, '`email_text`' => $search, '`created_at`' => $search];
        }


        if (isset($_GET['id']) && $_GET['id'] != '') {
            $builder->where($condition);
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $builder->orWhere($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $builder->where($where);
        }
        if (isset($addition_data) && !empty($addition_data)) {
            $builder->where($addition_data);
        }

        $count = $builder->select(' COUNT(id) as `total` ')->get()->getResultArray();
        // echo $db->lastQuery;

        $total = $count[0]['total'];

        $builder->select();
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $builder->orLike($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $builder->where($where);
        }
        if (isset($addition_data) && !empty($addition_data)) {
            $builder->where($addition_data);
        }

        $category_record = $builder->orderBy($sort, $order)->limit($limit, $offset)->get()->getResultArray();
        // print_r($db->getLastQuery());
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($category_record as $row) {

            $operations = '  
            <button class="btn btn-danger delete-template btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> </button>';

            $tempRow['id'] = $row['id'];
            $tempRow['email_type'] = $row['email_type'];
            $tempRow['email_subject'] = $row['email_subject'];
            $tempRow['email_text'] = json_decode(strip_tags($row['email_text']));
            $tempRow['email_text_for_edit'] = json_decode($row['email_text']);
            $tempRow['status'] = $row['status'];
            $tempRow['operations'] = $operations;





            $rows[] = $tempRow;
        }

        // else return json
        $bulkData['rows'] = $rows;
        return json_encode($bulkData);
    }
}
