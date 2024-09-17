<?php $data = get_settings('general_settings', true); ?>
<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <div class="d-inline-block"><?= strtoupper($current_lang) ?>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <?php foreach ($languages_locale as $language) { ?>
                    <span onclick="set_locale('<?= $language['code'] ?>')" class="dropdown-item has-icon <?= ($language['code'] == $current_lang) ? "text-primary" : "" ?>">
                        <?= strtoupper($language['code']) . " - "  . ucwords($language['language']) ?>
                    </span>
                <?php } ?>
            </div>
        </li>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <?= $profile_picture ?>
                <div class="d-sm-none d-lg-inline-block"><?= labels('hello', 'Hi') ?> , <?= $user ?>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="<?= base_url('admin/profile') ?>" class="dropdown-item has-icon">
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
            <a href="<?= base_url('admin/') ?>">
                <!-- <img src="<? //= base_url('public/uploads/site/1665642081_c1388f2165352b4aa971.png') 
                                ?>" alt="logo" class="sidebar_logo h-max-60px"> -->
                <img src=" <?= isset($data['logo']) && $data['logo'] != "" ? base_url("public/uploads/site/" . $data['logo']) : base_url('public/backend/assets/img/news/img01.jpg') ?>" class="sidebar_logo w-max-90 h-max-60px" alt="">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= base_url('admin/') ?>">
                <!-- <img src="<? //= base_url('public/uploads/site/1665642081_7b8b8feca86322c9a732.png') 
                                ?>" alt="logo" height="40px"> -->
                <img src="<?= isset($data['half_logo']) && $data['half_logo'] != "" ? base_url("public/uploads/site/" . $data['half_logo']) : base_url('public/backend/assets/img/news/img01.jpg') ?>" height="40px" alt="">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/dashboard/') ?>"><i class="fas fa-home text-danger"></i> <span><?= labels('home', 'Home') ?></span></span></a></li>
            
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-microphone  text-info"></i> <span><?= labels('total_text_to_speech', 'Text to Speech') ?></span></a>
                <ul class="dropdown-menu">
                    <li class="nav-item"><a href="<?= base_url('admin/text-to-speech') ?>" class="nav-link"><i class="fas fa-microphone text-primary"></i> <span><?= labels('synthesize_text', 'Synthesize') ?></span></a></li>
                    <li class="nav-item"><a href="<?= base_url('admin/tts_languages') ?>" class="nav-link"><i class="fas fa-solid fa-language text-primary"></i><span><?= labels('total_languages', 'TTS Languages') ?></span></a></li>
                    <li class="nav-item"><a href="<?= base_url('admin/voices') ?>" class="nav-link"><i class="fas fa-solid fa-music text-primary"></i><span><?= labels('total_voices', 'Voices') ?></span></a></li>
                </ul>
            </li>
            
            
            <!-- <li class="nav-item"><a href="<?//= base_url('admin/voices') ?>" class="nav-link"><i class="fas fa-solid fa-music text-primary"></i><span><?//= labels('total_voices', 'Voices') ?></span></a></li> -->
            <!-- <li class="nav-item"><a href="<?//= base_url('admin/tts_languages') ?>" class="nav-link"><i class="fas fa-solid fa-language text-primary"></i><span><?//= labels('total_languages', 'TTS Languages') ?></span></a></li> -->
            <!-- <li class="nav-item"><a href="<?//= base_url('admin/text-to-speech') ?>" class="nav-link"><i class="fas fa-microphone text-primary"></i> <span><?//= labels('total_text_to_speech', 'Text to Speech') ?></span></a></li> -->
            <li class="nav-item"><a href="<?= base_url('/admin/users/tts') ?>" class="nav-link"><i class="fas fa-comment-dots text-success"></i><span><?= labels('users', 'Users') ?> <?= labels('tts', "TTS") ?></span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/plans') ?>" class="nav-link"><i class="fas fa-book-open text-warning"></i> <span><?= labels('plans', 'Plans') ?></span></a></li>
            <li><a class="nav-link" href="<?= base_url('/admin/subscriptions'); ?>"><i class="fas fa-fire  text-danger"></i><span><?= labels('subscription', 'Subscriptions') ?></span></span></a></li>
            <li><a class="nav-link" href="<?= base_url('/admin/transactions'); ?>"><i class="fas fa-file-invoice-dollar text-success"></i> <span><?= labels('transactions', "Transactions") ?></span></span></a></li>
            <li>
                <a class="nav-link" href="<?= base_url('/admin/bank_transfers'); ?>"><i class="fas fa-bank text-danger"></i> <span><?= labels('bank_transfers', "Bank Transfers") ?></span></span></a>
            </li>
            <li><a class="nav-link" href="<?= base_url('/admin/users/'); ?>"><i class="fas fa-users text-primary"></i> <span><?= labels('users', "Users") ?></span></span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/blogs') ?>" class="nav-link"><i class="fas fa-solid fa-blog text-primary"></i><span><?= labels('total_blogs', 'Blogs') ?></span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/reviews') ?>" class="nav-link"><i class="fas fa-solid fa-star text-primary"></i><span><?= labels('all_reviews', 'User Reviews') ?></span></a></li>


            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog  text-info"></i> <span><?= labels('settings', "Settings") ?></span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="<?= base_url('admin/settings/general-settings') ?>"><?= labels('general_settings', "General Settings") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/scripts') ?>"><?= labels('scripts', "Header and Footer scripts") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/themes') ?>"><?= labels('themes', "Themes Settings") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/email-settings') ?>"><?= labels('smtp_email', "SMTP (Email)") ?></a></li>
                    <li>
                        <a class="nav-link" href="<?= base_url('/admin/mail-templates'); ?>">
                            <span><?= labels('mail_templates', "Email Templates") ?></span></span>
                        </a>
                    </li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/pg-settings') ?>"><?= labels('payment_gateway', "Payment Gateway") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/tts-settings') ?>"><?= labels('tts', "TTS") ?> <?= labels('configurations', "Configurations") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/languages') ?>"> <?= labels('languages', "Languages") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/app') ?>"><?= labels('app_settings', "App Settings") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/about-us') ?>"><?= labels('about_us', "About Us") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/terms-and-conditions') ?>"><?= labels('terms_and_conditions', "Terms and Conditions") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/privacy-policy') ?>"><?= labels('privacy_policy', "Privacy Policy") ?></a></li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/refund-policy') ?>"><?= labels('refund_policy', "Refund Policy") ?></a></li>

                    <li>
                        <a class="nav-link" href="<?= base_url('admin/settings/add-links') ?>">
                            <?= labels('add_links', "Add Social Links") ?>
                        </a>
                    </li>
                    <li><a class="nav-link" href="<?= base_url('admin/settings/updater') ?>"><?= labels('system_updater', "System Updater") ?></a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>