<!-- ======= Footer ======= -->
<?php
$data = [];
try {
    $data = get_settings('general_settings', true);
    $get_scripts = get_settings('scripts', true);
    $social_links = fetch_details('social_links');
} catch (Exception $e) {
    echo "<script>console.log('$e')</script>";
}
isset($data['phone']) && $data['phone'] != "" ?  $phone = $data['phone'] : $phone =  '+919999999999';
isset($data['support_email']) && $data['support_email'] != "" ?  $email = $data['support_email'] : $email =  'admin@admin.com';
?>
<footer id="footer" class="footer">
    <div class="footer-top">

        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-5 col-md-12 footer-info">
                    <a href="<?= base_url() ?>" class="logo d-flex align-items-center">
                        <img src="<?= isset($data['logo']) && $data['logo'] != "" ? base_url("public/uploads/site/" . $data['logo']) : base_url("public/frontend/retro/img/site/logo.png") ?>" alt="" />
                    </a>
                    <p>
                        <?= (isset($data['short_description']) && $data['short_description'] != "") ? $data['short_description']  : "espeech short description " ?>

                    </p>
                    <div class="social-links mt-3">
                        <?php if (!empty($social_links)) : ?>
                            <?php foreach ($social_links as $social_link) : ?>
                                <a href="<?= $social_link['site_url'] ?>" class="<?= trim($social_link['site_name']) ?>">
                                    <?= $social_link['site_html'] ?>
                                </a>
                            <?php endforeach ?>
                        <?php else : ?>
                            <a href="#" class="whatsapp"><i class="bi bi-whatsapp"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram bx bxl-instagram"></i></a>
                            <a href="#" class="linkedin"><i class="bi bi-linkedin bx bxl-linkedin"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h3>Useful Links</h3>
                    <ul>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="<?= base_url() ?>">Home</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i> <a href="<?= base_url("about-us") ?>">About</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="<?= base_url('features') ?>">Features</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="<?= base_url('pricing') ?>">Prices</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="<?= base_url('blogs') ?>">Blogs</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="<?= base_url('contact-us') ?>">Contact</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6 footer-links">
                    <h3></h3>
                    <ul class="mt-4">
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="<?= base_url('refund-policy') ?>">Refund Policy</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="<?= base_url('terms-condition') ?>">Terms and Condition</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="<?= base_url('privacy-policy') ?>">Privacy policy</a>
                        </li>

                    </ul>
                </div>


                <div class="col-lg-3 col-md-12  footer-contact text-center text-md-start">
                    <h3>Contact Us</h3>
                    <?= isset($data['address']) && $data['address'] != "" ?  $data['address'] : 'address' ?>
                    <p>
                        <strong>Phone:</strong> <a href="tel:<?= $phone ?>"><?= $phone ?></a><br />
                        <strong>Email:</strong>
                        <a href="mailto:<?= $email ?>"><?= $email ?> </a><br />
                    </p>
                </div>
                <div class="container">
                    <div class="copyright">
                        <?= (isset($data['copyright_details']) && $data['copyright_details'] != "") ? $data['copyright_details']  : "espeech copyright" ?>
                    </div>
                </div>
                <script>
                </script>
</footer>
<!-- End Footer -->
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="<?= base_url('public/frontend/retro/vendor/bootstrap/js/bootstrap.bundle.js') ?>"></script>
<script src="<?= base_url('public/frontend/retro/vendor/aos/aos.js') ?>"></script>
<script src="<?= base_url('public/frontend/retro/vendor/lottie/lottie.js') ?>"></script>
<script src="<?= base_url('public/frontend/retro/vendor/swiper/swiper-bundle.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/select2.min.js') ?>"></script>
<script src="<?= base_url('public/frontend/retro/vendor/star-rating.min.js') ?>"></script>
<script src="<?= base_url('public/frontend/retro/vendor/theme.min.js') ?>"></script>
<script src="<?= base_url('public/backend/assets/js/vendor/iziToast.min.js') ?>"></script>

<?= isset($get_scripts['footer_script']) ? $get_scripts['footer_script'] : '' ?>


<!-- Template Main JS File -->
<script src="<?= base_url('/public/frontend/retro/js/main.js') ?>"></script>