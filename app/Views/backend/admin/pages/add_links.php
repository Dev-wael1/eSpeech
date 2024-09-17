<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('settings', "Settings") ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Settings</a></div>
                <div class="breadcrumb-item">Add Links</div>
            </div>
        </div>
        <div class="container-fluid card">
            <div class="row">
                <div class="col-md">
                    <h2 class='section-title'><?= labels('social_link', "Social Links") ?></h2>
                </div>
            </div>
        </div>
        <div class=" row">
            <div class="col-md">
                <form id="social_links">
                    <div class="container-fluid card p-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="site_name">Site Name</label>
                                    <input id="site_name" class="form-control" type="text" name="site_name">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="site_url">Site Url</label>
                                    <input id="site_url" class="form-control" type="text" name="site_url">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm" id="file_ls">
                                <div class="custom-file form-group">
                                    <label for="site_logo">
                                        Site Image
                                    </label>
                                    <input type="file" class="form-control" id="site_logo" name="site_logo">
                                </div>
                            </div>
                            <div class="col-sm d-none" id="site-img">
                                <img src="<?= base_url('public/backend/assets/site_icon/default_view_image.png') ?>" id="site_img" alt="default view">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="site_html">Site HTML</label>
                                    <input id="site_html" class="form-control" type="text" name="site_html">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md">
                                <div class="form-group">
                                    <input type='submit' name='update' id='update' value='<?= labels('save', "Update") ?>' class='btn btn-success' />
                                    <input type='reset' name='clear' id='clear' value='<?= labels('reset', "Clear") ?>' class='btn btn-danger' />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="mail_list" data-detail-view="true" data-detail-formatter="user_formater" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("admin/settings/add-links/list") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC">
                            <thead>
                                <tr>
                                    <th data-field="id" class="text-center" data-visible="false" data-sortable="true"><?= labels('id', 'ID') ?></th>
                                    <th data-field="site_name" class="text-center"><?= labels('site_name', 'Site Name') ?></th>
                                    <th data-field="site_url" class="text-center"><?= labels('site_url', 'Site Url') ?></th>
                                    <th data-field="site_icon" class="text-center"><?= labels('site_icon', 'Site Icon') ?></th>
                                    <th data-field="operations" data-events="link_events" class="text-center"><?= labels('operations', 'Operations') ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>