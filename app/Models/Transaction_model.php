<?php

namespace App\Models;


class Transaction_model
{
    public function list_transactions()
    {
        $user_id = $multipleWhere = '';
        $condition = $bulkData = $rows = $tempRow = [];
        $db      = \Config\Database::connect();
        
        /* building query for total count */
        $builder = $db->table("transactions t")->join("users u", "u.id = t.user_id", "left");
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $user_id = $_GET['user_id'];
            $condition = ['t.user_id' => $user_id];
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
        if (isset($_GET['start_date']) and isset($_GET['start_date']) and ($_GET['end_date'] != '') and  ($_GET['end_date'] != '')) {
            $builder->where('((t.created_on >= "' . $_GET['start_date'] . ' 12:00:00") AND (t.created_on <= "' .$_GET['end_date']. ' 12:00:00"))');            
        }
        if (isset($_GET['txn_provider']) and $_GET['txn_provider'] != '') {
            $builder->where('(t.payment_method = "'.$_GET['txn_provider'].'")');
        }
        if (isset($_GET['transaction_status']) and $_GET['transaction_status'] != '') {
            if($_GET['transaction_status'] == "success"){
                
                $builder->where('((t.status = "success") OR (t.status = "authorized") OR (t.status = "captured"))');
            }
            if($_GET['transaction_status'] == "failed"){
                
                $builder->where('(t.status = "failed")');
            }
            if($_GET['transaction_status'] == "pending"){
                $builder->where('(t.status = "pending")');
            }
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

        /* Selecting actual data */
        $builder = $db->table("transactions t")
                    ->join("users u", "u.id = t.user_id", "left")
                    ->select("t.*, u.first_name, u.last_name, u.email, u.phone , u.image");
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
        if (isset($_GET['txn_provider']) and $_GET['txn_provider'] != '') {
            $builder->where('(t.payment_method = "'.$_GET['txn_provider'].'")');
        }
        if (isset($_GET['start_date']) and isset($_GET['start_date']) and ($_GET['end_date'] != '') and  ($_GET['end_date'] != '')) {
            $builder->where('((t.created_on >= "' . $_GET['start_date'] . ' 12:00:00") AND (t.created_on <= "' .$_GET['end_date']. ' 12:00:00"))');            
        }
        if (isset($_GET['transaction_status']) and $_GET['transaction_status'] != '') {
            if($_GET['transaction_status'] == "success"){
                
                $builder->where('((t.status = "success") OR (t.status = "authorized") OR (t.status = "captured"))');
            }
            if($_GET['transaction_status'] == "failed"){
                
                $builder->where('(t.status = "failed")');
            }
            if($_GET['transaction_status'] == "pending"){
                $builder->where('(t.status = "pending")');
            }
        }

        $subscriptions = $builder->orderBy($sort, "desc")->limit($limit, $offset)->get()->getResultArray();
       
        $bulkData['total'] = $total;
        foreach ($subscriptions as $row) {

            $email =  ALLOW_MODIFICATION == 0  ? mask_email($row['email']) : $row['email'];



            // profile start 
            
            if ($row['image'] != '') {
                if (check_exists(base_url('public/backend/assets/profiles/' . $row['image']))) {
                    $profile = '<a  href="' . base_url('public/backend/assets/profiles/' . $row['image'])  . '" data-lightbox="image-1"><img height="60px" class="rounded-circle" src="' . base_url("public/backend/assets/profiles/" . $row['image']) . '" alt=""></a>';
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
            if($row['image'] == ''){
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
            if ($status == 'authorized') {
                $status = '<div class="badge badge-success projects-badge">Authorized</div>';
            }
            if ($status == 'success') {
                $status = '<div class="badge badge-success projects-badge">Success</div>';
            }
            $plan_title = '';
            $plan_title = fetch_details('subscriptions',['id'=> $row['subscription_id']],['plan_title']);
            if(!empty($plan_title)){
                $plan_title = $plan_title[0]['plan_title']; 
            }
    
            $tempRow['id'] = $row['id'];
            $tempRow['user_id'] = $row['user_id'];
            $tempRow['profile'] = $profile;
            $tempRow['name'] = $row['first_name'] . " " . $row['last_name'];
            $tempRow['phone'] = $row['phone'];
            $tempRow['email'] = $email;
            $tempRow['plan_title'] = $plan_title;
            $tempRow['subscription_id'] = $row['subscription_id'];
            $tempRow['payment_method'] = $row['payment_method'];
            $tempRow['txn_id'] = $row['txn_id'];
            $tempRow['amount'] = $row['amount'];
            $tempRow['currency_code'] = $row['currency_code'];
          
            $tempRow['status'] = $status;
            $tempRow['created_on'] = $row['created_on'];
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return $bulkData;
    }
}
