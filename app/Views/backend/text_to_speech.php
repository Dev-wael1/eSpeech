<!-- Main Content -->
<div class="main-content">
    <section id='tts_form' class='section'>
        <div class="section-header">
            <h1><?= labels('text_to_speech','Text To Speech') ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('auth') ?>"><?= ($admin) ? "Admin" : "User" ?></a></div>
                <div class="breadcrumb-item">Text to Speech</div>
            </div>
        </div>
        <audio id='audio_controll'></audio>
        <div class="container-fluid card">
            <div class="card-header">
                <h4 class='section-title'><?= labels('text_to_speech','Text To Speech') ?> </h4>
            </div>
            <div class="card-body">
                <div class="row">
                <?php
        if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
            ?>
        <div class="col-sm-12">
        <div class="alert alert-warning mb-2">
                    <b>Note:</b> If you cannot Synthesize here, please close the codecanyon frame by clicking on <b>x Remove Frame</b> button from top right corner on the page or <a href="<?=current_url()?>" target="_blank">&gt;&gt; Click here &lt;&lt;</a>
                </div>
        </div>
        <?php } ?>
                    <div class="col-md">
                        <div class="form-group">
                            <label><?= labels('select_language','Select Language') ?> <small class="text-danger">*</small></label>
                            <select class="form-control" name='language' id='language' onchange="set_voices()">
                                <option value=""> <?= labels('select_language','Languages') ?></option>
                                <?php foreach ($languages as $key => $value) { ?>
                                    <option data-image="<?php echo base_url($value['flag']); ?>" value="<?= $value['language_code'] ?>">
                                        <?= $value['language_name']  ?>
                                    </option>
                                <?php } ?>
                                <!-- <?//php foreach ($languages as $key => $val) { ?>
                                    <option data-image="<?//php echo base_url('public/flags') . "/" . strtolower(substr($key, strpos($key, '-') + 1, strlen($key))); ?>.svg" value="<?//= $key ?>">
                                        <?//= $val  ?>
                                    </option>
                                <?//php } ?> -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label><?= labels('select_voices','Select Voices') ?> <small class="text-danger">*</small></label>
                            <select class="form-control selectric" name='voice' id="voice" onchange="set_predefined()">
                                <option> <span class="iconify" data-icon="logos:aws"></span> <?= labels('select_voices','Voices') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label> <?= labels('listen_selected_voice','Sample Voice') ?></label>
                            <button class="form-control btn btn-outline-primary" id='listen_sample_voice' style='width:100%'><i class="fas fa-play" id="play_test"></i><i class="fas fa-pause" id="pause_test"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label> <?= labels('title','Title') ?> ( <?= labels('optional','Optional') ?> )</label>
                            <input type="text" name="" class="form-control" id="title">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= labels('voice_modulations','Voice Modulations') ?></label>
                        </div>
                    </div>
                    <div class="col-md-2" id="voice_effects">
                        <div class="form-group">
                            
                            <select class='form-control' id="voice_effect" onchange="insert_ssml(this)">
                                <option data-start-tag="" data-end-tag="">Voice Effects</option>
                                <?php
                                foreach ($tags as $row) {
                                    if ($row['type'] == 'voice_effects') {

                                ?>
                                        <option data-start-tag="<?= $row['start_tag']  ?>" data-end-tag="<?= $row['end_tag']  ?>"><?= $row['title']  ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" id="say_as">
                        <div class="form-group">
                     
                            <select class='form-control' id="" onchange="insert_ssml(this)">
                                <option data-start-tag="" data-end-tag="">Say As</option>
                                <?php
                                foreach ($tags as $row) {
                                    if ($row['type'] == 'say_as') {

                                ?>
                                        <option data-start-tag="<?= $row['start_tag']  ?>" data-end-tag="<?= $row['end_tag']  ?>"><?= $row['title']  ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" id="emphasis">
                        <div class="form-group">
                        
                            <select class='form-control' onchange="insert_ssml(this)">
                                <option data-start-tag="" data-end-tag="">Emphasis</option>
                                <?php
                                foreach ($tags as $row) {
                                    if ($row['type'] == 'emphasis') {

                                ?>
                                        <option data-start-tag="<?= $row['start_tag']  ?>" data-end-tag="<?= $row['end_tag']  ?>"><?= $row['title']  ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" id="volume">
                        <div class="form-group">
                    
                            <select class='form-control' id="" onchange="insert_ssml(this)">
                                <option data-start-tag="" data-end-tag="">Volume</option>
                                <?php
                                foreach ($tags as $row) {
                                    if ($row['type'] == 'volume') {

                                ?>
                                        <option data-start-tag="<?= $row['start_tag']  ?>" data-end-tag="<?= $row['end_tag']  ?>"><?= $row['title']  ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                       
                            <select class='form-control' id="" onchange="insert_ssml(this)">
                                <option data-start-tag="" data-end-tag="">Speed</option>
                                <?php
                                foreach ($tags as $row) {
                                    if ($row['type'] == 'speed') {

                                ?>
                                        <option data-start-tag="<?= $row['start_tag']  ?>" data-end-tag="<?= $row['end_tag']  ?>"><?= $row['title']  ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                           
                            <select class='form-control' id="" onchange="insert_ssml(this)">
                                <option data-start-tag="" data-end-tag="">Pitch</option>
                                <?php
                                foreach ($tags as $row) {
                                    if ($row['type'] == 'pitch') {

                                ?>
                                        <option data-start-tag="<?= $row['start_tag']  ?>" data-end-tag="<?= $row['end_tag']  ?>"><?= $row['title']  ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                     
                            <select class='form-control' id="" onchange="insert_ssml(this)">
                                <option data-start-tag="" data-end-tag="">Pauses</option>
                                <?php foreach ($tags as $row) {
                                    if ($row['type'] == 'pauses') {
                                ?>
                                        <option data-start-tag="<?= $row['start_tag']  ?>" data-end-tag="<?= $row['end_tag']  ?>"><?= $row['title']  ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label><?= labels('text','Text') ?> <small class="text-danger">*</small></label>
                            </div>
                            <div class="col-md-6">
                                <button href="" class="float-right btn btn-danger btn-sm" onclick="clear_tags()"><?= labels('clear_voice_effect','Clear Voice Effects') ?></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea id='text' class='form-control' style='width:100%; resize: none; height:10em;' placeholder="<?= labels('enter_text_here','Let your thoughts speak out loud') ?>" name='tts_text' id='tts_text'></textarea><br><span id='limit'>0</span><span></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                     <div class="col-md-5">
                        <div class="custom-file">
                            <label for="formFile" class="form-label"><?= labels('upload_via_text_file','Upload text via a text file [ Choose .txt file to upload a text ]') ?></label>
                            <input  type="file" name="add_file" id="add_file" class="form-control" accept=".txt">
                        </div> 
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <div class="custom-control custom-switch mt-4">
                            <input class="custom-control-input" type="checkbox" name="changer" id="changer" aria-checked="true" checked="true">
                            <label class="custom-control-label" for="changer">
                                <p id="para" class="ml-10">
                                Append text at the end
                                </p>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <button id='get_tts' class='w-100 btn btn-primary'><i class="fas fa-microphone"></i>&nbsp; <?= labels('synthesize_text','Synthesize Text') ?></button>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <button class='w-100 btn btn-success' id="tts-play"><i class="fas fa-play" id="play_synthesize"></i><i class="fas fa-pause" id="pause_synthesize"></i> &nbsp;<span id="play_text"><?= labels('play_audio','Play audio') ?></span> </button>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <a id='download_tts' style="color: white;" class='w-100 btn btn-warning'><i class="fas fa-arrow-down"></i>&nbsp;<?= labels('download_audio','Download audio') ?></a>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <button id='save_tts' class='w-100 btn btn-info'><i class="fas fa-save"></i>&nbsp;<?= labels('save_result','Save Result') ?></button>
                        </div>
                    </div>
                    <?php if ($admin) { ?>
                        <div class="col-md">
                            <div class="form-group">
                                <button id='save_predefined' onclick="save_predefined()" class='w-100 btn btn-warning'><i class="fas fa-save"></i>&nbsp;<?= labels('save_as_predefine','Save As Predefine') ?></button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- predefined audio  -->
        <audio id='predefined_audio'></audio>
        <!--/ predefined audio  -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><?= labels('saved_text_to_speech','Saved Text to Speech') ?></h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="tts_table" data-detail-view="true" data-detail-formatter="detailFormatter" data-auto-refresh="true" data-toggle="table" data-url="<?= base_url("/admin/text-to-speech/tts-list") ?>" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 25, 50, 100, 200, All]" data-search="true" data-show-columns="true" data-show-columns-search="true" data-show-refresh="true" data-sort-name="id" data-sort-order="DESC" data-query-params="ttsQueryParams">
                            <thead>
                                <tr>
                                    <th data-field="id" data-visible="false" data-sortable="true"><?= labels('id','ID') ?></th>
                                    <th data-field="title" data-sortable="true"><?= labels('title','Title') ?></th>
                                    <th data-field="language" data-sortable="true" data-visible="false"><?= labels('language','Language') ?></th>
                                    <th data-field="voice" data-sortable="true" data-visible="false"><?= labels('voice','Voice') ?></th>
                                    <th data-field="provider" data-sortable="true"><?= labels('provider','Provider') ?></th>
                                    <th data-field="text" data-visible="false" data-sortable="true"><?= labels('text','Text') ?></th>
                                    <th data-field="used_characters" data-sortable="true"><?= labels('characters_used','Used Characters') ?></th>
                                    <th data-field="created_on" data-sortable="true"><?= labels('created_on','Created on') ?></th>
                                    <th data-field="operate"><?= labels('operate','Operate') ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="user_id" value="<?= $userId ?>">
        <div class="iziToast-wrapper iziToast-wrapper-topRight"></div>