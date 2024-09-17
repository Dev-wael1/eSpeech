<script src="<?= base_url('public/backend/assets/js/vendor/bootstrap-table.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/popper.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/summernote.min.js') ?>"></script>


<script src="<?= base_url('public/backend/assets/js/vendor/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/jquery.nicescroll.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/moment.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/stisla.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/iziToast.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/select2.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/cropper.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/bootstrap-colorpicker.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/daterangepicker.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/dropzone.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/sweetalert.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/lottie.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/backend/assets/js/vendor/tinymce/tinymce.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/backend/assets/js/vendor/star-rating.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('public/backend/assets/js/vendor/theme.min.js') ?>"></script>
<?= '<script src="' . base_url('public/backend/assets/js/page/admin_plans.js') . '"></script>'; ?>
<?= '<script src="' . base_url('public/backend/assets/js/page/admin.js') . '"></script>' ?>

<?php
switch ($main_page) {
    case "dashboard":
        echo '<script src="' . base_url('public/backend/assets/js/vendor/chart.min.js') . '"></script>';
        echo '<script src="' . base_url('public/backend/assets/js/vendor/iconify.min.js') . '"></script>';
        break;

    case "subscription":
        echo '<script src="' . base_url('public/backend/assets/js/page/subscription.js') . '"></script>';
        break;

    case "plans":


        break;

    case "voices":
        echo '<script  type="text/javascript" src="' . base_url('public/backend/assets/js/page/voices.js') . '"></script>';
        break;

    case "tts_languages":
        echo '<script  type="text/javascript" src="' . base_url('public/backend/assets/js/page/tts_languages.js') . '"></script>';
        break;


    case "create_blog":
        echo '<script  type="text/javascript" src="' . base_url('public/backend/assets/js/page/blogs.js') . '"></script>';
        break;

    case "update_blog":
        echo '<script  type="text/javascript" src="' . base_url('public/backend/assets/js/page/blogs.js') . '"></script>';
        break;

    case "reviews":
        echo '<script src="' . base_url('public/backend/assets/js/reviews.js') . '"></script>';
        break;

    case "../../text_to_speech":
        echo '<script src="' . base_url('public/backend/assets/js/page/tts.js') . '"></script>';
        break;
}
?>

<script src="<?= base_url('public/backend/assets/js/window_events.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/scripts.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/custom.js') ?>"></script>