<!-- Main Content -->
<div class="main-content">
    <section id='tts_form' class='section'>
        <div class="section-header">
            <h1><?= labels('add_users', 'Create User')  ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('auth') ?>"><?= ($admin) ? "Admin" : "User" ?></a></div>
                <div class="breadcrumb-item">Create User</div>
            </div>
        </div>

        <div class="container-fluid card d-flex justify-content-center">
            <div class="card-header">
                <h4 class='section-title'><?= labels('add_users', 'Create User')  ?> </h4>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('message')) { ?>
                    <strong><?= session()->getFlashdata('message'); ?></strong>
                    </button>
            </div>
        <?php } ?>
        <?php echo form_open('auth/register'); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group m-3">
                    <label for='first_name' class="form-label font-weight-bolder text-dark"><?= labels('first_name', 'Frist Name')  ?></label>
                    <input type="text" id="first_name" class="form-control" name='first_name' placeholder="Enter First name" required />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group m-3">
                    <label for='last_name' class="form-label font-weight-bolder text-dark"><?= labels('last_name', 'Last Name')  ?></label>
                    <input type="text" id="last_name" class="form-control" name='last_name' placeholder="Enter Last name" required />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group m-3">
                    <label for='email' class="form-label font-weight-bolder text-dark"><?= labels('email', 'Email')  ?></label>
                    <input type="email" id="email" class="form-control" name='email' placeholder="Enter Email Address" required />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group m-3">
                    <label for='phone' class="form-label font-weight-bolder text-dark"><?= labels('mobile_number', 'Mobile Number')  ?></label>
                    <input type="text" id="phone" class="form-control" name='phone' placeholder="Enter Mobile Number" required />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group m-3">
                    <label for='password' class="form-label font-weight-bolder text-dark"><?= labels('password', 'Password')  ?></label>
                    <input type="password" id="password" class="form-control" name='password' placeholder="Enter Password" required />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group m-3">
                    <label for='password_confirm' class="form-label font-weight-bolder text-dark"><?= labels('confirm_password', 'Confirm Password')  ?></label>
                    <input type="password" id="password_confirm" class="form-control" name='password_confirm' placeholder="Enter Confirm password" required />
                </div>
            </div>
        </div>

        <div class="text-center form-outline mt-4 pb-1">
            <input type="submit" class="btn btn-primary m-2 btn btn-buy el-em font-weight-bolder" value="Register" name="register">
            <a href="<?= base_url('admin/users') ?>" class="btn btn-primary m-2 btn btn-buy el-em font-weight-bolder"><?= labels('back', 'Back')  ?></a>
        </div>
        <?= form_close() ?>
        </div>
</div>
</section>
</div>