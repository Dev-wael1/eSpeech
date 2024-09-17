<!-- Main Content -->
<div class="main-content">
    <section id='tts_form' class='section'>
        <div class="section-header">
            <h1><?= labels('total_voices', 'Voices') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('auth') ?>"><?= ($admin) ? "Admin" : "User" ?></a>
                </div>
                <div class="breadcrumb-item">Voices</div>
            </div>
        </div>
        <div class="container-fluid card">
            <div class="align-items-end card-header d-flex justify-content-between">
                <h4 class='section-title'>
                    <?= labels('total_voices', 'Voices') ?> </h4>
                <button onclick="updateVoice()" id="updateVoices" class="btn btn-primary d-block rounded-0"><?= labels('update_voices', 'Update Voice') ?>
                </button>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><?= labels('select_language', 'Select Language') ?>
                                <small class="text-danger">*</small></label>
                            <select class="form-control select" name='language' id='language'>
                                <option value="">
                                    <?= labels('select_language', 'Languages') ?>
                                </option>
                                <?php foreach ($languages as $key => $val) { ?>
                                    <option data-image="<?php echo base_url('public/flags') . "/" . strtolower(substr($key, strpos($key, '-') + 1, strlen($key))); ?>.svg" value="<?= $key ?>">
                                        <?= $val ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="provider"><?= labels('select_provider', 'Select Provider') ?></label>
                            <select class="form-control select" name='provider' id='provider'>
                                <option value="">Select Provider</option>
                                <option value="google" data-image="<?= base_url('public/provider/google.svg') ?>">google</option>
                                <option value="aws" data-image="<?= base_url('public/provider/aws.svg') ?>">aws</option>
                                <option value="azure" data-image="<?= base_url('public/provider/azure.svg') ?>">azure</option>
                                <option value="ibm" data-image="<?= base_url('public/provider/ibm.svg') ?>">ibm</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group mt-2">
                            <label for=""></label>
                            <button class="btn btn-primary d-block" onclick="refresh_table('tts_voices')">
                                <?= labels('apply', "Apply") ?>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <table class="table table-striped" id="tts_voices" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url('admin/voices/show') ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="voices_params">
                        <thead>
                            <tr>
                                <th data-field="id" data-visible="false" data-sortable="true">
                                    <?= labels('id', 'ID') ?></th>
                                <th data-field="language" data-sortable="true">
                                    <?= labels('language', 'Language') ?></th>
                                <th data-field="voice" data-sortable="true">
                                    <?= labels('voice', 'Voice') ?></th>
                                <th data-field="display_name" data-sortable="true">
                                    <?= labels('display_name', 'Display Name') ?>
                                </th>
                                <th data-field="provider" data-sortable="true">
                                    <?= labels('provider', 'Provider') ?></th>
                                <th data-field="type" data-sortable="true" data-sortable="true">
                                    <?= labels('type', 'Type') ?></th>
                                <th data-field="gender" data-sortable="true">
                                    <?= labels('gender', 'Gender') ?></th>
                                <th data-field="icon" data-sortable="true">
                                    <?= labels('icon', 'Icon') ?></th>
                                <th data-field="status_text" data-sortable="true">
                                    <?= labels('status', 'Status') ?></th>
                                <th data-field="operations" data-sortable="false" data-events="voice_events" data-visible="true">
                                    <?= labels('operations', 'Operations') ?>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Edit Voices</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="edit_voice" enctype="multipart/form-data">
                        <div class="col-md">
                            <input type="hidden" name="id" id="id">
                        </div>
                        <div class="form-group mb-2">
                            <label for="language" class="form-label">Language :</label>
                            <input type="text" id="tts_language" name="language" class="form-control language" readonly>
                        </div>
                        <div class="form-group mb-2">
                            <label for="voice" class="form-label">Voice :</label>
                            <input type="text" id="tts_voice" name="voice" class="form-control voice" readonly>
                        </div>
                        <div class="form-group mb-2">
                            <label for="display_name" class="form-label">Display Name:</label>
                            <input type="text" id="display_name" name="display_name" class="form-control display_name">
                        </div>
                        <div class="form-group mb-2 gender">
                            <label class="form-label">Gender</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item" for="male">
                                    <input type="radio" name="gender" value="male" class="selectgroup-input" id="male">
                                    <span class="selectgroup-button">male</span>
                                </label>
                                <label class="selectgroup-item" for="female">
                                    <input type="radio" name="gender" value="female" class="selectgroup-input" id="female">
                                    <span class="selectgroup-button">female</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label for="icon" class="form-label">Choose Icon :</label>
                            <input type="file" name="image" class="form-control-file" id="icon">
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
                    <button type="button" id="update_voice_btn" class="btn btn-primary">Update Voice</button>
                </div>
            </div>
        </div>
    </div>
</div>