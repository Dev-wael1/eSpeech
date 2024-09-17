<!-- ======= Hero Section ======= -->
<section id="hero" class="hero d-flex align-items-center">
    <div class="container">


        <div class="row">
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <h1 data-aos="fade-up" id="home_title">
                    We offer modern solutions for your text to speech coversion.
                </h1>
                <h2 data-aos="fade-up" data-aos-delay="400" id="home_desc" oncontextmenu="contextmenu(this)">
                    Create realistic voices for any text in seconds by using over 1000+
                    realistic voices across 80+ languages.

                </h2>

                <div data-aos="fade-up" data-aos-delay="600">
                    <div class="text-center text-lg-start">
                        <a href="#tts" class="
                    btn-get-started
                    scrollto
                    d-inline-flex
                    align-items-center
                    justify-content-center
                    align-self-center
                  ">
                            <span>Explore</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
                <img src="<?= base_url('public/frontend/retro/img/hero-image.svg') ?>" class="img-fluid" alt="" />
            </div>
        </div>
</section>
<!-- End Hero -->
<!-- ======= Values Section ======= -->
<section id="tts" class="values hundred">
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <p>Text to speech</p>
            <h1>How is the service?</h1>
        </header>

        <div class="row">
            <div class="col-md-5">
                <img src="<?= base_url('public/frontend/retro/img/tts.png') ?>" class="img-fluid" alt="" />
            </div>
            <div class="col-md-7 mt-5">

                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Language</label>
                            <select name="" id="language" onchange="set_voices()" class="form-control selectric">
                                <option value="">Select a Language</option>
                                <?php foreach ($languages as $key => $value) { ?>
                                    <option data-image="<?php echo base_url($value['flag']); ?>" value="<?= $value['language_code'] ?>">
                                        <?= $value['language_name']  ?>
                                    </option>
                                <?php } ?>
                                <!-- <?//php foreach ($languages as $key => $val) { ?>
                                    <option data-image="<?//php echo base_url('public/flags') . "/" . strtolower(substr($key, strpos($key, '-') + 1, strlen($key))); ?>.svg" value="<?//= $key ?>">
                                        <?//= $val  ?>
                                    </option>
                                <?//php } ?> -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group ">
                            <label for="">Voices</label>
                            <select name="" id="voice" class="form-control selectric">
                                <option value="">Select a Voice</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control" id="text" name="message" rows="6" placeholder="Let your thoughts speak out loud." required=""></textarea>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="">
                            <label for="add_file" class="form-label"></label>
                            <input type="file" name="add_file" id="add_file" class="form-control" accept=".txt">
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="changer" id="changer" aria-checked="true" checked="true" style="width: 3rem; height: 1.5rem;">
                            <label class="form-check-label" for="changer" style="padding-top: 3px; margin-right: 5px;">
                                <p id="para" class="ml-10">
                                    Append
                                </p>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12 ">

                        <div class="row">
                            <div class="col-md mt-2">
                                <div class="form-group">
                                    <button id="get_tts" class="btn btn-primary form-control">
                                        <i class="fas fa-robot"></i> &nbsp; Synthesize
                                    </button>
                                </div>
                            </div>
                            <div class="col-md mt-2">
                                <div class="form-group">
                                    <button class="btn form-control btn-primary form-control" id="play-btn" onclick="play_pause()" disabled><i class="fas fa-play-circle"></i> Play </button>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<section id="values" class="values">
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <p>Our Values</p>
            <h1>Why eSpeech?</h1>
        </header>

        <div class="row">
            <div class="col-lg-4">
                <div class="box" data-aos="fade-up" data-aos-delay="200">
                    <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/value-for-money.json') ?>" background="transparent" speed="1" loop autoplay class="w-300-h-300"></lottie-player>
                    <h3>Value for money</h3>
                    <p>
                        Get the balance between the plan that fit your pocket and fulfills the requirements.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="box" data-aos="fade-up" data-aos-delay="400">
                    <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/future.json') ?>" background="transparent" speed="1" class="w-300-h-300" loop autoplay></lottie-player>
                    <h3>
                        Future enabled Technolgy
                    </h3>
                    <p>Generate realistic voiceovers for videos, podcasts, e-learning, etc. using our powerful online AI voice generator.</p>
                </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="box" data-aos="fade-up" data-aos-delay="600">
                    <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/setting.json') ?>" background="transparent" speed="1" class="w-300-h-300" loop autoplay></lottie-player>
                    <h3>Verity of options available</h3>
                    <p>
                        With variety of plans , choose the one which fits your need.
                    </p>
                </div>
            </div>
        </div>
    </div>


</section>
<!-- End Values Section -->

<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing ">
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

                    if ($i == 4) {
                        break;
                    }
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

                <script>
                    $('#pricing').hide();
                </script>
            <?php } ?>
        </div>
        <div class="row">
            <div class="text-center mt-5">
                <a href="<?= base_url('pricing') ?>" class="view-more-plans">View more</a>
            </div>
        </div>
    </div>
