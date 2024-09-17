<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('settings', "Settings") ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Settings</a></div>
                <div class="breadcrumb-item">General Settings</div>
            </div>
        </div>
        <div class="container-fluid card pt-3">
            <h2 class='section-title'><?= labels('general_settings', "General Settings") ?></h2>
            <?= form_open_multipart(base_url('admin/settings/general-settings')) ?>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for='company_title'><?= labels('company_title', "Company Title") ?></label>
                        <input type='text' class="form-control" name='company_title' id='company_title' value="<?= isset($company_title) ? $company_title : '' ?>" />
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for='support_name'><?= labels('support_name', "Support Name") ?></label>
                        <input type='text' class="form-control" name='support_name' id='support_name' value="<?= isset($support_name) ? $support_name : '' ?>" />
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for='support_email'><?= labels('support_email', "support Email") ?></label>
                        <input type='email' class="form-control" name='support_email' id='support_email' value="<?= isset($support_email) ? $support_email : '' ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 ">
                    <div class="form-group">
                        <label for='logo'><?= labels('logo', "Logo") ?></label>
                        <div class="gallery">
                            <img class="settings_logo" src="<?= isset($logo) && $logo != "" ? base_url("public/uploads/site/" . $logo) : base_url('public/backend/assets/img/news/img01.jpg') ?>">
                        </div>
                        <input type='file' class='form-control-file' name='logo' id='logo' />
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="form-group">
                        <label for='favicon'><?= labels('favicon', "Favicon") ?></label>
                        <div class="gallery">
                            <img class="settings_logo" src="<?= isset($favicon) && $favicon != "" ? base_url("public/uploads/site/" . $favicon) : base_url('public/backend/assets/img/news/img02.jpg') ?>">
                        </div>
                        <input type='file' class='form-control-file' name='favicon' id='favicon' />
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="form-group">
                        <label for='halfLogo'><?= labels('half_logo', "Half Logo") ?></label>
                        <div class="gallery">
                            <img class="settings_logo" src="<?= isset($half_logo) && $half_logo != "" ? base_url("public/uploads/site/" . $half_logo) : base_url('public/backend/assets/img/news/img03.jpg') ?>">
                        </div>
                        <input type='file' class='form-control-file' name='halfLogo' id='halfLogo' />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for='currency'><?= labels('currency_symbol', "Currency Symbol") ?></label>
                        <input type='text' class='form-control' name='currency' id='currency' value="<?= isset($currency) ? $currency : '' ?>" />
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <input type="hidden" id="set" value="<?= isset($system_timezone) ? $system_timezone : 'Asia/Kolkata' ?>">
                        <input type="hidden" name="system_timezone_gmt" value="<?= isset($system_timezone_gmt) ? $system_timezone_gmt : '' ?>" id="system_timezone_gmt" value="<?= isset($system_timezone_gmt) ? $system_timezone_gmt : '+05:30' ?>" />
                        <label for='timezone'><?= labels('select_time_zone', "Select Time Zone") ?></label>
                        <select class='form-control selectric' name='system_timezone' id='timezone' value="">
                            <?php foreach ($timezones as $row) { ?>
                                <option value="<?= $row[2] ?>" data-gmt="<?= $row[1] ?>"><?= $row[1] ?> - <?= $row[0] ?> - <?= $row[2] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="phone"><?= labels('mobile', "Phone") ?></label>
                        <input type="tel" class="form-control" name="phone" id="phone" value="<?= isset($phone) ? $phone : '' ?>" />
                    </div>
                </div>
                <div class="col-md">
                    <label class="" for="">
                        <span class="d-flex">
                            <p>
                                <?= labels('activate_mail_registration', 'Registration Mail') ?>
                            </p>
                            <a href="#" class="badge badge-pill text-danger" data-placement="top" data-toggle="popover" 
                            title="What Will this switch do ?" data-content="Once This switch is clicked, & Saved. No new user will be able to Register and then Login right after register, New users will get Link where they will get registration link only after their account is activated they can Log IN.">?</a>
                        </span>
                    </label>
                    <br>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="activate_registration" <?= (isset($activate_registration) && $activate_registration == '1') ? "checked == 'true" : ''  ?> id="activate_registration">
                        <label class="custom-control-label" for="activate_registration">
                            <span id="activate_text">
                                <?= (isset($activate_registration) && $activate_registration == '1') ? "Active" : 'Inactive'  ?>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <div class="">
                            <label for="primary_color"><?= labels('primary_color', "Primary Color") ?></label>
                        </div>
                        <input type="text" onkeyup="change_color('change_color',this)" oninput="change_color('change_color',this)" class="coloris form-control" name="primary_color" id="primary_color" value="<?= isset($primary_color) ? $primary_color : '' ?>" />
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <div class="">

                            <label for="secondary_color"><?= labels('secondary_color', "Secondary Color") ?></label>
                        </div>
                        <input type="text" class="coloris form-control" name="secondary_color" id="secondary_color" value="<?= isset($secondary_color) ? $secondary_color : '' ?>" />
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <div class="">

                            <label for="primary_shadow"><?= labels('primary_shadow_color', "Primary Shadow Color") ?></label>
                        </div>
                        <input type="text" class="coloris form-control" name="primary_shadow" id="primary_shadow" value="<?= isset($primary_shadow) ? $primary_shadow : '' ?>" />
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <label for="address"><?= labels('address', "Address") ?></label>
                    <textarea rows=30 class='form-control h-50 summernotes' name="address"><?= isset($address) ? $address : 'Enter Address' ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <label for="short_description"><?= labels('short_description', "Short Description") ?></label>
                    <textarea rows=30 class='form-control h-50 summernotes' name="short_description"><?= isset($short_description) ? $short_description : 'Enter Short Description' ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <label for="copyright_details"><?= labels('copyright_details', "Copyright Details") ?></label>
                    <textarea rows=30 class='form-control h-50 summernotes' name="copyright_details"><?= isset($copyright_details) ? $copyright_details : 'Enter Copyright details' ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <label for="copyright_details"><?= labels('support_hours', "Support Hours") ?></label>
                    <textarea rows=30 class='form-control h-50 summernotes' name="support_hours"><?= isset($support_hours) ? $support_hours : 'Enter Support Hours' ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <input type='submit' name='update' id='update' value='<?= labels('save', "Save") ?>' class='btn btn-success' />
                        <input type='reset' name='clear' id='clear' value='<?= labels('reset', "Reset") ?>' class='btn btn-danger' />
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </section>
</div>