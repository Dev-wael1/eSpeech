<!-- Main Content -->
<div class="main-content">
    <section class="section">

        <div class="section-header">
            <div class="row ">
                <div class="col-md mb-0">
                    <h2 class='section-title mb-1 mt-1'><?= labels('tts', "TTS") ?> <?= labels('configurations', "Configurations") ?></h2>
                </div>
            </div>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('/admin/dashboard') ?>">Admin</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Settings</a></div>
                <div class="breadcrumb-item">Text To Speech Configurations</div>
            </div>
        </div>

        <form name='tts_configs' id='PGSForm' action="<?= base_url('admin/settings/tts-settings') ?>" method='post'>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="container-fluid card pt-3">
                <h2 class='section-title'>Set Voice Icon</h2>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label class='form-lable' for="showVoiceIcon">What you want to show in Voices ? </label>
                            <div class="selectgroup h-100 w-100">
                                <label class="selectgroup-item h-100">
                                    <input type="radio" name="showVoiceIcon" value=providerIcon class="selectgroup-input" <?= isset($showVoiceIcon) && $showVoiceIcon === "providerIcon" ? 'checked' : '' ?>>
                                    <span class="selectgroup-button">Provider Icon</span>
                                </label>
                                <label class="selectgroup-item h-100">
                                    <input type="radio" name="showVoiceIcon" value=genderIcon class="selectgroup-input" <?= isset($showVoiceIcon) && $showVoiceIcon === "genderIcon" ? 'checked' : '' ?>>
                                    <span class="selectgroup-button">Gender Icon</span>
                                </label>
                                <label class="selectgroup-item h-100">
                                    <input type="radio" name="showVoiceIcon" value=voiceIcon class="selectgroup-input" <?= isset($showVoiceIcon) && $showVoiceIcon === "voiceIcon" ? 'checked' : '' ?>>
                                    <span class="selectgroup-button">Set Icon Image</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class='section-title'>Free Tier</h2>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label class='form-lable' for="isFreeTierAllows">Free Tier Allowed? </label>
                            <div class="selectgroup h-100 w-100">
                                <label class="selectgroup-item h-100">
                                    <input type="radio" name="isFreeTierAllows" value=false class="selectgroup-input" <?= isset($isFreeTierAllows) && $isFreeTierAllows === "false" ? 'checked' : '' ?>>
                                    <span class="selectgroup-button">No</span>
                                </label>
                                <label class="selectgroup-item h-100">
                                    <input type="radio" name="isFreeTierAllows" value=true class="selectgroup-input" <?= isset($isFreeTierAllows) && $isFreeTierAllows === "true" ? 'checked' : '' ?>>
                                    <span class="selectgroup-button">Yes</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label class='form-lable' for="freeTierAllowedSps[]">Free Tier Service Providers</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="freeTierAllowedSps[]" value="gcp" class="selectgroup-input" <?= isset($freeTierAllowedSps) && in_array('gcp', $freeTierAllowedSps, true) ? 'checked' : '' ?>>
                                    <span class="selectgroup-button">Google Cloud Plateform</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="freeTierAllowedSps[]" value="amazonPolly" class="selectgroup-input" <?= isset($freeTierAllowedSps) && in_array('amazonPolly', $freeTierAllowedSps, true) ? 'checked' : '' ?>>
                                    <span class="selectgroup-button">Amazon Polly</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="freeTierAllowedSps[]" value="ibm" class="selectgroup-input" <?= isset($freeTierAllowedSps) && in_array('ibm', $freeTierAllowedSps, true) ? 'checked' : '' ?>>
                                    <span class="selectgroup-button">IBM Whatson</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="freeTierAllowedSps[]" value="azure" class="selectgroup-input" <?= isset($freeTierAllowedSps) && in_array('azure', $freeTierAllowedSps, true) ? 'checked' : '' ?>>
                                    <span class="selectgroup-button">MS Azure</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for="freeTierCharacterLimit">Free Tier Character Limit</label>
                            <input type="number" value="<?= isset($freeTierCharacterLimit) ? $freeTierCharacterLimit : 0 ?>" name='freeTierCharacterLimit' id='freeTierCharacterLimit' placeholder='Enter character limit' class="form-control" />
                        </div>
                    </div>
                </div>

                <h2 class='section-title'>Google Cloud Platform</h2>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='gcpStatus'>Status</label>
                            <select class='form-control selectric' name='gcpStatus' id='gcpStatus'>
                                <option value='enable' <?= isset($gcpStatus) && $gcpStatus === 'enable' ? 'selected' : '' ?>>Enable</option>
                                <option value='disable' <?= isset($gcpStatus) && $gcpStatus === 'disable' ? 'selected' : '' ?>>Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="gcpApiKey">API Key</label>
                            <input type="text" value="<?= isset($gcpApiKey) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" :  $gcpApiKey) : '' ?>" name='gcpApiKey' id='gcpApiKey' placeholder='Enter GCP API key' class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row d-none">
                    <div class="col-md">
                        <div class="form-group">
                            <label for="gcpEndPointUrl">Endpoint URL</label>
                            <input type="text" value="<?= isset($gcpEndPointUrl) ? $gcpEndPointUrl : '' ?>" name='gcpEndPointUrl' id='gcpEndPointUrl' placeholder='Enter GCP Endpoint URL' class="form-control" />
                        </div>
                    </div>
                </div>

                <h2 class='section-title'>Amazon Polly</h2>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='amazonPollyStatus'>Status</label>
                            <select class='form-control selectric' name='amazonPollyStatus' id='amazonPollyStatus'>
                                <option value='enable' <?= isset($amazonPollyStatus) && $amazonPollyStatus === 'enable' ? 'selected' : '' ?>>Enable</option>
                                <option value='disable' <?= isset($amazonPollyStatus) && $amazonPollyStatus === 'disable' ? 'selected' : '' ?>>Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="amazonPollyAccessKey">Access Key</label>
                            <input type="text" value="<?= isset($amazonPollyAccessKey) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $amazonPollyAccessKey) : '' ?>" name='amazonPollyAccessKey' id='amazonPollyAccessKey' placeholder='Enter Amazon Polly Access key' class="form-control" />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="amazonPollySecretAccessKey">Secret Access Key</label>
                            <input type="text" value="<?= isset($amazonPollySecretAccessKey) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $amazonPollySecretAccessKey) : '' ?>" name='amazonPollySecretAccessKey' id='amazonPollySecretAccessKey' placeholder='Enter Amazon Polly Secret Access key' class="form-control" />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="awsRegion">AWS Region</label>
                            <input type="text" value="<?= isset($awsRegion) ? $awsRegion : '' ?>" name='awsRegion' id='awsRegion' placeholder='Enter AWS Region' class="form-control" />
                        </div>
                    </div>
                </div>

                <h2 class='section-title'>IBM Whatson</h2>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='ibmStatus'>Status</label>
                            <select class='form-control selectric' name='ibmStatus' id='ibmStatus'>
                                <option value='enable' <?= isset($ibmStatus) &&  $ibmStatus === 'enable' ? 'selected' : '' ?>>Enable</option>
                                <option value='disable' <?= isset($ibmStatus) && $ibmStatus === 'disable' ? 'selected' : '' ?>>Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="ibmApiKey">API Key</label>
                            <input type="text" value="<?= isset($ibmApiKey) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $ibmApiKey) : '' ?>" name='ibmApiKey' id='ibmApiKey' placeholder='Enter IBM API key' class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for="ibmEndPointUrl">Endpoint URL</label>
                            <input type="text" value="<?= isset($ibmEndPointUrl) ? $ibmEndPointUrl : '' ?>" name='ibmEndPointUrl' id='ibmEndPointUrl' placeholder='Enter IBM Endpoint URL' class="form-control" />
                        </div>
                    </div>
                </div>

                <h2 class='section-title'>Microsoft Azure</h2>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for='azureStatus'>Status</label>
                            <select class='form-control selectric' name='azureStatus' id='azureStatus'>
                                <option value='enable' <?= isset($azureStatus) && $azureStatus === 'enable' ? 'selected' : '' ?>>Enable</option>
                                <option value='disable' <?= isset($azureStatus) && $azureStatus === 'disable' ? 'selected' : '' ?>>Disable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="azureApiKey">API Key</label>
                            <input type="text" value="<?= isset($azureApiKey) ? ((defined('ALLOW_VIEW_KEYS') && ALLOW_VIEW_KEYS == 0) ? "asc****************adaca" : $azureApiKey) : '' ?>" name='azureApiKey' id='azureApiKey' placeholder='Enter Azure API key' class="form-control" />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="azureRegion">AWS Region</label>
                            <input type="text" value="<?= isset($azureRegion) ? $azureRegion : '' ?>" name='azureRegion' id='azureRegion' placeholder='Enter Azure Region' class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label for="azureEndPointUrl">Endpoint URL</label>
                            <input type="text" value="<?= isset($azureEndPointUrl) ? $azureEndPointUrl : '' ?>" name='azureEndPointUrl' id='azureEndPointUrl' placeholder='Enter Azure Endpoint URL' class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <input type='submit' name='update' id='update' value='Update' class='btn btn-success' />
                            <input type='reset' name='clear' id='clear' value='Clear' class='btn btn-danger' />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>