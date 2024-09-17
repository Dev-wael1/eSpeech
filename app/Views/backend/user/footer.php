<?php
$data = get_settings('general_settings', true);
isset($data['company_title']) && $data['company_title'] != "" ?  $company = $data['company_title'] : $company =  'company';
?>
<footer class="main-footer">
    <div class="footer-left">
        Copyright &copy; <?= date('Y') ?> <a href="#"><?= $company ?></a>
    </div>
    <div class="footer-right"></div>
</footer>