<?php

namespace Config;

use CodeIgniter\Config\Config;
use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use Config\Email;



/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', function () {
	if (ENVIRONMENT !== 'testing')
	{
		if (ini_get('zlib.output_compression'))
		{
			throw FrameworkException::forEnabledZlibOutputCompression();
		}

		while (ob_get_level() > 0)
		{
			ob_end_flush();
		}

		ob_start(function ($buffer) {
			return $buffer;
		});
	}

	/*
	 * --------------------------------------------------------------------
	 * Debug Toolbar Listeners.
	 * --------------------------------------------------------------------
	 * If you delete, they will no longer be collected.
	 */
    set_language();
	if (CI_DEBUG && ! is_cli())
	{
		Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
		Services::toolbar()->respond();
	}
});



Events::on('post_controller_constructor', function(){ 
    check_for_installer();
    set_system_timezone();
    set_theme();
    
});

function check_for_installer() {
    if(file_exists('install/index.php') ){
    	header("location:install/");
    	die();
    }
}
function set_theme(){
    $theme = fetch_details("themes",['is_default'=>1])[0]; 
    $config = config('Site');
    $config->theme  = $theme['slug'];
    return;
}
function set_language(){
    $config = config('App');
    $config->supportedLocales  = ['en','hi'];
}
function set_system_timezone() {
    $db  = \Config\Database::connect();
    $settings = get_settings('general_settings',true);
    /* Set database timezone */
    if(!empty($settings['system_timezone_gmt'])){
        $db->query("SET time_zone='".$settings['system_timezone_gmt']."'");
    }else{
        $db->query("SET time_zone='+05:30'");
    }
    /* Set PHP server timezone */
    if(!empty($settings['system_timezone'])){
        date_default_timezone_set($settings['system_timezone']);
    }else{
        date_default_timezone_set('Asia/Kolkata');
    }
}

