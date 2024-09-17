<?php

namespace App\Controllers\user;

use App\Models\Subscription_model;
use App\Models\Bank_transfers;

class Subscriptions extends User
{
    public $id;
    public $transfer;
    public $validation;
    public function __construct()
    {
        parent::__construct();
        $this->db =  \Config\Database::connect();
        $this->transfer = new Bank_transfers();
        $this->validation =  \Config\Services::validation();
    }
    public function index()
    {
        if ($this->isLoggedIn) {
            $this->data['title'] = 'Subscriptions | espeech';
            $this->data['main_page'] = 'subscriptions';

            // this block here pass bank details to the subscription page
            $settings = get_settings('payment_gateways_settings', true);
            if (isset($settings['bank_status']) && $settings['bank_status'] === "enable") {
                $this->data['bank'] = true;
                $this->data['bank_instruction'] = get_settings("payment_gateways_settings", true)["bank_instruction"];
                $this->data['account_details'] = get_settings("payment_gateways_settings", true)["account_details"];
                $this->data['extra_details'] = get_settings("payment_gateways_settings", true)["extra_details"];
            }
            return view('backend/user/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function table()
    {
        if ($this->isLoggedIn) {
            $transactions = new Subscription_model;
            return print_r(json_encode($transactions->list_subscriptions()));
        } else {
            return redirect('unauthorised');
        }
    }
    public function upload_bank_reciepts()
    {
        $this->validation->setRules([
            ''
        ]);
        $id =      $this->request->getVar('id');
        $files = $this->request->getFiles();

        $subcription_table = fetch_details('subscriptions', ['id' => $id], ['user_id', 'status']);
        $user_id = $subcription_table[0]['user_id'];

        // print_r($subcription_table);
        if ($subcription_table[0]['status'] == 1) {
            $_SESSION['toastMessage'] = "Your subscription is already active for this plan, so no more receipts are allowed for this plan!!";
            $_SESSION['toastMessageType']  = 'error';
            $this->session->markAsFlashdata('toastMessage');
            $this->session->markAsFlashdata('toastMessageType');
            $response = [
                'error' => true,
                'message' => "Your subscription is already active for this plan, so no more receipts are allowed for this plan!",
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'data' => [],
            ];
            return $this->response->setJSON($response);
        }

        $bank_data = fetch_details('bank_transfers', ['subscription_id' => $id]);

        if (isset($bank_data[0]['status']) && $bank_data[0]['status'] > 0 && $bank_data[0]['status'] ==  1) {
            $_SESSION['toastMessage'] = "No more receipt are allowed your receipt is already accepted";
            $_SESSION['toastMessageType']  = 'error';
            $this->session->markAsFlashdata('toastMessage');
            $this->session->markAsFlashdata('toastMessageType');
            $response = [
                'error' => true,
                'message' => "No more receipt are allowed your receipt is already accepted",
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'data' => [],
            ];
            return $this->response->setJSON($response);
        }


        $path = 'public/uploads/images/reciept';
        foreach ($files['reciept'] as $rec) {
            if ($rec->isValid() && !$rec->hasMoved()) {
                $file_name =  $rec->getName();
                $rec->move($path, $file_name);
                $this->data = [
                    'subscription_id' => $id,
                    'user_id' => $user_id,
                    'attachments' => $file_name,
                    'status' => 0, /* 0:pending | 1:accepted | 2:rejected	 */
                ];
                $this->transfer->save($this->data);
                $_SESSION['toastMessage'] = "Your receipt has been Uploaded";
                $_SESSION['toastMessageType']  = 'success';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                $response = [
                    'error' => false,
                    'message' => "Your receipt has been Uploaded",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => [],
                ];
                return $this->response->setJSON($response);
            } else {
                $text = "An error occured during uploading process";
                $_SESSION['toastMessage'] = $text;
                $_SESSION['toastMessageType']  = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                $response = [
                    'error' => true,
                    'message' => $text,
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => [],
                ];
                return $this->response->setJSON($response);
            }
        }
        return redirect()->to('user')->withCookies();
    }
}
