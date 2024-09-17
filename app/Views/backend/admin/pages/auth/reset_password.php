<section class="breadcrumbs">
    <div class="container">
        <ol>
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Reset Password</li>
        </ol>
    </div>
</section><!-- End Breadcrumbs -->

<section class="container" data-aos='fade-up'>
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <p><?php echo lang('Auth.reset_password_heading'); ?></p>
        </header>
    </div>
    <div class="row">
        <div class="col-lg-6 d-flex align-items-center">
            <div class="text-white p-1 p-md-5 mx-md-4 h-75">
                <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/forget-password.json') ?>" background="transparent" speed="1" loop autoplay></lottie-player>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="p-md-5 mx-md-4">
                <div class="text-center">
                    <h2 class="mt-1 mb-5 pb-1">Create New Password </h2>
                </div>
                <?php echo form_open('auth/reset_password/' . $code); ?>
                <div class="form-outline mb-4 input-group">
                    <label for="new_password" class="d-none"><?php echo sprintf(lang('Auth.reset_password_new_password_label'), $minPasswordLength); ?></label>
                    <span class="input-group-text"><i class='bi bi-lock'></i></span>
                    <?php echo form_input($new_password, "", "class='form-control' placeholder='Enter New Password.'"); ?>

                </div>
                <?php echo form_input($user_id); ?>

                <div class="form-outline mb-4 input-group">
                    <?php echo form_label(lang('Auth.reset_password_new_password_confirm_label'), 'new_password_confirm',['class' => "d-none"]); ?>
                    <span class="input-group-text"><i class='bi bi-lock'></i></span>
                    <?php echo form_input($new_password_confirm, "", "class='form-control' placeholder='Confirm New Password'"); ?>
                </div>

             

                <div class="text-center form-outline mb-4 pb-1">
                    <p><?php echo form_submit('submit', lang('Auth.reset_password_submit_btn'), "class='mb-2 btn btn-buy width-11'"); ?></p>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>
<style>
    .width-11{
        width: 11em;
    }
</style>