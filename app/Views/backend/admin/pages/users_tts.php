<!-- Main Content -->

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('users','Users') ?> <?= labels('tts', "TTS") ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/users') ?>">User</a></div>
                <div class="breadcrumb-item">Users TTS</div>
            </div>
        </div>
    </section>
    <section>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><?= labels('saved_text_to_speech','Saved Text to Speech') ?></h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="tts_table" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("admin/text-to-speech/tts-list/") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="ttsQueryParams">
                            <thead>
                                <tr>
                                    <th data-field="id" data-visible="false" data-sortable="true"><?= labels('id','ID') ?></th>
                                    <th data-field="title" data-sortable="true"><?= labels('title','Title') ?></th>
                                    <th data-field="language" data-sortable="true" data-visible="false"><?= labels('language','Language') ?></th>
                                    <th data-field="voice" data-sortable="true" data-visible="false"><?= labels('voice','Voice') ?></th>
                                    <th data-field="provider" data-sortable="true"><?= labels('provider','Provider') ?></th>
                                    <th data-field="text" data-visible="false" data-sortable="true"><?= labels('text','Text') ?></th>
                                    <th data-field="used_characters" data-sortable="true"><?= labels('characters_used','Used Characters') ?></th>
                                    <th data-field="created_on" data-sortable="true"><?= labels('created_on','Created on') ?></th>
                                    <th data-field="identity" data-sortable="true"><?= labels('identity','Identity') ?></th>
                                    <th data-field="delete_tts" data-events="tts_events" data-sortable="true"><?= labels('operations','Operations') ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>