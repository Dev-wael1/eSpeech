<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">
        <ol>
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Blogs</li>
        </ol>
    </div>
</section><!-- End Breadcrumbs -->

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

            if (count($blogs) > 0) {
                foreach ($blogs as $key => $value) {
                    if ($value['status'] == 1) {

            ?>
                        <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                            <div class="card">
                                <a class="blog-image" href="<?= base_url('/blogs/show/' . $value['slug']) ?>" style="background-image:url(<?= $value['image'] ?>)"></a>

                                <div class="content p-4">
                                    <h5 class="fw-bold text-lg font-medium"><?= $value['title'] ?></h5>
                                    <div class="card-text text-truncate"><?= $value['description'] ?></div>
                                    <a href="<?= base_url('/blogs/show/' . $value['slug']) ?>" class="card-link btn btn-primary">Read More</a>
                                </div>
                            </div>

                        </div>
                <?php $i++;
                    }
                }
            } else { ?>
                <div class="box" data-aos="fade-up" data-aos-delay="600">
                    <lottie-player src="<?= base_url('public/frontend/retro/img/lottieImages/empty.json') ?>" background="transparent" speed="1" style="height:400px" loop autoplay></lottie-player>
                    <h3>No Blogs Added Yet</h3>
                    <p>
                        After Some Time You Will Increase Your Knowledge with Our Blog
                    </p>
                </div>
            <?php } ?>
        </div>
    </div>
</section>