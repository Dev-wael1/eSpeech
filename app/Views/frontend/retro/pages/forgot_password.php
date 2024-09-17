<section class="breadcrumbs">
    <div class="container">
        <ol class="floatc-right">
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li><a href="<?= base_url('/auth/login') ?>">Login</a></li>
            <li>Forgot password</li>
        </ol>
    </div>
</section>

<!-- Start Forgot Password Section-->
<section class="container" data-aos='fade-up'>
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <p>Forgot Password</p>
        </header>
    </div>
    <div class="row h-100">
        <div class="col-md">
            <div class="text-white p-1 p-md-5 mx-md-4 h-75">
                <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/forget-password.json'); ?>" background="transparent" speed="1" loop autoplay style="height: 300px"></lottie-player>
            </div>
        </div>
        <div class="col-md">
            <div class="p-md-5 mx-md-4">
                <div id="infoMessage" class='alert'><?php echo $message; ?></div>
                <?php
                if (isset($message) && $message != '') {
                    echo "<script>document.getElementById('infoMessage').style.display = 'none';</script>";
                } else {
                    echo "<script>document.getElementById('infoMessage').style.display = 'block';</script>";
                }
                if (isset($_SESSION['no_id'])) {
                    echo $_SESSION['no_id'];
                }
                ?>
            </div>

            <?= form_open('auth/forgot_password') ?>
            <div class="form-outline mb-4 input-group">
                <label class="form-label" for="identity" style='display: none'><?php echo (($type === 'email') ? sprintf(lang('Auth.forgot_password_email_label'), $identity_label) : sprintf(lang('Auth.forgot_password_identity_label'), $identity_label)); ?></label>
                <span class="input-group-text">@</span>
                <input type="text" id="identity" class="form-control" name='identity' placeholder="Enter registered e-mail address" required />
            </div>
            <div class="text-center form-outline mb-4 pb-1">
                <input type="submit" class="mb-2 btn btn-buy w-10em" value="Submit">
                <a href="<?= base_url('auth/login') ?>" class=" mb-2 btn btn-buy w-10em">Go Back</a><br>
            </div>

            <?= form_close() ?>
        </div>
    </div>
    </div>
</section>
<!-- Signup Section End -->