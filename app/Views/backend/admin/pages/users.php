<!-- Main Content -->

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('users', 'Users') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item">users</div>
            </div>
        </div>
        <div class="section-header  d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="<?= base_url('admin/users/register_user') ?>" class="btn btn-info text-white" type="button"><i class="fa fa-user-plus p-1" aria-hidden="true"></i><?= labels('create_user', 'Create User') ?></a>
        </div>
        <section>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" id="user_list" data-detail-view="true" data-detail-formatter="user_formater" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("admin/users/list-user") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC">
                                <thead>
                                    <tr>
                                        <th data-field="id" class="text-center" data-visible="false" data-sortable="true"><?= labels('id', 'ID') ?></th>
                                        <th data-field="image" class="text-center"><?= labels('profile', 'Profile') ?></th>
                                        <th data-field="phone" class="text-center"><?= labels('mobile', 'Mobile') ?></th>
                                        <th data-field="active" class="text-center"><?= labels('user_status', 'User Status') ?></th>
                                        <th data-field="operations" class="text-center"><?= labels('operations', 'Operations') ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <!-- To deactivate given selected user -->
    <div class="modal fade" id="deactivate_user_modal" tabindex="-1" role="dialog" aria-labelledby="deactivate_user_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Deactivate user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('admin/users/deactivate') ?>" method="post" id="deactivate_user_form">
                        <input type="hidden" name="user_id" id="user_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="deactive_btn">Deactivate User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- To activate given selected user -->
    <div class="modal fade" id="activate_user_modal" tabindex="-1" role="dialog" aria-labelledby="activate_user_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Activate user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('admin/users/activate') ?>" method="post" id="activate_user_form">
                        <input type="hidden" name="user_id" id="user_id_active">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="activate_btn">Activate User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>