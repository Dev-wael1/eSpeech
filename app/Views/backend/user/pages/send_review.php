<!-- Main Content -->
<div class="main-content">
    <section id='tts_form' class='section'>
        <div class="section-header">
            <h1><?= labels('feedbeck', 'Feedback') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('auth') ?>"><?= ($admin) ? "Admin" : "User" ?></a></div>
                <div class="breadcrumb-item"><?= labels('feedbeck', 'Feedback') ?></div>
            </div>
        </div>

        <div class="container-fluid card p-3">
            <form id="reviewformuser" enctype="multipart/form-data" method="post">

                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <div class="col-md">
                    <input type="hidden" name="id" id="id" value="<?= isset($review_data['id']) ? $review_data['id'] : '' ?>">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-3">
                            <label for="input" class="control-label font-weight-bolder text-dark"><?= labels('rate_this', 'Rate This') ?></label>
                            <input id="input" name="rating" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-show-clear="false" data-show-caption="true" value="<?= isset($review_data['rating_number']) ? $review_data['rating_number'] : ''  ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-3">
                            <label for='subject' class="form-label font-weight-bolder text-dark"><?= labels('title', 'Title') ?></label>
                            <input type="text" class="form-control" id="subject" name="subject" value="<?= isset($review_data['subject']) ? $review_data['subject'] : '' ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-3">
                            <label for='review' class="form-label font-weight-bolder text-dark"><?= labels('comment', 'Comment') ?></label>
                            <textarea id="review" rows=10 class='form-control h-50 review' name="review"><?= isset($review_data['review']) ? $review_data['review'] : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group m-3">
                            <button type="submit" id="save" class="btn btn-primary save"><?= labels('save', 'Save') ?></button>
                            <input type='reset' name='clear' id='clear' value='<?= labels('reset', "Clear") ?>' class='btn btn-danger' />
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </section>
</div>