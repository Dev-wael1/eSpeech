<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">
        <ol>
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Reviews</li>
        </ol>
    </div>
</section><!-- End Breadcrumbs -->

<!-- ======= Review Section ======= -->
<section id="reviews" class="review">
    <div class="container main" data-aos="fade-up">
        <header class="section-header mb-5">
            <p>Customer Reviews</p>
            <h1>Our happy customers</h1>
        </header>

        <div class="row gy-4" data-aos="fade-down">
            <div class="row text-center">
                <?php
                if (count($reviews) > 0) {
                    $i = 0;
                    foreach ($reviews as $key => $value) {
                        if ($value['status'] == 1) {
                ?>
                            <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                                <div class="main card p-4 m-1 text-center">
                                    <div>
                                        <?php if ($value['user_image'] != null) { ?>
                                            <img src="<?= base_url($value['user_image']) ?>" alt="profile">
                                        <?php } else { ?>
                                            <img src="<?= base_url('public/backend/assets/profiles/default.png') ?>" alt="profile" style="height:50px; width:50px">
                                        <?php } ?>
                                    </div><br>
                                    <h5><?= $value['user_name'] ?></h5>
                                    <input id="input-xs" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="xs" data-show-clear="false" data-show-caption="false" value="<?= $value['rating_number'] ?>" readonly>
                                    <div class="mt-4">
                                        <p><?= $value['review'] ?></p><br>
                                    </div>
                                </div>
                            </div>
                    <?php $i++;
                        }
                    }
                } else { ?>
                    <div class="box" data-aos="fade-up" data-aos-delay="600">
                        <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/no-reviews.json') ?>" background="transparent" speed="1" style="height:400px" loop autoplay></lottie-player>
                        <h3>No reviews found</h3>
                        <p>
                            Comeback later to see what our precious customers are saying!
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>