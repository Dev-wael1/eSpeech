<?php

namespace App\Models;

use App\Controllers\BaseController;

class Subscription_model
{
    public $admin_id, $ionAuth;
    public function __construct()
    {
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->admin_id = ($this->ionAuth->isAdmin()) ? $this->ionAuth->user()->row()->id : 0;
    }
    public function list_subscriptions()
    {
        $user_id = $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();

        /* building query for total count */
        $builder = $db->table("subscriptions s")->join("users u", "u.id = s.user_id", "left");
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $user_id = $_GET['user_id'];
            $condition['s.user_id'] = $user_id;
            $builder->where($condition);
        }

        // $offset = 0;
        if (isset($_GET['offset']))
            $offset = $_GET['offset'];

        // $limit = 10;
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        // $sort = "s.id";
        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "s.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                's.`id`' => $search,
                's.`plan_title`' => $search,
                's.`user_id`' => $search,
                's.`type`' => $search,
                's.`price`' => $search,
                's.`characters`' => $search,
                's.`google`' => $search,
                's.`aws`' => $search,
                's.`ibm`' => $search,
                's.`azure`' => $search,
                's.`starts_from`' => $search,
                's.`tenure`' => $search,
                's.`expires_on`' => $search,
                's.`status`' => $search,
                'u.`email`' => $search,
                'u.`first_name`' => $search,
                'u.`last_name`' => $search,
                'u.`phone`' => $search,
            ];
        }

        if (isset($_GET['subscription_status']) and $_GET['subscription_status'] != '') {

            if ($_GET['subscription_status'] == 'active') {

                $builder->where('(s.starts_from <= "' . date("Y-m-d") . '") AND (s.expires_on >= "' . date("Y-m-d") . '") AND s.status = 1 ');
            }
            if ($_GET['subscription_status'] == 'expired') {

                $builder->where('((s.starts_from <= "' . date("Y-m-d") . '") AND (s.expires_on <= "' . date("Y-m-d") . '")) OR (s.status = 0)');
            }
        }

        if (isset($_GET['start_date']) and $_GET['start_date'] != '') {

            $builder->where('s.created_on BETWEEN "' . date('Y-m-d h:i:s', strtotime($_GET['start_date'])) . '" and "' . date('Y-m-d h:i:s', strtotime($_GET['end_date'])) . '"');
        }
        $builder->select(' COUNT(s.id) as `total`');
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
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
        /* Selecting actual data */
        $builder = $db->table("subscriptions s")
            ->join("users u", "u.id = s.user_id", "left")
            ->join("transactions t", "t.id = s.transaction_id", "left")
            ->select("
                    s.*, 
                    u.first_name, u.last_name, u.email, u.phone , u.image,
                    t.payment_method
                    ");
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
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

        if (isset($_GET['subscription_status']) and $_GET['subscription_status'] != '') {

            if ($_GET['subscription_status'] == 'active') {

                $builder->where('(s.starts_from <= "' . date("Y-m-d") . '") AND (s.expires_on >= "' . date("Y-m-d") . '") AND s.status = 1 ');
            }
            if ($_GET['subscription_status'] == 'expired') {

                $builder->where('((s.starts_from <= "' . date("Y-m-d") . '") AND (s.expires_on <= "' . date("Y-m-d") . '")) OR (s.status = 0)');
            }
            if ($_GET['subscription_status'] == 'pending') {

                $builder->where('(s.status = 2)');
            }
        }

        if (isset($_GET['date_filter_by']) and $_GET['date_filter_by'] != '') {
            if (isset($_GET['start_date']) and isset($_GET['start_date']) and ($_GET['end_date'] != '') and  ($_GET['end_date'] != '')) {
                $builder->where('s.' . $_GET['date_filter_by'] . ' BETWEEN "' . date('Y-m-d h:i:s', strtotime($_GET['start_date'])) . '" and "' . date('Y-m-d h:i:s', strtotime($_GET['end_date'])) . '"');
            }
        }
        $subscriptions = $builder->orderBy($sort, "desc")->limit($limit, $offset)->get()->getResultArray();

        $bulkData['total'] = $total;
        foreach ($subscriptions as $row) {
            // $row = output_escaping($row);
            $email =  ALLOW_MODIFICATION == 0  ? mask_email($row['email']) : $row['email'];

            // profile start 
            if ($row['image'] != '') {
                if (check_exists(base_url('public/backend/assets/profiles/' . $row['image']))) {
                    $profile = '<a  href="' . base_url('public/backend/assets/profiles/' . $row['image'])  . '" data-lightbox="image-1">
                    <img height="60px" class="rounded-circle" src="' . base_url("public/backend/assets/profiles/" . $row['image']) . '" alt=""></a>';
                } else {
                    $profile = '
                        <a href="#" id="pop">
                            <img id="profile_picture" src="' . base_url('public/backend/assets/profiles/default.png') . '" height="60px" class="rounded-circle">
                        </a>';
                }
            } else {
                $profile = '<a href="#" id="pop">
                        <img id="profile_picture" src="' . base_url('public/backend/assets/profiles/default.png') . '" height="60px" class="rounded-circle">
                    </a>';
            }
            if ($row['image'] == '') {
                $profile = '<a href="#" id="pop">
            <img id="profile_picture" src="' . base_url('public/backend/assets/profiles/default.png') . '" height="60px" class="rounded-circle">
            </a>';
            }

            $profile = '
            <li class="media p-2" >
            ' . $profile . '
                    <div class="media-body ml-2">
                    <div class="media-title mt-3">' .     $row['first_name'] . " " . $row['last_name'] . '</div>
                    <div class="text-job text-muted">' . $email . '</div>
                </div>
            </li>
            ';
            // profile end 
            $now = time();

            $starts_from = strtotime($row['starts_from']);
            $expiry_date = strtotime($row['expires_on']);
            $seconds = $expiry_date - $now;

            $available_status = ['expired', 'active', 'pending'];
            $status_color = ['danger', 'success', 'info'];

            // Various Actions on record

            $status = ($row['status'] == 1) ? (($starts_from > $now) ?
                '<div class="badge badge-warning projects-badge">Upcoming</div>' :
                '<div class="badge badge-success projects-badge">Active</div>') :
                '<div class="badge badge-' . $status_color[$row['status']] . ' projects-badge">' . $available_status[$row['status']] . '</div>';


            $upload_model_btn = '<br><a href="#" data-id="' . $row['id'] . '" class="btn-link recipt_upload" 
            data-toggle="modal" data-target="#reciept_modal" onclick="reciept_upload(this)" title="Upload Reciept"><i class="fa fa-upload" aria-hidden="true"></i></a> | <a href="#" data-id="' . $row['id'] . '" class="btn-link view-reciepts" data-toggle="modal" data-target="#reciept_list_modal" title="View Reciepts"><i class="fas fa-eye"></i></a>';


            // $action = '<button class="btn btn-primary" data-uid="' . $row['user_id'] . '" data-sid="' . $row['id'] . '" data-toggle="modal" data-target="#active_modal" onclick="active_sub(this)" title="Activate subscription"><i class="fa fa-check" aria-hidden="true"></i></button>';
            $action = '<button class="btn btn-primary active_subscription" data-uid="' . $row['user_id'] . '" data-sid="' . $row['id'] . '"><i class="fa fa-check" aria-hidden="true"></i></button>';


            $payment_method = ($row['payment_method'] != '') ? ucwords($row['payment_method']) : '';
            // $payment_method = $row['payment_method'];

            $payment_method .= ($payment_method == 'Bank Transfers') ? $upload_model_btn : "";
            $user_id =  $row['user_id'];
            $tempRow['id'] = $row['id'];
            $tempRow['profile'] = $profile;
            $tempRow['name'] = $row['first_name'] . " " . $row['last_name'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['email'] = $email;
            $tempRow['plan_title'] = $row['plan_title'];
            $tempRow['type'] = $row['type'];
            $tempRow['price'] = $row['price'];
            $tempRow['txn_id'] = $row['transaction_id'];
            // print_r($row['payment_method']);

            if ($this->admin_id > 0) {
                /* for admin */
                if ($row['payment_method'] == "bank_transfers" || $row['payment_method'] == "bank transfers" ||  $row['payment_method'] == "Bank Transfers") {
                    $tempRow['payment_method'] =  $row['payment_method'] . '<br> <a href="#" data-id="' . $row['id'] . '" class="btn-link view-reciepts" data-toggle="modal" data-target="#reciept_list_modal" title="View Reciept"><i class="fas fa-eye"></i></a>';
                } else {
                    $tempRow['payment_method'] = $row['payment_method'];
                }
            } else {
                /* for user */
                $tempRow['payment_method'] = $payment_method;
            }
            $tempRow['characters'] = $row['characters'];
            $tempRow['google'] = $row['google'];
            $tempRow['aws'] = $row['aws'];
            $tempRow['ibm'] = $row['ibm'];
            $tempRow['azure'] = $row['azure'];
            $tempRow['starts_from'] = $row['starts_from'];
            $tempRow['created_on'] = $row['created_on'];
            $tempRow['tenure'] = $row['tenure'] . "months";
            if ($row['tenure'] == 1) {
                $tempRow['tenure'] = $row['tenure'] . "month";
            }
            $tempRow['expires_on'] = $row['expires_on'];
            $tempRow['status'] = $status;

            if ($this->admin_id > 0) {
                /* for admin */
                if ($row['status'] == 2) {
                    if (active_plan($user_id)) {
                        $tempRow['active_subscription'] = "Subscription is active";
                    } else {
                        $tempRow['active_subscription'] = $action;
                    }
                } elseif ($row['status'] == 0) {
                    $tempRow['active_subscription'] = 'Subscription is expired!';
                } else if ($row['status'] == 1) {
                    $tempRow['active_subscription'] = "Subscription is active";
                }
            } else {
                /* for user */
                $tempRow['active_subscription'] = "Subscription is active";
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }

    public function upload_reciept()
    {
        $user_id = $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();

        /* Selecting actual data */
        $builder = $db->table("subscriptions s")
            ->join("users u", "u.id = s.user_id", "left")
            ->join("transactions t", "t.id = s.transaction_id", "left")
            ->select("
                    s.*, 
                    u.first_name, u.last_name, u.email, u.phone , u.image,
                    t.payment_method
                    ");

        if (isset($multipleWhere) && !empty($multipleWhere)) {
            $builder->groupStart();
            $builder->orLike($multipleWhere);
            $builder->groupEnd();
        }
        if (isset($where) && !empty($where)) {
            $builder->where($where);
        }

        $subscriptions = $builder->get()->getResultArray();

        foreach ($subscriptions as $row) {
            $email =  ALLOW_MODIFICATION == 0  ? mask_email($row['email']) : $row['email'];

            // profile start 
            if ($row['image'] != '') {
                if (check_exists(base_url('public/backend/assets/profiles/' . $row['image']))) {
                    $profile = '<a  href="' . base_url('public/backend/assets/profiles/' . $row['image'])  . '" data-lightbox="image-1">
                    <img height="60px" class="rounded-circle" src="' . base_url("public/backend/assets/profiles/" . $row['image']) . '" alt=""></a>';
                } else {
                    $profile = '
                        <a href="#" id="pop">
                            <img id="profile_picture" src="' . base_url('public/backend/assets/profiles/default.png') . '" height="60px" class="rounded-circle">
                        </a>';
                }
            } else {
                $profile = '<a href="#" id="pop">
                        <img id="profile_picture" src="' . base_url('public/backend/assets/profiles/default.png') . '" height="60px" class="rounded-circle">
                    </a>';
            }
            if ($row['image'] == '') {
                $profile = '<a href="#" id="pop">
            <img id="profile_picture" src="' . base_url('public/backend/assets/profiles/default.png') . '" height="60px" class="rounded-circle">
            </a>';
            }

            $profile = '
            <li class="media p-2" >
            ' . $profile . '
                    <div class="media-body ml-2">
                    <div class="media-title mt-3">' .     $row['first_name'] . " " . $row['last_name'] . '</div>
                    <div class="text-job text-muted">' . $email . '</div>
                </div>
            </li>
            ';
            // profile end 
            $now = time();

            $starts_from = strtotime($row['starts_from']);
            $expiry_date = strtotime($row['expires_on']);
            $seconds = $expiry_date - $now;

            $available_status = ['expired', 'active', 'pending'];
            $status_color = ['danger', 'success', 'info'];

            // Various Actions on record

            $status = ($row['status'] == 1) ? (($starts_from > $now) ? '<div class="badge badge-warning projects-badge">Upcoming</div>' : '<div class="badge badge-success projects-badge">Active</div>') : '<div class="badge badge-' . $status_color[$row['status']] . ' projects-badge">' . $available_status[$row['status']] . '</div>';

            $upload_model_btn = '<br><a href="#" data-id="' . $row['id'] . '" class="btn-link recipt_upload" 
            data-toggle="modal" data-target="#reciept_modal" onclick="reciept_upload(this)" title="Upload Reciept"><i class="fa fa-upload" aria-hidden="true"></i></a>';

            $initiate = '<button class="btn btn-primary" data-id="' . $row['user_id'] . '" data-toggle="modal" data-target="#active_modal" onclick="active_sub(this)"><i class="fa fa-check" aria-hidden="true"></i></button>';
            
            $payment_method = ($row['payment_method'] != '') ? ucwords($row['payment_method']) : '';
            $payment_method .= ($payment_method == 'Bank') ? $upload_model_btn : "";

            $tempRow['id'] = $row['id'];
            $tempRow['profile'] = $profile;
            $tempRow['name'] = $row['first_name'] . " " . $row['last_name'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['email'] = $email;
            $tempRow['plan_title'] = $row['plan_title'];
            $tempRow['type'] = $row['type'];
            $tempRow['price'] = $row['price'];
            if ($this->admin_id > 0) {
                /* for admin */

                $tempRow['payment_method'] =  $row['payment_method'] . '<br> <a href="#" data-id="' . $row['id'] . '" class="btn-link view-reciepts" data-toggle="modal" data-target="#reciept_list_modal" title="View Reciept"><i class="fas fa-eye"></i></a>';
            } else {
                /* for user */
                $tempRow['payment_method'] = $payment_method;
            }
            $tempRow['characters'] = $row['characters'];
            $tempRow['google'] = $row['google'];
            $tempRow['aws'] = $row['aws'];
            $tempRow['ibm'] = $row['ibm'];
            $tempRow['azure'] = $row['azure'];
            $tempRow['starts_from'] = $row['starts_from'];
            $tempRow['created_on'] = $row['created_on'];
            $tempRow['tenure'] = $row['tenure'] . "months";
            if ($row['tenure'] == 1) {
                $tempRow['tenure'] = $row['tenure'] . "month";
            }
            $tempRow['expires_on'] = $row['expires_on'];
            $tempRow['status'] = $status;
            if ($this->admin_id > 0) {
                /* for admin */
                if ($row['status'] == 2) {
                    if (active_plan($user_id)) {
                        $tempRow['active_subscription'] = "Subsciption is active";
                    } else {

                        $tempRow['active_subscription'] = $initiate;
                    }
                } elseif ($row['status'] == 0) {
                    $tempRow['active_subscription'] = 'Subscription is expired!';
                } else if ($row['status'] == 1) {
                    $tempRow['active_subscription'] = "Subsciption is active";
                }
            } else {
                /* for user */
                $tempRow['active_subscription'] = "Subsciption is active";
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }
}
