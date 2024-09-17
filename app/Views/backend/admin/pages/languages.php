<?php
helper('form')
?>
<div class="main-wrapper ">

    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1><?= labels("languages", "Languages") ?>
                </h1>
                <div class="section-header-breadcrumb">
                    <button class="btn btn-primary btn-rounded no-shadow" data-toggle="modal" data-target="#exampleModal"><?= labels('add_language', "Add Language") ?></button>
                </div>
            </div>
            <div class="section-body">
                <div id="output-status"></div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h4><?= labels('switch', "Switch") ?></h4>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                    <?php foreach ($languages as $lang) { ?>
                                        <li class="nav-item mt-2">
                                            <div class="d-flex">
                                                <a class="nav-link <?= ($lang['code'] == $code) ? "active" : "" ?> w-75" href='<?= base_url("admin/languages/change/" . $lang['code'])?>'>
                                                    <?= strtoupper($lang['code']) . " - " . ucfirst($lang['language']) ?></a>
                                                <?php if ($lang['code'] == 'en') { ?>
                                                    <button class="btn btn-danger ml-2 disabled"><i class="fa-solid fa-trash-can"></i></button>
                                                <?php } else { ?>
                                                    <a href='<?= base_url("admin/languages/remove?id=" . $lang['id']) ?>' class="btn btn-danger delete-language-btn ml-2"><i class="fa-solid fa-trash-can"></i></a>
                                                <?php } ?>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content no-padding" id="myTab2Content">
                            <div class="tab-pane fade show active" id="languages-settings" role="tabpanel" aria-labelledby="languages-tab4">
                                <?= form_open(base_url('admin/languages/set_labels'), [], ['code' => $code]) ?>

                                <div class="card" id="languages-settings-card">
                                    <div class="card-header">
                                        <h4>Labels</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">

                                            <!-- labels -->

                                            <?= create_label('total_users', 'Total Users') ?>
                                            <?= create_label('active_subscriptions', 'Active Subscriptions') ?>
                                            <?= create_label('expired_subscriptions', 'Expired Subscriptions') ?>
                                            <?= create_label('total_text_to_speech', 'Total Text to Speech') ?>
                                            <?= create_label('characters', 'Characters') ?>
                                            <?= create_label('characters_used', 'Characters used') ?>
                                            <?= create_label('assets_statistics', 'Assests Statistics') ?>
                                            <?= create_label('usage_chart', 'Usage Chart') ?>
                                            <?= create_label('earning_chart', 'Earning chart') ?>
                                            <?= create_label('subscription', 'Subscriptions') ?>
                                            <?= create_label('users', 'Users') ?>
                                            <?= create_label('id', 'ID') ?>
                                            <?= create_label('plan_name', 'Plan Name') ?>
                                            <?= create_label('plan_type', 'Plan Type') ?>
                                            <?= create_label('price', 'Price') ?>
                                            <?= create_label('status', 'Status') ?>

                                            <?= create_label('transaction_id', 'Transaction ID') ?>
                                            <?= create_label('upload_via_text_file','Upload text via a text file') ?>
                                            <?= create_label('total_characters', 'Total Characters') ?>
                                            <?= create_label('remaining_characters', 'Remaining Characters') ?>
                                            <?= create_label('tenure', 'Tenure') ?>
                                            <?= create_label('end_date', 'End Date') ?>
                                            <?= create_label('start_date', 'Start Date') ?>
                                            <?= create_label('hello', 'Hello') ?>
                                            <?= create_label('home', 'Home') ?>
                                            <?= create_label('plans', 'Plans') ?>
                                            <?= create_label('select_language', 'Select Language') ?>
                                            <?= create_label('select_voices', 'Select Voices') ?>
                                            <?= create_label('listen_selected_voice', 'Listen selected voice') ?>
                                            <?= create_label('title', 'Title') ?>
                                            <?= create_label('optional', 'Optional') ?>
                                            <?= create_label('voice_modulations', 'Voice Modulations') ?>
                                            <?= create_label('text', 'Text') ?>
                                            <?= create_label('clear_voice_effect', 'Clear Voice Effect') ?>
                                            <?= create_label('enter_text_here', 'enter text here') ?>
                                            <?= create_label('synthesize_text', 'Synthesize Text') ?>
                                            <?= create_label('play_audio', 'Play audio') ?>
                                            <?= create_label('text_to_speech', 'Text to Speech') ?>
                                            <?= create_label('download_audio', 'Download audio') ?>
                                            <?= create_label('save_result', 'Save Result') ?>
                                            <?= create_label('save_as_predefine', 'Save As Predefine') ?>
                                            <?= create_label('language', 'Language') ?>
                                            <?= create_label('voice', 'Voice') ?>
                                            <?= create_label('provider', 'Provider') ?>
                                            <?= create_label('created_on', 'Created on') ?>
                                            <?= create_label('operate', 'Operate') ?>
                                            <?= create_label('saved_text_to_speech', 'Saved Text to Speech') ?>
                                            <?= create_label('identity', 'Identity') ?>
                                            <?= create_label('available_plans', 'Available Plans') ?>
                                            <?= create_label('add_plan', 'Add Plan') ?>
                                            <?= create_label('select_type', 'Select Type') ?>
                                            <?= create_label('animation', 'Animation') ?>
                                            <?= create_label('months', 'Months') ?>
                                            <?= create_label('discounted_price', 'Discounted Price') ?>
                                            <?= create_label('plan_tenure_details', 'Plan Tenure Details') ?>
                                            <?= create_label('save', 'Save') ?>
                                            <?= create_label('select_user', 'Select User') ?>
                                            <?= create_label('users_full_name', 'User Full Name') ?>
                                            <?= create_label('add_subscription', 'Add Subscription') ?>
                                            <?= create_label('reset', 'Reset') ?>
                                            <?= create_label('transactions', 'Transactions') ?>
                                            <?= create_label('profile', 'Profile') ?>
                                            <?= create_label('amount', 'Amount') ?>
                                            <?= create_label('transaction_status', 'Transaction Status') ?>
                                            <?= create_label('mobile', 'Mobile') ?>
                                            <?= create_label('User_status', 'User Status') ?>
                                            <?= create_label('my_profile', 'My Profile') ?>
                                            <?= create_label('edit_profile', 'Edit Profile') ?>
                                            <?= create_label('change_profile_picture', 'Change Profile Picture') ?>
                                            <?= create_label('first_name', 'First Name') ?>
                                            <?= create_label('last_name', 'Last Name') ?>
                                            <?= create_label('old_password', 'old Password') ?>
                                            <?= create_label('new_password', 'New Password') ?>
                                            
                                            <?= create_label('create_user', 'Create User') ?>
                                            <?= create_label('total_blogs', 'Blogs') ?>
                                            <?= create_label('create_new_blog', 'Create New Blog')  ?>
                                            <?= create_label('create_blog', 'Create Blog')  ?>
                                            <?= create_label('blog_title', 'Blog Title')  ?>
                                            <?= create_label('blog_status', 'Blog Status')  ?>
                                            <?= create_label('blog_image', 'Blog Image')  ?>
                                            <?= create_label('blog_content', 'Blog Content')  ?>
                                            <?= create_label('cancel', 'Cancel')  ?>
                                            <?= create_label('update', 'Update')  ?>
                                            
                                            <?= create_label('all_reviews', 'User Reviews') ?>
                                            <?= create_label('feedback', 'Feedback') ?>
                                            <?= create_label('rate_this', 'Rate This') ?>
                                            <?= create_label('comment', 'Comment') ?>

                                            <?= create_label('general_settings', 'General settings') ?>
                                            <?= create_label('company_title', 'Company Title') ?>
                                            <?= create_label('support_name', 'Support Name') ?>
                                            <?= create_label('support_email', 'Support Email') ?>
                                            <?= create_label('logo', 'Logo') ?>
                                            <?= create_label('favicon', 'Favicon') ?>
                                            <?= create_label('half_logo', 'Half Logo') ?>
                                            <?= create_label('currency_symbol', 'Currency Symbol') ?>
                                            <?= create_label('select_time_zone', 'Select Time Zone') ?>
                                            <?= create_label('primary_color', 'Primary Color') ?>
                                            <?= create_label('secondary_color', 'Secondary Color') ?>
                                            <?= create_label('primary_shadow_color', 'Primary Shadow Color') ?>
                                            <?= create_label('address', 'Address') ?>
                                            <?= create_label('short_description', 'Short Description') ?>

                                            <?= create_label('copyright_details', 'Copyright Details') ?>
                                            <?= create_label('support_hours', 'Support hours') ?>
                                            <?= create_label('settings', 'Settings') ?>
                                            <?= create_label('image', 'Image') ?>
                                            <?= create_label('themes', 'Themes') ?>

                                            <?= create_label('email_settings', 'Email Settings') ?>
                                            <?= create_label('mail_protocol', 'Mail Protocol') ?>
                                            <?= create_label('mail_host', 'SMTP Host') ?>
                                            <?= create_label('smtp_username', 'SMTP Username') ?>
                                            <?= create_label('smtp_password', 'SMTP Password') ?>
                                            <?= create_label('smtp_port', 'SMTP Port Number') ?>
                                            <?= create_label('mail_encryption', 'Mail Encryption') ?>
                                            <?= create_label('choose_mail_type', 'Choose Mail Type') ?>

                                            <?= create_label('leave_blank', 'Leave it blank to disable it') ?>
                                            <?= create_label('app_heading', 'App Heading') ?>
                                            <?= create_label('app_sub_heading', 'App Sub Heading') ?>
                                            <?= create_label('android_link', 'Android Link') ?>
                                            <?= create_label('ios_link', 'IOS Link') ?>
                                            <?= create_label('app_settings', 'App settings') ?>
                                            <?= create_label('about_us', 'About Us') ?>
                                            <?= create_label('terms_and_conditions', 'Terms and Conditions') ?>
                                            <?= create_label('privacy_policy', 'Privacy Policy') ?>
                                            <?= create_label('refund_policy', 'Refund Policy') ?>
                                            <?= create_label('smtp_email', 'SMTP (Email) ') ?>
                                            <?= create_label('payment_gateway', 'Payment Gateway') ?>
                                            <?= create_label('tts', 'TTS') ?>
                                            <?= create_label('configurations', 'Configurations') ?>
                                            <?= create_label('system_updater', 'System Updater') ?>
                                            <?= create_label('dashboard', 'Dashboard') ?>
                                            <?= create_label('no_active', 'No active subscription found') ?>
                                            <?= create_label('purchase_date', 'Purchase Date') ?>
                                            <?= create_label('select_payment_type', 'Select Payment type') ?>
                                            <?= create_label('buy_now', 'Buy Now') ?>
                                            <?= create_label('checkout', 'checkout') ?>
                                            <?= create_label('subscribe', 'Subscribe') ?>
                                            <?= create_label('active_plan', 'Active Plan') ?>
                                            <?= create_label('logout', 'Logout') ?>
                                            <?= create_label('started_from', 'started from') ?>
                                            <?= create_label('expires_on', 'Expires on') ?>
                                            <?= create_label('languages', 'Languages') ?>
                                            <?= create_label('add_language', 'Add Language') ?>
                                            <?= create_label('switch', 'Switch') ?>
                                            <?= create_label('character_based', 'Character Based') ?>
                                            <?= create_label('service_provider_based', 'Service Provider Based') ?>
                                            <?= create_label('featured', 'Featured') ?>
                                            <?= create_label('featured_text', 'Featured Text') ?>
                                            <?= create_label('characters_max', 'Characters Max') ?>
                                            <?= create_label('edit', 'Edit') ?>
                                            <?= create_label('delete', 'Delete') ?>
                                            <?= create_label('filter_date_by', 'Filter Date by') ?>
                                            <?= create_label('all', 'all') ?>
                                            <?= create_label('date_range_filter', 'Date Range') ?>
                                            <?= create_label('filter_by_status', 'Filter by status') ?>
                                            <?= create_label('active', 'Active') ?>
                                            <?= create_label('expired', 'Expired') ?>
                                            <?= create_label('filter_by_status', 'Filter by status') ?>
                                            <?= create_label('apply_filters', 'Apply filters') ?>
                                            <?= create_label('apply', 'Apply') ?>
                                            <?= create_label('payment_method', 'Payment Method') ?>
                                            <?= create_label('transaction_date', 'Transaction Date') ?>
                                            <?= create_label('success', 'Success') ?>
                                            <?= create_label('failed', 'Failed') ?>
                                            <?= create_label('pending', 'Pending') ?>
                                            <?= create_label('message', 'Message') ?>
                                            <?= create_label('plan_order', 'Plan Order') ?>
                                            <?= create_label('row_order_id', 'Row Order Id') ?>
                                            <?= create_label('bank_transfers', "Bank Transfers") ?>
                                            <?= create_label("name", 'Name') ?>
                                            <?= create_label('type', "Type") ?>
                                            <?= create_label('plan', "Plan") ?>
                                            <?= create_label('receipts', 'Receipts') ?>
                                            <?= create_label('check', 'Check') ?>
                                            <?= create_label('date_of_upload', "Date of Upload") ?>
                                            <?= create_label('active_subscription', "Active Subscription") ?>
                                            <!-- labels -->
                                        </div>
                                    </div>
                                    <div class="card-footer bg-whitesmoke text-md-right">
                                        <button class="btn btn-primary" id="languages-save-btn"><?= labels('save', "Save") ?></button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <?= form_open(base_url('admin/languages/create'), ['id="modal-add-language-part"', 'class="modal-part"']); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Languages</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Langugae Name</label>
                        <div class="input-group">
                            <?= form_input(['name' => 'language', 'placeholder' => 'For Ex: English', 'class' => 'form-control']) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Langugae Code</label>
                        <div class="input-group">
                            <?= form_input(['name' => 'code', 'placeholder' => 'For Ex: en', 'class' => 'form-control']) ?>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>