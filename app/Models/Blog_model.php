<?php

namespace App\Models;

use CodeIgniter\Model;

class Blog_model extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'blogs';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['title', 'description', 'image', 'status', 'slug', 'created_at', 'updated_at'];


    public function list_blogs()
    {
        $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();

        $builder = $db->table('blogs b');
        // print_r();
        $offset = "0";
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];

        $limit = '10';
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        $sort = "b.id";
        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "b.id";
            } else {
                $sort = $_GET['sort'];
            }

        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                'b.`id`' => $search,
                'b.`title`' => $search,
                'b.`description`' => $search,
                'b.`image`' => $search,
                'b.`status`' => $search,
            ];
        }

        $builder->select(' COUNT(b.id) as `total` ');

        if (isset($_GET['title']) && $_GET['title'] != '') {
            $builder->where($condition);
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $builder->orWhere($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $builder->where($where);
        }

        $blogs_count = $builder->get()->getResultArray();
        $total = $blogs_count[0]['total'];
        // print_r($total);



        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $builder->orLike($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $builder->where($where);
        }

        $blogs = $builder->orderBy($sort, "asc")->limit($limit, $offset)->get()->getResultArray();
        $bulkData = array();
        $bulkData['total'] = $total;

        foreach ($blogs as $row) {
            // print_r($row);
            $image = ($row['image'] != null) ?  '<img src="' . base_url($row['image']) . '" height="40px" class="rounded">' : "";

            $tempRow['id'] = $row['id'];
            $tempRow['title'] = $row['title'];
            $tempRow['description'] = $row['description'];
            $tempRow['image'] = $image;
            $tempRow['status'] = $row['status'];
            $tempRow['created_at'] = $row['created_at'];
            $tempRow['status_text'] = ($row['status'] == 1) ? "<span class='badge badge-success'>Published</span>" : "<span class='badge badge-danger'>Hide</span>";

            $edit = '<a id="blog_edit" class="btn btn-info blog_edit btn-sm" title="edit" href="'. base_url('admin/blogs/edit/'. $row['id']).'" data-id="' . $row['id'] . '"> <i class="fas fa-edit"> </i> </a>';
            $delete = '<button class="btn btn-danger delete btn-sm" title="delete"> <i class="fas fa-trash"> </i> </button>';
            $tempRow['operations'] = $edit  . "&emsp;" . $delete;

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }

}