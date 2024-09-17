<!-- Main Content -->
<div class="main-content">
    <section id='blog_form' class='section'>
        <div class="section-header">
            <h1><?= labels('total_blogs', 'Blogs') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('auth') ?>"><?= ($admin) ? "Admin" : "User" ?></a>
                </div>
                <div class="breadcrumb-item"><?= labels('total_blogs', 'Blogs') ?></div>
            </div>
        </div>
        <div class="section-header  d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="<?= base_url('admin/blogs/create') ?>" class="btn btn-info text-white" type="button"><?= labels('create_new_blog', 'Create New Blog')  ?></a>
        </div>

        <section>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" id="blogs" data-detail-view="true" data-detail-formatter="user_formater" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url('admin/blogs/show') ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC">
                                <thead>
                                    <tr>
                                        <th data-field="id" class="text-center" data-visible="false" data-sortable="true"><?= labels('id', 'ID') ?></th>
                                        <th data-field="title" class="text-center"><?= labels('title', 'Title') ?></th>
                                        <th data-field="image" class="text-center"><?= labels('image', 'Image') ?></th>
                                        <th data-field="status_text" class="text-center"><?= labels('status', 'Status') ?></th>
                                        <th data-field="created_at" class="text-center"><?= labels('created_at', 'Created at') ?></th>
                                        <th data-field="operations" class="text-center" data-events="blog_events"><?= labels('operations', 'Operations') ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </section>
</div>