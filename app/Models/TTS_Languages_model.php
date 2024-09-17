<?php

namespace App\Models;

use CodeIgniter\Model;

class TTS_Languages_model extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'tts_languages';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['language_code', 'language_name', 'flag', 'status'];

    public function list_languages()
    {
        $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();

        $builder = $db->table('tts_languages tl');
        // print_r();
        $offset = "0";
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];

        $limit = '10';
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        $sort = "tl.id";
        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "tl.id";
            } else {
                $sort = $_GET['sort'];
            }

        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                'tl.`id`' => $search,
                'tl.`language_code`' => $search,
                'tl.`language_name`' => $search,
                'tl.`status`' => $search
            ];
        }

        if (isset($_GET['language_code']) and ($_GET['language_code'] != '')) {
            $language = $_GET['language_code'];
            $condition['tl.language_code'] = $language;
            $builder->where($condition);
        }

        $builder->select(' COUNT(tl.id) as `total` ');

        if (isset($_GET['language_code']) && $_GET['language_code'] != '') {
            $builder->where($condition);
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $builder->groupStart();
            $builder->orLike($multipleWhere);
            $builder->groupEnd();
        }
        
        if (isset($where) && !empty($where)) {
            $builder->where($where);
        }

        $tts_count = $builder->get()->getResultArray();
        $total = $tts_count[0]['total'];
        // print_r($total);


        if (isset($_GET['language_code']) and ($_GET['language_code'] != '')) {
            $language = $_GET['language_code'];
            $condition['tl.language_code'] = $language;
            $builder->where($condition);
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $builder->orLike($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $builder->where($where);
        }

        $tts_voices = $builder->orderBy($sort, "asc")->limit($limit, $offset)->get()->getResultArray();
        $bulkData = array();
        $bulkData['total'] = $total;

        // $rows = $tempRow = array();
        foreach ($tts_voices as $row) {

            //flag image set
            if ($row['flag'] != "") {
                $icon = '<img src="' . base_url($row['flag']) . '" height="40px" width="50px" class="rounded">';
            } else {
                $icon = "-";
            }

            $tempRow['id'] = $row['id'];
            $tempRow['language_code'] = $row['language_code'];
            $tempRow['language_name'] = $row['language_name'];
            $tempRow['language_name_flag'] = $icon .  "&emsp;" . $row['language_name'];
            $tempRow['status'] = $row['status'];
            $tempRow['status_text'] = ($row['status'] == 1) ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Deactive</span>";
            $edit = '<button class="btn btn-info edit_tts_language btn-sm" title="edit" data-toggle="modal" data-target="#edit_lan_Modal"> <i class="fas fa-edit"> </i> </button>';
            $tempRow['operations'] = $edit;

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }
}
