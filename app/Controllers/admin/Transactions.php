<?php

namespace App\Controllers\admin;

class Transactions extends Admin
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = new \App\Models\Transactions();;
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['title'] = 'Transactions | Admin Panel';
            $this->data['main_page'] = 'transactions';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function list_transactions()
    {
        $user_id = $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();

        /* building query for total count */
        $builder = $db->table("transactions t")->join("users u", "u.id = t.user_id", "left");
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $user_id = $_GET['user_id'];
            $condition['t.user_id'] = $user_id;
            $builder->where($condition);
        }

        if (isset($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']))
            $limit = $_GET['limit'];

        if (isset($_GET['sort']))
            if ($_GET['sort'] == 'id') {
                $sort = "t.id";
            } else {
                $sort = $_GET['sort'];
            }
        if (isset($_GET['order']))
            $order = $_GET['order'];

        if (isset($_GET['search']) and $_GET['search'] != '') {
            $search = $_GET['search'];
            $multipleWhere = [
                "u.first_name" => $search,
                "u.last_name" => $search,
                "u.email" => $search,
                "u.phone" => $search,
                "t.id" => $search,
                "t.payment_method" => $search,
                "t.txn_id" => $search,
                "t.amount" => $search,
                "t.status" => $search,
                "t.created_on" => $search,
            ];
        }

        $builder->select(' COUNT(t.id) as `total`');
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
        $builder = $db->table("transactions t")->join("users u", "u.id = t.user_id", "left")
            ->select("t.*, u.first_name, u.last_name, u.email, u.phone");
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

        $subscriptions = $builder->orderBy($sort, "desc")->limit($limit, $offset)->get()->getResultArray();
        $bulkData['total'] = $total;
        foreach ($subscriptions as $row) {

            $status = $row['status'];
            if ($status == 'pending') {
                $status = '<div class="badge badge-primary projects-badge">Pending</div>';
            }
            if ($status == 'captured') {
                $status = '<div class="badge badge-success projects-badge">Success</div>';
            }
            if ($status == 'failed') {
                $status = '<div class="badge badge-danger projects-badge">Failed</div>';
            }
            if ($status == 'Authorized') {
                $status = '<div class="badge badge-success projects-badge">Authorized</div>';
            }

            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['name'] = $row['first_name'] . " " . $row['last_name'];
            $tempRow['phone'] = $row['phone'];
            $tempRow['email'] = $row['email'];
            $tempRow['subscription_id'] = $row['subscription_id'];
            $tempRow['payment_method'] = $row['payment_method'];
            $tempRow['txn_id'] = $row['txn_id'];
            $tempRow['amount'] = $row['amount'];
            $tempRow['currency_code'] = $row['currency_code'];
            $tempRow['message'] = $row['message'];
            $tempRow['status'] = $status;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }
}
