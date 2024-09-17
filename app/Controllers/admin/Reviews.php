<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\Review_model;

class Reviews extends Admin
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['title'] = 'Reviews';
            $this->data['main_page'] = 'reviews';
            $this->data['users'] = fetch_details('users', [], ["id", "username"]);
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function send_review()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

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
                'userIdentity' => $_POST['userIdentity'],
                'subject' => $_POST['subject'],
                'review' => $_POST['review'],
                'rating_number' => $_POST['rating'],
            ];

            $userdata = fetch_details('users', ['id' => $_POST['userIdentity']], ['username', 'image', 'first_name', 'last_name']);
            foreach ($userdata as $value) {
                // print_r($value)
                $data['user_mail'] = $value['username'];
                $data['user_name'] = $value['first_name'] . " " . $value['last_name'];
                $data['user_image'] = $value['image'];
            }

            // print_r($data);
            // die();
            $status = $reviewModel->insert($data);
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


    public function show()
    {
        $reviews = new Review_model();
        if ($this->isLoggedIn && $this->userIsAdmin) {
            return print_r(json_encode($reviews->list_reviews()));
        } else {
            return redirect('unauthorised');
        }
    }

    public function get_username()
    {
        $user = fetch_details("users", ['id' => $_POST['user_id']], ['first_name', 'last_name']);
        if (!empty($user)) {
            $user = $user[0];
            $user = $user['first_name'] . " " . $user['last_name'];
            return $this->response->setJSON([
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                "data" => $user
            ]);
        }
        return $this->response->setJSON([
            "error" => true,
            'csrfName' => csrf_token(),
            'csrfHash' => csrf_hash(),
            "data" => ''
        ]);
    }

    public function delete_review()
    {

        if ($this->isLoggedIn && $this->userIsAdmin) {

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

            $validation =  \Config\Services::validation();
            $request = \Config\Services::request();
            $validation->setRules(
                [
                    'review_id' => 'required'

                ],
                [
                    'review_id' => [
                        'required' => 'Review id is required',
                    ]
                ]
            );
            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                $response = [
                    'error' => true,
                    'message' => $errors,
                    'data' => [],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];

                return $this->response->setJSON($response);
            }
            $review_id = $request->getPost('review_id');
            // print_r($review_id);
            $review = new Review_model;
            $db      = \Config\Database::connect();
            $builder = $db->table('reviews')->delete(['id' => $review_id]);
            if ($builder) {
                $response = [
                    'error' => false,
                    'message' => 'deleted successfully',
                    'data' => [],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];

                return $this->response->setJSON($response);
            }
            $response = [
                'error' => true,
                'message' => 'something went wrong..',
                'data' => [],
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ];

            return $this->response->setJSON($response);
        }
    }

    public function show_review()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

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
            
            $validation =  \Config\Services::validation();
            $request = \Config\Services::request();
            $validation->setRules(
                [
                    'review_id' => 'required'

                ],
                [
                    'review_id' => [
                        'required' => 'Review id is required',
                    ]
                ]
            );
            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                $response = [
                    'error' => true,
                    'message' => $errors,
                    'data' => [],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];

                return $this->response->setJSON($response);
            }
            $review_id = $request->getPost('review_id');

            $data = [
                'id' => $review_id,
                'status' => 1
            ];

            $status = update_details(
                $data,
                ['id' => $review_id],
                'reviews'
            );

            if ($status) {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => false,
                    'message' => "Review status changed successfully",
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

    public function hide_review()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

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

            $validation =  \Config\Services::validation();
            $request = \Config\Services::request();
            $validation->setRules(
                [
                    'review_id' => 'required'

                ],
                [
                    'review_id' => [
                        'required' => 'Review id is required',
                    ]
                ]
            );
            if (!$validation->withRequest($this->request)->run()) {
                $errors = $validation->getErrors();
                $response = [
                    'error' => true,
                    'message' => $errors,
                    'data' => [],
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ];

                return $this->response->setJSON($response);
            }
            $review_id = $request->getPost('review_id');

            $data = [
                'id' => $review_id,
                'status' => 0
            ];

            $status = update_details(
                $data,
                ['id' => $review_id],
                'reviews'
            );

            if ($status) {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => false,
                    'message' => "Review status changed successfully",
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
