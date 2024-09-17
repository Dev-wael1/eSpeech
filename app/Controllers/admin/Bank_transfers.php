<?php

namespace App\Controllers\admin;

use App\Models\Bank_transfers_model;

class Bank_transfers extends Admin
{
    public $bank_model;
    public $validation;
    public function __construct()
    {
        parent::__construct();
        $this->validation = \Config\Services::validation();
        $this->bank_model = new Bank_transfers_model;
    }

    public function index()
    {
        if ($this->isLoggedIn) {
            $this->data['title'] = 'Bank Transfer | espeech';
            $this->data['main_page'] = 'bank_transfers';

            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function table()
    {
        if ($this->isLoggedIn  && $this->userIsAdmin) {
            $transactions = $this->bank_model;
            return print_r(json_encode($this->bank_model->list_transfers()));
        } else {
            // return redirect('unauthorised');
        }
    }

    public function activate_subscription()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            $email = \config\Services::email();

            $user_id = $this->request->getVar('user_id');
            $subscription_id = $this->request->getVar('subscription_id');
            // print_r($subscription_id);
            $starts_from = '';
            if ($starts_from == '') {
                $starts_from = date('Y-m-d');
            }


            // subscrption details
            $sub_dels  = fetch_details('subscriptions', ['user_id' => $user_id], ['plan_title', 'plan_id', 'tenure', 'transaction_id', 'price']);
            $plan_id = $sub_dels[0]['plan_id'];
            $transaction_id = $sub_dels[0]['transaction_id'];
            // print_r($transaction_id);
            $tenure = $sub_dels[0]['tenure'];

            // plan tenures details  and expiry date things
            // Getting User dettails
            $user = fetch_details('users', ['id' => $user_id]);
            $active_plan_id = active_plan($user_id);
            if ($active_plan_id) {
                $previous = fetch_details('subscriptions', ['id' => $active_plan_id], ['status', 'expires_on', 'starts_from']);

                $starts_from = $previous[0]['expires_on'];
                $expiry_date = new \DateTime($starts_from);
                $expiry_date = $expiry_date->modify('+' . $tenure . ' months')->format('Y-m-d');
            } else {
                $expiry_date = new \DateTime($starts_from);
                $expiry_date = $expiry_date->modify('+' . $tenure . ' months')->format('Y-m-d');
            }

            // checking the reciepts 
            $reciept_image = fetch_details(' bank_transfers', ['subscription_id' => $subscription_id], ['attachments']);
            $reciept_status = fetch_details(' bank_transfers', ['subscription_id' => $subscription_id], ['status']);
            if (!$reciept_image) {
                $response = [
                    'error' => true,
                    'message' => "Reciepts aren't uploaded yet!. Make sure to inform customer",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            }
            // print_r($reciept_status[0]['status']);
            // die();
            if ($reciept_status[0]['status'] == 0) {
                $response = [
                    'error' => true,
                    'message' => "Reciepts haven't been reviwed just yet please review it first and then try again later on",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            }

            if ($reciept_status[0]['status'] == 2) {
                $response = [
                    'error' => true,
                    'message' => "Reciepts have been rejected ",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                // print_r($response);
                // die();
                return $this->response->setJSON($response);
            }


            // this will set date in accordence with active plan 
            $trans_update =  update_details(
                [
                    'status' => 'active',
                ],
                ['subscription_id' => $subscription_id],
                'transactions'
            );
            $update_data = update_details([
                'starts_from' => $starts_from,
                'status' => 1,
                'expires_on' => $expiry_date,
                'last_modified' => $starts_from
            ], [
                'id' => $subscription_id
            ], 'subscriptions');
            if (!$trans_update) {
                $response = [
                    'error' => true,
                    'message' => "Transaction could not be updated due to some errors. Try again later!",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            }
            if (!$update_data) {
                $response = [
                    'error' => true,
                    'message' => "Subscription could not be updated due to some errors. Try again later!",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            }
            //setting Email Data 

            $extra_data = [
                'amount' => $sub_dels[0]['price'],
                'transaction_id' => $transaction_id,
                'start_date' => $starts_from,
                'month' => $tenure,
                'expiry_date' => $expiry_date
            ];
            $result =  send_mail_with_template('activate_subscription', $user, '', '', $extra_data);
            if ($result['error'] == false) {
                $_SESSION['toastMessage'] = "Users plan has been approved";
                $_SESSION['toastMessageType']  = 'success';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                $response = [
                    'error' => false,
                    'message' => "Users plan has been approved and mail has been sent",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            }
        } else {
            return redirect('unauthorised');
        }
    }

    public function update_receipt()
    {
        if ($this->isLoggedIn  && $this->userIsAdmin) {


            $this->validation->setRules(
                [
                    'reason' => 'required',
                ],
                [
                    'reason' => [
                        'required' => 'reason is required',
                    ],
                ]
            );

            if (!$this->validation->withRequest($this->request)->run()) {
                $errors = $this->validation->getErrors();

                $response['error'] = 'true';
                foreach ($errors as $e) {
                    $response['message'] = $e;
                }
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['data'] = $_POST;
                return $this->response->setJSON($response);
            }

            $bank_transfer_id = $this->request->getVar('id');
            $reason = $this->request->getVar('reason');
            $status =  $this->request->getVar('status');
            $user_id = $this->request->getVar('user_id');

            $bank_transfer_receipt = fetch_details("bank_transfers", ['id' => $bank_transfer_id]);

            if (empty($bank_transfer_receipt)) {
                $response = [
                    'error' => true,
                    'message' => "Invalid Bank Transfer Receipt.",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            }
            if ($bank_transfer_receipt[0]['status'] == 1 || $bank_transfer_receipt[0]['status'] == 2) {
                $response = [
                    'error' => true,
                    'message' => "Bank Transfer receipt is already " . ($bank_transfer_receipt[0]['status'] == 1) ? "accepted." : "rejected.",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            }
            $subscription = fetch_details("subscriptions", ['id' => $bank_transfer_receipt[0]['subscription_id']]);
            if (empty($subscription)) {
                $response = [
                    'error' => true,
                    'message' => "No subscription for this receipt.",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            }
            // if ( $subscription[0]['status'] == 1) {
            //     $response = [
            //         'error' => true,
            //         'message' => "Subscription is already accepted.",
            //         'csrfName' => csrf_token(),
            //         'csrfHash' => csrf_hash(),
            //         'data' => $_POST
            //     ];
            //     return $this->response->setJSON($response);
            // }

            $receipt_update = update_details(
                [
                    'status' => $status,
                    'message' => $reason,
                ],
                ['subscription_id' => $subscription[0]['id']],
                'bank_transfers'
            );
            if ($receipt_update) {
                $user_details = fetch_details('users', ['id' => $user_id]);

                $extra_data = [
                    'amount' => $subscription[0]['price'],
                    'transaction_id' => $subscription[0]['transaction_id'],
                    'message' => $reason,
                ];
                $result = '';
                if ($status == '1') {
                    $result = send_mail_with_template('receipt_accepted', $user_details, "", "", $extra_data);

                    $_SESSION['toastMessage'] = 'Receipt accepted';
                    $_SESSION['toastMessageType']  = 'success';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');

                    $response = [
                        'error' => false,
                        'message' => 'Receipt accepted',
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => []
                    ];
                    return $this->response->setJSON($response);
                } else {
                    $result = send_mail_with_template('receipt_rejected', $user_details, "", "", $extra_data);

                    $_SESSION['toastMessage'] = 'Receipt rejected';
                    $_SESSION['toastMessageType']  = 'success';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');

                    $response = [
                        'error' => false,
                        'message' => 'Receipt rejected',
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => []
                    ];
                    return $this->response->setJSON($response);
                }


                // $email = email_sender($user_email_id, $subject, $text);
                // if ($email) {
                //     $message = "email has been sent";
                //     $_SESSION['toastMessage'] = $message;
                //     $_SESSION['toastMessageType']  = 'success';
                //     $this->session->markAsFlashdata('toastMessage');
                //     $this->session->markAsFlashdata('toastMessageType');

                //     $response = [
                //         'error' => false,
                //         'message' => $message,
                //         'csrfName' => csrf_token(),
                //         'csrfHash' => csrf_hash(),
                //         'data' => []
                //     ];
                //     return $this->response->setJSON($response);
                // } else {
                //     $message = "there may have been some error";
                //     $_SESSION['toastMessage'] = $message;
                //     $_SESSION['toastMessageType']  = 'danger';
                //     $this->session->markAsFlashdata('toastMessage');
                //     $this->session->markAsFlashdata('toastMessageType');

                //     $response = [
                //         'error' => true,
                //         'message' => $message,
                //         'csrfName' => csrf_token(),
                //         'csrfHash' => csrf_hash(),
                //         'data' => []
                //     ];
                //     return $this->response->setJSON($response);
                // }

                // $message = "Receipt updated successfully";
                // $_SESSION['toastMessage'] = $message;
                // $_SESSION['toastMessageType']  = 'success';
                // $this->session->markAsFlashdata('toastMessage');
                // $this->session->markAsFlashdata('toastMessageType');

                // $response = [
                //     'error' => false,
                //     'message' => $message,
                //     'csrfName' => csrf_token(),
                //     'csrfHash' => csrf_hash(),
                //     'data' => []
                // ];
                // return $this->response->setJSON($response);
            } else {
                $msg = "there may have some error occurred";
                $_SESSION['toastMessage'] = $msg;
                $_SESSION['toastMessageType']  = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');

                $response = [
                    'error' => true,
                    'message' => $msg,
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            }
        }
    }

    public function delete_transaction()
    {
        if ($this->isLoggedIn  && $this->userIsAdmin) {
            $id = $this->request->getPost('id');
            $db      = \Config\Database::connect();
            $builder = $db->table('bank_transfers')->delete(['id' => $id]);
            if ($builder) {
                $response = [
                    'error' => false,
                    'message' => "Deleted Bank Transactions.",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $_POST
                ];
                return $this->response->setJSON($response);
            } else {
                $response = [
                    'error' => true,
                    'message' => "Something went wrong.",
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => $builder
                ];
                return $this->response->setJSON($response);
            }
        } else {
            return redirect('unauthorised');
        }
    }
}
