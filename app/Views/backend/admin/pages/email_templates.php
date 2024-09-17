<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('email_templates', 'Email Templates') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item">Email Templates</div>
            </div>
        </div>
        <div class="container-fluid card">
            <div class="row">
                <div class="align-items-center col-12 d-flex justify-content-between">
                    <h2 class='section-title'><?= labels('email_templates_list', 'Email Template List') ?></h2>
                    <button class="btn btn-primary h-50" id="add_template_btn">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="container-fluid d-none card" id="template_body">
            <div class="row">
                <div class="col-md">
                    <h6 class='section-title'><?= labels('add_mail_type', 'Add Mail Type') ?></h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <form class="form" id="mail_template">
                        <div class="row">
                            <div class="col-md">
                                <div class="form-group">
                                    <select name="mail_type" class="form-control mail_type" id="mail_tye" aria-required="true" aria-invalid="false">
                                        <option value="0" selected>Select Types</option>
                                        <option value="contact_us">Contact Us</option>
                                        <option value="forgot_password">Forgot Password</option>
                                        <option value="subscription">New Subscription</option>
                                        <option value="deactivate_user">Deactivate User</option>
                                        <option value="activate_user">Activate User</option>
                                        <option value="activate_new_user">Activate New User</option>
                                        <option value="receipt_accepted">Receipt Accepted</option>
                                        <option value="receipt_rejected">Receipt Rejected</option>
                                        <option value="activate_subscription">Activate Subscription Manually</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <div class="form-group">
                                    <label for="subject_of_mail">Subject</label>
                                    <input id="subject_of_mail" class="form-control" type="text" name="mail_subject">
                                </div>
                            </div>

                            <div class="col-md mt-3">
                                <label> <?= labels('template_status', 'Template Status') ?> </label><br>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="template_status" id="template_status" checked>
                                    <label class="custom-control-label" for="template_status">
                                        <span id="template_status_text">
                                            Active
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <textarea rows=50 class='form-control h-50 summernotes' id="mail_text" name="mail_text"></textarea>
                            </div>
                        </div>
                        <p id="tags" class="font-weight-bolder text-110">

                        </p>
                        <div class="row my-4">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success btn-lg" id="template_submit">
                                    Submit template
                                </button>

                                <button type="reset" class="btn btn-danger btn-lg">
                                    Clear template
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="mail_list" data-detail-view="true" data-detail-formatter="user_formater" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("mail-templates/mail-list") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC">
                            <thead>
                                <tr>
                                    <th data-field="id" class="text-center" data-visible="false" data-sortable="true"><?= labels('id', 'ID') ?></th>
                                    <th data-field="email_type" class="text-center"><?= labels('email_type', 'Email Type') ?></th>
                                    <th data-field="email_subject" class="text-center"><?= labels('email_subject', 'Email Subject') ?></th>
                                    <th data-field="email_text" class="text-center"><?= labels('email_text', 'Email Text') ?></th>
                                    <th data-field="status" class="text-center"><?= labels('status', 'Status') ?></th>
                                    <th data-field="operations" data-events="template_events" class="text-center"><?= labels('operations', 'Operations') ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>