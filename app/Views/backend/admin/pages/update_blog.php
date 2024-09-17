<!-- Main Content -->
<div class="main-content">
    <section id='tts_form' class='section'>
        <div class="section-header">
            <h1><?= labels('update_blog', 'Update Blog')  ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('auth') ?>"><?= ($admin) ? "Admin" : "User" ?></a></div>
                <div class="breadcrumb-item">Update Blog</div>
            </div>
        </div>

        <div class="container-fluid card d-flex justify-content-center">
            <div class="card-header">
                <h4 class='section-title'><?= labels('update_blog', 'Update Blog')  ?> </h4>
            </div>
        </div>
        <div class="container-fluid card p-3">
            <form id="updateblog" enctype="multipart/form-data" method="post">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                <?php foreach ($blog as $key => $value) { ?>
                    <div class="col-md">
                        <input type="hidden" name="id" id="id" value="<?= $value['id'] ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-3">
                                <label for='title' class="form-label font-weight-bolder text-dark"><?= labels('blog_title', 'Blog Title')  ?></label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= $value['title'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group m-3">
                                <label for='status' class="form-label font-weight-bolder text-dark"><?= labels('blog_status', 'Blog Status')  ?></label>
                                <select class="form-control select" name='status' id='status'>
                                    <option value="">Select Status</option>
                                    <?php if ($value['status'] == 1) { ?>
                                        <option value="1" selected>Publish</option>
                                        <option value="0">Hide</option>
                                    <?php } elseif ($value['status'] == 0) { ?>
                                        <option value="1">Publish</option>
                                        <option value="0" selected>Hide</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group m-3">
                                <label for='image' class="form-label font-weight-bolder text-dark"><?= labels('blog_image', 'Blog Image')  ?></label>
                                <input type="file" name="image" class="form-control-file" id="image" value="<?= $value['image'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-3">
                                <label for='description' class="form-label font-weight-bolder text-dark"><?= labels('blog_content', 'Blog Content')  ?></label>
                                <textarea id="description" rows=50 class='form-control h-50 summernotes description' name="description"><?= $value['description'] ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php
                } ?>
                <div class="row mt-2">
                    <div class="col-md">
                        <div class="form-group">
                            <button type="submit" id="update" class="btn btn-primary update"><?= labels('update', 'Update')  ?></button>
                            <a href="<?= base_url('admin/blogs') ?>" class="btn btn-danger text-white" type="button"><?= labels('cancel', 'Cancel')  ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>