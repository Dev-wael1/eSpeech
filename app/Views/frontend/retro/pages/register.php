<!-- Start Breadcrumbs -->
<section class="breadcrumbs">
    <div class="container">
        <ol class='floatc-right'>

            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Registeration</li>
        </ol>
    </div>
</section>
<!-- End Breadcrumbs -->

<!-- Start Signup Section-->
<section class="container" data-aos='fade-up'>
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <p>Registration</p>
        </header>
    </div>
    <div class="row h-100">
    <?php
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            ?>
        <div class="col-sm-12">
        <div class="alert alert-warning mb-0">
                    <b>Note:</b> If you cannot Register here, please close the codecanyon frame by clicking on <b>x Remove Frame</b> button from top right corner on the page or <a href="https://espeech.in" target="_blank">&gt;&gt; Click here &lt;&lt;</a>
                </div>
        </div>
        <?php } ?>
        <div class="col-md">
            <div class="text-white p-1 p-md-5 mx-md-4 h-75">
                <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/register.json'); ?>" background="transparent" speed="1" loop autoplay class="h-300"></lottie-player>
            </div>
        </div>
        <div class="col-md">
            <div class="p-md-5 mx-md-4">
                <div id="" class='alert'><?php echo $message; ?></div>

            </div>

            <?php echo form_open('auth/create_user'); ?>

            <div class="form-outline mb-4 input-group">



                <label for='first_name' class="d-none">Enter First Name</label>
                <label for='last_name' class="d-none">Enter Last Name</label>
                <span class="input-group-text"><i class='bi bi-person'></i></span>
                <input type="text" id="first_name" class="form-control" name='first_name' placeholder="First name" required />
                <input type="text" id="last_name" class="form-control" name='last_name' placeholder="Last name" required />
            </div>

            <div class="form-outline mb-4 input-group">

                <label for='email' class="d-none">Enter Email</label>

                <span class="input-group-text">@</span>

                <input type="email" id="email" class="form-control" name='email' placeholder="Email Address" required />
            </div>

            <div class="form-outline mb-4 input-group">
                <label for='phone' class="d-none">Enter Mobile Number</label>
                <span class="input-group-text"><i class='bi bi-phone'></i></span>
                <input type="text" id="phone" class="form-control" name='phone' placeholder="Mobile Number" required />
            </div>

            <div class="form-outline mb-4 input-group">
                <label for='password' class="d-none">Enter Password</label>
                <label for='password_confirm' class="d-none">Confirm Password</label>
                <span class="input-group-text"><i class='bi bi-lock'></i></span>
                <input type="password" id="password" class="form-control" name='password' placeholder="Password" required />
                <input type="password" id="password_confirm" class="form-control" name='password_confirm' placeholder="Confirm password" required />
            </div>

            <div class="text-center form-outline mb-4 pb-1">
                <input type="submit" class="mb-2 btn btn-buy el-em" value="Register">
                <a href="<?= base_url('auth/login') ?>" class=" mb-2 btn btn-buy el-em">Login</a><br>
            </div>
            <?= form_close() ?>
        </div>
    </div>
    </div>
</section>
<!-- Signup Section End -->