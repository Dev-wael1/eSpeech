<?php

namespace App\Models;

use CodeIgniter\Model;

class Review_model extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'reviews';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['subject', 'user_id', 'user_name', 'user_mail', 'user_image', 'rating_number', 'review', 'status', 'created_at', 'updated_at'];


    public function list_reviews()
    {
        $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();

        $builder = $db->table('reviews r');
        // print_r();
        $offset = "0";
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];

        $limit = '10';
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        $sort = "r.id";
        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "r.id";
            } else {
                $sort = $_GET['sort'];
            }

        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                'r.`id`' => $search,
                'r.`user_id`' => $search,
                'r.`user_name`' => $search,
                'r.`subject`' => $search,
                'r.`review`' => $search,
                'r.`status`' => $search,
                'r.`rating_number`' => $search,

            ];
        }

        $builder->select(' COUNT(r.id) as `total` ');

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

        $reviews = $builder->orderBy($sort, "asc")->limit($limit, $offset)->get()->getResultArray();
        $bulkData = array();
        $bulkData['total'] = $total;

        foreach ($reviews as $row) {
            // print_r($row);

            $status = ($row['status'] == 1) ?
                '<button class="btn btn-danger btn-sm show_review"  title="Hide Review" data-uid="' . $row['id'] . '">
            <i class="fa-solid fa-ban"></i></button>' :
                '<button class="btn btn-success btn-sm hide_review"  title="Show Review" data-uid="' . $row['id'] . '">
            <i class="fa-solid fa-check"></i></button>';


            $image = ($row['user_image'] != null) ?  '<img src="' . base_url($row['user_image']) . '" height="40px" class="rounded">' : '<img src="' . base_url("public/backend/assets/profiles/default.png") . '" height="40px" class="rounded">';


            $tempRow['id'] = $row['id'];
            $tempRow['user_name'] = $row['user_name'];
            $tempRow['user_image'] = $image;
            $tempRow['subject'] = $row['subject'];
            $tempRow['status'] = $row['status'];
            $tempRow['review'] = $row['review'];
            // $tempRow['rating_number'] = $star;
            $tempRow['rating_number'] = $row['rating_number'];
            $tempRow['status_text'] = ($row['status'] == 1) ? "<span class='badge badge-success'>Show</span>" : "<span class='badge badge-danger'>Hide</span>";


            $delete = '<button class="btn btn-danger delete btn-sm" title="delete"> <i class="fas fa-trash"> </i> </button>';
            $tempRow['operations'] = $status  . "&emsp;" . $delete;

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }
}
