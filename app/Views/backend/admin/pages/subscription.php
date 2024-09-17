<!-- Main Content -->

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <input type="hidden" value="" id="months">
            <h1><?= labels('subscription', 'Subscriptions') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/subscriptions') ?>">Subscriptions</a></div>
            </div>
        </div>
        <div class="container-fluid card rounded py-3">
            <h2 class='section-title'><?= labels('add_subscription', 'Add Subscription') ?></h2>
            <form name='add_subscription' id='ASForm' action="<?= base_url('admin/subscriptions/add-subscription') ?>" method='post'>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='userIdentity'><?= labels('subscription', 'Subscriptions') ?></label>
                            <select name='userIdentity' id="userIdentity" class='selectric form-control' onchange="set_name()">
                                <?php
                                foreach ($users as $user) {
                                ?>
                                    <option value='<?= $user['id'] ?>'><?= $user['username'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='userName'><?= labels('users_full_name', "User's Full Name") ?></label>
                            <input class="form-control" name='userName' id='userName' readonly />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='plan'><?= labels('plan_name', 'Select Plan') ?></label>
                            <select name='plan' id="plan" onchange="get_plan_data()" class='selectric form-control'>
                                <?php
                                foreach ($plans as $plan) {
                                ?>
                                    <option value='<?= $plan['id'] ?>'><?= $plan['title'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planType'><?= labels('plan_type', 'Plan Type') ?></label>
                            <input type='text' name='planType' id='planType' class='form-control' value="null" readonly />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planTenure'><?= labels('tenure', 'select Plan Tenure') ?></label>
                            <select name='planTenure' id='planTenure' class='selectric form-control' onchange="get_price()">

                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='price'><?= labels('price', 'Price') ?></label>
                            <input class="form-control" name='price' id='price' value="0.00" readonly>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='discountPrice'><?= labels('discounted_price', 'Discount Price') ?></label>
                            <input class="form-control" name='discountPrice' id='discountPrice' value="0.00" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='subscriptionStartfrom'><?= labels('start_date', 'Start Date') ?></label>
                            <input type='date' class="form-control" value="<?= date('Y-m-d') ?>" name='subscriptionStartfrom' onchange="handler(event);" id='starts_from'>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='subscriptionEndAt'><?= labels('end_date', 'End Date') ?></label>
                            <input type='date' class="form-control" name='subscriptionEndAt' id='ends_from' readonly>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md">
                    <button id='add' class='btn btn-block btn-success'><?= labels('add_subscription', 'Add Subscription') ?></button>
                </div>
                <div class="col-md">
                    <button id='cancel' class='btn btn-block btn-danger'><?= labels('reset', 'Reset') ?></button>
                </div>
            </div>
        </div>
        <div class="container-fluid card">
            <h2 class='section-title'><?= labels('subscription', 'Subscriptions') ?></h2>

            <div class="row">
                <div class="col-md">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date"><?= labels('filter_date_by', "Filter Date By") ?></label>
                                    <select name="date_filter_by" id="date_filter_by" class="form-control selectric">
                                        <option value=""><?= labels('all', 'All') ?></option>
                                        <option value="starts_from"><?= labels('start_date', "Start Date") ?></option>
                                        <option value="expires_on"><?= labels('end_date', "End Date") ?></option>
                                        <option value="created_on"><?= labels('purchase_date', "Purchase Date") ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date"><?= labels('date_range_filter', "Date Range Filter") ?></label>
                                    <input type="text" name="date_range" id="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date"><?= labels('filter_by_status', "Filter by status") ?></label>
                                    <select name="subscription_type" class="form-control selectric" id="subscription_type">
                                        <option value=""><?= labels('all', 'All') ?></option>
                                        <option value="active"><?= labels('active', "Active") ?></option>
                                        <option value="expired"><?= labels("expired", "Expired") ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for=""><?= labels('apply_filters', "Apply filters") ?></label>
                                    <button class="btn btn-primary d-block" onclick="refresh_table('subscription_table')">
                                        <?= labels('apply', "Apply") ?>
                                    </button>
                                </div>
                            </div>

                        </div>
                        <table class="table table-striped" id="subscription_table" data-detail-view="true" data-detail-formatter="detailFormatter" data-toggle="table" data-url="<?= base_url("user/subscriptions/table") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="subscriptions_query">
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true" data-visible="true"><?= labels('id', 'ID') ?></th>
                                    <th data-field="profile"><?= labels('users', 'Users') ?></th>
                                    <th data-field="plan_title"><?= labels('plan_name', 'Plan Name') ?></th>
                                    <th data-field="type"><?= labels('plan_type', 'Plan Type') ?></th>
                                    <th data-field="price" data-sortable="true" data-visible="false"><?= labels('price', 'Price') ?></th>
                                    <th data-field="txn-id" data-visible="false"><?= labels('transaction_id', 'Transaction ID') ?></th>
                                    <th data-field="status" data-visible="true"><?= labels('status', 'Status') ?></th>
                                    <th data-field="payment_method" data-visible="true"><?= labels('payment_method', 'Payment method') ?></th>
                                    <th data-field="characters" data-sortable="true" data-visible="false"><?= labels('total_characters', 'Total Characters') ?></th>
                                    <th data-field="remaining_characters" data-sortable="true" data-visible="false"><?= labels('remaining_characters', 'Total Remaining Characters') ?></th>
                                    <th data-field="google" data-sortable="true" data-visible="false">GCP <?= labels('total_characters', 'Total Characters') ?></th>
                                    <th data-field="remaining_google" data-sortable="true" data-visible="false">GCP <?= labels('remaining_characters', 'Remaining Characters') ?></th>
                                    <th data-field="aws" data-sortable="true" data-visible="false">Amazon Polly <?= labels('total_characters', 'Total Characters') ?></th>
                                    <th data-field="remaining_aws" data-sortable="true" data-visible="false">Amazon Polly <?= labels('remaining_characters', 'Remaining Characters') ?></th>
                                    <th data-field="ibm" data-sortable="true" data-visible="false">IBM Whatson <?= labels('total_characters', 'Total Characters') ?></th>
                                    <th data-field="remaining_ibm" data-sortable="true" data-visible="false">IBM Whatson <?= labels('remaining_characters', 'Remaining Characters') ?></th>
                                    <th data-field="azure" data-sortable="true" data-visible="false">MS Azure <?= labels('total_characters', 'Total Characters') ?></th>
                                    <th data-field="remaining_azure" data-sortable="true" data-visible="false">MS Azure <?= labels('remaining_characters', 'Remaining Characters') ?></th>
                                    <th data-field="active_subscription" data-visible="true"><?= labels('active_subscription', 'Active Subscription') ?></th>
                                    <th data-field="starts_from" data-visible="false"><?= labels('start_date', ' Subscription Start Date') ?></th>
                                    <th data-field="expires_on" data-visible="false"><?= labels('end_date', ' Subscription End Date') ?></th>
                                    <th data-field="tenure" data-visible="true"><?= labels('tenure', ' Subscription tenure') ?></th>

                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="reciept_list_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reciepts uploaded by User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="subscription_id" />
                <table class="table table-striped" id="bank_transfer" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("admin/bank_transfers/table") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="bank_transfer_params">
                    <thead>
                        <tr>
                            <th data-field="name" data-sortable="true" data-visible="true"><?= labels("Name", 'Name') ?></th>
                            <th data-field="plan_type" data-sortable="true" data-visible="true"><?= labels('type', "Type") ?></th>
                            <th data-field="plan_title" data-sortable="true" data-visible="true"><?= labels('plan', "Plan") ?></th>
                            <th data-field="price" data-sortable="true" data-visible="true"><?= labels('price', "Price") ?></th>
                            <th data-field="attachments_img" data-sortable="false" data-visible="true"><?= labels('reciepts', 'Reciepts') ?></th>
                            <th data-field="created_at" data-sortable="true" data-visible="true"><?= labels('date_of_upload', "Date of Upload") ?></th>
                            <th data-field="active_subscription" data-sortable="true" data-visible="false"><?= labels('active_subscription', "Active Subscription") ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
