<div class="main-content">
    <section class="section">
        <div class="container-fluid card">
            <h2 class='section-title'><?= labels('bank_transfers', "Bank Transfers") ?></h2>

            <div class="row">
                <div class="col-md">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date"><?= labels('filter_date_by', "Filter date by") ?></label>
                                    <select name="date_filter_by" id="date_filter_by" class="form-control selectric">
                                        <option value=""><?= labels('all', 'All') ?></option>
                                        <option value="created_on"><?= labels('date_of_upload', 'Date of Upload') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date"><?= labels('date_range_filter', 'Date Range') ?></label>
                                    <input type="text" name="date_range" id="date" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for=""><?= labels('apply_filters', 'Apply Filters') ?></label>
                                    <button class="btn btn-primary d-block" onclick="refresh_table('bank_transfer')">
                                        <?= labels('apply', 'Apply') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped" id="bank_transfer" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("admin/bank_transfers/table") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="bank_transfer_params">
                            <thead>
                                <tr>
                                    <th data-field="name" data-sortable="true" data-visible="true"><?= labels("name", 'Name') ?></th>
                                    <th data-field="plan_type" data-sortable="true" data-visible="true"><?= labels('type', "Type") ?></th>
                                    <th data-field="plan_title" data-sortable="true" data-visible="true"><?= labels('plan', "Plan") ?></th>
                                    <th data-field="price" data-sortable="true" data-visible="true"><?= labels('price', "Price") ?></th>
                                    <th data-field="attachments_img" data-sortable="false" data-visible="true"><?= labels('reciepts', 'Reciepts') ?></th>
                                    <th data-field="receipt_check" data-sortable="false" data-visible="true"><?= labels('check', 'Check') ?></th>
                                    <th data-field="operations" data-sortable="false" data-events="bank_events" data-visible="true"><?= labels('operations', 'Operations') ?></th>
                                    <th data-field="created_at" data-sortable="true"  data-visible="false"><?= labels('date_of_upload', "Date of Upload") ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- modal to active subscriptinos -->
<div class="modal fade" id="receipt_check_modal" tabindex="-1" aria-labelledby="receipt_check_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Activate subscription</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= helper('form') ?>
                <form action="<?= base_url('admin/bank_transfers/update_receipt') ?>" method="post" id="reciept_check_form">
                    <div class="col-md">
                        <input type="hidden" name="id" id="bank_transfer_id">
                        <input type="hidden" name="user_id" id="user_id">
                    </div>
                    <div class="col-md">
                        <label for="message"><?= labels('message', "Message") ?></label>
                        <textarea rows="10" cols="20" class='form-control h-25' name="reason" id="message"></textarea>
                    </div>
                    <div class="col-md mt-3 ml-5">
                        <div class="custom-control custom-radio  custom-control-inline">
                            <input type="radio" id="pending" value="0" name="status" class="custom-control-input" checked aria-checked="true">
                            <label class="custom-control-label" for="pending">Pending reciept</label>
                        </div>
                        <div class="custom-control custom-radio  custom-control-inline">
                            <input type="radio" id="accept" value="1" name="status" class="custom-control-input">
                            <label class="custom-control-label" for="accept">Accept reciept</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="reject" value="2" name="status" class="custom-control-input">
                            <label class="custom-control-label" for="reject">Reject reciept</label>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="update_receipt_btn">Update reciept</button>
                </form>
                <!-- <?= form_close() ?> -->
            </div>
        </div>
    </div>
</div>