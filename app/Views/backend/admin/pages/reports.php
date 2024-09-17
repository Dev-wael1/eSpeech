<div class="main-content">
    <section class="section">
        <div class="section-header">

            <h1>Subscriptions</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/subscriptions') ?>">usage</a></div>
                <div class="breadcrumb-item">Usage</div>
            </div>
        </div>

        <div class="container-fluid card py-3 mt-0">
            <div class="row">
                <div class="col-md">
                    <div class="card-body">
                        <table class="table table-striped" id="tts_table" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("admin/text-to-speech/tts-list/") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC">
                            <thead>
                                <tr>
                                    <th data-field="id" data-visible="false" data-sortable="true">ID</th>
                                    <th data-field="title" data-sortable="true">Title</th>
                                    <th data-field="language" data-sortable="true" data-visible="false">Language</th>
                                    <th data-field="voice" data-sortable="true" data-visible="false">Voice</th>
                                    <th data-field="provider" data-sortable="true">Provider</th>
                                    <th data-field="text" data-visible="false" data-sortable="true">Text</th>
                                    <th data-field="used_characters" data-sortable="true">Used Characters</th>
                                    <th data-field="created_on" data-sortable="true">Created on</th>
                                    <th data-field="identity" data-sortable="true">Identity</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>