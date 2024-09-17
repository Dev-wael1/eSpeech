<!-- ======= Header ======= -->
<?php
$data = [];
try {
    $data = get_settings('general_settings', true);
} catch (Exception $e) {
    echo "<script>console.log('$e')</script>";
}
?>








<header id="header" class="header fixed-top new-top">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="<?= base_url() ?>" class="logo d-flex align-items-center">
            <img src="<?= isset($data['logo']) && $data['logo'] != "" ? base_url("public/uploads/site/" . $data['logo']) : base_url('public/backend/assets/img/news/img01.jpg') ?>" alt="" />

        </a>



        <nav id="navbar" class="navbar">

            <ul>
                <li>
                    <a class="nav-link scrollto" href="<?= base_url() ?>/"><i class="bi bi-house"></i><span>Home</span></a>
                </li>
                <li>
                    <a class="nav-link scrollto" href="<?= base_url('features') ?>"><i class="bi bi-gear"></i><span>Features</span></a>
                </li>
                <li>
                    <a class="nav-link scrollto" href="<?= base_url('pricing') ?>"><i class="bi bi-currency-dollar"></i><span>Pricing</span></a>
                </li>
                <li>
                    <a class="nav-link scrollto" href="<?= base_url('about-us') ?>"><i class="bi bi-card-text"></i><span>About</span></a>
                </li>
                <li>
                    <a class="nav-link scrollto" href="<?= base_url('contact-us') ?>"><i class="bi bi-pin-map"></i><span>Contact</span></a>
                </li>
                <li>
                    <a class="nav-link scrollto" href="<?= base_url('blogs') ?>"><i class="fas fa-blog"></i><span>Blogs</span></a>
                </li>

                <li>
                    <a class="getstarted scrollto" href="<?= base_url('auth') ?>">

                        <span><?= (isset($logged) && $logged) ? '<i class="fas fa-tachometer-alt"></i> Go to Dashboard' : '<i class="bi bi-person-plus"></i> Login / Register' ?></span></a>
                </li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>

        </nav>
        <!-- .navbar -->
    </div>
</header>
<!-- End Header -->