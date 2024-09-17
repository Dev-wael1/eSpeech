<?php

namespace App\Models;

use CodeIgniter\Model;

class Tts_model extends Model
{
    public function list_tts()
    {
        $user_id = '';
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $user_id = $_GET['user_id'];
            $condition = ['user_id' => $user_id, 'is_saved' => '1'];
        }

        $db      = \Config\Database::connect();
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $builder = $db->table("users_tts ut")->where($condition);
        } else {
            $builder = $db->table("users_tts ut");
        }
        $multipleWhere = '';

        $offset = "0";
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];

        $limit = '10';
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        $sort = "ut.id";
        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "ut.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = ['ut.`id`' => $search, 'ut.`language`' => $search, 'ut.`voice`' => $search, 'ut.`provider`' => $search, 'ut.`title`' => $search, 'ut.`text`' => $search];
        }
        $builder->select(' COUNT(ut.id) as `total` ');

        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $builder->select(' COUNT(ut.id) as `total` ')->where($condition);
        }

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $builder->orWhere($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $builder->where($where);
        }
        $tts_count = $builder->get()->getResultArray();
        $total = $tts_count[0]['total'];
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $builder->select()->where($condition);
        } else {
            $builder->select();
        }
        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $builder->orLike($multipleWhere);
        }
        if (isset($where) && !empty($where)) {
            $builder->where($where);
        }

        $users_tts = $builder->orderBy($sort, "desc")->limit($limit, $offset)->get()->getResultArray();
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        foreach ($users_tts as $row) {
            $row = output_escaping($row);

            $email = $db->table('users')->select('username')->where(['id' => $row['user_id']])->get()->getResultArray()[0]['username'];
            $email =  ALLOW_MODIFICATION == 0  ? mask_email($email) : $email;
            if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
                if ($row['is_saved'] == '1' && $row['user_id'] == $_GET['user_id']) {
                    if (!isset($row['title']) || trim($row['title']) == '') {
                        $slug =  $row['provider'] . '_' . $row['id'] . '_' . $row['user_id'];
                    } else {
                        $slug = slugify($row['title']);
                    }
                    $operate = '<a href="data:audio/mp3;base64,' . $row['base_64'] . '" download="' . $slug . '.mp3" class="view_btn btn btn-warning btn-xs mr-1 mb-1"  title="view"><i class="fas fa-arrow-down"></i></i></a>';
                    $operate .= '<button id="play_button' . $row['id'] . '" class="action_button edit_btn btn btn-success btn-xs mr-1 mb-1" title="Play" data-id="' . $row['id'] . '" onclick="playAudio(' . $row['id'] . ')" onload="playAudio(' . $row['id'] . ')"><i class="fas fa-play" id="play_saved' . $row['id'] . '"></i><i class="fas fa-pause" id="pause_saved' . $row['id'] . '" style="display: none;"></i></button>';
                    $operate .= '<button onclick="delete_tts(' . $row['id'] . ')" class="btn btn-danger btn-xs mr-1 mb-1" id="delete-promo-code" title="Delete" data-id="' . $row['id'] . '" ><i class="fa fa-trash"></i></button>';
                    $operate .= '<audio id="play' . $row['id'] . '" src="data:audio/mpeg;base64,' . $row['base_64'] . '"></audio>';
                    $delete = '<button class="btn btn-danger delete"> <i class="fas fa-trash"> </i> </button>';
                    $tempRow['id'] = $row['id'];

                    $tempRow['language'] = $row['language'];
                    $tempRow['voice'] = $row['voice'];
                    $tempRow['user_id'] = $row['user_id'];
                    $tempRow['provider'] = $row['provider'];
                    $tempRow['title'] = (!isset($row['title']) || trim($row['title']) == '') ? "" : $row['title'];
                    $tempRow['used_characters'] = $row['used_characters'];
                    $tempRow['is_ssml'] = $row['is_ssml'];
                    $tempRow['created_on'] = $row['created_on'];
                    $tempRow['text'] = $row['text'];
                    $tempRow['operate'] = $operate;
                    $tempRow['delete_tts'] = $delete;

                    if (isset($row['title'])) {

                        $tempRow['title'] = $row['title'];
                    } else {
                        $tempRow['title'] = "Title was not added";
                    }
                    $rows[] = $tempRow;
                }
            } else {
                $delete = '<button class="btn btn-danger btn-sm delete"> <i class="fas fa-trash"> </i> </button>';

                $tempRow['id'] = $row['id'];
                $tempRow['identity'] = $email;
                $tempRow['user_id'] = $row['user_id'];
                $tempRow['language'] = $row['language'];
                $tempRow['voice'] = $row['voice'];
                $tempRow['provider'] = $row['provider'];
                $tempRow['delete_tts'] = $delete;

                if (isset($row['title'])) {

                    $tempRow['title'] = $row['title'];
                } else {
                    $tempRow['title'] = "Title was not added";
                }
                $tempRow['used_characters'] = $row['used_characters'];
                $tempRow['is_ssml'] = $row['is_ssml'];
                $tempRow['created_on'] = $row['created_on'];
                $tempRow['text'] = $row['text'];
                $rows[] = $tempRow;
            }
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }
    public function delete_tts($id)
    {
        if (update_details(['is_saved' => 0, 'base_64' => ''], ['id' => $id], 'users_tts')) {
            return true;
        }
        return false;
    }
    public function insert_tts($set)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('users_tts');
        $id = $set['user_id'];
        $identity = fetch_details('users', ['id' => $id])[0]['username'];
        $set['identity'] = $identity;
        $builder = $db->table('users_tts');
        $builder->insert($set);
        return $db->insertID();
    }
    public function save_predefined($tts_id, $base64)
    {
        $db      = \Config\Database::connect();
        $users_tts = $db->table('users_tts')->where(['id' => $tts_id])->get()->getResultArray();
        $provider = $users_tts[0]['provider'];
        $language = $users_tts[0]['language'];
        $voice = $users_tts[0]['voice'];
        $text = $users_tts[0]['text'];
        $status = 1;
        $used_characters = strlen($text);
        if (exists(['provider' => $provider, 'voice' => $voice], 'predefined_tts')) {
            $builder = $db->table('predefined_tts');
            $builder->update([
                'provider' => $provider,
                'language' => $language,
                'voice' => $voice,
                'text' => $text,
                'base_64' => $base64,
                'used_characters' => $used_characters,
                'status' => $status,
            ], ['provider' => $provider, 'voice' => $voice]);
            if ($builder) {
                return true;
            } else {
                return false;
            }
        }
        $builder = $db->table('predefined_tts');
        $builder->insert([
            'provider' => $provider,
            'language' => $language,
            'voice' => $voice,
            'text' => $text,
            'base_64' => $base64,
            'used_characters' => $used_characters,
            'status' => $status,
        ]);
        if ($builder) {
            return true;
        } else {
            return false;
        }
    }
    public function get_predefined($voice, $language)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('predefined_tts');
        return $builder->where(['voice' => $voice, 'language' => $language])->select('base_64')->get()->getResultArray();
    }
}
