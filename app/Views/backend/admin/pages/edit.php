<!-- Main Content -->
<?php
    $currency = get_currency();
?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit plan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Settings</a></div>
                <div class="breadcrumb-item">Edit Plans</div>
            </div>
        </div>

        <div class="container-fluid card pb-3">
            <h2 class='section-title'><?= labels('plans', 'Plan') ?></h2>
            <form id='addplanbundel' method="post">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <input type="hidden" name="plan_id" value="<?= $plans['id'] ?>">
                <input type="hidden" name="tenure" id="hiddenTenure">
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planTitle'><?= labels('title', 'title') ?></label>
                            <input type='text' class="form-control" required name='planTitle' value="<?= $plans['title'] ?>" id='planTitle' placeholder="Eg. Dimond, Gold, Silver..." />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label><?= labels('animation', 'Select Animation') ?></label>
                            <select class="form-control selectric" required id='lottie_selects' onchange="load_lotties(this,'lottie_demo','#lottie_selects')">
                                <option value='<?= base_url('public/frontend/retro/img/lottieImages/bike.json') ?>'>Bike</option>
                                <option value='<?= base_url('public/frontend/retro/img/lottieImages/car.json') ?>'>Car</option>
                                <option value=''>custom lottie</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="lottie_url" value="<?= $plans['lottie'] ?>">
                    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.13/lottie.min.js"></script>
                    <div class="col-md-7">
                        <div id="lottie_demo" class="tenem"></div>
                    </div>
                    <div class="col-md-5" id="lottie_customs" class="mb-2">
                        <div class="form-group">
                            <label for="">Lottie URL</label>
                            <input type="text" id="lottie_inputs" class="form-control" onchange="load_lottie_inputs(this,'lottie_demo')">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label><?= labels('plan_type', 'Select Plan Type') ?></label>
                            <select class="form-control selectric" required name='planType' id='planType'>
                                <option value='general' <?= $plans['type'] == 'general' ? "selected" : ""  ?>>
                                <?= labels('character_based','Character Based') ?></option>
                                <option value='provider' <?= $plans['type'] == 'provider' ? "selected" : ""  ?>>
                                <?= labels('service_provider_based','Service Provider Based') ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id='planServiceProviderCharacter1'>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planGoogleCharacter'><span class="iconify" data-icon="logos:google-cloud"></span> Google <?= labels('characters', 'Charaters') ?></label>
                            <input type='number' id='planGoogleCharacter' name='planGoogleCharacter' class='form-control' value="<?= $plans['google'] ?>" />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planAwsCharacter'><span class="iconify" data-icon="logos:aws"></span> Amazon Polly <?= labels('characters', 'Charaters') ?></label>
                            <input type='number' id='planAwsCharacter' name='planAwsCharacter' class='form-control' value='<?= $plans['aws'] ?>' />
                        </div>
                    </div>
                </div>
                <div class="row" id='planServiceProviderCharacter2'>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planIbmCharacter'><span class="iconify" data-icon="logos:ibm"></span> IBM Whatson <?= labels('characters', 'Charaters') ?></label>
                            <input type='number' id='planIbmCharacter' name='planIbmCharacter' class='form-control' value="<?= $plans['ibm'] ?>" />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planAzureCharacter'><span class="iconify" data-icon="logos:azure-icon"></span> Microsoft Azure <?= labels('characters', 'Charaters') ?></label>
                            <input type='number' id='planAzureCharacter' name='planAzureCharacter' class='form-control' value="<?= $plans['azure'] ?>" />
                        </div>
                    </div>
                </div>
                <div class="row" id='planCharacters'>
                    <div class="col-md">
                        <div class="form-group">
                            <label for='planCharacter'><?= labels('characters', 'Plan Total Charaters') ?></label>
                            <input type='number' id='planCharacter' name='planCharacter' class='form-control' value="<?= $plans['no_of_characters'] ?>" />
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md">
                        <div class="form-check">
                            <input class="form-check-input" name="featured" onchange="featured_toggle()" id="featured" type="checkbox" value="featured" <?= ($plans['is_featured'] == '1') ? "checked" : "" ?>>
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
                                <?= labels('featured_text') ?> ( 15 <?= labels('characters_max',"Characters Max") ?> )
                            </label>
                            <input class="form-control" maxlength="15" name="featured_text" type="text" value="<?= $plans['featured_text'] ?>">
                        </div>
                    </div>
                </div>
                <h2 class='section-title'><?= labels('plan_tenure_details','Plan Tenure Details') ?></h2>

                <div class="row">
                    <div class="col-md-12">
                        <div class="container-fluid">
                            <div class="row custom-table-header">
                                <div class="col-md-3 custom-col">
                                <?= labels('tenure','Tenure') ?><span class="asterisk text-danger"> *</span>
                                </div>
                                <div class="col-md-3 custom-col">
                                <?= labels('months','Months') ?><span class="asterisk text-danger"> *</span>
                                </div>
                                <div class="col-md-2 custom-col">
                                <?= labels('price','Price') ?> (<?= $currency ?>)<span class="asterisk text-danger"> *</span>
                                </div>
                                <div class="col-md-2 custom-col">
                                <?= labels('discounted_price','Discounted Price')  ?> (<?= $currency ?>)
                                </div>
                                <div class="col-md-1 custom-col">
                                <?= labels('operate','Operate') ?> </div>
                            </div><br>
                            <div id="tenure_items">
                                <div class="tenure-item py-1">
                                    <div class="row">
                                        <div class="col-md-3 custom-col">
                                            <input type="text" class="form-control" id="tenure" name="tenure" placeholder="Ex.  Monthly, Quarterly,Yearly">
                                        </div>
                                        <div class="col-md-3 custom-col">
                                            <select class="form-control" id="months" name="months">
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
                                            <input type="number" class="form-control" id="price" name="price" min="0" placeholder="0.00">
                                        </div>
                                        <div class="col-md-2 custom-col">
                                            <input type="number" class="form-control" id="discounted_price" name="discounted_price" min="0" placeholder="0.00">
                                        </div>
                                        <div class="col-md-2 custom-col text-center">
                                            <button class="btn btn-icon btn-success" id="add_tenure"><i class="fas fa-plus"></i></button>
                                            <br/>
                                            ( <span class="text-danger">*</span> click this to add plan ) 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tenure data -->
                            <div id="tenures">
                                <?php foreach ($tenures as $row) { ?>
                                    <div class="tenure-item py-1">
                                        <div class="row">
                                            <div class="col-md-3 custom-col">
                                                <input type="text" class="form-control" name="tenure[]" value="<?= $row['title'] ?>" placeholder="Ex. Weekly, Quarterly, Monthly, Yearly" value="test" required="">
                                            </div>
                                            <div class="col-md-3 custom-col">
                                                <select class="form-control" name="months[]" required="">
                                                    <?php for ($i = 1; $i <= 36; $i++) { ?>
                                                        <option value="<?= $i ?>" <?= ($i == $row['months']) ? 'selected' : ''; ?>><?= $i ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 custom-col"><input type="number" class="form-control" name="price[]" min="0" placeholder="0.00" value="<?= $row['price'] ?>" required=""></div>
                                            <div class="col-md-2 custom-col"><input type="number" class="form-control" name="discounted_price[]" min="0" value="<?= $row['discounted_price'] ?>" placeholder="0.00"></div>
                                            <input type="hidden" name="tenure_id[]" value="<?= $row['id'] ?>">
                                            <div class="col-md-1 custom-col"><button class="btn btn-icon btn-danger remove-tenure-item" name="remove_tenure"><i class="fas fa-trash"></i></button></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <button type="submit" class='btn btn-block btn-success' id='addPlan' name="update"><?= labels('save','Save') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>