<?php

namespace App\Controllers\admin;
// use app\Models\Plan;
class Plans extends Admin
{
    private $plan_model, $plans_tenures_model;

    public function __construct()
    {
        parent::__construct();
        $this->plan_model = new \App\Models\Plan();
        $this->plans_tenures_model = new \App\Models\Tenures();
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->plan_model->builder()->select()->where('status', 1);
            $this->plans_tenures_model->builder()->select();
            $this->data['plans'] =  fetch_details('plans', [], [], null, "0", "row_order", "ASC");
            $this->data['tenure'] =  $this->plans_tenures_model->builder()->get()->getResultArray();
            $currency = get_settings('general_settings', true);
            $currency = (isset($currency['currency'])) ? $currency['currency'] : '₹';
            $this->data['currency'] =  $currency;
            $this->data['title'] = 'Plans | Admin Panel';
            $this->data['main_page'] = 'plans';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function add_plan()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                $_SESSION['toastMessageType']  = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/plans')->withCookies();
            }
            $recievedData = $this->request->getPost();


            if ($check = $this->request->getPost('tenure')) {
                if (empty($check)) {
                    $_SESSION['toastMessage'] = "Tenure cannot be empty";
                    $_SESSION['toastMessageType']  = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/plans')->withCookies();
                }
            }
            if (!isset($recievedData['months'])) {
                $_SESSION['toastMessage'] = "Please add atleast one tenure.";
                $_SESSION['toastMessageType']  = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/plans')->withCookies();
            }
            $title = $recievedData['tenure'];
            $months = $recievedData['months'];
            $price = $recievedData['price'];
            $discounted_price = ($recievedData['discounted_price'] != '') ? $recievedData['discounted_price'] : '00';
            $data = array(
                'title' => $recievedData['planTitle'],
                'type' => $recievedData['planType'],
                'google' => $recievedData['planGoogleCharacter'],
                'aws' => $recievedData['planAwsCharacter'],
                'ibm' => $recievedData['planIbmCharacter'],
                'azure' => $recievedData['planAzureCharacter'],
                'no_of_characters' => $recievedData['planCharacter'],
                'lottie' => $recievedData['lottie'],
                'featured_text' => DEFAULT_FEATURED_TEXT,
                'status' => 1
            );

