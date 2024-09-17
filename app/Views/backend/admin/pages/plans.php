<!-- Main Content -->


<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= labels('plans', 'Plans') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item">Plans</div>
            </div>
        </div>
        <div class="container-fluid card">
            <div class="row">
                <div class="col-md">
                    <h2 class='section-title'><?= labels('available_plans', 'Available Plans') ?></h2>
                </div>
            </div>
            <div class="row">
                <input type='hidden' name="currency" id="currency" value="<?= $currency ?>" />
                <?php foreach ($plans as $key => $value) { ?>
                    <div class="col-md-4">
                        <div class=" container pricing pricing-highlight shadow">
                            <div class="pricing-title mt-2">
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
                            <div class="pricing-padding">
                                <div class="pricing-price">
                                    <div><?= $currency ?>
                                        <span id="price<?= $key ?>">

                                            <?php foreach ($tenure as $key2 => $value2) {
                                                if ($value2['plan_id'] == $value['id']) {
                                            ?>
                                                    <?php if ($value2['discounted_price'] != '' && $value2['discounted_price'] > 0) : ?>
                                                        <?= number_format($value2['discounted_price']) ?>
                                                    <?php else : ?>
                                                        <?= number_format($value2['price']) ?>
                                                    <?php endif; ?>
                                                    <?php if ($value2['discounted_price'] > 0) : ?>
                                                        <?= "<h6> <strike> " . $currency . ' &nbsp;' .
                                                            number_format($value2['price']) . " </strike> </h6>"  ?>
                                                    <?php endif; ?>
                                            <?php
                                                    break;
                                                }
                                            } ?>

                                        </span>
                                    </div>
                                    <div class="col-md-6 offset-md-3">
                                        <select class="form-control selectric" id='plan<?= $key ?>' onchange="display_discounted_price(<?= $key ?>);">
                                            <?php foreach ($tenure as $key2 => $value2) {
                                                if ($value2['plan_id'] == $value['id']) {
                                            ?>
                                                    <option data-price="<?= number_format($value2['price']) ?>" value='<?= $value2['id'] ?>' data-discount="<?= number_format($value2['discounted_price']) ?>">
                                                        <?= $value2['title'] ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="pricing-details">
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $value['no_of_characters'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['no_of_characters'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= number_format($value['no_of_characters']) ?></b> Overall <?= labels('characters', 'Characters') ?></div>
                                    </div>
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $value['google'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['google'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= number_format($value['google']) ?></b> Google Clould Plateform <?= labels('characters', 'Characters') ?></div>
                                    </div>
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $value['aws'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['aws'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= number_format($value['aws']) ?></b> Amazon Polly <?= labels('characters', 'Characters') ?></div>
                                    </div>
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $value['ibm'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['ibm'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= number_format($value['ibm']) ?></b> IBM Whatson <?= labels('characters', 'Characters') ?></div>
                                    </div>
                                    <div class="pricing-item">
                                        <div class="pricing-item-icon bg-<?= $value['azure'] > 0 ? 'success' : 'danger' ?>"><i class="fas fa-<?= $value['azure'] > 0 ? 'check' : 'times' ?>"></i></div>
                                        <div class="pricing-item-label"><b><?= number_format($value['azure']) ?></b> Microsoft Azure <?= labels('characters', 'Characters') ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3">
                                <div class="row">
                                    <div class="col-md">
                                        <a href="<?= base_url('admin/plans/edit/' . $value['id']) ?>" class="btn btn-primary btn-sm widthhun"> <i class="fa fa-pen"></i> <?= labels('edit', "Edit") ?></a>
                                    </div>
                                    <div class="col-md">
                                        <button onclick="delete_plan(this);" data-plan-id="<?= $value['id'] ?>" class="btn btn-danger btn-sm widthhun"> <i class="fa fa-trash"></i> <?= labels('delete', "Delete") ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container-fluid card pb-3">
            <h2 class='section-title'><?= labels('add_plan', 'Add Plan') ?></h2>
            <form id='addplanbundel' method="post" action="<?= base_url('admin/plans/add-plan') ?>">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <input type="hidden" name="tenure" id="hiddenTenure">
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planTitle'><?= labels('title', 'Title') ?></label>
                            <input type='text' class="form-control" required name='planTitle' id='planTitle' placeholder="Eg. Dimond, Gold, Silver..." />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label><?= labels('plan_type', 'Select Plans type') ?></label>
                            <select class="form-control selectric" required name='planType' id='planType'>
                                <option value='null'>-<?= labels('select_type', 'Select Type') ?>-</option>
                                <option value='general'><?= labels('character_based', "Character based") ?></option>
                                <option value='provider'><?= labels('service_provider_based', "Service Provider Based") ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" id='planServiceProviderCharacter1'>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planGoogleCharacter'><span class="iconify" data-icon="logos:google-cloud"></span> Google <?= labels('characters', 'Characters') ?></label>
                            <input type='number' id='planGoogleCharacter' name='planGoogleCharacter' class='form-control' value=0 />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planAwsCharacter'><span class="iconify" data-icon="logos:aws"></span> Amazon Polly <?= labels('characters', 'Characters') ?></label>
                            <input type='number' id='planAwsCharacter' name='planAwsCharacter' class='form-control' value=0 />
                        </div>
                    </div>
                </div>
                <div class="row" id='planServiceProviderCharacter2'>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planIbmCharacter'><span class="iconify" data-icon="logos:ibm"></span> IBM Whatson <?= labels('characters', 'Characters') ?></label>
                            <input type='number' id='planIbmCharacter' name='planIbmCharacter' class='form-control' value=0 />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planAzureCharacter'><span class="iconify" data-icon="logos:azure-icon"></span> Microsoft Azure <?= labels('characters', 'Characters') ?></label>
                            <input type='number' id='planAzureCharacter' name='planAzureCharacter' class='form-control' value=0 />
                        </div>
                    </div>
                </div>
                <div class="row" id='planCharacters'>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planCharacter'><?= labels('characters', 'Total Characters') ?></label>
                            <input type='number' id='planCharacter' name='planCharacter' class='form-control' value=0 />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label><?= labels('animation', 'Select Animation') ?></label>
                            <select class="form-control selectric" required id='lottie_select' onchange="load_lottie(this,'lottie_demo','#lottie_select')">
                                <option value='<?= base_url('public/frontend/retro/img/lottieImages/bike.json') ?>'>Bike</option>
                                <option value='<?= base_url('public/frontend/retro/img/lottieImages/car.json') ?>'>Car</option>
                                <option value=''>custom lottie</option>
                            </select>
                        </div>
                    </div>
                    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.13/lottie.min.js"></script>
                    <div class="col-md-7">
                        <div id="lottie_demo" class="tenem"></div>
                    </div>
                    <div class="col-md-5" id="lottie_custom" class="mb-2">
                        <div class="form-group">
                            <label for="">Lottie URL</label>
                            <input type="text" id="lottie_input" class="form-control" onchange="load_lottie_input(this,'lottie_demo')">
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md">
                        <div class="form-check">
                            <input class="form-check-input" name="featured" onchange="featured_toggle()" id="featured" type="checkbox" value="featured">
                            <label class="form-check-label" for="defaultCheck1">
                                <?= labels('featured', 'Featured') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row" id="featured_text">
                    <div class="col-md">
                        <div class="form-group">
                            <label for="defaultCheck1">
                                <?= labels('featured_text', "Featured Text") ?> ( 15 <?= labels("characters_max", 'Characters Max') ?> )
                            </label>
                            <input class="form-control" maxlength="15" name="featured_text" type="text">
                        </div>
                    </div>
                </div>
                <h2 class='section-title'><?= labels('plan_tenure_details', 'Plan Tenure Details') ?></h2>
                <div class="row">

                    <div class="col-md-12">


                        <div class="container-fluid">
                            <div class="row custom-table-header">
                                <div class="col-md-3 custom-col">
                                    <?= labels('tenure', 'Tenure') ?><span class="asterisk"> *</span>
                                </div>
                                <div class="col-md-3 custom-col">
                                    <?= labels('months', 'Month(s)') ?> </div>
                                <div class="col-md-2 custom-col">
                                    <?= labels('price', 'Price') ?> (<?= $currency ?>)<span class="asterisk"> *</span>
                                </div>
                                <div class="col-md-2 custom-col">
                                    <?= labels('discounted_price', 'Discounted Price') ?> (<?= $currency ?>)<span class="asterisk text-danger"> *</span>
                                </div>
                                <div class="col-md-1 custom-col">
                                    <?= labels('operate', 'Action') ?> </div>
                            </div><br>
                            <div id="tenure_items">
                                <div class="tenure-item py-1">
                                    <div class="row">
                                        <div class="col-md-3 custom-col">
                                            <input type="text" class="form-control" id="tenure" placeholder="Ex.  Monthly, Quarterly,Yearly">
                                        </div>
                                        <div class="col-md-3 custom-col">
                                            <select class="form-control" id="months">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                                <option value="32">32</option>
                                                <option value="33">33</option>
                                                <option value="34">34</option>
                                                <option value="35">35</option>
                                                <option value="36">36</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 custom-col">
                                            <input type="number" class="form-control" id="price" min="0" placeholder="0">
                                        </div>
                                        <div class="col-md-2 custom-col">
                                            <input type="number" class="form-control" id="discounted_price" value="0" min="0" placeholder="0">
                                        </div>
                                        <div class="col-md-2 custom-col text-center">
                                            <button class="btn btn-icon btn-success mb-1" id="add_tenure"><i class="fas fa-plus"></i></button>
                                            <br />
                                            ( <span class="text-danger">*</span> click this to add plan )
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tenure data -->
                            <div id="tenures">
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <button type="submit" class='btn btn-block btn-success' id='addPlan' name="add"><?= labels('add_plan', 'Add Plan') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="section">
        <div class="container-fluid card pb-3">
            <h2 class="section-title"><?= labels('plan_order', "Plan Order") ?></h2>
            <div class="row mt-2">
                <div class="col-md-6 col-12 offset-md-3">
                    <div class="row font-weight-bold">
                        <div class="col-md-4">&nbsp;&nbsp; #</div>
                        <div class="col-md-4"><?= labels('row_order_id', "Row order ID")  ?></div>
                        <div class="col-md-4"><?= labels('title', 'Name') ?></div>
                    </div>
                    <ul class="list-group bg-grey move order-container ui-sortable" id="plan">
                        <?php $i = 1;
                        foreach ($plans as $key => $value) { ?>
                            <li class="list-group-item d-flex bg-gray-light align-items-center h-25 ui-sortable-handle plan_view" id="plan_id-<?= $value['id'] ?>">
                                <div class="col-md-4"><span> <?= $i ?> </span></div>
                                <div class="col-md-4"><span> <?= $value['row_order'] ?> </span></div>
                                <div class="col-md-4"><span> <?= $value['title'] ?> </span></div>
                            </li>
                        <?php $i++;
                        } ?>
                    </ul>
                    <button type="button" class="btn btn-block btn-success btn-lg mt-3" id="update_btn" onclick="arange()"><?= labels('save', 'Save') ?></button>
                </div>
            </div>
        </div>
    </section>

</div>

<style scoped>
    .price {
        position: absolute;
        right: -20px;
        top: -5px;
        z-index: 1;
        overflow: hidden;
        width: 93px;
        height: 93px;
        text-align: right;
    }
</style>