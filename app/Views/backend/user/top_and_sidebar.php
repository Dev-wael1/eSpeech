<?php
$data = get_settings('general_settings', true);
?>
<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <!-- <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li> -->
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">

                <div class="d-sm-none d-lg-inline-block"><?= strtoupper($current_lang) ?>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <?php foreach ($languages_locale as $language) { ?>
                    <span onclick="set_locale('<?= $language['code'] ?>')" class="dropdown-item has-icon <?= ($language['code'] == $current_lang) ? "text-primary" : "" ?>">
                        <?= strtoupper($language['code']) ?>
                    </span>
                <?php } ?>


            </div>
        </li>

        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <?= $profile_picture ?>

                <div class="d-sm-none d-lg-inline-block"><?= labels('hello', 'Hi') ?> , <span id="header_name"><?= $user ?></span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">

                <a href="<?= base_url('user/profile') ?>" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> <?= labels('profile', "Profile") ?>
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('auth/logout') ?>" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> <?= labels('logout', "Logout") ?>
                </a>
            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand">
            <a href="<?= base_url('user/') ?>">
                <img src="<?= isset($data['logo']) && $data['logo'] != "" ? base_url("public/uploads/site/" . $data['logo']) : base_url('public/backend/assets/img/news/img01.jpg') ?>" class="sidebar_logo h-max-60px" alt="Logo">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= base_url('admin/') ?>">
                <img src="<?= isset($data['half_logo']) && $data['half_logo'] != "" ? base_url("public/uploads/site/" . $data['half_logo']) : base_url('public/backend/assets/img/news/img01.jpg') ?>" height="40px" alt="half Logo">
            </a>
        </div>
        <ul class="sidebar-menu">

            <li class="nav-item "><a class="nav-link" href="<?= base_url('/user') ?>"><i class="fas fa-home  text-danger"></i> <span><?= labels('home', "Home") ?></span></span></a></li>
            <li>
                <a class="nav-link" href="<?= base_url('/user/text-to-speech'); ?>"><i class="fas fa-microphone  text-primary"></i> <span><?= labels('text_to_speech', "Text To Speech") ?></span></span></a>
            </li>
            <li>
                <a class="nav-link" href="<?= base_url('/user/plans'); ?>"><i class="fas fa-book-open text-warning"></i> <span><?= labels('plans', "Plans") ?></span></span></a>
            </li>
            <li>
                <a class="nav-link " href="<?= base_url('/user/transactions'); ?>"><i class="fas fa-receipt text-info"></i> <span><?= labels('transactions', "Transactions") ?></span></span></a>
            </li>
            <li>
                <a class="nav-link" href="<?= base_url('/user/subscriptions'); ?>"><i class="fas fa-fire  text-danger"></i> <span><?= labels('subscription', "Subscriptions") ?></span></span></a>
            </li>
            <li>
                <a class="nav-link" href="<?= base_url('/user/bank_transfers'); ?>"><i class="fas fa-bank  text-success"></i> <span><?= labels('bank_transfers', "Bank Transfers") ?></span></span></a>
            </li>
            <li>
                <a class="nav-link  " href="<?= base_url('/user/profile'); ?>"><i class="fas fa-user text-primary"></i> <span><?= labels('profile', "Profile") ?></span></span></a>
            </li>
            <li>
                <a class="nav-link  " href="<?= base_url('/user/send_review'); ?>"><i class="fas fa-star text-primary"></i> <span><?= labels('feedback', "Your Feedback") ?></span></span></a>
            </li>
        </ul>
    </aside>
</div>