            if (isset($_POST['featured'])) {
                $data['is_featured'] = '1';
                if (trim($_POST['featured_text']) != "") {
                    $data['featured_text'] = $_POST['featured_text'];
                }
            } else {
                $data['is_featured'] = '0';
            }
            $plan_id = $this->plan_model->insert($data, true);
            if ($plan_id) {
                // echo "<pre>";
                for ($i = 0; $i < count($title); $i++) {
                    $data = [
                        'plan_id' => $plan_id,
                        'title' => $title[$i],
                        'months' => $months[$i],
                        'price' => round((int)$price[$i], 2),
                        'discounted_price' => $discounted_price[$i],
                    ];
                    // print_r($data);
                    $this->plans_tenures_model->insert($data);
                }
                // die();

                $msg = array('toastMessage' => 'Plan added successfully.', 'toastMessageType' => 'success');
                $this->session->setFlashdata($msg);
            } else {
                $msg = array('toastMessage' => 'Unable to add plan.', 'toastMessageType' => 'error');
                $this->session->setFlashdata($msg);
            }
            return redirect()->to('admin/plans')->withCookies();
        } else {
            return redirect('unauthorised');
        }
    }
    public function edit_plan($id)
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            if (isset($_POST['update'])) {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType']  = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/plans')->withCookies();
                }
                $recievedData = $this->request->getPost();
                if (!isset($recievedData['months'])) {
                    $_SESSION['toastMessage'] = "Please add atleast one tenure.";
                    $_SESSION['toastMessageType']  = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/plans')->withCookies();
                }

                $title = $recievedData['tenure'];
                $months = $recievedData['months'];
                $price = $recievedData['price'];
                $price = $recievedData['price'];
                $plan_id = $recievedData['plan_id'];
                $discounted_price = $recievedData['discounted_price'];
                if (isset($recievedData['tenure_id'])) {
                    $tenure_id = $recievedData['tenure_id'];
                }
                $data = array(
                    'title' => $recievedData['planTitle'],
                    'type' => $recievedData['planType'],
                    'google' => $recievedData['planGoogleCharacter'],
                    'aws' => $recievedData['planAwsCharacter'],
                    'ibm' => $recievedData['planIbmCharacter'],
                    'azure' => $recievedData['planAzureCharacter'],
                    'no_of_characters' => $recievedData['planCharacter'],
                    'lottie' => $recievedData['lottie'],
                    'status' => 1
                );
                if ($recievedData['planType'] == 'general') {
                    $data['aws'] = 0;
                    $data['ibm'] = 0;
                    $data['azure'] = 0;
                    $data['google'] = 0;
                } elseif ($recievedData['planType'] == 'provider') {
                    $data['no_of_characters'] = 0;
                }
                if (isset($_POST['featured'])) {
                    $data['is_featured'] = '1';
                    $data['featured_text'] = DEFAULT_FEATURED_TEXT;
                    if (trim($_POST['featured_text']) != "") {
                        $data['featured_text'] = $_POST['featured_text'];
                    }
                } else {
                    $data['is_featured'] = '0';
                }
                $check = $this->plan_model->update($plan_id, $data);

                $all_id = fetch_details('plans_tenures', ['plan_id' => $plan_id], ['id']);
                $db      = \Config\Database::connect();
                $delete = $db->table('plans_tenures');
                if (!empty($all_id)) {
                    foreach ($all_id as $row) {
                        if (!in_array($row['id'], $tenure_id)) {

                            $delete->delete(['id' => $row['id']]);
                        }
                    }
                }
                if ($check) {


                    for ($i = 0; $i < count($title); $i++) {
                        $data = array(
                            'plan_id' => $plan_id,
                            'title' => $title[$i],
                            'months' => $months[$i],
                            'price' => round((int)$price[$i], 2),
                            'discounted_price' => ($discounted_price[$i] != '') ? $discounted_price[$i] : '0',
                        );

                        if (isset($tenure_id[$i]) and $tenure_id[$i] != '') {
                            $this->plans_tenures_model->update($tenure_id[$i], $data);
                        } else {
                            $this->plans_tenures_model->insert($data);
                        }
                    }



                    $msg = array('toastMessage' => 'Plan Edited successfully.', 'toastMessageType' => 'success');
                    $this->session->setFlashdata($msg);
                } else {
                    $msg = array('toastMessage' => 'Unable to add plan.', 'toastMessageType' => 'error');
                    $this->session->setFlashdata($msg);
                }
                return redirect()->to('admin/plans')->withCookies();
            }


            $this->data['title'] = 'Plans | Admin Panel';
            $this->data['main_page'] = 'edit';
            $this->data['id'] = $id;
            $plans = fetch_details('plans', ['id' => $id]);
            if (empty($plans)) {
                return redirect('unauthorised');
            }
            $tenures = fetch_details('plans_tenures', ['plan_id' => $id]);
            $this->data['plans'] = $plans[0];
            $this->data['tenures'] = $tenures;

            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function draft_plan()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
        } else {
            return redirect('unauthorised');
        }
    }
    public function delete_plan()
    {
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            $response = [
                'error' => true,
                'message' => DEMO_MODE_ERROR,
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'data' => []
            ];
            return $this->response->setJSON($response);
        }
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $db = \Config\Database::connect();
            $plan_id =  $_POST['plan_id'];
            $builder = $db->table('plans')->delete(['id' => $plan_id]);
            $builder = $db->table('plans_tenures')->delete(['plan_id' => $plan_id]);
            $response = [
                'error' => false,
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                "id" => $_POST['plan_id']
            ];
            return $this->response->setJSON($response);
        } else {
            return redirect('unauthorised');
        }
    }
    public function arange()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $response = [
                    'error' => true,
                    'message' => DEMO_MODE_ERROR,
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => []
                ];
                return $this->response->setJSON($response);
            }
            $plan_id = (isset($_GET["plan_id"]) && !empty($_GET["plan_id"])) ? $_GET["plan_id"] : "";
            if (empty($plan_id)) {
                $arr = [
                    "error" => true,
                    "message" => "There are no plans to arrange!"
                ];
                return $this->response->setJSON($arr);
            }

            $flag = 0;
            foreach ($plan_id as $key => $value) {
                if (!update_details(['row_order' => $key], ['id' => $value], 'plans')) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
                $arr = [
                    "error" => true,
                    "message" => "something went wrong",
                ];
            } else {
                $arr = [
                    "error" => false,
                    "message" => "Plan order Updated Successfully.",
                ];
            }
            return $this->response->setJSON($arr);
        } else {
            return redirect('unauthorised');
        }
    }
}
