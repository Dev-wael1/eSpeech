<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('settings', "Settings") ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Settings</a></div>
                <div class="breadcrumb-item">Scripts</div>
            </div>
        </div>
        <div class="container-fluid card">
            <div class="row">
                <div class="col-md">
                    <h2 class='section-title'><?= labels('header_footer_scripts', "Header And Footer Scripts") ?></h2>
                </div>
            </div>
        </div>
        <form action="<?= base_url('admin/settings/scripts') ?>" method="post">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="container-fluid card p-3">
                <div class="row">
                    <div class="col-lg">
                        <label for="header_script">Header Script</label>
                        <textarea  id="header_script" class='form-control h-100' name="header_script"><?= isset($header_script) ? $header_script : 'Enter Header script.' ?></textarea>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-lg">
                        <label for="footer_scripts">Footer Scripts</label>
                        <textarea class='form-control h-100' id="footer_script" name="footer_script"><?= isset($footer_script) ? $footer_script : 'Enter Footer script.' ?></textarea>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md">
                        <div class="form-group">
                            <input type='submit' name='update' id='update' value='<?= labels('save', "Update") ?>' class='btn btn-success' />
                            <input type='reset' name='clear' id='clear' value='<?= labels('reset', "Clear") ?>' class='btn btn-danger' />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>