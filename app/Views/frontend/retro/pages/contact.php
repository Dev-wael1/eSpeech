<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">
        <ol>
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Contact us</li>
        </ol>
    </div>
</section><!-- End Breadcrumbs -->

<!-- ======= Contact Section ======= -->
<section id="contact" class="contact">
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <p>Contact </p>
            <h1>Contact Us</h1>
        </header>

        <div class="row gy-4">
            <div class="col-lg-6">
                <div class="row gy-4">
                    <?php if (isset($settings['support_email']) && $settings['support_email'] != "") {?>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-envelope-open"></i>
                                <h3>Email Us</h3>
                                <p> <?= $settings['support_email'] ?></p>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    if (isset($settings['support_hours']) && $settings['support_hours'] != "") {

                    ?>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-hourglass-bottom"></i>
                                <h3>Support Hours</h3>
                                <p><?= $settings['support_hours'] ?></p>
                            </div>
                        </div>
                    <?php } ?>

                    <?php
                    if (isset($settings['phone']) && $settings['phone'] != "") {

                    ?>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-telephone"></i>
                                <h3>Call Us</h3>
                                <p>
                                    <?= $settings['phone'] ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    if (isset($settings['address']) && $settings['address'] != "") {

                    ?>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-pin-map"></i>
                                <h3>Address</h3>
                                <p>

                                    <?= $settings['address'] ?>
                                </p>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </div>


            <div class="col-lg-6">
                <form action="<?= base_url('contact-us/sendMail') ?>" id="contact_form" method="post" class="php-email-form">
                    <input type="hidden" id="csrfName" value="<?= csrf_token() ?>">
                    <input type="hidden" id="csrfHash" value="<?= csrf_hash() ?>">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" placeholder="Your Name" required />
                        </div>

                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" placeholder="Your Email" required />
                        </div>

                        <div class="col-md-12">
                            <input type="text" class="form-control" name="subject" placeholder="Subject" required />
                        </div>

                        <div class="col-md-12">
                            <textarea class="form-control" name="message" rows="6" placeholder="Message" required></textarea>
                        </div>

                        <div class="col-md-12 text-center">
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">
                                Your message has been sent. Thank you!
                            </div>

                            <button id="contact_submit" type="submit">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- End Contact Section -->