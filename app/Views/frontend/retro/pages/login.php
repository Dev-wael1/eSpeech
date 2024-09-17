<!-- Start Breadcrumbs -->
<section class="breadcrumbs">
    <div class="container">
        <ol class="floatc-right">
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Login</li>
        </ol>
    </div>
</section>
<!-- End Breadcrumbs -->


<section class="container" data-aos='fade-up'>
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <p>Authentication</p>
        </header>
    </div>
    <div class="row">
        <?php
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            ?>
        <div class="col-sm-12">
        <div class="alert alert-warning mb-0">
                    <b>Note:</b> If you cannot login here, please close the codecanyon frame by clicking on <b>x Remove Frame</b> button from top right corner on the page or <a href="https://espeech.in" target="_blank">&gt;&gt; Click here &lt;&lt;</a>
                </div>
        </div>
        <?php } ?>
        <div class="col-lg-6 d-flex align-items-center">
            <div class="text-white p-1 p-md-5 mx-md-4 h-75">
                <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/login.json'); ?>" background="transparent" speed="1" loop autoplay></lottie-player>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="p-md-5 mx-md-4">
                <div class="text-center">
                    <h2 class="mt-1 mb-5 pb-1">Login </h2>
                </div>


                <?php echo $message; ?>
                <?php
                if (isset($_SESSION['logout_msg'])) {
                ?>
                    <div class="alert alert-primary" id="logout_msg">
                        <?= $_SESSION['logout_msg'] ?>
                    </div>
                <?php } ?>

                <?= form_open('auth/login', ['method' => "post", "class" => "h-75"]); ?>
                <div class="form-outline mb-4 input-group">
                    <label class="form-label d-none" for="identity"><?= lang('Auth.login_identity_label') ?></label>
                    <span class="input-group-text">@</span>
                    <input type="text" id="identity" class="form-control" name='identity' placeholder="Enter registered e-mail address" required />
                </div>

                <div class="form-outline mb-4 input-group">
                    <label class="form-label  d-none" for="password"><?= lang('Auth.login_password_label') ?></label>
                    <span class="input-group-text"><i class='bi bi-lock'></i></span>
                    <input type="password" id="password" name='password' class="form-control" placeholder="Enter your password" required />
                </div>

                <div class="form-outline mb-4">
                    <input type="checkbox" id="remember" name='remember' value=1 class="form-check-input" />
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <div class="text-center form-outline mb-4 pb-1">
                    <input type="submit" href="<?= base_url('auth/login') ?>" class="mb-2 btn btn-buy w-10em" value="Login">
                    <a href="<?= base_url('auth/create_user') ?>" class=" mb-2 btn btn-buy w-10em">Register</a><br>
                    <a class="text-muted" href="<?= base_url('auth/forgot-password') ?>">Forgot password?</a>
                </div>
                <?= form_close(); ?>

                <?php

                if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                ?>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-danger w-100" onclick="set_admin()">
                                Login as admin
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-warning w-100" onclick="set_user()">
                                Login as User
                            </button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>