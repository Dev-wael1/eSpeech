<!-- Main Content -->
<div class="main-content">
    <section id='tts_form' class='section'>
        <div class="section-header">
            <h1><?= labels('total_languages', 'TTS Languages') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('auth') ?>"><?= ($admin) ? "Admin" : "User" ?></a>
                </div>
                <div class="breadcrumb-item">TTS_Languages</div>
            </div>
        </div>
        <div class="container-fluid card">
            <div class="align-items-end card-header d-flex justify-content-between">
                <h4 class='section-title'>
                    <?= labels('total_languages', 'TTS Languages') ?> </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
                    ?>
                        <div class="col-sm-12">
                            <div class="alert alert-warning mb-2">
                                <b>Note:</b> If you cannot Synthesize here, please
                                close the codecanyon frame by clicking on <b>x
                                    Remove Frame</b> button from top right corner on
                                the page or <a href="<?= current_url() ?>" target="_blank">&gt;&gt; Click here &lt;&lt;</a>
                            </div>
                        </div>
                    <?php } ?>

                </div>

                <div>
                    <table class="table table-striped" id="tts_languages" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url('admin/tts_languages/show') ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="tts_language_events">
                        <thead>
                            <tr>
                                <th data-field="id" data-visible="false" data-sortable="true">
                                    <?= labels('id', 'ID') ?></th>
                                <th data-field="language_code" data-sortable="true">
                                    <?= labels('language', 'Language') ?></th>
                                <th data-field="language_name_flag" data-sortable="true">
                                    <?= labels('language_name', 'Language Name') ?></th>
                                <th data-field="status_text" data-sortable="true">
                                    <?= labels('status', 'Status') ?></th>
                                <th data-field="operations" data-sortable="false" data-events="language_events" data-visible="true">
                                    <?= labels('operations', 'Operations') ?>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>

    </section>

    <div class="modal fade" id="edit_lan_Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Update Language Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="edit_tts_lan">
                            <div class="col-md">
                                <input type="hidden" name="id" id="lan_id">
                            </div>
                            <div class="form-group mb-2">
                                <label for="language_code" class="form-label">Language Code:</label>
                                <input type="text" id="language_code" name="language_name" class="form-control language_code" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <label for="language_name" class="form-label">Language Name :</label>
                                <input type="text" id="language_name" name="language_name" class="form-control language_name" readonly>
                            </div>

                            <div class="form-group mb-2 mt-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status">
                                    <label class="custom-control-label" for="status">Status</label>
                                </div>
                            </div><br>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" id="update_lang_btn" class="btn btn-primary">Update Language</button>
                    </div>
                </div>
            </div>
        </div>
</div>