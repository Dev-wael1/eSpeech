<?php $data = get_settings('general_settings', true);?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= $title ?> &mdash; <?= ( isset($data['company_title']) && $data['company_title'] != "" )? $data['company_title'] : "eSpeech";?></title>

    <?= view('backend/admin/include-css') ?>
    <?php
    isset($data['primary_color']) && $data['primary_color'] != "" ?  $primary_color = $data['primary_color'] : $primary_color =  '#05a6e8';
    isset($data['secondary_color']) && $data['secondary_color'] != "" ?  $secondary_color = $data['secondary_color'] : $secondary_color =  '#003e64';
    isset($data['primary_shadow']) && $data['primary_shadow'] != "" ?  $primary_shadow = $data['primary_shadow'] : $primary_shadow =  '#05A6E8';
    ?>
    <style>
        body {
            --primary-color: <?= $primary_color ?>;
            --secondary-color: <?= $secondary_color ?>;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('public/backend/assets/css/vendor/cropper.css') ?>" />
</head>
<body>
    <?php
    if (isset($_SESSION['toastMessage'])) { ?>
        <script>
            $(document).ready(function() {
                showToastMessage("<?= $_SESSION['toastMessage'] ?>", "<?= $_SESSION['toastMessageType'] ?>")
            });
        </script>";
    <?php } ?>
    <div id="app">
        <div class="main-wrapper">
            <?= view('backend/admin/top_and_sidebar') ?>
            <?= view('backend/admin/pages/' . $main_page) ?>
            <?= view('backend/admin/footer') ?>
            <?= view('backend/admin/include-scripts') ?>
        </div>
    </div>
</body>

</html>