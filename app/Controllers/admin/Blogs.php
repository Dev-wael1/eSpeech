<?php

namespace App\Controllers\admin;

use App\Models\Blog_model;


class Blogs extends Admin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            $this->data['tags'] = fetch_details('ssml_tags');
            $this->data['title'] = 'Blogs';
            $this->data['main_page'] = 'blogs';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function blog()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $this->data['title'] = 'Create Blog';
            $this->data['main_page'] = 'create_blog';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function add_blog()
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
            $blogModel = new Blog_model();
            $validation->setRules(
                [
                    'title' => 'required',
                    'image' => 'is_image[image]',
                    'description' => 'required',
                    'status' => 'required'
                ],
                [
                    'title' => [
                        'required' => 'Blog Title is required',
                    ],
                    'image' => [
                        'is_image' => 'Please enter a valid Image',
                    ],
                    'description' => [
                        'required' => 'Blog description is required',
                    ],
                    'status' => [
                        'required' => 'Blog Status is required',
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
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'status' => $_POST['status'],
            ];

            $file = $request->getFile('image');

            if ($file->isValid()) {
                $imgRandom = $file->getRandomName();
                if ($file->move('public/uploads/blog_image/', $imgRandom)) {
                    $data['image'] = "public/uploads/blog_image/" . $file->getName();
                }
            }

            $title = trim($_POST['title']);

            $slug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($title)));

            $latest = $blogModel->like('title', $title)->get()->getResultArray();;

            $i = 1;
            if (!empty($latest)) {
                $data['slug'] = $slug . '-' . $i;
                $i++;
            } else {
                $data['slug'] = $slug;
            }

            $status = $blogModel->insert($data);
            if ($status) {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => false,
                    'message' => "Blog created successfully",
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
        $blogs = new Blog_model();
        if ($this->isLoggedIn && $this->userIsAdmin) {
            return print_r(json_encode($blogs->list_blogs()));
        } else {
            return redirect('unauthorised');
        }
    }

    public function edit($id)
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                $_SESSION['toastMessageType'] = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/blogs')->withCookies();
            }

            $this->data['title'] = 'Update Blog';
            $this->data['main_page'] = 'update_blog';
            $this->data['id'] = $id;
            $allowedFields = ['id', 'title', 'description', 'image', 'status', 'created_at', 'updated_at'];

            $this->data['blog'] = fetch_details('blogs', ['id' => $id], $allowedFields);
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function update()
    {

        if ($this->isLoggedIn && $this->userIsAdmin) {

            if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                $_SESSION['toastMessageType'] = 'error';
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/blogs')->withCookies();
            }

            $validation = \Config\Services::validation();
            $request = \Config\Services::request();
            $blogModel = new Blog_model();
            $validation->setRules(
                [
                    'title' => 'required',
                    'image' => 'is_image[image]',
                    'description' => 'required',
                    'status' => 'required'
                ],
                [
                    'title' => [
                        'required' => 'Blog Title is required',
                    ],
                    'image' => [
                        'is_image' => 'Please enter a valid Image',
                    ],
                    'description' => [
                        'required' => 'Blog description is required',
                    ],
                    'status' => [
                        'required' => 'Blog Status is required',
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
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'status' => $_POST['status'],
            ];

            $file = $request->getFile('image');

            $id = $request->getVar('id');
            if ($file->isValid()) {
                $imgRandom = $file->getRandomName();
                if ($file->move('public/uploads/blog_image/', $imgRandom)) {
                    $data['image'] = "public/uploads/blog_image/" . $file->getName();
                }
                $blog_data = fetch_details("blogs", ['id' => $id], ['image']);
                if (!empty($blog_data[0]['image'])) {
                    unlink($blog_data[0]['image']);
                }
            }

            $title = trim($_POST['title']);

            $slug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($title)));

            $latest = $blogModel->like('title', $title)->get()->getResultArray();;

            $i = 1;
            if (!empty($latest)) {
                $data['slug'] = $slug . '-' . $i;
                $i++;
            } else {
                $data['slug'] = $slug;
            }


            $status = $blogModel->update($id, $data);
            if ($status) {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => false,
                    'message' => "Blog updated successfully",
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

    public function delete_blog()
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
                    'blog_id' => 'required'

                ],
                [
                    'blog_id' => [
                        'required' => 'blog_id is required',
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
            $blog_id = $request->getPost('blog_id');
            $tts = new Blog_model;
            $db      = \Config\Database::connect();
            $builder = $db->table('blogs')->delete(['id' => $blog_id]);
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
}
