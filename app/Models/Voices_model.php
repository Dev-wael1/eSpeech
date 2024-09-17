<?php

namespace App\Models;

use CodeIgniter\Model;

class Voices_model extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'tts_voices';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $allowedFields = ['language', 'voice', 'display_name', 'type', 'gender', 'provider', 'status', 'icon'];


    public function list_voices()
    {
        $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();

        $builder = $db->table('tts_voices tv');
        // print_r();
        $offset = "0";
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];

        $limit = '10';
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        $sort = "tv.id";
        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "tv.id";
            } else {
                $sort = $_GET['sort'];
            }

        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                'tv.`id`' => $search,
                'tv.`language`' => $search,
                'tv.`voice`' => $search,
                'tv.`display_name`' => $search,
                'tv.`gender`' => $search,
                'tv.`provider`' => $search,
                'tv.`status`' => $search
            ];
        }

        if (isset($_GET['language']) and ($_GET['language'] != '')) {
            $language = $_GET['language'];
            $condition['tv.language'] = $language;
            $builder->where($condition);
        }

        if (isset($_GET['provider']) && $_GET['provider'] != '') {
            $provider = $_GET['provider'];
            $condition['tv.provider'] = $provider;
            $builder->where($condition);
        }

        $builder->select(' COUNT(tv.id) as `total` ');

        if (isset($_GET['language']) && $_GET['language'] != '') {
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


        if (isset($_GET['language']) and ($_GET['language'] != '')) {
            $language = $_GET['language'];
            $condition['tv.language'] = $language;
            $builder->where($condition);
        }

        if (isset($_GET['provider']) && $_GET['provider'] != '') {
            $provider = $_GET['provider'];
            $condition['tv.provider'] = $provider;
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
        $provider = '';
        // $rows = $tempRow = array();
        foreach ($tts_voices as $row) {
            // $row = output_escaping($row);

            // provider image set 
            if ($row['provider'] == "azure") {
                $provider =
                    '<img src="' . base_url('public/provider/azure.svg') . '" height="40px" class="rounded-circle" alt="azure">';
            } elseif ($row['provider'] == "aws") {
                $provider =
                    '<img src="' . base_url('public/provider/aws.svg') . '" height="40px" class="rounded-circle" alt="aws">';
            } elseif ($row['provider'] == "google") {
                $provider =
                    '<img src="' . base_url('public/provider/google.svg') . '" height="40px" class="rounded-circle" alt="google">';
            } elseif ($row['provider'] == "ibm") {
                $provider =
                    '<img src="' . base_url('public/provider/ibm.svg') . '" height="40px" class="rounded-circle" alt="ibm">';
            }

            //gender image set
            if ($row['gender'] == 'male') {
                $gender =
                    '<img src="' . base_url('public/provider/male.png') . '" height="40px" class="rounded-circle">';
            } elseif ($row['gender'] == 'female') {
                $gender =
                    '<img src="' . base_url('public/provider/female.png') . '" height="40px" class="rounded-circle">';
            } else {
                $gender = '-';
            }

            //icon image set
            if ($row['icon'] != "") {
                $icon = '<img src="' . base_url($row['icon']) . '" height="40px" class="rounded">';
            } else {
                $icon = "-";
            }

            $tempRow['id'] = $row['id'];
            $tempRow['language'] = $row['language'];
            $tempRow['voice'] = $row['voice'];
            $tempRow['display_name'] = $row['display_name'];
            $tempRow['provider'] = $provider . "&emsp;" . $row['provider'];
            $tempRow['type'] = $row['type'];
            $tempRow['status'] = $row['status'];
            $tempRow['status_text'] = ($row['status'] == 1) ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Deactive</span>";
            $tempRow['gender'] = $gender;
            $tempRow['gender_base'] = $row['gender'];
            $tempRow['icon'] = $icon;
            $edit = '<button class="btn btn-info edit btn-sm" title="edit" data-toggle="modal" data-target="#editModal"> <i class="fas fa-edit"> </i> </button>';
            $tempRow['operations'] = $edit;

            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }
}
