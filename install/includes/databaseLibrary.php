<?php

use IonAuth\Libraries\IonAuth;

class Database
{

    function create_database($data)
    {
        $mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], '');
        if (mysqli_connect_errno())
            return false;
        $mysqli->query("CREATE DATABASE IF NOT EXISTS " . $data['database']);
        $mysqli->close();
        return true;
    }

    function create_tables($data)
    {
        $link = mysqli_connect($data['hostname'], $data['username'], $data['password'], $data['database']);
        if (mysqli_connect_errno())
            return false;
     
        $filename = 'assets/sqlcommand.sql';
        

        $tempLine = '';
        // Read in the full file
        $lines = file($filename);
        // Loop through each line
        foreach ($lines as $line) {

            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current segment
            $tempLine .= $line;
            // If its semicolon at the end, so that is the end of one query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                mysqli_query($link, $tempLine) or print("Error in " . $tempLine . ":" . mysqli_error($link));
                // Reset temp variable to empty
                $tempLine = '';
            }
        }
        return true;
    }

    function create_admin($data)
    {
        // $ionAuth = new IonAuth;
        // $ionAuth->register($data['admin_email'],$data['admin_password'],$data['admin_email'],[],[1]);

        // return true;
        $mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['database']);
        if (mysqli_connect_errno())
            return false;

        $password = $data['admin_password'];
        $admin_email = $data['admin_email'];

        $params = [
            'cost' => 12
        ];

        if (empty($password) || strpos($password, "\0") !== FALSE || strlen($password) > 32) {
            return FALSE;
        } else {
            $password = password_hash($password, PASSWORD_BCRYPT, $params);
        }
        $mysqli->query("UPDATE `users` SET `username` = '$admin_email', `password` = '$password', `email` = '$admin_email', `phone` = '' WHERE `users`.`id` = 1;");
        $mysqli->close();
        return true;
    }

    function create_base_url($data)
    {
        $mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['database']);
        if (mysqli_connect_errno())
            return false;
        $data_json = array(
            'app_url' => $data['app_url'],
            'company_title' => 'TaskHub'
        );
        $data = json_encode($data_json);

        $mysqli->query("UPDATE settings SET `data`='$data' WHERE `type`='general'");

        $mysqli->close();
        return true;
    }
}
