<?php

namespace App\Controllers\admin;

class Settings extends Admin
{
    private $db, $builder;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('settings');
    }

    public function __destruct()
    {
        $this->db->close();
        $this->data = [];
    }

    public function general_settings()
    {
        helper('form');
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if ($this->request->getPost('update')) {

                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/general-settings')->withCookies();
                }
                $updatedData = $this->request->getPost();

                $flag = 0;
                $favicon = false;
                $halfLogo = false;
                $logo = false;
                $files = array();
                $data = get_settings('general_settings', true);
                if ($_FILES['favicon']['name'] != "") {
                    if (!valid_image('favicon')) {
                        $flag = 1;
                    } else {
                        $favicon = true;
                    }
                }
                if ($_FILES['halfLogo']['name'] != "") {
                    if (!valid_image('halfLogo')) {
                        $flag = 1;
                    } else {
                        $halfLogo = true;
                    }
                }
                if ($_FILES['logo']['name'] != "") {
                    if (!valid_image('logo')) {
                        $flag = 1;
                    } else {
                        $logo = true;
                    }
                }
                if ($favicon) {
                    $file = $this->request->getFile('favicon');
                    $path = FCPATH . 'public/uploads/site/';
                    $image = $file->getName();
                    $newName = $file->getRandomName();
                    $file->move($path, $newName);
                    $updatedData['favicon'] = $newName;
                } else {

                    $updatedData['favicon'] = $data['favicon'];
                }
                if ($logo) {
                    $file = $this->request->getFile('logo');
                    $path = FCPATH . 'public/uploads/site/';
                    $image = $file->getName();
                    $newName = $file->getRandomName();
                    $file->move($path, $newName);
                    $updatedData['logo'] = $newName;
                } else {

                    $updatedData['logo'] = $data['logo'];
                }
                if ($halfLogo) {
                    $file = $this->request->getFile('halfLogo');
                    $path = FCPATH . 'public/uploads/site/';
                    $image = $file->getName();
                    $newName = $file->getRandomName();
                    $file->move($path, $newName);
                    $updatedData['half_logo'] = $newName;
                } else {

                    $updatedData['half_logo'] = $data['half_logo'];
                }

                // return;
                unset($updatedData['update']);
                $updatedData['activate_registration'] = (isset($updatedData['activate_registration']) && $updatedData['activate_registration'] == 'on') ? '1' : '0';

                $updatedData['allow_view_keys'] = (isset($updatedData['allow_view_keys']) && $updatedData['allow_view_keys'] == 'on') ? '1' : '0';
                $json_string = json_encode($updatedData);
                if ($flag == 0) {

                    if ($this->update_setting('general_settings', $json_string)) {
                        $_SESSION['toastMessage'] = 'Unable to update the settings.';
                        $_SESSION['toastMessageType'] = 'error';
                    } else {
                        $_SESSION['toastMessage'] = 'Settings has been successfuly updated.';
                        $_SESSION['toastMessageType'] = 'success';
                    }
                } else {
                    $_SESSION['toastMessage'] = 'please insert valid image.';
                    $_SESSION['toastMessageType'] = 'error';
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                // return;
                return redirect()->to('admin/settings/general-settings')->withCookies();
            }
            $this->builder->select('value');
            $this->builder->where('variable', 'general_settings');
            $query = $this->builder->get()->getResultArray();

            if (count($query) == 1) {
                $settings = $query[0]['value'];
                $settings = json_decode($settings, true);
                if (!empty($settings)) {
                    $this->data = array_merge($this->data, $settings);
                }
            }
            $this->data['timezones'] = get_timezone_array();
            $this->data['title'] = 'General Settings | Admin Panel';
            $this->data['main_page'] = 'general_settings';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function email_settings()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if ($this->request->getGet('update')) {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/email-settings')->withCookies();
                }

                $updatedData = $this->request->getGet();
                $json_string = json_encode($updatedData);

                if ($this->update_setting('email_settings', $json_string)) {
                    $_SESSION['toastMessage'] = 'Unable to update the email settings.';
                    $_SESSION['toastMessageType'] = 'error';
                } else {
                    $_SESSION['toastMessage'] = 'Email settings has been successfuly updated.';
                    $_SESSION['toastMessageType'] = 'success';
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/settings/email-settings')->withCookies();
            }

            $this->builder->select('value');
            $this->builder->where('variable', 'email_settings');
            $query = $this->builder->get()->getResultArray();

            if (count($query) == 1) {
                $settings = $query[0]['value'];
                $settings = json_decode($settings, true);
                $this->data = array_merge($this->data, $settings);
            }

            $this->data['title'] = 'Email Settings | Admin Panel';
            $this->data['main_page'] = 'email_settings';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function pg_settings()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            if ($this->request->getPost('update')) {

                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/pg-settings')->withCookies();
                }

                $updatedData = $this->request->getPost();

                unset($updatedData['update']);
                $json_string = json_encode($updatedData);

                if ($this->update_setting('payment_gateways_settings', $json_string)) {
                    $_SESSION['toastMessage'] = 'Unable to update the payment gateways settings.';
                    $_SESSION['toastMessageType'] = 'error';
                } else {
                    $_SESSION['toastMessage'] = 'Payment gate ways settings has been successfuly updated.';
                    $_SESSION['toastMessageType'] = 'success';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/settings/pg-settings')->withCookies();
            } else {

                $this->builder->select('value');
                $this->builder->where('variable', 'payment_gateways_settings');
                $query = $this->builder->get()->getResultArray();
                if (count($query) == 1) {
                    $settings = $query[0]['value'];
                    $settings = json_decode($settings, true);
                    $this->data = array_merge($this->data, $settings);
                }

                $this->data['title'] = 'Payment Gateways Settings | Admin Panel';
                $this->data['main_page'] = 'payment_gateways';
                return view('backend/admin/template', $this->data);
            }
        } else {
            return redirect('unauthorised');
        }
    }

    public function tts_settings()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            $request = \Config\Services::request();
            // $data = change_voice_status();
            if ($tts_config = get_settings('tts_config', true, true)) {
                if ($tts_config['gcpStatus'] == "disable") {
                    // $google_voices = fetch_details('tts_voices', ['provider' => 'google']);
                    update_details(
                        ['status' => 0],
                        ['provider' => 'google'],
                        'tts_voices',
                        false
                    );
                } else {
                    update_details(
                        ['status' => 1],
                        ['provider' => 'google'],
                        'tts_voices',
                        false
                    );
                }
                if ($tts_config['amazonPollyStatus'] == "disable") {
                    // $aws_voices = fetch_details('settings', ['variable' => 'aws_voices']);
                    update_details(
                        ['status' => 0],
                        ['provider' => 'aws'],
                        'tts_voices',
                        false
                    );
                } else {
                    update_details(
                        ['status' => 1],
                        ['provider' => 'aws'],
                        'tts_voices',
                        false
                    );
                }
                if ($tts_config['azureStatus'] == "disable") {
                    // $azure_voices = fetch_details('settings', ['variable' => 'azure_voices']);
                    update_details(
                        ['status' => 0],
                        ['provider' => 'azure'],
                        'tts_voices',
                        false
                    );
                } else {
                    update_details(
                        ['status' => 1],
                        ['provider' => 'azure'],
                        'tts_voices',
                        false
                    );
                }
                if ($tts_config['ibmStatus'] == "disable") {
                    // $ibm_voices = fetch_details('settings', ['variable' => 'ibm_voices']);
                    update_details(
                        ['status' => 0],
                        ['provider' => 'ibm'],
                        'tts_voices',
                        false
                    );
                } else {
                    update_details(
                        ['status' => 1],
                        ['provider' => 'ibm'],
                        'tts_voices',
                        false
                    );
                }
            }
            // if ($this->request->getPost('gcpStatus') == 'disable') {
            //     // update_details(
            //     //     ['status' => 0],
            //     //     ['provider' => 'google'],
            //     //     'tts_voices',
            //     //     false
            //     // );
            //     // print_r($data);
            //     // die();
            // } else {
            // update_details(
            //     ['status' => 1],
            //     ['provider' => 'google'],
            //     'tts_voices',
            //     false
            // );
            // }

            // if ($this->request->getPost('amazonPollyStatus') == 'disable') {
            //     update_details(
            //         ['status' => 0],
            //         ['provider' => 'aws'],
            //         'tts_voices',
            //         false
            //     );
            // } else {
            //     update_details(
            //         ['status' => 1],
            //         ['provider' => 'aws'],
            //         'tts_voices',
            //         false
            //     );
            // }

            // if ($this->request->getPost('ibmStatus') == 'disable') {
            //     update_details(
            //         ['status' => 0],
            //         ['provider' => 'ibm'],
            //         'tts_voices',
            //         false
            //     );
            // } else {
            //     update_details(
            //         ['status' => 1],
            //         ['provider' => 'ibm'],
            //         'tts_voices',
            //         false
            //     );
            // }

            // if ($this->request->getPost('azureStatus') == 'disable') {
            //     update_details(
            //         ['status' => 0],
            //         ['provider' => 'azure'],
            //         'tts_voices',
            //         false
            //     );
            // } else {
            //     update_details(
            //         ['status' => 1],
            //         ['provider' => 'azure'],
            //         'tts_voices',
            //         false
            //     );
            // }

            // die();

            if ($this->request->getPost('update')) {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/tts-settings')->withCookies();
                }

                $updatedData = $this->request->getPost();
                $json_string = json_encode($updatedData);

                if ($this->update_setting('tts_config', $json_string)) {
                    $_SESSION['toastMessage'] = 'Unable to update the text to speech configuratins.';
                    $_SESSION['toastMessageType'] = 'error';
                } else {
                    $_SESSION['toastMessage'] = 'Text to Speech Configuratins has been successfuly updated.';
                    $_SESSION['toastMessageType'] = 'success';
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/settings/tts-settings')->withCookies();
            }
            $this->builder->select('value');
            $this->builder->where('variable', 'tts_config');
            $query = $this->builder->get()->getResultArray();
            if (count($query) == 1) {
                if (!empty($query[0]['value'])) {

                    $settings = $query[0]['value'];
                    $settings = json_decode($settings, true);
                    $this->data = array_merge($this->data, $settings);
                }
            }

            $this->data['title'] = 'Text to Speech Settings | Admin Panel';
            $this->data['main_page'] = 'tts_config';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function privacy_policy()
    {

        if ($this->isLoggedIn && $this->userIsAdmin) {

            if ($this->request->getPost('update')) {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/privacy-policy')->withCookies();
                }

                $updatedData = $this->request->getPost();
                unset($updatedData['update']);
                unset($updatedData['files']);
                $json_string = json_encode($updatedData);

                if ($this->update_setting('privacy_policy', $json_string)) {
                    $_SESSION['toastMessage'] = 'Unable to update the privacy policy.';
                    $_SESSION['toastMessageType'] = 'error';
                } else {
                    $_SESSION['toastMessage'] = 'privacy Policy has been successfuly updated.';
                    $_SESSION['toastMessageType'] = 'success';
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/settings/privacy-policy')->withCookies();
            }
            $this->builder->select('value');
            $this->builder->where('variable', 'privacy_policy');
            $query = $this->builder->get()->getResultArray();
            if (count($query) == 1) {

                $settings = $query[0]['value'];
                $settings = json_decode($settings, true);

                $this->data = array_merge($this->data, $settings);
            }

            $this->data['title'] = 'Privacy Policy Settings | Admin Panel';
            $this->data['main_page'] = 'privacy_policy';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function refund_policy()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            if ($this->request->getPost('update')) {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/refund-policy')->withCookies();
                }

                $updatedData = $this->request->getPost();
                unset($updatedData['update']);
                unset($updatedData['files']);
                $json_string = json_encode($updatedData);

                if ($this->update_setting('refund_policy', $json_string)) {
                    $_SESSION['toastMessage'] = 'Unable to update the refund policy.';
                    $_SESSION['toastMessageType'] = 'error';
                } else {
                    $_SESSION['toastMessage'] = 'refund Policy has been successfuly updated.';
                    $_SESSION['toastMessageType'] = 'success';
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/settings/refund-policy')->withCookies();
            }
            $this->builder->select('value');
            $this->builder->where('variable', 'refund_policy');
            $query = $this->builder->get()->getResultArray();
            if (count($query) == 1) {

                $settings = $query[0]['value'];
                $settings = json_decode($settings, true);

                $this->data = array_merge($this->data, $settings);
            }

            $this->data['title'] = 'Refund Policy Settings | Admin Panel';
            $this->data['main_page'] = 'refund_policy';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function updater()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            $this->data['title'] = 'Updater | Admin Panel';
            $this->data['main_page'] = 'updater';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function terms_and_conditions()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if ($this->request->getPost('update')) {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/terms-and-conditions')->withCookies();
                }

                $updatedData = $this->request->getPost();
                unset($updatedData['files']);
                unset($updatedData['update']);
                $json_string = json_encode($updatedData);

                if ($this->update_setting('terms_conditions', $json_string)) {
                    $_SESSION['toastMessage'] = 'Unable to update the terms & conditions.';
                    $_SESSION['toastMessageType'] = 'error';
                } else {
                    $_SESSION['toastMessage'] = 'Terms & Conditions has been successfuly updated.';
                    $_SESSION['toastMessageType'] = 'success';
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/settings/terms-and-conditions')->withCookies();
            }
            $this->builder->select('value');
            $this->builder->where('variable', 'terms_conditions');
            $query = $this->builder->get()->getResultArray();
            if (count($query) == 1) {
                $settings = $query[0]['value'];
                $settings = json_decode($settings, true);
                $this->data = array_merge($this->data, $settings);
            }

            $this->data['title'] = 'Terms & Conditions Settings - Admin Panel | eSpeech';
            $this->data['main_page'] = 'terms_and_conditions';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function about_us()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if ($this->request->getPost('update')) {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/about-us')->withCookies();
                }

                $updatedData = $this->request->getPost();
                unset($updatedData['files']);
                unset($updatedData['update']);
                $json_string = json_encode($updatedData);

                if ($this->update_setting('about_us', $json_string)) {
                    $_SESSION['toastMessage'] = 'Unable to update about-us section.';
                    $_SESSION['toastMessageType'] = 'error';
                } else {
                    $_SESSION['toastMessage'] = 'About-us section has been successfuly updated.';
                    $_SESSION['toastMessageType'] = 'success';
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/settings/about-us')->withCookies();
            }
            $this->builder->select('value');
            $this->builder->where('variable', 'about_us');
            $query = $this->builder->get()->getResultArray();
            if (count($query) == 1) {
                $settings = $query[0]['value'];
                $settings = json_decode($settings, true);
                $this->data = array_merge($this->data, $settings);
            }

            $this->data['title'] = 'About us Settings | Admin Panel';
            $this->data['main_page'] = 'about_us';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    private function update_setting($variable, $value)
    {
        $this->builder->where('variable', $variable);
        if (exists(['variable' => $variable], 'settings')) {
            $this->db->transStart();
            $this->builder->update(['value' => $value]);
            $this->db->transComplete();
        } else {
            $this->db->transStart();
            $this->builder->insert(['variable' => $variable, 'value' => $value]);
            $this->db->transComplete();
        }

        return $this->db->transComplete() ? true : false;
    }

    public function themes()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {
            if ($this->request->getPost('update')) {
            }
            $this->data["themes"] = fetch_details('themes', [], [], null, '0', 'id', "ASC");

            $this->data['title'] = 'About us Settings | Admin Panel';
            $this->data['main_page'] = 'themes';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
    public function app_settings()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            if ($this->request->getPost('update')) {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/app')->withCookies();
                }

                $updatedData = $this->request->getPost();
                $json_string = json_encode($updatedData);

                if ($this->update_setting('app_settings', $json_string)) {
                    $_SESSION['toastMessage'] = 'Unable to update the App settings.';
                    $_SESSION['toastMessageType'] = 'error';
                } else {
                    $_SESSION['toastMessage'] = 'App settings has been successfuly updated.';
                    $_SESSION['toastMessageType'] = 'success';
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/settings/app')->withCookies();
            }

            $this->builder->select('value');
            $this->builder->where('variable', 'app_settings');
            $query = $this->builder->get()->getResultArray();

            if (count($query) == 1) {
                $settings = $query[0]['value'];
                $settings = json_decode($settings, true);
                $this->data = array_merge($this->data, $settings);
            }

            $this->data['title'] = 'App Settings | Admin Panel';
            $this->data['main_page'] = 'app';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }

    public function scripts()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            if ($this->request->getPost('update')) {
                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    $_SESSION['toastMessage'] = DEMO_MODE_ERROR;
                    $_SESSION['toastMessageType'] = 'error';
                    $this->session->markAsFlashdata('toastMessage');
                    $this->session->markAsFlashdata('toastMessageType');
                    return redirect()->to('admin/settings/scripts')->withCookies();
                }

                $updatedData = $this->request->getPost();
                $json_string = json_encode($updatedData);

                if ($this->update_setting('scripts', $json_string)) {
                    $_SESSION['toastMessage'] = 'Unable to update the Scripts settings.';
                    $_SESSION['toastMessageType'] = 'error';
                } else {
                    $_SESSION['toastMessage'] = 'Scripts settings has been successfuly updated.';
                    $_SESSION['toastMessageType'] = 'success';
                }
                $this->session->markAsFlashdata('toastMessage');
                $this->session->markAsFlashdata('toastMessageType');
                return redirect()->to('admin/settings/scripts')->withCookies();
            }

            $this->builder->select('value');
            $this->builder->where('variable', 'scripts');
            $query = $this->builder->get()->getResultArray();

            if (count($query) == 1) {
                $settings = $query[0]['value'];
                $settings = json_decode($settings, true);
                $this->data = array_merge($this->data, $settings);
            }

            $this->data['title'] = 'Header & Footer Scripts | Admin Panel';
            $this->data['main_page'] = 'scripts';
            return view('backend/admin/template', $this->data);
        } else {
            return redirect('unauthorised');
        }
    }
}
