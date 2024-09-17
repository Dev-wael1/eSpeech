<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override(
    function () {
        $data['title'] = "Page not found";
        $data['main_page'] = "error404";
        $data['meta_keywords'] = "voice systhensis, AI Voices, text to voice services, voice over";
        $data['meta_description'] = "";
        return view('frontend/retro/template', $data);
    }
);
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/api/v1', 'api\V1::index');
$routes->get("/api/v1/(:any)", "api\V1::$1");
$routes->post("/api/v1/(:any)", "api\V1::$1");

$routes->get('/api/v2', 'api/V2::index');
$routes->get("/api/v2/(:any)", "api\V2::$1");
$routes->post("/api/v2/(:any)", "api\V2::$1");

$routes->get('/api/webhooks', 'api/Webhooks::index');
$routes->get("/api/webhooks/(:any)", "api\Webhooks::$1");
$routes->post("/api/webhooks/(:any)", "api\Webhooks::$1");

$routes->add("/admin/update_payment_gateway", "admin\Update_pg::update_payment_gateway");
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

/**
 * Custome Routs
 */

$routes->add('unauthorised', 'Home::unauthorised');
$routes->add('/home/synthesize', 'Home::synthesize');
$routes->add('/home/set-voices', 'Home::set_voices');
// $routes->add('set-voices/aws', 'Test::index');
$routes->add('admin/gender', 'Test::gender');


/**
 * Admin Routs
 */

$routes->add('admin', 'admin\Dashboard::index');
$routes->add('admin/dashboard', 'admin\Dashboard::index');
$routes->get('lang/(:any)', 'Language::index/$1');
$routes->get('admin/languages/', "admin\Languages::index");
$routes->post('admin/languages/create', "admin\Languages::create");
$routes->post('admin/languages/set_labels', "admin\Languages::set_labels");
$routes->get('admin/languages/change/(:any)', "admin\Languages::change/$1");

// for add all voices in database
// $routes->add('admin/voices/add_voices', 'admin\Voices_add::add_voices');

$routes->add('admin/voices', 'admin\Voices::index');
$routes->add('admin/voices/show', 'admin\Voices::show');
$routes->post('admin/voices/update-voices', 'admin\Voices::update_voices');
$routes->add('admin/update-all-voices', 'admin\Voices::update_all_voices');

// $routes->add('admin/tts_languages/add_languages', 'admin\TTS_Languages::add_languages');
$routes->add('admin/tts_languages', 'admin\TTS_Languages::index');
$routes->add('admin/tts_languages/show', 'admin\TTS_Languages::show');
$routes->post('admin/tts_languages/update-tts-language', 'admin\TTS_Languages::update_tts_language');

$routes->add('admin/text-to-speech', 'admin\Text_To_Speech::index');
$routes->add('admin/text-to-speech/set-voices', 'admin\Text_To_Speech::set_voices');
$routes->add('admin/text-to-speech/convert_active', 'admin\Text_To_Speech::convert_active');
$routes->post('admin/text-to-speech/synthesize', 'admin\Text_To_Speech::synthesize');
$routes->post('admin/text-to-speech/save-tts', 'admin\Text_To_Speech::save_tts');
$routes->add('admin/text-to-speech/tts-list', 'admin\Text_To_Speech::tts_list');
$routes->post('admin/text-to-speech/delete-tts', 'admin\Text_To_Speech::delete_tts');
$routes->post('admin/text-to-speech/save-predefined', 'admin\Text_To_Speech::save_predefined');
$routes->post('admin/text-to-speech/set-predefined', 'admin\Text_To_Speech::set_predefined');

$routes->add('admin/plans', 'admin\Plans::index');
$routes->add('admin/plans/add-plan', 'admin\Plans::add_plan');
$routes->add('admin/plans/delete_plan', 'admin\Plans::delete_plan');
$routes->add('admin/plans/edit/(:any)', "admin\Plans::edit_plan/$1");
$routes->add('/admin/plans/arange', "admin\Plans::arange");

$routes->add('admin/reports', 'admin\Reports::index');

