<?php

namespace App\Controllers;
use App\Controllers\BaseController;



/**
 * Baseclass or Parent class for all admin controllers.
 */
class Frontend extends BaseController
{
    protected $settings, $appName;

    public function __construct()
    {   
        $this->settings = get_settings("general_settings",true);
        $this->appName = (isset($this->settings['company_title'])) ? $this->settings['company_title'] : "eSpeech";
    }
}
function get_settings($type = 'system_settings', $is_json = false, $bool = false)
{
    $db      = \Config\Database::connect();
    $builder = $db->table('settings');
    $res = $builder->select(' * ')->where('variable', $type)->get()->getResultArray();
    if (!empty($res)) {
        if ($is_json) {
            return json_decode($res[0]['value'], true);
        } else {
            return $res[0]['value'];
        }
    } else {
        if ($bool) {

            return false;
        } else {
            return [];
        }
    }
}
