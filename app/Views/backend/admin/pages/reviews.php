<!-- Main Content -->
<div class="main-content">
    <section id='tts_form' class='section'>
        <div class="section-header">
            <h1><?= labels('feedback', 'Feedback') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('auth') ?>"><?= ($admin) ? "Admin" : "User" ?></a></div>
                <div class="breadcrumb-item"><?= labels('feedback', 'Feedback') ?></div>
            </div>
        </div>

        <div class="container-fluid card">
            <div class="card-header">
                <h4 class="section-title"><?=labels('add_review', "Add a Review")?></h4>
            </div>
            <div class="card-body">
                <form id="reviewadmin" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for='userReview' class="control-label font-weight-bolder text-dark"><?= labels('user', 'User') ?></label>
                                <select name='userReview' id="userReview" class='selectric form-control' onchange="set_name()">
                                    <?php
                                    foreach ($users as $user) {
                                    ?>
                                        <option value='<?= $user['id'] ?>'><?= $user['username'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label for="input" class="control-label font-weight-bolder text-dark"><?= labels('rate_this', 'Rate This') ?></label>
                                <input id="input" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-show-clear="false" data-show-caption="true">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label for='subject' class="form-label font-weight-bolder text-dark"><?= labels('title', 'Title') ?></label>
                                <input type="text" class="form-control" id="subject" name="subject" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label for='review' class="form-label font-weight-bolder text-dark"><?= labels('comment', 'Comment') ?></label>
                                <textarea id="review" rows=10 class='form-control h-50 review' name="review"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md">
                            <div class="form-group">
                                <button type="submit" id="savereview" class="btn btn-primary save"><?= labels('save', 'Save') ?></button>
                                <input type='reset' name='clear' id='clear' value='<?= labels('reset', "Clear") ?>' class='btn btn-danger' />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="reviews" data-detail-view="true" data-detail-formatter="user_formater" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url('admin/reviews/show') ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC">
                            <thead>
                                <tr>
                                    <th data-field="id" class="text-center" data-visible="false" data-sortable="true"><?= labels('id', 'ID') ?></th>
                                    <th data-field="user_name" class="text-center"><?= labels('user_name', 'User Name') ?></th>
                                    <th data-field="user_image" class="text-center"><?= labels('user_image', 'User Profile') ?></th>
                                    <th data-field="subject" class="text-center"><?= labels('subject', 'Subject') ?></th>
                                    <th data-field="review" class="text-center"><?= labels('review', 'Review') ?></th>
                                    <th data-field="rating_number" class="text-center"><?= labels('rating_number', 'Rating Star') ?></th>
                                    <th data-field="status_text" class="text-center"><?= labels('status', 'Status') ?></th>
                                    <th data-field="operations" class="text-center" data-events="review_events"><?= labels('operations', 'Operations') ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>