$routes->add('admin/settings', 'admin\Settings::index');
$routes->add('admin/settings/themes', 'admin\Settings::themes');
$routes->add('admin/settings/general-settings', 'admin\Settings::general_settings');
$routes->add('admin/settings/scripts', 'admin\Settings::scripts');
$routes->add('admin/settings/email-settings', 'admin\Settings::email_settings');
$routes->add('admin/settings/pg-settings', 'admin\Settings::pg_settings');
$routes->post('admin/settings/tts-settings', 'admin\Settings::tts_settings');
$routes->get('admin/settings/tts-settings', 'admin\Settings::tts_settings');
$routes->add('admin/settings/terms-and-conditions', 'admin\Settings::terms_and_conditions');
$routes->add('admin/settings/privacy-policy', 'admin\Settings::privacy_policy');
$routes->add('admin/settings/refund-policy', 'admin\Settings::refund_policy');

$routes->add('admin/settings/add-links', 'admin\Social_links::index');
$routes->add('admin/settings/add-links/add-link', 'admin\Social_links::add_link');
$routes->add('admin/settings/add-links/update-link', 'admin/Social_links::update_link');
$routes->add('admin/settings/add-links/list', 'admin\Social_links::list');
$routes->add('admin/settings/add-links/delete-link', 'admin\Social_links::delete_link');

// Language removing from here
$routes->add('admin/languages/remove', 'admin\Languages::remove');
$routes->add('admin/settings/updater', 'admin\Updater::index');
$routes->add('admin/upload_update_file', 'admin\Updater::upload_update_file');
$routes->add('admin/settings/about-us', 'admin\Settings::about_us');
$routes->add('admin/settings/app', 'admin\Settings::app_settings');

$routes->add('admin/subscriptions', 'admin\Subscriptions::index');
$routes->add('admin/subscriptions/add-subscription', 'admin\Subscriptions::add_subscription');
$routes->add('admin/subscriptions/get-userdetails', 'admin\Subscriptions::get_userdetails');
$routes->add('admin/subscriptions/get-plans', 'admin\Subscriptions::get_plans');
$routes->add('admin/subscriptions/get-tenures', 'admin\Subscriptions::get_tenures');
$routes->add('admin/subscriptions/get-subscriptions', 'admin\Subscriptions::get_subscriptions');
$routes->add('admin/subscriptions/add-subscriptions', 'admin\Subscriptions::add_subscriptions');
$routes->add('admin/subscriptions/get_users', 'admin\Subscriptions::get_users');

$routes->post('admin/subscriptions/get-username', 'admin\Subscriptions::get_username');
$routes->post('admin/subscriptions/get-plan-type', 'admin\Subscriptions::get_plan_type');
$routes->post('admin/subscriptions/get-price', 'admin\Subscriptions::get_price');
$routes->post('admin/subscriptions/get-plan-data', 'admin\Subscriptions::get_plan_data');
$routes->post('admin/subscriptions/get-plan-tenures', 'admin\Subscriptions::get_plan_tenures');
$routes->post('admin/subscriptions/get-price', 'admin\Subscriptions::get_price');
$routes->get('admin/subscriptions/get-subscriptions', 'admin\Subscriptions::get_subscriptions');
$routes->add('admin/subscriptions/get-tenure-month', 'admin\Subscriptions::get_tenure_month');

$routes->add('admin/transactions', 'admin\Transactions::index');
$routes->add('admin/transactions/list-transactions', 'admin\Transactions::list_transactions');

// to active subs
$routes->add('admin/bank_transfers', 'admin\Bank_transfers::index');
$routes->add('admin/bank_transfers/table', 'admin\Bank_transfers::table');
$routes->add('admin/bank_transfers/update_receipt', 'admin\Bank_transfers::update_receipt');
$routes->add('admin/bank_transfers/activate_subscription', 'admin\Bank_transfers::activate_subscription');
$routes->add('admin/bank_transfers/delete_transaction', 'admin\Bank_transfers::delete_transaction');

$routes->add('admin/users', 'admin\Users::index');
$routes->add('admin/users/register_user', 'admin\Users::register_user');

