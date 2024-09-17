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
                                        <option value="created_at"><?= labels('date_of_upload', 'Date of Upload') ?></option>
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
                        <table class="table table-striped" id="bank_transfer" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("user/bank_transfers/table") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[2,5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="bank_transfer_params">
                            <thead>
                                <tr>
                                    <th data-field="id" data-visible="false"><?= labels("id", 'ID') ?></th>
                                    <th data-field="name"><?= labels("name", 'Name') ?></th>
                                    <th data-field="plan_type" data-sortable="true"><?= labels('type', "Type") ?></th>
                                    <th data-field="plan_title" data-sortable="true"><?= labels('plan', "Plan") ?></th>
                                    <th data-field="price" data-sortable="true" ><?= labels('price', "Price") ?></th>
                                    <th data-field="status" data-sortable="false" data-visible="true"><?= labels('check_status', 'Recipt Status') ?></th>
                                    <th data-field="attachments_img" ><?= labels('reciepts','Reciepts')?></th>
                                    <th data-field="created_at" data-sortable="true" ><?= labels('date_of_upload', "Date of Upload") ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>