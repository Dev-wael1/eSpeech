<div class="main-content">
    <section class="section">

        <div class="container-fluid card">
            <h2 class='section-title'><?= labels('subscription', "Subscriptions") ?></h2>

            <div class="row">
                <div class="col-md">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date"><?= labels('filter_date_by', "Filter date by") ?></label>
                                    <select name="date_filter_by" id="date_filter_by" class="form-control selectric">
                                        <option value=""><?= labels('all', 'All') ?></option>
                                        <option value="starts_from"><?= labels('start_date', 'Start date') ?></option>
                                        <option value="expires_on"><?= labels('send_date', 'End date') ?></option>
                                        <option value="created_on"><?= labels('purchase_date', 'Purchase Date') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date"><?= labels('date_range_filter', 'Date Range') ?></label>
                                    <input type="text" name="date_range" id="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date"><?= labels('filter_by_status', "Filter By Status") ?></label>
                                    <select name="subscription_type" class="form-control selectric" id="subscription_type">
                                        <option value=""><?= labels('all', "All") ?></option>
                                        <option value="active"><?= labels('active', 'Active') ?></option>
                                        <option value="expired"><?= labels('expired', 'Expired') ?></option>
                                        <option value="pending"><?= labels('pending', 'Pending') ?></option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for=""><?= labels('apply_filters', 'Apply Filters') ?></label>
                                    <button class="btn btn-primary d-block" onclick="refresh_table('subscription_table')">
                                        <?= labels('apply', 'Apply') ?>
                                    </button>
                                </div>
                            </div>

                        </div>
                        <table class="table table-striped" id="subscription_table" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("user/subscriptions/table") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="subscription_table_params">
                            <thead>
                                <tr>
                                    <th data-field="id" data-sortable="true" data-visible="false"><?= labels('id', "ID") ?></th>
                                    <th data-field="plan_title"><?= labels('plan_name', "Plan Name") ?></th>
                                    <th data-field="type"><?= labels('plan_type', "Plan Type") ?></th>
                                    <th data-field="price" data-sortable="true"><?= labels('price', "Price") ?></th>
                                    <th data-field="txn_id" data-visible="false"><?= labels('transaction_id', "Transaction ID") ?></th>
                                    <th data-field="characters" data-sortable="true" data-visible="false"> <?= labels('total_characters', "Total Characters") ?></th>
                                    <th data-field="remaining_characters" data-sortable="true" data-visible="false">Total <?= labels('remaining_characters', "Remaining Characters") ?></th>
                                    <th data-field="status" data-visible="true"><?= labels('status', 'Status') ?></th>
                                    <th data-field="payment_method" data-visible="true"><?= labels('payment_method', 'Payment method') ?></th>
                                    <th data-field="google" data-sortable="true" data-visible="false">GCP <?= labels('total_characters', "Total Characters") ?></th>
                                    <th data-field="remaining_google" data-sortable="true" data-visible="false">GCP <?= labels('remaining_characters', "Remaining Characters") ?></th>
                                    <th data-field="aws" data-sortable="true" data-visible="false">Amazon Polly <?= labels('total_characters', "Total Characters") ?></th>
                                    <th data-field="remaining_aws" data-sortable="true" data-visible="false">Amazon Polly <?= labels('remaining_characters', "Remaining Characters") ?></th>
                                    <th data-field="ibm" data-sortable="true" data-visible="false">IBM Whatson <?= labels('total_characters', "Total Characters") ?></th>
                                    <th data-field="remaining_ibm" data-sortable="true" data-visible="false">IBM Whatson <?= labels('remaining_characters', "Remaining Characters") ?></th>
                                    <th data-field="azure" data-sortable="true" data-visible="false">MS Azure <?= labels('total_characters', "Total Characters") ?></th>
                                    <th data-field="remaining_azure" data-sortable="true" data-visible="false">MS Azure <?= labels('remaining_characters', "Remaining Characters") ?></th>
                                    <th data-field="starts_from"><?= labels('start_date', "Start Date") ?></th>
                                    <th data-field="expires_on"><?= labels('end_date', "End Date") ?></th>
                                    <th data-field="tenure" data-sortable="true"><?= labels('tenure', "Tenure") ?></th>
                                    <th data-field="created_on" data-sortable="true"><?= labels('purchase_date', "Purchase Date") ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div>
    <div class="modal fade" id="reciept_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload reciept</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php helper("form"); ?>
                <form action='<?= base_url('user/subscriptions/upload_bank_reciepts') ?>' method="post" enctype="multipart/form-data" id="upload_form">
                    <div class="modal-body">
                        <div class="file-upload">
                            <div class="mb-3">
                                <input type="hidden" name="id" id="id">
                                <label for="reciept" class="form-label"></label>
                                <input type="file" name="reciept[]" id="reciept" accept="image/*" multiple class="form-control" title="reciept upload">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="update_receipt_btn">Upload Receipt</button>
                    </div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Bank Transfer</h5>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <?php if (isset($bank_instruction)) : ?>
                                <?= trim($bank_instruction) ?>
                            <?php endif; ?>
                        </div>
                        <div class="alert alert-primary">
                            <?php if (isset($account_details)) : ?>
                                <?= trim($account_details) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reciept_list_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reciepts uploaded by you</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="subscription_id" />
                <table class="table table-striped" id="bank_transfer" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("user/bank_transfers/table") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="bank_transfer_params">
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