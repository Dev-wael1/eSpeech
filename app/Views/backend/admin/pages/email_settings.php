<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('settings',"Settings") ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Settings</a></div>
                <div class="breadcrumb-item">Email Settings</div>
            </div>
        </div>

        <div class="container-fluid card pt-3">
            <h2 class='section-title'><?= labels('email_settings',"Email Settings") ?></h2>
            <form name='email_settings' id='ESForm' action="<?= base_url('admin/settings/email-settings') ?>" method='get'>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='mailProtocol'><?= labels('mail_protocol',"Mail Protocol") ?></label>
                            <input type='text' class="form-control" name='mailProtocol' id='mailProtocol' value='SMTP' readonly />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='smtpHost'><?= labels('mail_host',"SMTP Host") ?></label>
                            <input type='text' class="form-control" name='smtpHost' id='smtpHost' placeholder="eg. smtp.google.com" value="<?= isset($smtpHost) ? $smtpHost : '' ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='smtpUsername'><?= labels('smtp_username',"SMTP Username") ?></label>
                            <input type='email' class="form-control" name='smtpUsername' id='smtpUsername' placeholder="eg. example@gmail.com" value="<?= isset($smtpUsername) ? $smtpUsername : '' ?>" />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='smtpPassword'><?= labels('smtp_password',"SMTP Password") ?></label>
                            <input type='password' class="form-control" name='smtpPassword' id='smtpPassword' placeholder="Mail account password" value="<?php if( isset($smtpPassword)) { if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) { echo"ahdb***********afasf";  }else{ echo $smtpPassword; } }?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label for='smtpPort'><?= labels('smtp_port',"SMTP Port Number") ?></label>
                            <input type='text' class="form-control" name='smtpPort' id='smtpPort' placeholder="Port number of your SMTP host" value="<?= isset($smtpPort) ? $smtpPort : '' ?>" />
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label for='smtpEncryption'><?= labels('mail_encryption',"Mail Encryption") ?></label>
                            <select class='form-control selectric' name='smtpEncryption' id='smtpEncryption'>
                                <option value='off' <?= isset($smtpEncryption) && $smtpEncryption === 'off' ? 'selected' : '' ?>>Off</option>
                                <option value='ssl' <?= isset($smtpEncryption) && $smtpEncryption === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                <option value='tls' <?= isset($smtpEncryption) && $smtpEncryption === 'tls' ? 'selected' : '' ?>>TLS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label for='mailType'><?= labels('choose_mail_type',"Choose Mail Type") ?></label>
                            <select class='form-control selectric' name='mailType' id='mailType'>
                                <option value='text' <?= isset($mailType) && $mailType === 'text' ? 'selected' : '' ?>>Text</option>
                                <option value='html' <?= isset($mailType) && $mailType === 'html' ? 'selected' : '' ?>>HTMl</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <input type='submit' name='update' id='update' value='<?= labels('save',"Update") ?>' class='btn btn-success' />
                            <input type='reset' name='clear' id='clear' value='<?= labels('reset',"Reset") ?>' class='btn btn-danger' />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>