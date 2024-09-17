<!-- Start Breadcrumbs -->
<section class="breadcrumbs">
    <div class="container">
        <ol class='floatc-right'>
            <li><a href="<?= base_url() ?>">Payment</a></li>
            <li> <?= $status ? "Success" : "Failed" ?></li>
        </ol>
    </div>
</section>
<!-- End Breadcrumbs -->

<section class="container" data-aos='fade-up'>
    <div class="container" data-aos="fade-up">
        <header class="section-header">
            <?php
            if ($status) {
            ?>
                <lottie-player class="player h-150" src="<?= base_url('public/frontend/retro/success.json') ?>" background="transparent" speed="1" autoplay></lottie-player>
            <?php
            } else {
            ?>
                <lottie-player class="player h-150" src="<?= base_url('public/frontend/retro/failed.json') ?>" background="transparent" speed="1" autoplay></lottie-player>

            <?php } ?>
            <p><?= $status ? "Payment success" : "Payment Failed" ?> </p>
        </header>
    </div>
    <div class="container">
        <a href='<?= base_url("auth") ?>'>Go BACK</a>
    </div>
</section>