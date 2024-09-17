		<script src="<?= base_url('public/backend/assets/js/vendor/popper.min.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/vendor/bootstrap.min.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/vendor/jquery.nicescroll.min.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/vendor/moment.min.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/stisla.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/vendor/iziToast.min.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/vendor/bootstrap-table.min.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/vendor/select2.min.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/vendor/iconify.min.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/vendor/cropper.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('public/backend/assets/js/vendor/star-rating.min.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('public/backend/assets/js/vendor/theme.min.js') ?>"></script>
		<script src="https://js.stripe.com/v3/"></script>

		<script src="<?= base_url('public/backend/assets/js/vendor/daterangepicker.js') ?>"></script>

		<script src="<?= base_url('public/backend/assets/js/vendor/sweetalert.js') ?>"></script>

		<?php
        echo '<script src="' . base_url('public/backend/assets/js/vendor/chart.min.js') . '"></script>';

        ?>
		<?= '<script src="' . base_url('public/backend/assets/js/page/user_pan.js') . '"></script>'; ?>

		<?php
        switch ($main_page) {
            case "../../text_to_speech":
                echo '<script src="' . base_url('public/backend/assets/js/page/tts.js') . '"></script>';
                break;

            case "checkout":
                echo '<script src="https://checkout.razorpay.com/v1/checkout-frame.js"></script>';
                echo '<script src="' . base_url('public/backend/assets/js/vendor/paystack-v1.js') . '"></script>';
                echo '<script src="' . base_url('public/backend/assets/js/page/checkout.js') . '"></script>';
                echo `<script src="https://js.stripe.com/v3/"></script>`;
                echo `<script src="https://js.paystack.co/v1/inline.js"></script>`;
                break;

            case "plans":
                echo '<script src="' . base_url('public/backend/assets/js/page/admin_plans.js') . '"></script>';
                break;

            case "send_review":
                echo '<script src="' . base_url('public/backend/assets/js/reviews.js') . '"></script>';
                break;
        }
        ?>

		<script src="<?= base_url('public/backend/assets/js/scripts.js') ?>"></script>
		<script src="<?= base_url('public/backend/assets/js/custom.js') ?>"></script>