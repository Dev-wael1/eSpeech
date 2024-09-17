<?php
helper('function');
$data = [];
try {
    $data = get_settings('general_settings', true);
    $get_scripts = get_settings('scripts', true);
} catch (Exception $e) {
    echo "<script>console.log('$e')</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title><?= $title ?> - Voice Synthesis Services</title>
    <?= view("frontend/retro/include-css"); ?>
    <script src="<?= base_url('public/frontend/retro/vendor/jQuery/jquery-min.js');  ?>"></script>
    <?php
    isset($data['primary_color']) && $data['primary_color'] != "" ?  $primary_color = $data['primary_color'] : $primary_color =  '#05a6e8';
    isset($data['secondary_color']) && $data['secondary_color'] != "" ?  $secondary_color = $data['secondary_color'] : $secondary_color =  '#003e64';
    isset($data['primary_shadow']) && $data['primary_shadow'] != "" ?  $primary_shadow = $data['primary_shadow'] : $primary_shadow =  '#05A6E8';
    ?>
    <style>
        body {
            --primary: <?= $primary_color ?>;
            --secondary: <?= $secondary_color ?>;
            --nav-link: <?= $secondary_color ?>;
            --primary-shadow: 0px 5px 30px <?= $primary_shadow ?>;
        }
    </style>
    <script>
        var baseUrl = "<?= base_url() ?>";
        let csrfName = "<?= csrf_token() ?>";
        let csrfHash = "<?= csrf_hash() ?>";
    </script>
    <?= isset($get_scripts['header_script']) ? $get_scripts['header_script'] : '' ?>
</head>

<body>
    <?= view("frontend/retro/header"); ?>
    <?= view("frontend/retro/pages/$main_page"); ?>
    <?= view("frontend/retro/footer"); ?>

</body>

</html>