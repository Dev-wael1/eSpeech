<?php

namespace App\Models;

use CodeIgniter\Model;

class Social_model extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'social_links';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['site_name', 'site_url', 'site_icon', 'site_html'];

    public function list($sort, $order, $limit, $offset, $search)
    {

        $multipleWhere = '';
        $db      = \Config\Database::connect();
        $builder = $db->table('social_links');

        $condition  = [];

        if (isset($search) and $search != '') {
            $multipleWhere = ['`id`' => $search, '`site_name`' => $search, '`site_url`' => $search, '`created_at`' => $search];
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

        $category_record = $builder->orderBy($sort, $order)->limit($limit, $offset)->get()->getResultArray();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();

        foreach ($category_record as $row) {

            $operations = '
            <button class="btn btn-success edit-link btn-sm"> <i class="fa fa-pen" aria-hidden="true"></i> </button>  
            <button class="btn btn-danger delete-link btn-sm"> <i class="fa fa-trash" aria-hidden="true"></i> </button>';

            $tempRow['id'] = $row['id'];
            $tempRow['site_name'] = $row['site_name'];
            $tempRow['site_url'] = $row['site_url'];
            $tempRow['site_html'] = $row['site_html'];
            $tempRow['site_icon'] = ($row['site_icon'] != '') ?
                '<a  href=" ' . base_url($row["site_icon"]) . ' " data-lightbox="image-1">
                    <img height="60px" class="rounded-circle" src=" ' . base_url($row["site_icon"]) . ' " alt="">
                </a>'
                :
                '<a  href=" ' . base_url("public/backend/assets/site_icon/default_view_image.png") . ' " data-lightbox="image-1">
                    <img height="60px" class="rounded-circle" src=" ' . base_url("public/backend/assets/site_icon/default_view_image.png") . ' " alt="">
                </a>';
            $tempRow['icon_for_edit'] = ($row['site_icon'] != '') ?
                base_url($row["site_icon"]) :
                base_url("public/backend/assets/site_icon/default_view_image.png");
            $tempRow['operations'] = $operations;





            $rows[] = $tempRow;
        }

        // else return json
        $bulkData['rows'] = $rows;
        return json_encode($bulkData);
    }
}
