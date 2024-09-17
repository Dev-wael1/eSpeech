<?php

namespace App\Controllers\user;

use App\Controllers\BaseController;

/**
 * Baseclass or Parent class for all admin controllers.
 */
class User extends BaseController
{
    protected $ionAuth, $session, $data;

    public function __construct()
    {

        helper('function');
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->session = \Config\Services::session();
        $this->updateUser();

        $this->data['admin'] = $this->userIsAdmin;
        $this->data['user'] = $this->user;
        $this->data['userId'] = $this->userId;

        $active_plan = active_plan($this->userId);
        $active_plan = $this->data['active_plan'] = fetch_details('subscriptions', ['id' => $active_plan]);
        $this->data['active_plan'] = $active_plan;

        $this->data['userIdentity'] = $this->userIdentity;
        $session = session();

        $lang = $session->get('lang');
        if (empty($lang)) {
            $lang = 'en';
        }
        $this->data['current_lang'] = $lang;
        $this->data['languages_locale'] = fetch_details('languages', [], [], null, '0', 'id', 'ASC');
        $data = fetch_details('users', ["id" => $this->userId]);
        $profile = "";
        if (!empty($data)) {
            $data = $data[0];
            if ($data['image'] != '') {
                if (check_exists(base_url('public/backend/assets/profiles/' . $data['image']))) {
                    $profile = '<img alt="image" src="' .  base_url("public/backend/assets/profiles/" . $data['image']) . '" class="rounded-circle mr-1">';
                } else {

                    $profile = '<figure class="avatar mb-2 avatar-sm mt-1" data-initial="' . strtoupper($data['first_name'][0]) . strtoupper($data['last_name'][0]) . '"></figure>';
                }
            } else {
                $profile = '<figure class="avatar mb-2 avatar-sm mt-1" data-initial="' . strtoupper($data['first_name'][0]) . strtoupper($data['last_name'][0]) . '"></figure>';
            }
            $this->data['profile_picture'] = $profile;
        }
        $this->data['profile_picture'] = $profile;
    }
}
