<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('settings', "Settings") ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Settings</a></div>
                <div class="breadcrumb-item">App settings</div>
            </div>
        </div>

        <div class="container-fluid card pt-3">
            <h2 class='section-title'><?= labels('app_settings', "App Settings") ?></h2>
            <form name='email_settings' id='ESForm' action="<?= base_url('admin/settings/app') ?>" method='post'>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='mailProtocol'><?= labels('status', "Status") ?></label>
                            <select name="app_status" class="form-control">
                                <option value="enable">Enable</option>
                                <option value="disable">Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='smtpHost'><?= labels('app_heading', "App Heading") ?></label>
                            <input type='text' class="form-control" name='app_heading' id='smtpHost' placeholder="App heading" value="<?= isset($app_heading) ? $app_heading : '' ?>" />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='smtpHost'><?= labels('app_sub_heading', "App Sub Heading") ?></label>
                            <input type='text' class="form-control" name='app_sub_heading' id='smtpHost' placeholder="Sub heading" value="<?= isset($app_sub_heading) ? $app_sub_heading : '' ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='smtpUsername'><?= labels('android_link', "Android Link") ?> ( <?= labels('leave_blank', "Leave it blank to disable it") ?> )</label>
                            <input type='text' class="form-control" name='android_link' id='smtpUsername' placeholder="Android Link" value="<?= isset($android_link) ? $android_link : '' ?>" />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='smtpPassword'><?= labels('ios_link', "IOS Link") ?> ( <?= labels('leave_blank', "Leave it blank to disable it") ?> )</label>
                            <input type='text' class="form-control" name='ios_link' id='smtpPassword' placeholder="Ios Link" value="<?= isset($ios_link) ? $ios_link : '' ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <input type='submit' name='update' id='update' value='<?= labels('save', "Update") ?>' class='btn btn-success' />
                            <input type='reset' name='clear' id='clear' value='<?= labels('reset', "Clear") ?>' class='btn btn-danger' />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>