<?php

namespace App\Controllers\api;

/**
 * 1. Razorpay
 * 2. Stripe
 * 3. Paypal
 */


use App\Controllers\BaseController;
use App\Libraries\Paypal_lib;
use App\Libraries\Stripe;
use App\Libraries\Razorpay;

class Webhooks extends BaseController
{
    private $stripe, $razorpay, $paypal;
    public function __construct()
    {
        $this->stripe = new Stripe;
        $this->razorpay = new Razorpay;
        $this->paypal = new Paypal_lib;
    }

    public function razorpay()
    {

        $creds = $this->razorpay->get_credentials();
        $request_body = file_get_contents('php://input');

        $post = $_POST;
        $input = json_decode($request_body, FALSE);
        // print_r('abcd');
        
        // let's make this dynamic
        $key = $creds['razorpay_webhook_secret'];
        log_message('error', 'Razorpay Webhook input --> ' . var_export($input, true));
        log_message('error', 'Razorpay Webhook post --> ' . var_export($post, true));


        if ($input->entity == 'event' && $input->event == "payment.captured") {
            if (!empty($input->payload->payment->entity->id)) {
                $txn_id = (isset($input->payload->payment->entity->id)) ? $input->payload->payment->entity->id : "";
                if (!empty($txn_id)) {
                }
                $amount = $input->payload->payment->entity->amount;
            } else {
                $amount = 0;
                $currency = (isset($input->payload->payment->entity->currency)) ? $input->payload->payment->entity->currency : "";
            }
        }

        $http_razorpay_signature = isset($_SERVER['x-razorpay-signature']) ? $_SERVER['x-razorpay-signature'] : "";
        $amount = $input->payload->payment->entity->amount;
        $user_id = $input->payload->payment->entity->notes->user_id;
        $plan_id = $input->payload->payment->entity->notes->plan_id;
        $tenure_id = $input->payload->payment->entity->notes->tenure_id;

        $db = \Config\Database::connect();
        $result =  hash_hmac('sha256', $request_body, $key);
        $tenure = $db->table('plans_tenures')->where(['id' => $tenure_id, 'plan_id' => $plan_id])->get()->getResultArray();

        $id = $user_id;
        $id = $user_id;
        if ($result == $http_razorpay_signature) {
            if ($input->event == 'payment.captured') {

                $insert_id = add_transaction($txn_id, $amount, "razorpay", $id, "success");
                if ($sub_id = add_subscription($id, $plan_id, $tenure['months'], $txn_id, $amount)) {
                    $response['error'] = false;
                    $response['message'] = "Order Placed Successfully";

                    $response['plan'] = $plan_id;
                    update_details(
                        [

                            'transaction_id' => $insert_id,
                        ],
                        [
                            'id' => $sub_id,

                        ],
                        'subscriptions'
                    );
                    update_details(
                        [
                            'subscription_id' => $sub_id,
                        ],
                        [
                            'id' => $insert_id,

                        ],
                        'transactions'
                    );
                    return $this->response->setJSON($response);
                }
                $response['error'] = true;
                $response['message'] = "something went wrong";


                return $this->response->setJSON($response);


                $response['error'] = false;
                $response['transaction_status'] = $input->eventlefo;
                $response['message'] = "Transaction successfully done";
                echo json_encode($response);
                return false;
            } elseif ($input->event == 'charge.failed') {
                $insert_id = add_transaction($txn_id, $amount, "Stripe", $id, "failed");
            } elseif ($input->event == 'charge.pending') {
                $insert_id = add_transaction($txn_id, $amount, "Stripe", $id, "pending");

                return false;
            } elseif ($input->event == 'charge.expired') {
                $insert_id = add_transaction($txn_id, $amount, "Stripe", $id, "failed");
                return false;
            } elseif ($input->event == 'charge.refunded') {
                $insert_id = add_transaction($txn_id, $amount, "Stripe", $id, "refunded");

                return false;
            } else {
                $response['error'] = true;
                $response['transaction_status'] = $input->evente;
                $response['message'] = "Transaction could not be detected.";

                echo json_encode($response);
                return false;
            }
        } else {
            log_message('error', 'Stripe Webhook | Invalid Server Signature  --> ' . var_export($result, true));
            return false;
        }
    }

