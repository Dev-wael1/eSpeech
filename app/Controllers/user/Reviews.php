<?php

namespace App\Controllers\user;

use App\Controllers\BaseController;
use App\Models\Review_model;

class Reviews extends User
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if ($this->isLoggedIn) {
            $this->data['title'] = 'Feedback';
            $this->data['main_page'] = 'send_review';

            $review_data = fetch_details('reviews', ['user_id' => $this->userId]);
            if ($review_data) {
                $this->data['review_data'] = fetch_details('reviews', ['user_id' => $this->userId])[0];
            } else {
                $this->data['review_data'] = fetch_details('reviews', ['user_id' => $this->userId]);
            }
            
            return view('backend/user/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function send_review()
    {
        if ($this->isLoggedIn && !$this->userIsAdmin) {

            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $response = [
                    'error' => true,
                    'message' => DEMO_MODE_ERROR,
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'data' => [],
                ];
                return $this->response->setJSON($response);
            }

            $validation = \Config\Services::validation();
            $request = \Config\Services::request();
            $reviewModel = new Review_model();

            $validation->setRules(
                [
                    'review' => 'required',
                    'rating' => 'required',
                ],
                [
                    'review' => [
                        'required' => 'Review is required',
                    ],
                    'rating' => [
                        'required' => 'Rating is required',
                    ],
                ]
            );
            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                $response = [
                    'error' => true,
                    'message' => $errors,
                    'data' => [],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                ];
                return $this->response->setJSON($response);
            }

            $data = [
                'subject' => $_POST['subject'],
                'review' => $_POST['review'],
                'rating_number' => $_POST['rating'],
            ];

            $userdata = fetch_details('users', ['id' => $this->userId], ['username', 'image', 'first_name', 'last_name']);
            foreach ($userdata as $value) {
                
                $data['user_id'] = $this->userId;
                $data['user_mail'] = $value['username'];
                $data['user_name'] = $value['first_name'] . " " . $value['last_name'];
                $data['user_image'] = $value['image'];
            }

            if (exists(['user_id' => $this->userId], 'reviews')) {
                $status = update_details(
                    $data,
                    ['user_id' => $this->userId],
                    'reviews'
                );
            } else {
                $status = $reviewModel->insert($data);
            }
            
            if ($status) {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => false,
                    'message' => "Review send successfully",
                    "data" => $data,
                ]);
            } else {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => true,
                    'message' => "Something went wrong...",
                    "data" => [],
                ]);
            }
        } else {
            return redirect('unauthorised');
        }
    }
}
