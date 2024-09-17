<?php

namespace App\Models;

use App\Controllers\BaseController;



class Bank_transfers_model
{
    public $base;
    public $admin_id;
    public function __construct()
    {
        $this->base = new BaseController;

        $ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->admin_id = ($ionAuth->isAdmin()) ? $ionAuth->user()->row()->id : 0;
    }
    public function list_transfers()
    {

        $user_id = $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();

        $sortable_fields = ['price' => 's.price', 'id' => 'b.id', 'plan_title' => 's.plan_title', 'name' => 'u.first_name', 'plan_type' => 's.type', 'created_at' => 'b.created_at'];
        // for total count
        $builder = $db->table('bank_transfers b')->join("users u", "u.id = b.user_id", "left");
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $user_id = $_GET['user_id'];
            $condition['b.user_id'] = $user_id;
            $builder->where($condition);
        }
        if (isset($_GET['subscription_id']) && $_GET['subscription_id'] != '') {
            $subscription_id = $_GET['subscription_id'];
            $condition['b.subscription_id'] = $subscription_id;
            $builder->where($condition);
        }

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];

        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            $sort = (isset($sortable_fields[$sort])) ? $sortable_fields[$sort] : "b.id";
        }
        if (isset($_GET['order']))
            $order = $_GET['order'];


        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                "b.subscription_id" => $search,
                "b.status" => $search,
                "b.created_at" => $search
            ];
        }
        if (isset($_GET['date_of_upload'])) {

            $builder->where('b.created_at');
        }

        $builder->select(' COUNT(b.id) as `total`');
        if (isset($_GET['start_date']) and isset($_GET['start_date']) and ($_GET['end_date'] != '') and  ($_GET['end_date'] != '')) {
            $builder->where('((b.created_at >= "' . $_GET['start_date'] . ' 12:00:00") AND (b.created_at <= "' . $_GET['end_date'] . ' 12:00:00"))');
        }

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

        /** actual data */
        $builder = $db->table('bank_transfers b')
            ->join("subscriptions s", "s.id = b.subscription_id", "left")
            ->join("users u", "u.id = s.user_id", "left")
            ->join("users_groups g", "g.user_id = u.id", "left")
            ->join("transactions t", "t.user_id = u.id", "left")

            ->select(
                '
                b.*,
                s.type,s.plan_title,s.price,s.status as substatus,
                t.txn_id,
                u.first_name,u.last_name,
                g.group_id,u.email
                '
            )->groupBy('b.id');

        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $builder->where($condition);
        }
        if (isset($_GET['subscription_id']) && $_GET['subscription_id'] != '') {
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

        if (isset($_GET['date_filter_by']) and $_GET['date_filter_by'] != '') {
            if (isset($_GET['start_date']) and isset($_GET['start_date']) and ($_GET['end_date'] != '') and  ($_GET['end_date'] != '')) {
                $builder->where('s.' . $_GET['date_filter_by'] . ' BETWEEN "' . date('Y-m-d h:i:s', strtotime($_GET['start_date'])) . '" and "' . date('Y-m-d h:i:s', strtotime($_GET['end_date'])) . '"');
            }
        }

        $transfers = $builder->orderBy($sort, $order)->limit($limit, $offset)->get()->getResultArray();
        $bulkData['total'] = $total;
        // data sent from here

        foreach ($transfers as $row) {
            // $row = output_escaping($row);
            $email =  ALLOW_MODIFICATION == 0  ? mask_email($row['email']) : $row['email'];

            if ($row['attachments'] != '') {
                if (check_exists(base_url('public/uploads/images/reciept/' . $row['attachments']))) {
                    $receipts_img = '<a  href="' . base_url('public/uploads/images/reciept/' . $row['attachments'])  . '" data-lightbox="image-1"> 
                    <img height="60px" 
                    class="rounded" 
                    src="' . base_url("public/uploads/images/reciept/" . $row['attachments']) . '" 
                    alt="this is where images are supposed to be">
                    </a>';
                } else {
                    $receipts_img = "you've not uploaded any images just yet";
                }
            } else {
                $receipts_img = "you've not uploaded any images just yet";
            }


            $created = date($row['created_at']);

            $initiate = '<button class="btn btn-primary" data-id="' . $row['user_id'] . '" data-toggle="modal" data-target="#active_modal" onclick="active_sub(this)">
            <i class="fa fa-check" aria-hidden="true"></i></button>';

            $ramodal = '<button class="btn btn-primary" data-id="' . $row['id'] . '" data-uid="' . $row['user_id'] . '" data-toggle="modal" data-target="#receipt_check_modal" onclick="receipt_check(this)">
                        <i class="fa-solid fa-upload"></i>
                        </button>';

            // admin check
            if ($this->admin_id == $row['group_id']) {
                $active = '<button type="submit" class="btn btn-primary"> this is </button>';
            }

            $u_id = $row['user_id'];

            // getting status
            $subscription_details = fetch_details('subscriptions', ['user_id' => $u_id], ['status']);
            $operations = '<button class="btn btn-danger btn-sm delete"> <i class="fas fa-trash"></i> </button>';

            if($row['status'] == 0){
                $status = '<span class="badge badge-info">Reciept is Pending</span>';
            }elseif ($row['status'] == 1) {
                $status = '<span class="badge badge-success">Reciept is Accepted</span>';
            }elseif ($row['status'] == 2) {
                $status = '<span class="badge badge-danger">Reciept is Rejected</span>';
            }


            $tempRow['id'] = $row['id'];
            // user Detailes passing from here
            $tempRow['name'] = $row['first_name'] . "&emsp;" . $row['last_name'];
            $tempRow['email'] = $email;

            // subscription details
            $tempRow['plan_type'] = $row['type'];
            $tempRow['plan_title'] = $row['plan_title'];
            $tempRow['price'] = $row['price'];
            $tempRow['transaction_id'] = $row['txn_id'];
            $tempRow['status'] = $status;
            // banlk details
            $tempRow['created_at'] = $created;
            
            //to dispolay receipts
            $tempRow['attachments_img'] = $receipts_img;
            if ($this->admin_id > 0) {
                $tempRow['operations'] = $operations;
                if ($row['status'] == 1) {
                    $tempRow['receipt_check'] = '<span class="badge badge-success">Reciept is accepted</span>';
                } elseif ($row['status'] == 2) {
                    $tempRow['receipt_check'] = '<span class="badge badge-danger">Reciept is rejected</span>';
                } else {
                    $tempRow['receipt_check'] = $ramodal;
                }
            }
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }

    public function bank_transfer_list()
    {
        $user_id = $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();

        $sortable_fields = ['price' => 's.price', 'id' => 'b.id', 'plan_title' => 's.plan_title', 'name' => 'u.first_name', 'plan_type' => 's.type', 'created_at' => 'b.created_at'];
        // for total count
        $builder = $db->table('bank_transfers b')->join("users u", "u.id = b.user_id", "left");
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $user_id = $_GET['user_id'];
            $condition['b.user_id'] = $user_id;
            $builder->where($condition);
        }

        if (isset($_GET['subscription_id']) && $_GET['subscription_id'] != '') {
            $subscription_id = $_GET['subscription_id'];
            $condition['b.subscription_id'] = $subscription_id;
            $builder->where($condition);
        }

        $limit = 0;

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];

        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            $sort = (isset($sortable_fields[$sort])) ? $sortable_fields[$sort] : "b.id";
        }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                "b.subscription_id" => $search,
                "b.status" => $search,
                "b.created_at" => $search
            ];
        }

        if (isset($_GET['date_of_upload'])) {
            $builder->where('b.created_at');
        }

        $builder->select(' COUNT(b.id) as `total`');

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                "b.subscription_id" => $search,
                "b.status" => $search,
                "b.created_at" => $search
            ];
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

        /** actual data */
        $builder = $db->table('bank_transfers b')
            ->join("subscriptions s", "s.id = b.subscription_id", "left")
            ->join("users u", "u.id = s.user_id", "left")
            ->join("users_groups g", "g.user_id = u.id", "left")
            ->join("transactions t", "t.user_id = u.id", "left")

            ->select(
                '
                b.*,
                s.type,s.plan_title,s.price,s.status, s.plan_id,
                t.txn_id,
                u.first_name,u.last_name,
                g.group_id,u.email
                '
            )->groupBy('b.id');

        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $builder->where($condition);
        }
        if (isset($_GET['subscription_id']) && $_GET['subscription_id'] != '') {
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

        if (isset($_GET['date_filter_by']) and $_GET['date_filter_by'] != '') {
            if (isset($_GET['start_date']) and isset($_GET['start_date']) and ($_GET['end_date'] != '') and  ($_GET['end_date'] != '')) {
                $builder->where('s.' . $_GET['date_filter_by'] . ' BETWEEN "' . date('Y-m-d h:i:s', strtotime($_GET['start_date'])) . '" and "' . date('Y-m-d h:i:s', strtotime($_GET['end_date'])) . '"');
            }
        }

        $transfers = $builder->orderBy($sort, $order)->limit($limit, $offset)->get()->getResultArray();
        $bulkData['total'] = $total;
        // data sent from here

        foreach ($transfers as $row) {
            $row = output_escaping($row);
            $email =  ALLOW_MODIFICATION == 0  ? mask_email($row['email']) : $row['email'];
            $receipts_img = $relative_path = "";

            if ($row['attachments'] != '') {
                if (check_exists(base_url('public/uploads/images/reciept/' . $row['attachments']))) {
                    $receipts_img = base_url('public/uploads/images/reciept/' . $row['attachments']);
                    $relative_path = "public/uploads/images/reciept/" . $row['attachments'];
                }
            }

            $created = date($row['created_at']);

            $initiate = '<button class="btn btn-primary" data-id="' . $row['user_id'] . '" data-toggle="modal" data-target="#active_modal" onclick="active_sub(this)"><i class="fa fa-check" aria-hidden="true"></i></button>';

            $ramodal = '<button class="btn btn-primary" data-id="' . $row['id'] . '" data-uid="' . $row['user_id'] . '" data-toggle="modal" data-target="#receipt_check_modal" onclick="receipt_check(this)">
                        <i class="fa-solid fa-upload"></i>
                        </button>';

            // admin check
            if ($this->admin_id == $row['group_id']) {
                $active = '<button type="submit" class="btn btn-primary"> this is </button>';
            }

            $u_id = $row['user_id'];

            // getting status
            $subscription_details = fetch_details('subscriptions', ['user_id' => $u_id], ['status']);
            $status_subscription = $subscription_details[0]['status'];

            $tempRow['id'] = $row['id'];
            // user Detailes passing from here
            $tempRow['subscription_id'] = $row['subscription_id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['name'] = $row['first_name'] . "&emsp;" . $row['last_name'];
            $tempRow['email'] = $email;

            // subscription details
            $tempRow['plan_id'] = $row['plan_id'];
            $tempRow['plan_type'] = $row['type'];
            $tempRow['plan_title'] = $row['plan_title'];
            $tempRow['price'] = $row['price'];
            $tempRow['transaction_id'] = $row['txn_id'];
            $tempRow['status_1'] = $row['status'];
            //to dispolay receipts
            $tempRow['attachment'] = $receipts_img;
            $tempRow['attachment_relative_path'] = $relative_path;

            if ($this->admin_id > 0) {
                if ($row['status'] == 1) {
                    $tempRow['receipt_check'] = '<span class="badge badge-success">Reciept is accepted</span>';
                } elseif ($row['status'] == 2) {
                    $tempRow['receipt_check'] = '<span class="badge badge-danger">Reciept is rejected</span>';
                } else {
                    $tempRow['receipt_check'] = $ramodal;
                }
            }

            // banlk details
            $tempRow['created_at'] = $created;
            $rows[] = $tempRow;
        }
        $bulkData['message'] = (!empty($rows)) ? "Bank transfer records retrieved successfully" : "No Bank Transfer records found!";
        $bulkData['rows'] = $rows;
        return $bulkData;
    }
}
