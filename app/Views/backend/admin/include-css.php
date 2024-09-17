<?php
$get_scripts = get_settings('scripts', true);
?>
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/bootstrap-table.css') ?>" />
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/bootstrap.min.css') ?>" />
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/summernote.min.css') ?>" />
<link rel="stylesheet" href="<?= base_url('public/fontawesome/css/all.css') ?>" />
<!-- Template CSS -->

<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/style.css') ?>" />
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/iziToast.min.css') ?>" />
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/daterangepicker.css') ?>" />
<?php $data = get_settings('general_settings', true);?>
<!-- Site Identity -->
<link href="<?= isset($data['favicon']) && $data['favicon'] != "" ? base_url("public/uploads/site/" . $data['favicon']) : base_url('public/backend/assets/img/news/img01.jpg') ?>" rel="icon" />
<link href="<?= base_url("public/frontend/retro/img/site/apple-touch-icon.png") ?>" rel="apple-touch-icon" />
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/select2.min.css') ?>" />

<script src="<?= base_url('public/backend/assets/js/vendor/jquery.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/components.css') ?>" />
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/dropzone.css') ?>" />
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/star-rating.min.css') ?>" />
<link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/theme.min.css') ?>" />
<script>
    var baseUrl = '<?= base_url() ?>';
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';
</script>