</section>

<?php if (count($blogs) > 0) { ?>
    <!-- ======= Blog Section ======= -->
    <section id="blog" class="blog">
        <div class="container" data-aos="fade-up">
            <header class="section-header">
                <p>Blogs</p>
                <h1>Check our Blogs</h1>
            </header>

            <div class="row gy-4 text-center" data-aos="fade-left">
                <?php
                $i = 0;
                foreach ($blogs as $blog) {
                    if ($i == 4) {
                        break;
                    }
                ?>
                    <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                        <div class="card">
                            <a class="blog-image" href="<?= base_url('/blogs/show/' . $blog['slug']) ?>" style="background-image:url(<?= $blog['image'] ?>)"></a>
                                <!-- <img src="<?//= base_url($blog['image']) ?>" alt="blog image" ></a> -->
                            <div class="content p-4">
                                <h5 class="fw-bold text-lg font-medium"><?= $blog['title'] ?></h5>
                                <div class="card-text text-truncate"><?= $blog['description'] ?></div>
                                <a href="<?= base_url('/blogs/show/' . $blog['slug']) ?>" class="card-link btn btn-primary">Read More</a>
                            </div>
                        </div>

                    </div>
                <?php $i++;
                } ?>
            </div>
            <div class="row">
                <div class="text-center mt-5">
                    <a href="<?= base_url('blogs') ?>" class="view-more-plans">View more</a>
                </div>
            </div>
        </div>
    </section>
<?php } ?>

<?php if (count($reviews) > 0) { ?>
    <!-- ======= Review Section ======= -->
    <section id="review" class="review">
        <div class="container main" data-aos="fade-up">
            <header class="section-header mb-5">
                <p>Customer Reviews</p>
                <h1>What Our happy customers Say</h1>
            </header>

            <div class="container">
                <div class="row gy-4 text-center" data-aos="fade-down">
                    <div class="row ">

                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner" id="carousel">
                                <?php
                                $i = 0;
                                foreach ($reviews as $review) {
                                    if ($i == 4) {
                                        break;
                                    }
                                ?>
                                    <div class="carousel-item">
                                        <div class="main">

                                            <?php if ($review['user_image'] != null) { ?>
                                                <img src="<?= base_url($review['user_image']) ?>" alt="profile">
                                            <?php } else { ?>
                                                <img src="<?= base_url('public/backend/assets/profiles/default.png') ?>" alt="profile" style="height:50px; width:50px">
                                            <?php } ?><br>
                                            <h5 class="text-dark"><?= $review['user_name'] ?></h5>
                                            <div class="star">
                                                <input id="input-xs" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-size="xs" data-show-clear="false" data-show-caption="false" value="<?= $review['rating_number'] ?>" readonly>
                                            </div>
                                            <div class="mt-4">
                                                <p><?= $review['review'] ?></p><br>
                                            </div>
                                        </div>
                                    </div>
                                <?php $i++;
                                } ?>

                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="text-center mt-5 me-3">
                    <a href="<?= base_url('review') ?>" class="view-more-plans">View more</a>
                </div>
            </div>
        </div>
    </section>
<?php } ?>

<?php
if ($app_settings['app_status'] == 'enable') {
?>
    <section class="p-1 mb-5">
        <div class="container  ">

            <div class="container-fluid mobile-app ">
                <div class="row gy-4">
                    <div class="col-md-6 vertical-center  d-flex justify-content-center">

                        <div class="mobile-app-wrapper text-center d-flex justify-content-end">
                            <img src="<?= base_url("public/frontend/retro/img/espeech-app-download.png") ?>" alt="download-espeech-application">

                        </div>

                    </div>
                    <div class="col-md-6 vertical-center">
                        <div class="text-area">
                            <h1 class="header-h1 text-second mt-3">
                                <?= (isset($app_settings) && $app_settings['app_heading'] != "") ? $app_settings['app_heading'] : "Espeech Heading" ?>
                            </h1>
                            <h3 class="text-first "> <?= (isset($app_settings) && $app_settings['app_sub_heading'] != "") ? $app_settings['app_sub_heading'] : "Espeech Sub Heading" ?></h3>
                            <h3 class="mt-3">Get the App</h3>
                            <div class="mt-3 d-flex ">
                                <?php
                                if (isset($app_settings) && $app_settings['ios_link'] != "") {
                                ?>
                                    <a href="<?= $app_settings['ios_link'] ?>" target="_blank"><img src="<?= base_url('public/frontend/retro/img/app-store.png') ?>" alt="" class="download_section" width="150"></a>
                                <?php } ?>
                                <?php
                                if (isset($app_settings) && $app_settings['android_link'] != "") {
                                ?>
                                    <a href="<?= $app_settings['android_link'] ?>" target="_blank"><img src="<?= base_url('public/frontend/retro/img/google-play-store.png') ?>" alt="" class="download_section ml-10" width="150"></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div> <!-- end of row -->
            </div>
        </div>
    </section>
<?php } ?>