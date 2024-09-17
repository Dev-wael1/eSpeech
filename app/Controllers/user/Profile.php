<?php

namespace App\Controllers\user;


class Profile extends User
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        helper('function');
        if ($this->isLoggedIn) {
            $this->data['title'] = 'Profile | espeech';
            $this->data['main_page'] = 'profile';
            $this->data['data'] = fetch_details('users', ['id' => $this->userId])[0];
            return view('backend/user/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function update()
    {
        if ($this->isLoggedIn) {
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
            // print_r($_POST);
            // die();
            $data = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'phone' => $_POST['phone'],
            ];
            if ($this->request->getPost('profile')) {

                $img = $this->request->getPost('profile');
                $f = finfo_open();
                $mime_type = finfo_buffer($f, $img, FILEINFO_MIME_TYPE);
                if ($mime_type != 'text/plain') {
                    $response['error'] = true;

                    return $this->response->setJSON([
                        'csrfName' => csrf_token(),
                        'csrfHash' => csrf_hash(),
                        'error' => true,
                        'message' => "Please Insert valid image",
                        "data" => []
                    ]);
                }
                $data_photo = $img;
                $img_dir = './public/backend/assets/profiles/';

                list($type, $data_photo) = explode(';', $data_photo);
                list(, $data_photo) = explode(',', $data_photo);
                $data_photo = base64_decode($data_photo);
                $filename = microtime(true) . '.jpg';
                if (!is_dir($img_dir)) {
                    mkdir($img_dir, 0777, true);
                }

                if (file_put_contents($img_dir . $filename, $data_photo)) {
                    $profile = $filename;
                    $data['image'] = $filename;
                    $old_image = fetch_details('users', ['id' => $this->userId], ['image']);

                    if ($old_image[0]['image'] != "") {
                        if (is_readable("public/backend/assets/profiles/" . $old_image[0]['image']) && unlink("public/backend/assets/profiles/" . $old_image[0]['image'])) {
                        }
                    }
                } else {

                    $data['image'] = $this->input->post('old_profile');
                    $profile = $this->input->post('old_profile');
                }
            }



            $status = update_details(
                $data,
                ['id' => $this->userId],
                'users'
            );

            if ($status) {
                if (isset($_POST['old']) && isset($_POST['new']) && ($_POST['new'] != "") && ($_POST['old'] != "")) {

                    $identity = $this->session->get('identity');

                    if ($_POST['new'] != $_POST['password_confirm']) {
                        return $this->response->setJSON([
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash(),
                            'error' => true,
                            'message' => "Confirm password did not matched.",
                            "data" => $_POST
                        ]);
                    }
                    $change = $this->ionAuth->changePassword($identity, $this->request->getPost('old'), $this->request->getPost('new'));
                    // $match = ($_POST['new'] == $_POST['password_confirm']);

                    if ($change) {
                        return $this->response->setJSON([
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash(),
                            'error' => true,
                            'message' => "Old password did not matched.",
                            "data" => $_POST
                        ]);
                    } else {
                        $this->ionAuth->logout();
                        return $this->response->setJSON([
                            'csrfName' => csrf_token(),
                            'csrfHash' => csrf_hash(),
                            'error' => false,
                            'message' => "User updated successfully",
                            "data" => $_POST
                        ]);
                    }
                }
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => false,
                    'message' => "User updated successfully",
                    "data" => $_POST
                ]);
            } else {
                return $this->response->setJSON([
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash(),
                    'error' => true,
                    'message' => "Something went wrong...",
                    "data" => []
                ]);
            }
        } else {
            return $this->response->setJSON([
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash(),
                'error' => true,
                'message' => "unauthorized",
                "data" => []
            ]);
        }
    }
}
