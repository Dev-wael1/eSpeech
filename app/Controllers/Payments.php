<?php

namespace App\Controllers;

use App\Libraries\Razorpay;
use App\Libraries\Stripe;
use App\Models\Tenures;
use App\Libraries\Paytm;
use App\Libraries\Paypal_lib;

class Payments extends BaseController
{
    private $paytm, $paypal;
    function __construct()
    {
        $this->paytm = new Paytm;
        $this->paypal = new Paypal_lib;
    }
    public function pre_payment_setup()
    {

        if (isset($_POST['user_id']) && $_POST['user_id'] != "") {
            if (has_upcoming($_POST['user_id'])) {
                $response['error'] = true;
                $response['message'] = "user already have a upcoming plan.";
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                return $this->response->setJSON($response);
            }
        }
        $razorpay = new Razorpay;

        if ($this->isLoggedIn) {
            if ($_POST['payment_method'] == "Razorpay") {
                $amount = $_POST['amount'];
                $order = $razorpay->create_order(($amount * 100));

                if (!isset($order['error'])) {
                    $response['order_id'] = $order['id'];
                    $response['error'] = false;
                    $response['message'] = "Client Secret Get Successfully.";
                    $response['csrfName'] = csrf_token();
                    $response['csrfHash'] = csrf_hash();
                    return $this->response->setJSON($response);
                } else {
                    $response['error'] = true;
                    $response['message'] = $order['error']['description'];
                    $response['details'] = $order;
                    $response['csrfName'] = csrf_token();
                    $response['csrfHash'] = csrf_hash();
                    return $this->response->setJSON($response);
                }
            } elseif ($_POST['payment_method'] == "stripe") {
                $amount = $_POST['amount'];
                $stripe = new Stripe;
                $payload = [
                    'amount' => ($amount * 100),
                    'metadata' => [
                        'user_id' => $_POST['user_id'],
                        'amount' => $amount,
                        'plan_id' => $_POST['plan_id'],
                        'tenure' => $_POST['tenure_id']
                    ]
                ];

                $order = $stripe->create_payment_intent($payload);
                $response['client_secret'] = $order['client_secret'];
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                $response['id'] = $order['id'];
                return $this->response->setJSON($response);
            } elseif ($_POST['payment_method'] == "paystack") {

                $response['error'] = false;
                $response['message'] = "";
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                return $this->response->setJSON($response);
            } elseif ($_POST['payment_method'] == "paypal") {
                $response['error'] = false;
                $response['message'] = "";
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();
                return $this->response->setJSON($response);
            } elseif ($_POST['payment_method'] == "bank") {
                //  bank transfers starts$
                $response['csrfName'] = csrf_token();
                $response['csrfHash'] = csrf_hash();

                $method = 'bank transfers';
                $message = "order placed successfully";
                $db = \Config\Database::connect();
                $tenure_id = $this->request->getPost('tenure_id');
                $plan_id = $this->request->getPost('plan_id');
                $tenure = $db->table('plans_tenures')->where(['id' => $tenure_id, 'plan_id' => $plan_id])->get()->getResultArray()[0];
                $price = $tenure['price'] - $tenure['discounted_price'];
                $id = $this->ionAuth->user()->row()->id;
                $txn_id = "bank-transfer-" . time() . '-' . rand(100, 999) . '-' . $id;
                $is_bank = true;

                $insert_id = add_transaction($txn_id, $price, $method, $id, 'pending', '-', $message);
                if ($sub_id = add_subscription($id, $plan_id, $tenure['months'], $insert_id, $price, "", false, $is_bank)) {
                    $response['message'] = $message;
                    $response['error'] = false;
                    $response['plan'] = $plan_id;
                    update_details(
                        ['subscription_id' => $sub_id],
                        ['id' => $insert_id],
                        'transactions'
                    );
                    return $this->response->setJSON($response);
                } else {
                    $response['error'] = true;
                    $response['message'] = "failed";
                    return $this->response->setJSON($response);
                }
                //  bank transfers ends 
            } elseif ($_POST['payment_method'] == "paytm") {

                $support_name = $_POST['app_name'];
                $amount = $_POST['amount'];
                $user_id = $_POST['user_id'];
                $order_id =  trim($support_name) . "-" . time() . rand(1000, 9999);

                $paytmParams = array();

                $paytmParams["body"] = array(
                    "requestType"   => "Payment",
                    "websiteName"   => "WEBSTAGING",
                    "orderId"       => $order_id,
                    "txnAmount"     => array(
                        "value"     => $amount,
                        "currency"  => "INR",
                    ),
                    "callbackUrl"   => base_url('payment/paytm_response'),
                    "userInfo"      => array(
                        "custId"    => $user_id,
                    ),
                );
                $res = $this->paytm->get_credentials();
                $mid = $res['paytm_merchant_id'];
                $paramList['MID'] = $mid;
                $paramList['ORDER_ID'] = $order_id;
                $paramList["CUST_ID"] = $user_id;
                $paramList["INDUSTRY_TYPE_ID"]  = $res['paytm_industry_type_id'];
                $paramList["CHANNEL_ID"] = "WEB";
                $paramList["TXN_AMOUNT"] =  $amount;
                $paramList["WEBSITE"] = $res['paytm_website'];
                $paramList["CALLBACK_URL"] = base_url("payment/paytm-response");
                $checksum = $this->paytm->generateSignature($paramList, $res['paytm_merchant_key']);

                $form_html = "<body>
        <table align='center' cellspacing='4'>
            <tr>
                <td align='center'><STRONG>Transaction is being processed,</STRONG></td>
            </tr>
            <tr>
                <td align='center'>
                    <font color='blue'>Please wait ...</font>
                </td>
            </tr>
            <tr>
                <td align='center'>(Please do not press 'Refresh' or 'Back' button)</td>
            </tr>
            <tr>
                <td align='center'><img src=" . base_url('assets/old-pre-loader.gif') . " alt='Please wait.. Loading' title='Please wait.. Loading..' width='140px' /></td>
            </tr>
            <tr>
                <td align='center'><a href='#' style='padding: 8px 12px;background-color: #008CBA;color:white;text-decoration:none;' onclick='document.forms[\"payment_form\"].submit();'>Click here if you are not automatically redirected..</a></td>
            </tr>
            
        </table>
        <FORM NAME='payment_form' ACTION='https://securegw-stage.paytm.in/theia/processTransaction' METHOD='POST'>
            <input type='hidden' name='MID' value='" . $res['paytm_merchant_id'] . "'>
            <input type='hidden' name='WEBSITE' value='" . $res['paytm_website'] . "'>
            <input type='hidden' name='ORDER_ID' value='" . $order_id . "'>
            <input type='hidden' name='CUST_ID' value='" . $user_id . "'>
            <input type='hidden' name='INDUSTRY_TYPE_ID' value='" . $res['paytm_industry_type_id'] . "'>
            <input type='hidden' name='CHANNEL_ID' value='WEB'>
            <input type='hidden' name='TXN_AMOUNT' value='" . $amount . "'>
            <input type='hidden' name='CALLBACK_URL' value='" . $paramList['CALLBACK_URL'] . "'>
            <input type='hidden' name='CHECKSUMHASH' value='" . $checksum . "'>
           
        </FORM>
    </body>
    <script type='text/javascript'>
        document.forms[0].submit();
    </script>";
                $trans_init = $this->paytm->initiate_transaction($paytmParams);
                if ($trans_init) {
                    $response['error'] = false;
                    $response['message'] = 'trasaction initiated successfully';
                    $response['csrfName'] = csrf_token();
                    $response['csrfHash'] = csrf_hash();
                    $response['data'] = $trans_init;
                    $response['data']['order_id'] = $order_id;
                    return $this->response->setJSON($response);
                } else {
                    $response['error'] = true;
                    $response['message'] = "transaction wasn't successfull";
                    $response['csrfName'] = csrf_token();
                    $response['csrfHash'] = csrf_hash();
                    $response['data'] = $trans_init;
                    $response['data']['order_id'] = $order_id;
                    return $this->response->setJSON($response);
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Something went wrong during transaction";
                return $this->response->setJSON($response);
            }
        }
    }
    public function post_payment()
    {
        if ($provider = $this->request->getPost('provider')) {
            $txn_id = $this->request->getPost('txn_id');
            $db = \Config\Database::connect();
            $tenure_id = $this->request->getPost('tenure_id');
            $plan_id = $this->request->getPost('plan_id');
            $tenure = $db->table('plans_tenures')->where(['id' => $tenure_id, 'plan_id' => $plan_id])->get()->getResultArray()[0];
            $price = $tenure['price'] - $tenure['discounted_price'];
            $id = $this->ionAuth->user()->row()->id;
            $user_data = fetch_details('users', ['id' => $id], ['email', 'username']);
            $user_email = $user_data[0]['email'];
            $user_name = $user_data[0]['username'];
            // $insert_id ='';

            $insert_id = add_transaction($txn_id, $price, $provider, $id);
            if ($provider == 'razorpay') {
                $razorpay = verify_payment_transaction($txn_id, 'razorpay', $insert_id);
                if ($razorpay['error']) {
                    $response['error'] = true;
                    $response['message'] = "Invalid Razorpay Payment Transaction.";
                    $response['data'] = [];
                    update_details([
                        'message' => $response['message'],
                        'status' => $razorpay['status'],
                        'amount' => $price
                    ], [
                        'id' => $insert_id
                    ], 'transactions');
                    return $this->response->setJSON($response);
                } elseif ($razorpay['amount'] >= $price) {

                    if ($sub_id = add_subscription($id, $plan_id, $tenure['months'], $txn_id, $price)) {
                        $response['error'] = false;
                        $response['message'] = "Order Placed Successfully";
                        $response['data'] = $razorpay;
                        $response['plan'] = $plan_id;
                        update_details(
                            [
                                'message' => $response['message'],
                                'status' => $razorpay['status'],
                                'subscription_id' =>  $sub_id,
                                'amount' => $price
                            ],
                            [
                                'id' => $insert_id
                            ],
                            'transactions'
                        );
                        update_details(
                            [

                                'transaction_id' => $insert_id,
                            ],
                            [
                                'id' => $sub_id,

                            ],
                            'subscriptions'
                        );
                        return $this->response->setJSON($response);
                    }
                    send_mail_with_template('subscription',  $user_data);
                    $response['error'] = true;
                    $response['message'] = "something went wrong";
                    $response['data'] = $razorpay;

                    return $this->response->setJSON($response);
                }
            } elseif ($provider == 'paystack') {
                $transfer = verify_payment_transaction($txn_id, 'paystack');
                if (isset($transfer['data']['status']) && $transfer['data']['status']) {
                    if (isset($transfer['data']['data']['status']) && $transfer['data']['data']['status'] != "success") {
                        $response['error'] = true;
                        $response['message'] = "Invalid Paystack Transaction.";
                        $response['data'] = array();
                        send_mail_with_template('subscription',  $user_data);
                        update_details(
                            [
                                'message' => $response['message'],
                                'status' => 'failed',
                                'amount' => $price
                            ],
                            [
                                'id' => $insert_id
                            ],
                            'transactions'
                        );

                        return $this->response->setJSON($response);
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Error While Fetching the Order Details.Contact Admin ASAP.";
                    $response['data'] = $transfer;

                    return $this->response->setJSON($response);
                }
                if ($transfer['amount'] >= $price) {
                    $id = $this->ionAuth->user()->row()->id;
                    if ($sub_id = add_subscription($id, $plan_id, $tenure['months'], $txn_id, $price)) {
                        $response['error'] = false;
                        $response['message'] = "Order Placed Successfully";
                        $response['data'] = $transfer;
                        $response['plan'] = $plan_id;
                        update_details(
                            [
                                'message' => $response['message'],
                                'status' => $transfer['status'],
                                'subscription_id' =>  $sub_id,
                                'amount' => $price
                            ],
                            [
                                'id' => $insert_id
                            ],
                            'transactions'
                        );
                        update_details(
                            [

                                'transaction_id' => $insert_id,
                            ],
                            [
                                'id' => $sub_id,

                            ],
                            'subscriptions'
                        );
                        return $this->response->setJSON($response);
                    }
                    $response['error'] = true;
                    $response['message'] = "something went wrong";
                    $response['data'] = $transfer;
                    update_details(
                        [
                            'message' => $response['message'],
                            'status' => 'failed',
                            'amount' => $price
                        ],
                        [
                            'id' => $insert_id
                        ],
                        'transactions'
                    );

                    return $this->response->setJSON($response);
                }
            } elseif ($provider == 'paytm') {
                $payment = verify_payment_transaction($txn_id, 'paytm');
                $status = ($payment['data']['body']['resultInfo']['resultStatus'] == "TXN_SUCCESS") ? "Success" : "Pending";

                $subject = "Regarding your subscription activation";
                $message = "hello $user_name we're delighted to know that yo've chosen our system for voice synthesize, your payment was successfull and your subscription is now active. thank you for your time.";

                if ($status == "Success") {
                    if ($payment['data']['body']['txnAmount'] >= $price) {
                        if ($sub_id = add_subscription($id, $plan_id, $tenure['months'], $txn_id, $price)) {
                            $txn_id = $_POST['txn_id'];
                            $response['error'] = false;
                            $response['message'] = "Order Placed Successfully";
                            $response['data'] = $this->paytm;
                            $response['plan'] = $plan_id;
                            update_details(
                                [
                                    'message' => $response['message'],
                                    'status' => $status,
                                    'subscription_id' =>  $sub_id,
                                    'amount' => $price
                                ],
                                [
                                    'id' => $insert_id
                                ],
                                'transactions'
                            );
                            update_details(
                                [

                                    'transaction_id' => $insert_id,
                                ],
                                [
                                    'id' => $sub_id,

                                ],
                                'subscriptions'
                            );
                            send_mail_with_template('subscription',  $user_data);

                            return $this->response->setJSON($response);
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Something went wrong";
                        $response['data'] = '';

                        return $this->response->setJSON($response);
                    }
                } else if ($status == "Pending") {
                    $response = [
                        'error' => true,
                        'message' => "Your transaction is currently pending ",
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => [
                            'error' => true,
                        ],
                    ];
                    return $this->response->setJSON($response);
                } else {
                    $response = [
                        'error' => true,
                        'message' => "Your transaction may have failed due to some reason please try again later on",
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'data' => [
                            'error' => true,
                        ],
                    ];
                    return $this->response->setJSON($response);
                }
            } elseif ($provider == "paypal") {

                $capture_id = $this->request->getPost('capture_id');


                $transfer = verify_payment_transaction($capture_id, $provider);
                // print_r($transfer);
                // die();  
                if ($transfer['data']['status'] == "COMPLETED" &&  $transfer['amount'] >= $price) {
                    if ($sub_id = add_subscription($id, $plan_id, $tenure['months'], $txn_id, $price)) {
                        $response['error'] = false;
                        $response['message'] = "Order Placed Successfully";
                        $response['data'] = $transfer;
                        $response['plan'] = $plan_id;
                        $data = [
                            'error' => 'false',
                            'message' => 'payment completed successfully',
                            'data' => [
                                'error' => false,
                                'data' => $transfer
                            ],
                        ];
                        update_details(
                            [
                                'message' => $response['message'],
                                'status' => $transfer['data']['status'],
                                'subscription_id' =>  $sub_id,
                                'amount' => $price
                            ],
                            [
                                'id' => $insert_id
                            ],
                            'transactions'
                        );
                        update_details(
                            [

                                'transaction_id' => $insert_id,
                            ],
                            [
                                'id' => $sub_id,

                            ],
                            'subscriptions'
                        );
                        send_mail_with_template('subscription',  $user_data);
                        return $this->response->setJSON($data);
                    }

                    // return $this->response->setJSON($data);
                } elseif ($transfer['data']['status'] == "PENDING" &&  $transfer['amount'] >= $price) {
                    if ($sub_id = add_subscription($id, $plan_id, $tenure['months'], $txn_id, $price)) {
                        $response['error'] = false;
                        $response['message'] = "Order Placed Successfully";
                        $response['data'] = $transfer;
                        $response['plan'] = $plan_id;
                        $data = [
                            'error' => 'false',
                            'message' => 'payment completed successfully',
                            'data' => [
                                'error' => false,
                                'data' => $transfer
                            ],
                        ];
                        update_details(
                            [
                                'message' => $response['message'],
                                'status' => $transfer['data']['status'],
                                'subscription_id' =>  $sub_id,
                                'amount' => $price
                            ],
                            [
                                'id' => $insert_id
                            ],
                            'transactions'
                        );
                        update_details(
                            [

                                'transaction_id' => $insert_id,
                                'status' => '2',
                            ],
                            [
                                'id' => $sub_id,

                            ],
                            'subscriptions'
                        );
                    send_mail_with_template('subscription',  $user_data);
                        return $this->response->setJSON($data);
                    }
                } else {
                    $data = [
                        'error' => 'true',
                        'message' => 'Error occurred while completing transaction',
                        'data' => [
                            'error' => true,
                            'data' => $transfer,
                        ],
                    ];
                    return $this->response->setJSON($data);
                }
            }
            if ($provider == "Stripe") {
                $stripe = new Stripe;
                $order = $stripe->create_payment_intent(array('amount' => ($price * 100)));
                $this->response['client_secret'] = $order['client_secret'];
                $this->response['id'] = $order['id'];
                // send_mail_with_template('subscription',  $user_data);
            } else {
                $data['error'] = true;
                $data['message'] = "Invalid Provider.";
                $data['data'] = array();
                return $this->response->setJSON($data);
            }
        }
    }
}
