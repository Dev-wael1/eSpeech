<!-- Main Content -->

<div class="main-content">

    <section class="section">

        <?php if (isset($active_plan[0])) {
            $active_plan = $active_plan[0];
        ?>

            <div class="container-fluid card">
                <h2 class="section-title"> <?= labels('active_plan', "Active Plan") ?></h2>

            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="d-style btn btn-brc-tp border-2 bgc-white btn-outline-blue btn-h-outline-blue btn-a-outline-blue w-100 my-2 py-3 shadow-sm bg-white">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"> <?= $active_plan['plan_title'] ?></h4>
                            </div>
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"><?= labels('started_from', "Started From") ?></h4>
                                <div class="text-secondary-d1 text-120"> <span class="ml-n15 align-text-bottom"></span><span class="text-180"><?= $active_plan['starts_from'] ?></span></div>
                            </div>
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"> <?= labels('tenure', "Tenure") ?></h4>
                                <div class="text-secondary-d1 text-120"> <span class="ml-n15 align-text-bottom"></span><span class="text-180"><?= $active_plan['tenure'] ?></span><?= ($active_plan['tenure'] > 1) ? "Months" : "Month" ?></div>
                            </div>
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"><?= labels('expires_on', "Expires on") ?></h4>
                                <div class="text-secondary-d1 text-120"> <span class="ml-n15 align-text-bottom"></span><span class="text-180"><?= $active_plan['expires_on'] ?></span></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>





            <div class="row">
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="carbon:character-patterns" data-width="30"></span>
                        </div>
                        <div class="card-wrap ">
                            <div class="card-header">
                                <h4><?= labels('total_characters', "Total Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""> <?= numbers_initials($active_plan['remaining_characters']) ?> </span>/ <span class=""><?= numbers_initials($active_plan['characters']) ?></span>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:google-cloud"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Google <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($active_plan['remaining_google']) ?></span> / <?= numbers_initials($active_plan['google']) ?> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:aws"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Amazon <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($active_plan['remaining_aws']) ?></span> / <span class=""><?= numbers_initials($active_plan['aws']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:ibm"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>IBM <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($active_plan['remaining_ibm']) ?></span> / <span class=""><?= numbers_initials($active_plan['ibm']) ?> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:azure-icon"></span>
                        </div>
                        <div class="card-wrap ">
                            <div class="card-header">
                                <h4>Azure <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($active_plan['remaining_azure']) ?></span> / <span class=""><?= numbers_initials($active_plan['azure']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <?php } else if ($free_data['isFreeTierAllows'] == "true") { ?>
            <div class="container-fluid card mb-2">
                <h2 class="section-title"><?= labels('no_active', "No active subscription found") ?></h2>

            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="d-style btn btn-brc-tp border-2 bgc-white btn-outline-blue btn-h-outline-blue btn-a-outline-blue w-100 my-2 py-3 shadow-sm bg-white">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"> <?= "Free Tire Plan" ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="carbon:character-patterns" data-width="30"></span>
                        </div>
                        <div class="card-wrap ">
                            <div class="card-header">
                                <h4><?= labels('total_characters', "Total Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""> <?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>/ <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:google-cloud"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Google <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span> / <?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:aws"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Amazon <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span> / <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:ibm"></span>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>IBM <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span> / <span><?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card card-statistic-1 shadow">
                        <div class="card-icon">
                            <span class="iconify" data-icon="logos:azure-icon"></span>
                        </div>
                        <div class="card-wrap ">
                            <div class="card-header">
                                <h4>Azure <?= labels('characters', "Characters") ?></h4>
                            </div>
                            <div class="card-body">
                                <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?></span> / <span class=""><?= numbers_initials($free_data['freeTierCharacterLimit']) ?> </span>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php } else { ?>


            <div class="row mb-3">
                <div class="col-md">
                    <div class="d-style btn btn-brc-tp border-2 bgc-white btn-outline-blue btn-h-outline-blue btn-a-outline-blue w-100 my-2 py-3 shadow-sm bg-white">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-3">
                                <h4 class="pt-3 text-170 text-600 text-primary-d1 letter-spacing"> <?= labels('no_active', "No active subscription found") ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>




        <div class="container-fluid card">

            <div class="row">
                <div class="col-md">
                    <h2 class='section-title'><?= labels('plans', "Plans") ?></h2>
                </div>
            </div>
            <div class="row">
                <input type='hidden' name="currency" id="currency" value="<?= $currency ?>" />
                <?php foreach ($plans as $key => $value) { ?>
                    <div class="col-md-4">
                        <form action="<?= base_url('user/plans/checkout') ?>">

                            <input type="hidden" name="plan_id" value="<?= $value['id'] ?>">
                            <div class="pricing pricing-highlight shadow">
                                <!-- Use this line to show active plan ribbon. -->
                                <!-- <div class="ribbon ribbon-top-left"><span>Active</span></div> -->

                                <div class="pricing-title">
                                    <?= $value['title'] ?>
                                </div>
                                <?php if ($value['is_featured'] == '1') {
                                ?>
                                    <!-- ribbon here  -->
                                    <div id="ribbon-container">
                                        <a href="#" id="ribbon"><?= $value['featured_text'] ?></a>
                                    </div>

                                <?php
                                } ?>
                                <?php if (isset($value2['discounted_price'])) : ?>

                                <?php endif; ?>
                                <div class="pricing-padding">
                                    <div class="pricing-price">
                                        <div><?= $currency ?>
                                            <span id="price<?= $key ?>">
                                                <?php foreach ($tenure as $key2 => $value2) {
                                                    if ($value2['plan_id'] == $value['id']) {
                                                ?>
                                                        <?php if ($value2['discounted_price'] != null && $value2['discounted_price'] > 0) : ?>
                                                            <?= number_format($value2['discounted_price']) ?>
                                                        <?php else : ?>
                                                            <?= number_format($value2['price']) ?>
                                                        <?php endif; ?>
                                                        <?php if ($value2['discounted_price'] > 0) : ?>
                                                            <?= "<h6> <strike> " . $currency . ' &nbsp;' . number_format($value2['price']) . " </strike> </h6>"  ?>
                                                        <?php endif; ?>
                                                <?php
                                                        break;
                                                    }
                                                } ?>
                                            </span>
                                        </div>
                                        <div class="col-md-6 offset-md-3">
                                            <select class="form-control selectric" name="tenure" id="plan<?= $key ?>" onchange="display_discounted_price(<?= $key ?>)">
                                                <?php foreach ($tenure as $key2 => $value2) {
                                                    if ($value2['plan_id'] == $value['id']) {
                                                ?>
                                                        <option data-price="<?= $value2['price'] ?>" value='<?= $value2['id'] ?>' data-discount="<?= number_format($value2['discounted_price']) ?>">
                                                            <?= $value2['title'] ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="pricing-details">
                                        <div class="pricing-item">
                                            <div class="pricing-item-icon bg-<?= $value['no_of_characters'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['no_of_characters'] > 0 ? 'check' : 'times' ?>"></i></div>
                                            <div class="pricing-item-label"><b><?= number_format($value['no_of_characters']) ?></b> <?= labels('total_characters', "Total Characters") ?></div>
                                        </div>
                                        <div class="pricing-item">
                                            <div class="pricing-item-icon bg-<?= $value['google'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['google'] > 0 ? 'check' : 'times' ?>"></i></div>
                                            <div class="pricing-item-label"><b><?= number_format($value['google']) ?></b> Google Clould Plateform <?= labels('characters', "Characters") ?></div>
                                        </div>
                                        <div class="pricing-item">
                                            <div class="pricing-item-icon bg-<?= $value['aws'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['aws'] > 0 ? 'check' : 'times' ?>"></i></div>
                                            <div class="pricing-item-label"><b><?= number_format($value['aws']) ?></b> Amazon Polly <?= labels('characters', "Characters") ?></div>
                                        </div>
                                        <div class="pricing-item">
                                            <div class="pricing-item-icon bg-<?= $value['ibm'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['ibm'] > 0 ? 'check' : 'times' ?>"></i></div>
                                            <div class="pricing-item-label"><b><?= number_format($value['ibm']) ?></b> IBM Whatson <?= labels('characters', "Characters") ?></div>
                                        </div>
                                        <div class="pricing-item">
                                            <div class="pricing-item-icon bg-<?= $value['azure'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['azure'] > 0 ? 'check' : 'times' ?>"></i></div>
                                            <div class="pricing-item-label"><b><?= number_format($value['azure']) ?></b> Microsoft Azure <?= labels('characters', "Characters") ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pricing-cta p-4">
                                    <button class="btn btn-primary btn-block" type="submit"><?= labels('subscribe', "Subscribe") ?></a>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>