$routes->add('admin/blogs', 'admin\Blogs::index');
$routes->add('admin/blogs/create', 'admin\Blogs::blog');
$routes->post('admin/blogs/add-blog', 'admin\Blogs::add_blog');
$routes->add('admin/blogs/show', 'admin\Blogs::show');
$routes->add('admin/blogs/edit/(:any)', 'admin\Blogs::edit/$1');
$routes->add('admin/blogs/update-blog', 'admin\Blogs::update');
$routes->add('admin/blogs/delete-blog', 'admin\Blogs::delete_blog');

$routes->add('blogs/', 'Blog::index');
$routes->add('blogs/show/(:any)', 'Blog::show/$1');



$routes->add('admin/users/deactivate', 'admin\Users::deactivate');
$routes->add('admin/users/activate', 'admin\Users::activate');
$routes->add('admin/profile', 'admin\Profile::index');
$routes->add('admin/update-profile', 'admin\Profile::update');
$routes->add('admin/users/delete', 'admin\Users::delete');
$routes->add('admin/users/tts', 'admin\Users::tts');
$routes->add('admin/reports/usage', 'admin\Reports::usage');
$routes->add('admin/users/list-user', 'admin\Users::list_user');

$routes->add('payments/post_payment', 'Payments::post_payment');

/**
 * User Routs
 */
$routes->add('user', 'user\Dashboard::index');
$routes->add('user/dashboard', 'user\Dashboard::index');
$routes->add('user/text-to-speech', 'user\Text_To_Speech::index');

$routes->add('user/plans', 'user\Plans::index');
$routes->add('user/plans/checkout', 'user\Plans::checkout');
$routes->add('payments/pre_payment_setup', 'Payments::pre_payment_setup');

$routes->add('user/subscriptions', 'user\Subscriptions::index');
$routes->add('user/subscriptions/table', 'user\Subscriptions::table');
$routes->add('user/subscriptions/upload_bank_reciepts', 'user\Subscriptions::upload_bank_reciepts');

$routes->add('user/profile', 'user\Profile::index');
$routes->add('user/update-profile', 'user\Profile::update');

$routes->add('user/transactions', 'user\Transactions::index');
$routes->add('user/transactions/table', 'user\Transactions::table');

$routes->add('user/bank_transfers', 'user\Bank_transfers::index');
$routes->add('user/bank_transfers/table', 'user\Bank_transfers::table');

$routes->add('payment-success', 'Home::payment_success');
$routes->add('payment-failed', 'Home::payment_failed');
$routes->add('contact-us/sendMail', 'Contact_us::sendMail');
$routes->add('contact-us', 'Contact_us::index');
$routes->add('terms-condition', 'Home::terms_condition');
$routes->add('privacy-policy', 'Home::privacy_policy');
$routes->add('refund-policy', 'Home::refund_policy');

$routes->add('admin/mail-templates', 'admin\Mail_templates::index');
$routes->add('mail-templates/add-mail-template', 'admin\Mail_templates::add_mail_template');
$routes->add('mail-templates/mail-list', 'admin\Mail_templates::mail_template_list');
$routes->add('admin/mail-templates/delete-mail-template', 'admin\Mail_templates::delete_mail_template');
$routes->add('mail-templates/fetch-mail-type-data', 'admin\Mail_templates::fetch_mail_type_data');



$routes->add('user/send_review', 'user\Reviews::index');
$routes->add('user/review/send', 'user\Reviews::send_review');


$routes->add('review/', 'Review::index');


$routes->add('admin/reviews', 'admin\Reviews::index');
$routes->add('admin/reviews/show', 'admin\Reviews::show');
$routes->post('admin/reviews/get-username', 'admin\Reviews::get_username');
$routes->add('admin/review/send', 'admin\Reviews::send_review');
$routes->add('admin/reviews/delete-review', 'admin\Reviews::delete_review');
$routes->add('admin/reviews/show_review', 'admin\Reviews::show_review');
$routes->add('admin/reviews/hide_review', 'admin\Reviews::hide_review');





/**
 *      for migrations
 */
$routes->add('migrate', 'admin\Migrate::index');
$routes->add('migration/createmigrations', 'admin\Migrate::createmigrations');

