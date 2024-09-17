<!-- Main Content -->
<div class="main-content">
    <section class="section">

        <div class="container-fluid card">
            <div class="row">
                <div class="col-md-6">
                    <h2 class='section-title'> <?= labels('checkout', "Checkout") ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md offset-3">
                    <div class="col-md-8">


                        <input type="hidden" id="plan_id" value="<?= $plan['id'] ?>">
                        <input type="hidden" id="logo" value="<?= $logo ?>">
                        <input type="hidden" id="price" value="<?= $tenure['price'] - $tenure['discounted_price'] ?>">
                        <input type="hidden" id="tenure_id" value="<?= $tenure['id'] ?>">
                        <?php if ($razorpay) { ?>
                            <input type="hidden" id="razorpay_payment_id" value>
                            <input type="hidden" id="razorpay_key_id" value='<?= $razorpay_key ?>'>
                            <input type="hidden" id="razorpay_signature" value>
                        <?php } ?>
                        <?php if ($paystack) { ?>
                            <input type="hidden" id="paystack_reference" value>
                            <input type="hidden" id="paystack_key_id" value='<?= $paystack_key ?>'>
                        <?php } ?>

                        <?php if ($stripe) { ?>
                            <input type="hidden" id="stripe_key" value='<?= $stripe_key ?>'>
                        <?php } ?>
                        <?php if (isset($paytm)) : ?>
                            <input type="hidden" id="paytm_transaction_token" name="paytm_transaction_token" value="" />
                            <input type="hidden" id="paytm_order_id" name="paytm_order_id" value="" />
                        <?php endif; ?>
                        <?php if (isset($paypal)) : ?>
                            <?php $final_price =  $tenure['price'] - $tenure['discounted_price']  ?>
                            <input type="hidden" id="paypal_transaction_token" name="paypal_transaction_token" value="" />
                            <input type="hidden" id="paypal_order_id" name="paypal_order_id" value="" />
                            <input type="hidden" id="amount" name="amount" value="<?= $final_price ?>" />
                        <?php endif; ?>
                        <?php if (isset($app_name)) : ?>
                            <input type="hidden" name="app_name" id="app_name" value="<?= $app_name ?>">
                        <?php endif; ?>
                        <?php if (isset($support_name)) : ?>
                            <input type="hidden" name="support_name" id="support_name" value="<?= $support_name ?>">
                        <?php endif; ?>
                        <input type="hidden" id="razorpay_currency" value='<?= $razorpay_currency ?>'>
                        <input type="hidden" id="paystack_currency" value='<?= $paystack_currency ?>'>
                        <input type="hidden" id="stripe_client_secret" value>
                        <input type="hidden" id="razorpay_order_id" value>
                        <input type="hidden" id="app_name" value="espeech">
                        <div class="pricing pricing-highlight shadow">
                            <div class="pricing-title">
                                <?= $plan['title'] ?>
                            </div>
                            <div class="pricing-padding">
                                <div class="pricing-price">
                                    <div>

                                        <?php if ($tenure['discounted_price'] != '' && $tenure['discounted_price'] > 0) : ?>
                                            <span><?= $currency ?> <?= number_format($tenure['discounted_price']) ?></span>
                                            <?= "<h6> <strike> " . $currency . ' &nbsp;' .
                                                number_format($tenure['price']) . " </strike> </h6>"  ?>
                                        <?php else : ?>
                                            <span><?= $currency ?> <?= number_format($tenure['price']) ?></span>
                                        <?php endif; ?>
                                        
                                    </div>
                                    <div class="col-md-4 offset-md-4">
                                        <p class="h6"><?= $tenure['title'] ?></p>
                                    </div>
                                </div>
                                <div class="pricing-details">
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $plan['no_of_characters'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $plan['no_of_characters'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= $plan['no_of_characters'] ?></b> <?= labels('total_characters', "Total Characters") ?></div>
                                    </div>
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $plan['google'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $plan['google'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= $plan['google'] ?></b> Google Clould Plateform <?= labels('characters', "Characters") ?></div>
                                    </div>
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $plan['aws'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $plan['aws'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= $plan['aws'] ?></b> Amazon Polly <?= labels('characters', "Characters") ?></div>
                                    </div>
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $plan['ibm'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $plan['ibm'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= $plan['ibm'] ?></b> IBM Whatson <?= labels('characters', "Characters") ?></div>
                                    </div>
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $plan['azure'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $plan['azure'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= $plan['azure'] ?></b> Microsoft Azure <?= labels('characters', "Characters") ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <?= labels('select_payment_type', "Select Payment Type") ?> :-
                            </div>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <?php
                                if ($razorpay) {
                                ?>
                                    <label class="btn btn-primary">
                                        <input type="radio" id="razorpay" value="razorpay" name="payment_type" value="razorpay" aria-label="Radio button for following text input"> Razorpay
                                    </label>
                                <?php } ?>
                                <?php
                                if ($paystack) {
                                ?>
                                    <label class="btn btn-primary">
                                        <input type="radio" id="paystack" value="paystack" name="payment_type" value="paystack" aria-label="Radio button for following text input"> Paystack
                                    </label>
                                <?php }
                                if ($stripe) { ?>

                                    <label class="btn btn-primary">
                                        <input type="radio" name="payment_type" value="stripe" id="stripe"> Stripe
                                    </label>
                                <?php } ?>

                                <!-- EDITED -->
                                <?php if ($bank) : ?>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="payment_type" value="bank" id="bank"> Bank
                                    </label>
                                <?php endif; ?>
                                <!-- Paytm starts -->
                                <?php if ($paytm) : ?>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="payment_type" value="paytm" id="paytm"> Paytm
                                    </label>
                                <?php endif; ?>
                                <!-- paypal here -->
                                <?php if ($paypal) : ?>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="payment_type" value="paypal" id="paypal"> Paypal
                                    </label>
                                <?php endif; ?>
                            </div>
                            <div class="paypal_btn pt-4" id="paypal_btn"></div>

                            <div id="stripe_div" class="px-4 pt-4">
                                <div class="form-group">
                                    <div class="form-control">
                                        <div id="stripe-card">
                                            <!-- A Stripe Element will be inserted here. -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pricing-cta p-4">
                                <button class="btn btn-primary btn-block" id="subscribe"><?= labels('buy_now', "Buy Now") ?></a>
                            </div>
                        </div>

                        <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        <div class="loader">
                                            <div class="spinner-border text-light" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <div clas="loader-txt">
                                            <p>Please wait while we process your transaction. </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="" id="bank_modal" tabindex="-1" role="dialog" aria-labelledby="bank_label_modal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Bank Transfer</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <?php if (isset($bank_instruction)) : ?>
                                                <?= $bank_instruction ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="alert alert-primary">
                                            <?php if (isset($account_details)) : ?>
                                                <?= $account_details ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="alert alert-primary">
                                            <?php if (isset($extra_details)) : ?>
                                                <?= $extra_details ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="alert alert-warning">
                                            <ul>
                                                <li>
                                                    <p>
                                                        <strong>
                                                            you can upload bank recipt from Subscription Page
                                                        </strong>
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="offset-1"></div>
            </div>
        </div>
        <!-- Paytm script for URL and merchant_id -->
        <?php if (isset($paytm) && $paytm != "") {
            $url = ($paytm_mode == "live") ? "https://securegw.paytm.in/" : "https://securegw-stage.paytm.in/";
        ?>
            <script src="<?= $url ?>merchantpgpui/checkoutjs/merchants/<?= $paytm_merchant_id ?>.js">
                console.log($url);
            </script>
        <?php } ?>

        <!-- Paytm script for URL and merchant_id ENDS HERE-->

        <!-- Paypal script for client id -->
        <?php if (isset($paypal) && isset($paypal_client_id)) : ?>
            <script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id ?>" data-namespace="paypal_sdk"></script>
        <?php endif;  ?>
        <!-- Paypal script for client id ENDS HERE -->
    </section>
</div>