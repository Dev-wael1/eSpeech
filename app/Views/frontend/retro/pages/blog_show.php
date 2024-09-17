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

        <div class="row gy-4" data-aos="fade-left">
            <?php
            foreach ($blogs as $key => $value) { ?>


                <section id="blog-wrapper">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <h5 class="fs-4 fw-bolder text-center"><?= $value['title'] ?></h5><br>
                            <div class="col-md-9 col-sm-9">
                                <div class="blog">
                                    <img src=" <?= base_url($value['image']) ?>" alt="Blog Image" class="show-image">
                                </div><br>

                                <div class="fs-12">
                                    <div>
                                        <?= $value['description'] ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </section>
            <?php } ?>
        </div>
    </div>
</section>