    public function stripe()
    {
        $credentials = $this->stripe->get_credentials();
        $request_body = file_get_contents('php://input');
        $event = json_decode($request_body, FALSE);
        log_message('error', 'Stripe Webhook --> ' . var_export($event, true));

        if (!empty($event->data->object->payment_intent)) {
            $txn_id = (isset($event->data->object->payment_intent)) ? $event->data->object->payment_intent : "";
            if (!empty($txn_id)) {
            }
            $amount = $event->data->object->amount;
            $currency = $event->data->object->currency;
            $balance_transaction = $event->data->object->balance_transaction;
        } else {
            $order_id = 0;
            $amount = 0;
            $currency = (isset($event->data->object->currency)) ? $event->data->object->currency : "";
            $balance_transaction = 0;
        }

        /* Wallet refill has unique format for order ID - wallet-refill-user-{user_id}-{system_time}-{3 random_number}  */


        $http_stripe_signature = isset($_SERVER['HTTP_STRIPE_SIGNATURE']) ? $_SERVER['HTTP_STRIPE_SIGNATURE'] : "";
        $result = $this->stripe->construct_event($request_body, $http_stripe_signature, $credentials['webhook_key']);

        $amount = $event->data->object->metadata->amount;
        $user_id = $event->data->object->metadata->user_id;
        $plan_id = $event->data->object->metadata->plan_id;
        $tenure_id = $event->data->object->metadata->tenure;
        $db = \Config\Database::connect();

        $tenure = $db->table('plans_tenures')->where(['id' => $tenure_id, 'plan_id' => $plan_id])->get()->getResultArray()[0];
        $price = $tenure['price'];
        $id = $user_id;
        if ($result == "Matched") {
            if ($event->type == 'charge.succeeded') {

                $insert_id = add_transaction($txn_id, $amount, "Stripe", $id, "success");
                if ($sub_id = add_subscription($id, $plan_id, $tenure['months'], $txn_id, $amount)) {
                    $response['error'] = false;
                    $response['message'] = "Order Placed Successfully";

                    $response['plan'] = $plan_id;
                    update_details(
                        [

                            'transaction_id' => $insert_id,
                        ],
                        [
                            'id' => $sub_id,

                        ],
                        'subscriptions'
                    );
                    update_details(
                        [
                            'subscription_id' => $sub_id,
                        ],
                        [
                            'id' => $insert_id,

                        ],
                        'transactions'
                    );
                    return $this->response->setJSON($response);
                }
                $response['error'] = true;
                $response['message'] = "something went wrong";


                return $this->response->setJSON($response);


                $response['error'] = false;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction successfully done";
                echo json_encode($response);
                return false;
            } elseif ($event->type == 'charge.failed') {
                $insert_id = add_transaction($txn_id, $amount, "Stripe", $id, "failed");
            } elseif ($event->type == 'charge.pending') {
                $insert_id = add_transaction($txn_id, $amount, "Stripe", $id, "pending");

                return false;
            } elseif ($event->type == 'charge.expired') {
                $insert_id = add_transaction($txn_id, $amount, "Stripe", $id, "failed");


                return false;
            } elseif ($event->type == 'charge.refunded') {
                $insert_id = add_transaction($txn_id, $amount, "Stripe", $id, "refunded");

                return false;
            } else {
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction could not be detected.";

                echo json_encode($response);
                return false;
            }
        } else {
            log_message('error', 'Stripe Webhook | Invalid Server Signature  --> ' . var_export($result, true));
            return false;
        }
    }

    public function paypal()
    {
        $paypal_lib = new Paypal_lib;
        $credentials = $this->paypal_lib->get_credentials();
        $request_body = file_get_contents('php://input');
        $event = json_decode($$request_body, false);
        log_message('error', 'Stripe Webhook --> ' . var_export($event, true));


        $http_paypal_signature = isset($_SERVER['paypal-transmission-sig']) ? $_SERVER['paypal-transmission-sig'] : "";
        $transmission_time = isset($_SERVER['paypal-transmission-time']) ? $_SERVER['paypal-transmission-time'] : "";
        $transmission_id = isset($_SERVER['paypal-transmission-id']) ? $_SERVER['paypal-transmission-id'] : "";
        $transmission_time = '2022-05-27T11:23:33Z';
        $transaction_id = '715e1810-ddaf-11ec-ab28-1d8642b00629';

        // remember to pick it up from your dashboard where you've add webhook link
        $webhook_id = $credentials['webhook_id'];
        $crc = '1330495958'; //not sure how  it works

        $status_trans = $event->resource->status;
        $status_subs = '1';


        $transaction_id  = '7E6169861L969294S';
        $trans_data = fetch_details('transactions', ['txn_id' => $transaction_id]);

        $sign =  hash('sha256', $transmission_time | $transmission_id | $webhook_id | $crc);
        $txn_table_id = $trans_data[0]['id'];
        if ($sign == $http_paypal_signature) {
            if ($event->resource->status == 'COMPLETED') {
                $update_id = update_details(['status' => $status_trans], ['txn_id' => $transaction_id], 'transactions');
                if ($update_id) {
                    $update_subs = update_details(['status' => $status_subs], ['transaction_id' => $txn_table_id], 'subscriptions');
                    if ($update_subs) {
                        $response['error'] = false;
                        $response['transaction_status'] = $status_trans;
                        $response['message'] = "Transaction successfully done";
                        return $this->response->setJSON($response);
                    } else {
                        $response['error'] = true;
                        $response['transaction_status'] = $status_trans;
                        $response['message'] = "Transaction successfully done";
                        return $this->response->setJSON($response);
                    }
                }
            } else if ($event->resource->status == 'PENDING') {
                $update_id = update_details(['status' => 'PENDING'], ['txn_id' => $transaction_id], 'transactions');
            } elseif ($event->resource->status == 'VOIDED') {
                $update_id = update_details(['status' => 'VOIDED'], ['txn_id' => $transaction_id], 'transactions');
            } elseif ($event->resource->status == 'DENIED') {
                $update_id = update_details(['status' => 'DENIED'], ['txn_id' => $transaction_id], 'transactions');
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'som error while making payment';
            $response['message'] = "Transaction successfully done";
            return $this->response->setJSON($response);
        }
    }
}
