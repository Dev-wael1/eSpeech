<!-- Main Content -->

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <input type="hidden" value="" id="months">
            <h1><?= labels('settings','settings') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/subscriptions') ?>">Themes</a></div>

            </div>
        </div>
        <div class="container-fluid card rounded py-3">
            <h2 class='section-title'><?= labels('themes',"Themes") ?></h2>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th><?= labels('image',"Image") ?></th>
                                <th><?= labels('title',"Title") ?></th>
                             
                                <th><?= labels('status',"Status") ?></th>
                                <th><?= labels('operate',"Action") ?></th>
                                <th><?= labels('created_on',"Created At") ?></th>
                            </tr>

                            <?php $i = 1;
                            foreach ($themes as $row) { ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>

                                        <img src="<?= base_url('public/frontend/theme/' . $row['image']) ?>" class="theme_thumbnail" alt="">
                                    </td>
                                    <td><?= $row['name'] ?></td>
                          

                                    <td>
                                        <?php
                                        if ($row['is_default'] == 1) {
                                        ?>
                                            <div class="badge badge-success">Default</div>
                                        <?php } else { ?>
                                            <div class="badge badge-warning">Inactive</div>
                                        <?php } ?>
                                    </td>
                                    <td><Button name="theme_id" value="<?= $row['id'] ?>" class="btn btn-primary" <?= ($row['is_default'] == 1) ? "disabled" : "" ?>>Set Default</button></td>
                                    <td><?= $row['created_at'] ?></td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>