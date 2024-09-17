<?php

namespace App\Controllers\admin;

use App\Models\Voices_model;

use App\Controllers\BaseController;

class Voices_add extends BaseController
{

    public function add_voices()
    {
        if ($this->isLoggedIn && $this->userIsAdmin) {

            $azure_voices = get_settings('azure_voices', true, true);
            $ibm_voices = get_settings('ibm_voices', true, true);
            $google_voices = get_settings('google_voices', true, true);
            $aws_voices = get_settings('aws_voices', true, true);
            $all_voices = array_merge($azure_voices, $ibm_voices, $google_voices, $aws_voices);

            // echo "<pre>";
            // print_r($all_voices);
            $add_voices = new Voices_model();
            foreach ($all_voices as $row) {

                if (!isset($row['type'])) {
                    $row['type'] = "Null";
                }
                $data = [
                    "voice" => $row['voice'],
                    "display_name" => $row['display_name'],
                    "language" => $row['language'],
                    "type" => $row['type'],
                    "provider" => $row['provider']
                ];
                $add_voices->save($data);
            }
        } else {
            return redirect('unauthorised');
        }
    }
}
