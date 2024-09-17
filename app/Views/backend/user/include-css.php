    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/bootstrap-table.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/iziToast.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/daterangepicker.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/select2.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/fontawesome/css/all.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/star-rating.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/theme.min.css') ?>" />
    <?php $data = get_settings('general_settings', true); ?>

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/components.css') ?>" />


    <!-- Site Identity -->
    <link href="<?= isset($data['favicon']) && $data['favicon'] != "" ? base_url("public/uploads/site/" . $data['favicon']) : base_url('public/backend/assets/img/news/img01.jpg') ?>" rel="icon" />
    <link href="<?= base_url("public/frontend/retro/img/site/apple-touch-icon.png") ?>" rel="apple-touch-icon" />

    <script src="<?= base_url('public/backend/assets/js/vendor/jquery.min.js') ?>"></script>

    <script>
        var baseUrl = '<?= base_url() ?>';
        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>';
        var users_id = <?= $userId ?>;
    </script>
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/cropper.css') ?>" />