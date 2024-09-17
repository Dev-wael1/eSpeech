<?php

$img[0] = base_url('public/frontend/retro/img/lottieImages/turtle.json');
$img[1] = base_url('public/frontend/retro/img/lottieImages/bicycle.json');
$img[2] = base_url('public/frontend/retro/img/lottieImages/car.json');
$img[3] = base_url('public/frontend/retro/img/lottieImages/rocket.json');

?>
<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">
        <ol>
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Pricing</li>
        </ol>
    </div>
</section><!-- End Breadcrumbs -->

<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing">
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <p>Pricing</p>
            <h1>Check our Pricing</h1>
        </header>

        <div class="row gy-4" data-aos="fade-left">
            <input type='hidden' name="currency" id="currency" value="<?= $currency ?>" />
            <?php
            $i = 0;
            if (count($plans) > 0) {
                foreach ($plans as $key => $value) {
            ?>
                    <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                        <div class="box">
                            <?php
                            $flag = false;
                            if ($value['is_featured'] == '1') {
                                $flag = true;
                                $featured_text = "Featured";
                                if ($value['featured_text'] != "") {
                                    $featured_text = $value['featured_text'];
                                }
                            }
                            if ($flag) {
                            ?>
                                <span class="featured"><?= $featured_text ?></span>
                            <?php } ?>
                            <h3 class="text-primary"><?= $value['title'] ?></h3>
                            <div class="price d-inline-flex"><?= $currency ?>
                                <div id='price<?= $key ?>'>
                                    <?php foreach ($tenure as $key2 => $value2) {
                                        if ($value2['plan_id'] == $value['id']) {
                                    ?>
                                            <?php if ($value2['discounted_price'] != '' && $value2['discounted_price'] > 0) : ?>
                                                <?= number_format($value2['discounted_price']) ?>
                                            <?php else : ?>
                                                <?= number_format($value2['price']) ?>
                                            <?php endif; ?>
                                            <?php if ($value2['discounted_price'] > 0) : ?>
                                                <?= "<h6> <strike> " . $currency . ' &nbsp;' .
                                                    number_format($value2['price']) . " </strike> </h6>"  ?>
                                            <?php endif; ?>
                                    <?php
                                            break;
                                        }
                                    } ?>
                                </div>

                            </div>
                            <div class="container">
                                <div class="form-group">
                                    <select class="form-control selectric" id='plan<?= $key ?>' onchange="display_discounted_price(<?= $key ?>);">

                                        <?php foreach ($tenure as $key2 => $value2) {
                                            if ($value2['plan_id'] == $value['id']) {
                                        ?>
                                                <option data-price="<?= number_format($value2['price']) ?>" value='<?= $value2['id'] ?>' data-discount="<?= number_format($value2['discounted_price']) ?>">
                                                    <?= $value2['title'] ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <lottie-player src="<?= $value['lottie'] ?>" background="transparent" class="tenem" speed="1" loop autoplay></lottie-player>
                            <ul>

                                <li class="<?= $value['no_of_characters'] > 0 ? '' : 'na' ?>">
                                    <?= $value['no_of_characters'] ?> Overall Characters
                                </li>
                                <li class="<?= $value['google'] > 0 ? '' : 'na' ?>">
                                    <?= $value['google'] ?> Google Clould Plateform Characters
                                </li>
                                <li class="<?= $value['aws'] > 0 ? '' : 'na' ?>">
                                    <?= $value['aws'] ?> Amazon Polly Characters
                                </li>
                                <li class="<?= $value['ibm'] > 0 ? '' : 'na' ?>">
                                    <?= $value['ibm'] ?> IBM Whatson Characters
                                </li>
                                <li class="<?= $value['azure'] > 0 ? '' : 'na' ?>">
                                    <?= $value['azure'] ?> Microsoft Azure Characters
                                </li>

                            </ul>

                            <a href="<?= $logged ? base_url('user/plans') : base_url('auth') ?>" class="btn-buy">Buy Now</a>

                        </div>
                    </div>
                <?php $i++;
                }
            } else { ?>
                <div class="box" data-aos="fade-up" data-aos-delay="600">
                    <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/empty2.json') ?>" background="transparent" speed="1" style="height:300px" loop autoplay></lottie-player>
                    <h3>Verity of options not available yet</h3>
                    <p>
                        You will get Surprise Plan After Some Time
                    </p>
                </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="text-center mt-5">
                <a href="<?= base_url('pricing') ?>" class="view-more-plans">View more</a>
            </div>
        </div>
    </div>
</section>