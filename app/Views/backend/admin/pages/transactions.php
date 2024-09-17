<!-- Main Content -->
<div class="main-content">
    <section class="section">

        <div class="container-fluid card">
            <div class="card-head">

                <h2 class='section-title'><?= labels('transactions','Transactions') ?></h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 ">
                        <div class="form-group">
                            <label for="date"><?= labels('payment_method') ?></label>
                            <select name="payment_method" id="payment_method" class="form-control selectric">
                                <option value=""><?= labels('all',"All") ?></option>
                                <option value="Stripe">Stripe</option>
                                <option value="razorpay">Razorpay</option>
                                <option value="paystack">Paystack</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date"><?= labels('transaction_date',"Transaction Date") ?></label>
                            <input type="text" name="date_range" id="txn_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date"><?= labels('filter_by_status',"Filter by Status") ?></label>
                            <select name="subscription_type" class="form-control selectric" id="transaction_status">
                                <option value=""><?= labels('all','All') ?></option>
                                <option value="success"><?= labels('success',"Success") ?></option>
                                <option value="failed"><?= labels('failed',"Failed") ?></option>
                                <option value="pending"><?= labels('pending',"Pending") ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for=""><?= labels('apply_filters',"Apply Filters") ?></label>
                            <button class="btn btn-primary d-block" onclick="refresh_table('transaction_table')">
                                <?= labels('apply','Apply') ?>
                            </button>
                        </div>
                    </div>
                </div>
                <table class="table table-striped" id="transaction_table" data-detail-view="true" data-detail-formatter="transaction_table_formatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("user/transactions/table") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="txn_table">

                    <thead>
                        <tr>
                            <th data-field="profile"><?= labels('profile','Profile') ?></th>
                            <th data-field="plan_title"><?= labels('plan_name','Plan Name') ?></th>
                            <th data-field="payment_method"><?= labels('payment_method',"Payment Method") ?></th>
                            <th data-field="txn_id"><?= labels('transaction_id','Transaction ID') ?></th>
                            <th data-field="amount" data-sortable="true"><?= labels('amount','Amount') ?></th>
                            <th data-field="status"><?= labels('transaction_status','Transaction Status') ?></th>
                            <th data-field="created_on"><?= labels('created_on','Created on') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>