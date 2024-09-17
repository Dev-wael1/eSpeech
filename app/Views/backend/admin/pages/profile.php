<div class="main-content profile-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('my_profile', 'My Profile') ?></h1>
        </div>
        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-md-12">
                    <div class="card">
                        <form action="<?= base_url('admin/update-profile') ?>" id="update_user_profile" method="post" accept-charset="utf-8">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            <div class="card-header">
                                <h4><?= labels('edit_profile', "Edit Profile") ?></h4>
                            </div>
                            <div class="card-body">

                                <div class="row ">
                                    <div class="avatar-item col-md-3">
                                        <?php
                                        if ($data['image'] != '') {
                                        ?>
                                            <a href="<?= base_url('public/backend/assets/profiles/' . $data['image'])  ?>" data-lightbox="image-1"><img class="" height="100px" src="<?= base_url('public/backend/assets/profiles/' . $data['image'])  ?>" alt=""></a>
                                        <?php
                                        } else {
                                        ?>
                                            <figure class="avatar mb-2 avatar-xl" data-initial="<?= strtoupper($data['first_name'][0])  ?><?= strtoupper($data['last_name'][0])  ?>"></figure>
                                        <?php }
                                        ?>

                                        <div class="form-group  mt-4">

                                            <label for=""><?= labels('change_profile_picture', "Change Profile Picture") ?></label>
                                            <div class="custom-file">
                                                <input type="file" name="test" class="custom-file-input" id="customFile">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <canvas id="canvas">
                                            Your browser does not support the HTML5 canvas element.
                                        </canvas>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4 col-12">
                                        <label><?= labels('first_name', "First Name") ?></label>
                                        <input type="text" class="form-control" name="first_name" id="first_name" value="<?= $data['first_name']  ?>" required="">
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label><?= labels('last_name', "Last Name") ?></label>
                                        <input type="text" class="form-control" id='last_name' name="last_name" value="<?= $data['last_name']  ?>" required="">

                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label><?= labels('mobile', "Mobile") ?></label>
                                        <input type="tel" name='phone' id="phone" name="phone" class="form-control" value="<?= $data['phone']  ?>">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4 col-12">
                                        <label><?= labels('old_password', "Old Password") ?> ( <?= labels('leave_blank', "Leave it blank to disable it") ?> )</label>
                                        <input type="text" class="form-control" name="old">
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label><?= labels('new_password', "New Password") ?> ( <?= labels('leave_blank', "Leave it blank to disable it") ?> )</label>
                                        <input type="password" class="form-control" name="new">
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label><?= labels('confirm_password', "Confirm Password") ?></label>
                                        <input type="password" id="password_confirm" class="form-control" name='password_confirm' />
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary"><?= labels('cancel', "Cancel") ?></a>
                                <button class="btn btn-primary" type="submit"><?= labels('save', "Save") ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>