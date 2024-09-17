<?php
$data = get_settings('general_settings', true);
$get_scripts = get_settings('scripts', true);
isset($data['company_title']) && $data['company_title'] != "" ?  $company = $data['company_title'] : $company =  'company';
?>
<footer class="main-footer">
    <div class="footer-left">
        <?php $data = get_settings('general_settings', true); ?>
        <?= (isset($data['copyright_details']) && $data['copyright_details'] != "") ? $data['copyright_details']  : "espeech copyright" ?>
    </div>
    <div class="footer-right"></div>
    <script>
    </script>
</footer>