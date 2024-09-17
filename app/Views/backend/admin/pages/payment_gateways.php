<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('payment_gateway', "Payment Gateways") ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Settings</a></div>
                <div class="breadcrumb-item">Payment Gateways Settings</div>
            </div>
        </div>
        <form method="POST" action="<?= base_url('admin/settings/pg-settings') ?>">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="container-fluid card pt-3">
                <h2 class='section-title'>RazorPay</h2>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='razorpayApiStatus'>Status</label>
                            <select class='form-control selectric' name='razorpayApiStatus' id='razorpay_status'>
                                <option value='enable' <?= isset($razorpayApiStatus) && $razorpayApiStatus === 'enable' ? 'selected' : '' ?>>Enable</option>
                                <option value='disable' <?= isset($razorpayApiStatus) && $razorpayApiStatus === 'disable' ? 'selected' : '' ?>>Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="razorpayMode">Mode</label>
                            <select class='form-control selectric' name='razorpay_mode' id='razorpay_mode'>
                                <option value='test' <?= isset($razorpay_mode) && $razorpay_mode === 'test' ? 'selected' : '' ?>>Test</option>
                                <option value='live' <?= isset($razorpay_mode) && $razorpay_mode === 'live' ? 'selected' : '' ?>>Live</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="razorpayMode">Currency Code</label>
                            <input type="text" value="<?= isset($razorpay_currency) ? $razorpay_currency : '' ?>" name='razorpay_currency' id='razorpay_currency' placeholder='Enter Razorpay currency' class="form-control" />
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="razorpay_secret">Secret Key</label>
                            <input type="text" value="<?= isset($razorpay_secret) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $razorpay_secret) : '' ?>" name='razorpay_secret' id='razorpay_secret' placeholder='Enter Razor Pay secret key' class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="razorpay_key">API Key</label>
                            <input type="text" value="<?= isset($razorpay_key) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $razorpay_key) : '' ?>" name='razorpay_key' id='razorpay_key' placeholder='Enter Razor Pay API key' class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for='razorpay_webhook_url'>Webhook URL <small>(Set this as webhook URL in you Razorpay account)</small></label>

                        <input type="text" class="form-control" value="<?= base_url('api/webhooks/razorpay') ?>" name='razorpay_webhook_url' id='razorpay_webhook_url' placeholder='Enter Webhook URL Here' readonly />
                    </div>
                    <div class="col-sm-6">
                        <label for='razorpay_webhook_secret'>Webhook Secret <small>(Secret key you provided while adding webhook)</small>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#secret_key_modal"><i class="fa fa-question-circle" aria-hidden="true"></i></a> </label>
                        <input type="text" class="form-control" value="<?= $razorpay_webhook_secret = isset($razorpay_webhook_secret) ? $razorpay_webhook_secret : ''  ?>" name='razorpay_webhook_secret' id='razorpay_webhook_secret' placeholder='Enter Webhook Secret here' />
                    </div>
                </div>

                <h2 class='section-title'>Paystack</h2>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='razorpayApiStatus'>Status</label>
                            <select class='form-control selectric' name='paystack_status' id='paystack_status'>
                                <option value='enable' <?= isset($paystack_status) && $paystack_status === 'enable' ? 'selected' : '' ?>>Enable</option>
                                <option value='disable' <?= isset($paystack_status) && $paystack_status === 'disable' ? 'selected' : '' ?>>Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="paystack_mode">Mode</label>
                            <select class='form-control selectric' name='paystack_mode' id='paystack_mode'>
                                <option value='test' <?= isset($paystack_mode) && $paystack_mode === 'test' ? 'selected' : '' ?>>Test</option>
                                <option value='live' <?= isset($paystack_mode) && $paystack_mode === 'live' ? 'selected' : '' ?>>Live</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="razorpayMode">Currency Code</label>
                            <input type="text" value="<?= isset($paystack_currency) ? $paystack_currency : '' ?>" name='paystack_currency' id='paystack_currency' placeholder='Enter Paystack currency' class="form-control" />
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="paystack_secret">Secret Key</label>
                            <input type="text" value="<?= isset($paystack_secret) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $paystack_secret) : '' ?>" name='paystack_secret' id='paystack_secret' placeholder='Enter Razor Pay secret key' class="form-control" />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="paystack_key">API Key</label>
                            <input type="text" value="<?= isset($paystack_key) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $paystack_key) : '' ?>" name='paystack_key' id='paystack_key' placeholder='Enter Razor Pay API key' class="form-control" />
                        </div>
                    </div>
                </div>

                <!-- Bank Details here -->
                <div class="container-fluid card pt-3">
                    <h2 class='section-title'>Bank</h2>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label for='bank_status'>Status</label>
                                <select class='form-control selectric' name='bank_status' id='bank_status'>
                                    <option value='disable' <?= isset($bank_status) && $bank_status === 'disable' ? 'selected' : '' ?>>Disable</option>
                                    <option value='enable' <?= isset($bank_status) && $bank_status === 'enable' ? 'selected' : '' ?>>Enable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label for="bank_instruction">Bank Instructions</label>
                                <textarea rows=30 class='form-control  summernotes' name="bank_instruction"><?= isset($bank_instruction) ? $bank_instruction : 'Kindly Add instructions for bank transactions' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label for="Account Details">Account Details</label>
                                <textarea rows=30 class='form-control h-50 summernotes' name="account_details"><?= isset($account_details) ? $account_details : 'Kindly Add your Account Details' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label for="Account Details">Extra Details</label>
                                <textarea rows=30 class='form-control h-50 summernotes' name="extra_details">
                                    <?= isset($extra_details) ? $extra_details : 'Kindly add extra details like notes or anything' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paytm starts here -->

                <h2 class='section-title'>Paytm</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for='paytm_status'>Status</label>
                            <select class='form-control selectric' name='paytm_status' id='paytm_status'>
                                <option value='disable' <?= isset($paytm_status) && $paytm_status === 'disable' ? 'selected' : '' ?>>Disable</option>
                                <option value='enable' <?= isset($paytm_status) && $paytm_status === 'enable' ? 'selected' : '' ?>>Enable</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Paytm Mode <small>[ sandbox / live ]</small>
                        </label>
                        <label for="paytm_mode">Mode</label>
                        <select class='form-control selectric' name='paytm_mode' id='paytm_mode'>
                            <option value='test' <?= isset($paytm_mode) && $paytm_mode === 'test' ? 'selected' : '' ?>>Test</option>
                            <option value='live' <?= isset($paytm_mode) && $paytm_mode === 'live' ? 'selected' : '' ?>>Live</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="paytm_merchant_id">Paytm Merchant ID </label>

                        <input type="text" class="form-control" value="<?= isset($paytm_merchant_id) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $paytm_merchant_id) : '' ?>" name='paytm_merchant_id' id='paytm_merchant_id' placeholder='Enter paytm Merchant ID' />
                    </div>
                    <div class="col-md-6">
                        <label for="paytm_merchant_key">Paytm Merchant Key </label>

                        <input type="text" class="form-control" value="<?= isset($paytm_merchant_key) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $paytm_merchant_key) : '' ?>" name='paytm_merchant_key' id='paytm_merchant_key' placeholder='Enter paytm Merchant key' />
                    </div>
                </div>
                <div class="row">
                    <?php if (isset($paytm_mode) && $paytm_mode == 'live') :  ?>
                        <div class="form-group col-md-6">
                            <label for="paytm_website">Paytm Website <small>[<a href="https://dashboard.paytm.com/next/apikeys?src=dev" target="_blank">click here</a> to know]</small></label>
                            <input type="text" class="form-control" name="paytm_website" value="<?= isset($paytm_website) ? $paytm_website : '' ?>" placeholder="Paytm Website" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="paytm_industry_type_id">Industry Type ID <small>[<a href="https://dashboard.paytm.com/next/apikeys?src=dev" target="_blank">click here</a> to know]</small></label>
                            <input type="text" class="form-control" name="paytm_industry_type_id" value="<?= isset($paytm_industry_type_id) ? $paytm_industry_type_id : '' ?>" placeholder="Industry Type ID" />
                        </div>
                    <?php else : ?>
                        <div class="form-group col-md-6">
                            <label for="paytm_website">Paytm Website <small>[<a href="https://dashboard.paytm.com/next/apikeys?src=dev" target="_blank">click here</a> to know]</small></label>
                            <input type="text" class="form-control" name="paytm_website" placeholder="Paytm Website" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="paytm_industry_type_id">Industry Type ID <small>[<a href="https://dashboard.paytm.com/next/apikeys?src=dev" target="_blank">click here</a> to know]</small></label>
                            <input type="text" class="form-control" name="paytm_industry_type_id" placeholder="Industry Type ID" />
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Paytm ends here -->

                <!-- Paypal Starts Here -->

                <h2 class='section-title'>Paypal</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for='paypal_status'>Status</label>
                            <select class='form-control selectric' name='paypal_status' id='paypal_status'>
                                <option value='disable' <?= isset($paypal_status) && $paypal_status === 'disable' ? 'selected' : '' ?>>Disable</option>
                                <option value='enable' <?= isset($paypal_status) && $paypal_status === 'enable' ? 'selected' : '' ?>>Enable</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="paypal_mode">Paypal Mode <small>[ sandbox / live ]</small>
                        </label>

                        <select class='form-control selectric' name='paypal_mode' id='paypal_mode'>
                            <option value='test' <?= isset($paypal_mode) && $paypal_mode === 'test' ? 'selected' : '' ?>>Send Box <small>(testing)</small></option>
                            <option value='live' <?= isset($paypal_mode) && $paypal_mode === 'live' ? 'selected' : '' ?>>Production <small>(Live)</small></option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for='paypal_client_id'>Client Id <small>[FOR transaction purpose]</small></label>

                            <input type="text" class="form-control" value="<?= isset($paypal_client_id) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $paypal_client_id) : '' ?>" name='paypal_client_id' id='paypal_client_id' placeholder='Enter Client Id HERE' />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for='paypal_client_secret'>Secret key<small>[FOR transaction purpose]</small></label>

                            <input type="text" class="form-control" value="<?= isset($paypal_client_secret) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $paypal_client_secret) : '' ?>" name='paypal_client_secret' id='paypal_client_secret' placeholder='Enter Client Secret HERE' />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for='webhook_url'>Webhook URL <small>(Set this as webhook URL in you PayPal account)</small></label>

                            <input type="text" class="form-control" value="<?= base_url('api/v1/paypal_notification') ?>" name='webhook_url' id='webhook_url' placeholder='Enter Webhook URL Here' readonly />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for='webhook_id'>Webhook ID <small>(get from place where you set dash board)</small></label>

                            <input type="text" class="form-control" value="<?= isset($webhook_id) ?  $webhook_id : '' ?>" name='webhook_id' id='webhook_id' placeholder='Enter Webhook ID Here' />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="business_email">Business Email</label>
                            <input id="business_email" class="form-control" type="text" name="business_email" value="<?= isset($business_email) ?  $business_email : '' ?>">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="end_point_url">End Point URL <small>[Link Will change according to the mode of Payment]</small></label>
                            <input id="end_point_url" class="form-control" type="text" name="end_point_url" value="https://api-m.sandbox.paypal.com/" readonly>
                        </div>
                    </div>
                </div>
                <!-- Paypal Ends Here -->

                <div class="container-fluid card pt-3">
                    <h2 class='section-title'>Stripe</h2>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label for='stripe_status'>Status</label>
                                <select class='form-control selectric' name='stripe_status' id='stripe_status'>
                                    <option value='enable' <?= isset($stripe_status) && $stripe_status === 'enable' ? 'selected' : '' ?>>Enable</option>
                                    <option value='disable' <?= isset($stripe_status) && $stripe_status === 'disable' ? 'selected' : '' ?>>Disable</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <label for="razorpayMode">Mode</label>
                                <select class='form-control selectric' name='stripe_mode' id='stripe_mode'>
                                    <option value='test' <?= isset($stripe_mode) && $stripe_mode === 'test' ? 'selected' : '' ?>>Test</option>
                                    <option value='live' <?= isset($stripe_mode) && $stripe_mode === 'live' ? 'selected' : '' ?>>Live</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <label for="razorpayMode">Currency Code</label>
                                <input type="text" value="<?= isset($stripe_currency) ? $stripe_currency : '' ?>" name='stripe_currency' id='stripe_currency' placeholder='Enter stripe currency' class="form-control" />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label for="publishable_key">Stripe Publishable key</label>
                                <input type="text" value="<?= isset($stripe_publishable_key) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $stripe_publishable_key) : '' ?>" name='stripe_publishable_key' id='stripe_publishable_key' placeholder='Enter Stripe Publishable key' class="form-control" />
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group">
                                <label for="publishable_key">Stripe Webhook secret</label>
                                <input type="text" value="<?= isset($stripe_webhook_secret_key) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $stripe_webhook_secret_key) : '' ?>" name='stripe_webhook_secret_key' id='stripe_webhook_secret_key' placeholder='Enter Stripe Publishable key' class="form-control" />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="razorpaySecretKey">Stripe Secret key</label>
                                <input type="text" value="<?= isset($stripe_secret_key) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $stripe_secret_key) : '' ?>" name='stripe_secret_key' id='stripe_secret_key' placeholder='Enter Stripe secret key' class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for='webhook_url'>Webhook URL <small>(Set this as webhook URL in you PayPal account)</small></label>

                            <input type="text" class="form-control" value="<?= base_url('api/webhooks/stripe') ?>" name='webhook_url' id='webhook_url' placeholder='Enter Webhook URL Here' readonly />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <input type='submit' name='update' id='update' value='Update' class='btn btn-success' />
                                <input type='reset' name='clear' id='clear' value='Clear' class='btn btn-danger' />
                            </div>
                        </div>
                    </div>

                </div>
        </form>
    </section>
    <div class="modal fade" id="secret_key_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">How To?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <img src="<?= base_url('/public/uploads/site/secmo.png') ?>" alt=""> -->
                    <a href="<?= base_url('/public/uploads/site/secmo.png') ?>" data-lightbox="image-1">
                        <img height="50%" class="rounded" src="<?= base_url('/public/uploads/site/secmo.png') ?>" alt="this is where images are supposed to be">
                    </a>
                    <ul>
                        <li>
                            <p>Use Key You Inserted in <span class="text-danger">Above's Highlighted</span> Part</p>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>