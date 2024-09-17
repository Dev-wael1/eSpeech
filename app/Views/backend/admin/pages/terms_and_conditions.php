<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('settings', "Settings") ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Settings</a></div>
                <div class="breadcrumb-item">Terms & Conditions </div>
            </div>
        </div>
        <div class="container-fluid card">
            <div class="row">
                <div class="col-md">
                    <h2 class='section-title'><?= labels('terms_and_conditions', "Terms and Conditions") ?></h2>
                </div>
            </div>
        </div>
        <form action="<?= base_url('admin/settings/terms-and-conditions') ?>" method="post">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="container-fluid card p-3">
                <div class="row">
                    <div class="col-lg">
                        <textarea rows=50 class='form-control h-50 summernotes' name="terms_conditions"><?= isset($terms_conditions) ? $terms_conditions : 'Enter Terms & Conditions.' ?></textarea>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md">
                        <div class="form-group">
                            <input type='submit' name='update' id='update' value='<?= labels('save', "Update") ?>' class='btn btn-success' />
                            <input type='reset' name='clear' id='clear' value='<?= labels('reset', "clear") ?>' class='btn btn-danger' />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>