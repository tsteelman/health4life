<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Diseases', '/admin/diseases');
$this->Html->addCrumb(__('New Disease'));
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
			<?php echo __(h("Add Disease")); ?>
        </h1>
    </div><!--/.page-header-->
	<?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->

			<?php
			echo $this->Form->create('Disease', array(
				'action' => 'add',
				'class' => 'form-horizontal',
				'label' => false,
				'div' => false,
				'type' => 'file'
			));
			?>
            <div class="control-group">
                <label for="form-field-1" class="control-label">Parent Disease</label>

                <div class="controls">
					<?php
					echo $this->Form->input('parent_id', array(
						'type' => 'select',
						'options' => $parent_list,
						'default' => false,
						'empty' => 'none',
						'div' => false,
						'label' => false));
					?>
                </div>
            </div>
            <!--
			
                        <div class="form-group">
                            <div>
                                <label>
			<?php // echo __('Upload Profile Picture'); ?>
                                </label><span class="optional">(Optional)</span>
                            </div>
                            <div id="uploadPreview">
			<?php // echo $this->Html->image('user_default_3_medium.png', array('alt' => 'User Thumbnail', 'style' => 'width: 140px; height: 140px;', 'class' => "profile_brdr_5 img-responsive pull-left img-thumbnail")); ?>
                                <div class="col-lg-3 pull-left">
                                    <div id="bootstrapped-fine-uploader">
                                        <div class="qq-upload-button-selector qq-upload-button btn" style="width: auto;">
                                            <div>Upload</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>		
                            <div class="row">
                                <div class="col-lg-10">
                                    <div id="uploadmessages"></div>
                                </div>	
                            </div>
			
                            <input type="hidden" value="cropfileName" name="cropfileName" id="cropfileName" />
                            <input type="hidden" value="0" name="x1" id="x1" />
                            <input type="hidden" value="0" name="y1" id="y1" />
                            <input type="hidden" value="200" name="w" id="w" />
                            <input type="hidden" value="200" name="h" id="h" />
                        </div>
            -->
            <div class="control-group">
                <label class="control-label">
                    <span class="red_star_span"> *</span>
                    <span>Disease name</span>
                </label>
                <div class="controls">
					<?php
					echo $this->Form->input('name', array('class' => 'form-control',
						'label' => false,
						'div' => false));
					echo $this->Form->input("disease_image", array("type" => "hidden"));
					echo $this->Form->input("id", array("type" => "hidden"));
					?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">
                    <span>Home page name</span>
                </label>
                <div class="controls">
					<?php
					echo $this->Form->input('frontend_name', array('class' => 'form-control',
						'label' => false,
						'div' => false,
						'maxlength' => 30
					));
					?>
                </div>
            </div>

            <div class="control-group">
                <label for="form-field-2" class="control-label">Disease Image</label>
                <div class="controls span4">
                    <div class="avatar_upload_container">
                        <span> <?php echo __('Add Disease Image'); ?></span>
                        <div id="uploadPreview" class="col-lg-12">
							<?php echo $diseaseImg; ?>
                        </div>
                        <div class="space-4"></div>
                        <div id="uploadmessages"></div>
                        <div>
                            <div class="qq-upload-button-selector btn " id="upload_avatar" style="width: 40%;">
                                <div><i class="icon-upload icon-white">&nbsp;</i>Upload</div>
                            </div>
                            <div class="space-4"></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="" name="cropfileName" id="cropfileName" />
            </div>
            <div class="control-group">
                <label for="form-field-2" class="control-label">Disease Logo</label>
                <div class="controls span4">
                    <div class="avatar_upload_container">
                        <span> <?php echo __('Add Disease Logo'); ?></span>
                        <div id="logoPreview" class="col-lg-12">
							<?php echo $profileImg; ?>
							<?php
							if (isset($dashboard_data['profile_image'])) {
								$value = $dashboard_data['profile_image'];
							} else {
								$value = '';
							}
							echo $this->Form->input('profile_image', array('type' => 'hidden',
								'label' => false,
								'value' => $value,
								'div' => false));
							?>
                        </div>
                        <div class="space-4"></div>
                        <div>
                            <button class="btn" data-toggle="modal" data-target="#disease_logos">
                                Select Logo
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Profile Video (Youtube videos only)</label>
                <div class="controls span6">
                    <div id="disease_vido_url_form" class="add_video_url" style="display:block;">
                        <div class="form-group" id="link_input_section">
							<?php
							echo $this->Form->input('ProfileVideo', array(
								'placeholder' => __('Enter a video url'),
								'data-videoUrl' => 'true',
								'class' => 'form-control',
								'escape' => 'false',
								'label' => false,
								'div' => false,
								'value' => ''
									)
							);
							?>
                            <button type="button" id="disease_grab_video_url_btn" class="add_video btn btn_add pull-right event_video_add_btn" disabled="disabled" value="Add" data-videoUrl="true">Add</button>       
                        </div>
                        <div id="preview_profile_video" class="block " style="display: none;">
                            <div id="previewImages" class="pull-left">
                                <div id="previewImage"></div>
                                <div id="previewImagesNav" style="display: none;">			                
                                </div>
                            </div>
                            <div id="closeDiseaseVideoPreview" title="Remove" class="close_video_preview pull-right hidden" data-close="true">
                                <button class="close" type="button">×</button>
                            </div>
                            <div id="previewContent" class="pull-left" >
                                <div id="previewTitle" class="owner"></div>
                                <div id="previewUrl"></div>
                                <div id="previewDescription"></div>
                            </div>
                        </div>
                    </div>
                    <!--                        <div class="toolTip">
                                                You can enter one of the following :
                                                <ul>
                                                    <li>A video link ( YouTube, Vimeo etc ), or link to Ustream.tv channel(s) ( for event broadcasting ).</li>
                                                    <li>Brief description for the Online Event.</li>
                                                </ul>
                                            </div>-->
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Advertisement Video (Youtube videos only)</label>
                <div class="controls span6">
                    <div id="advertisement_video_url_form" class="add_video_url" style="display:block;">
                        <div id="advertisement_video">
                            <div class="form-group" id="link_input_section">
								<?php
								echo $this->Form->input('advertisement_video', array('class' => 'form-control',
									'label' => false,
									'type' => 'url',
									'value' => '',
									'div' => false,
									'placeholder' => 'Enter a video url',
									'data-videoUrl' => 'true'
								));
								?>
                                <button type="button" id="disease_grab_video_url_btn" class="add_video btn btn_add pull-right event_video_add_btn" disabled="disabled" value="Add" data-videoUrl="true">Add</button>       
                            </div>
                            <div id="preview_advertisement" class="block " style="display: none;">
                                <div id="previewImages" class="pull-left">
                                    <div id="previewImage"></div>
                                    <div id="previewImagesNav" style="display: none;">			                
                                    </div>
                                </div>
                                <div id="closeAdvertisementVideoPreview" title="Remove" class="close_video_preview pull-right hidden" data-close="true">
                                    <button class="close" type="button">×</button>
                                </div>
                                <div id="previewContent" class="pull-left" >
                                    <div id="previewTitle" class="owner"></div>
                                    <div id="previewUrl"></div>
                                    <div id="previewDescription"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Description</label>
                <div class="controls">
					<?php
					echo $this->form->input('Disease.description', array(
						'type' => 'textarea',
						'escape' => FALSE,
						'div' => FALSE,
						'label' => FALSE,
						'maxlength' => 300
					));
					?>
                </div>
            </div>
            <hr/>
            <h3 style="color:#2679B5;">
                Add Symptoms
            </h3>
            <div class="control-group">
                <label class="control-label"><?php echo __('Symptoms') ?></label>
                <div class="controls">
                    <ul class="facelist">
                        <li class="token-input">

							<?php
							echo $this->Form->input("DiseaseSymptoms", array(
								'type' => 'text',
								'class' => 'medication_input',
								'placeholder' => 'Multiple symptoms can be added',
								'div' => false,
								'label' => false,
								'style' => 'width:220px;'
							));

							echo $this->Form->hidden("DiseaseSymptoms_id", array(
								'class' => 'treatment_id_hidden',
								'value' => '',
								'div' => false,
								'label' => false
							));
							?>
                        </li>
                    </ul>

                    <div class="result_list" style="display:none;"></div>
                </div>
            </div>
            <hr/>
            <h3 style="color:#2679B5;">
                Add Survey
            </h3>
            <div class="control-group">
                <label class="control-label">Survey name</label>
                <div class="controls">
					<?php
					echo $this->Form->input('survey_id', array(
						'type' => 'select',
						'options' => $surveyList,
						'empty' => 'select one survey',
						'default' => '0',
						'div' => false,
						'label' => false));
					?>
                </div>
            </div>
            <hr/>
            <h3 style="color:#2679B5;">
                Library
            </h3>
            <div class="control-group library_videos_input">
                <label class="control-label">Video <?php echo 1; ?></label>



                <div class="controls span6">
                    <div id="library_video_url_form_<?php echo 1; ?>" class="add_library_video_url" style="display:block;">
                        <div class="form-group" id="link_input_section">
							<?php
							echo $this->Form->input('Disease.url.' . 1 . '.src', array('class' => 'form-control',
								'label' => false,
								'type' => 'url',
								'value' => '',
								'div' => false,
								'placeholder' => 'Enter a video url',
								'data-videoUrl' => 'true'
							));
							echo $this->Form->input('Disease.url.' . 1 . '.image', array('class' => 'hidden',
								'label' => false,
								'type' => 'url',
								'div' => false,
								'value' => '',
								'style' => 'display:none'
							));
							?>
                            <button type="button" id="disease_grab_video_url_btn" class="add_video btn btn_add pull-right event_video_add_btn" disabled="disabled" value="Add" data-videoUrl="true">Add</button>       
                        </div>
                        <div id="preview_library_<?php echo 1; ?>" style="display: none;">
                            <div id="previewImages" class="pull-left">
                                <div id="previewImage"></div>
                                <div id="previewImagesNav" style="display: none;">			                
                                </div>
                            </div>
                            <div id="closeLibraryVideoPreview<?php echo 1; ?>" title="Remove" class="close_video_preview pull-right hidden" data-close="true">
                                <button class="close" type="button">×</button>
                            </div>
                            <div id="previewContent" class="pull-left" >
                                <div id="previewTitle" class="owner"></div>
                                <div id="previewUrl"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button id="add_more_videos_btn" class="add_more_videos_btn btn" type="button">+ Add more</button>
            <hr/>
            <h3 style="color:#2679B5;">
                Add Dashboard Details
            </h3>
            <div class="control-group">
                <label class="control-label span2">Connect</label>
                <div class="controls span6">
					<?php
					echo $this->Form->input('connect_text', array(
						'type' => 'textarea',
						'placeholder' => 'Add Text Here',
						'maxlength' => '250',
						'class' => 'dashboard_data_teaxtarea',
						'div' => false,
						'label' => false));
					?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label span2">Track & Manage Health</label>
                <div class="controls span6">
					<?php
					echo $this->Form->input('manage_health_text', array(
						'type' => 'textarea',
						'placeholder' => 'Add Text Here',
						'maxlength' => '250',
						'class' => 'dashboard_data_teaxtarea',
						'div' => false,
						'label' => false));
					?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label span2">Learn & Empower</label>
                <div class="controls span6">
					<?php
					echo $this->Form->input('learn_text', array(
						'type' => 'textarea',
						'placeholder' => 'Add Text Here',
						'maxlength' => '250',
						'class' => 'dashboard_data_teaxtarea',
						'div' => false,
						'label' => false));
					?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label span2">Manage Medical Records</label>
                <div class="controls span6">
					<?php
					echo $this->Form->input('medical_mecords_text', array(
						'type' => 'textarea',
						'placeholder' => 'Add Text Here',
						'maxlength' => '250',
						'class' => 'dashboard_data_teaxtarea',
						'div' => false,
						'label' => false));
					?>
                </div>
            </div>
            <!-- hiding
            <div class="control-group">
              <label class="control-label">Choose an Image</label>
              <div class="controls">
                <div id="uploadPreview">
			<?php echo $this->Html->image('/theme/Admin/avatars/user.jpg', array('alt' => 'Default Event Photo', 'style' => 'height: 210px;', 'class' => 'img-responsive img-thumbnail', 'id' => 'preview_image')); ?>
			<?php echo $this->Html->image('loading_big.gif', array('alt' => 'Loading image...', 'style' => 'height: 210px;', 'class' => 'img-responsive img-thumbnail hide', 'id' => 'loading_img')); ?>
                </div>
                <label>  </label>
                <div id="uploadmessages" class="alert" style="display: none;"></div>
                <div id="bootstrapped-fine-uploader" style="position: relative; overflow: hidden; direction: ltr;width:125px;" class="">
                  <div style="width: auto;" class="qq-upload-button-selector qq-upload-button btn btn-success">
                    <div>Upload Image</div>
                  </div>
                </div>
              </div>
            </div><!--/.control-group-->

			<div class="modal-footer">
				<?php $options = array('id' => 'save_button', 'label' => 'Save', 'class' => 'btn btn-primary'); ?>
				<?php echo $this->Form->end($options); ?>
            </div>

        </div><!--/.page-content-->
    </div> 

    <!-- Modal for selecting disease logo -->
    <div class="modal fade" id="disease_logos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style='display:none;'>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center" id="myModalLabel">Disease Logos</h4>
                </div>
                <div class="modal-body">

					<?php
					if (!empty($disease_logos)) {
						foreach ($disease_logos as $key => $logo) {
							?>
							<div class="padding_block">
								<div class="image_div" value="<?php echo $logo; ?>" key="<?php echo $key; ?>">
									<img src="<?php echo Configure::read('App.UPLOAD_PATH_URL') . '/disease_logos/' . $logo; ?>">
								</div>
							</div>
							<?php
						}
					}
					?>
                </div>
                <div class="modal-footer">
                    <button id="cancel_logo" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="save_logo" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="/theme/App/js/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="/theme/App/js/vendor/bootbox.min.js"></script>
    <script type="text/javascript" src="/theme/App/js/vendor/jquery.imgareaselect.min.js"></script>
    <script type="text/javascript" src="/theme/App/js/vendor/fineuploader-4.0.2.min.js"></script>
    <script type="text/javascript" src="/theme/Admin/js/disease.js"></script>
    <link rel="stylesheet" href="/theme/App/css/fineuploader-4.0.2.min.css"/>
    <link rel="stylesheet" href="/theme/App/css/imgareaselect-animated.css"/>
    <script type="text/javascript">
		$(document).ready(function() {

			$('.medication_input').each(function() {
				initFaceList('#' + $(this).attr('id'));
			});
			$('#editor1').html($('#DiseaseDescription').val());
			$("ul.nav-list li").removeClass('active');
			$('#disease-list-li').addClass('active');
		});

		function initFaceList(input) {
			var result_field = $(input).next('.treatment_id_hidden').attr('id');
			$(input).facelist('/api/searchSymptoms', properties = {
				matchContains: true,
				minChars: 2,
				selectFirst: false,
				intro_text: 'Type Name',
				no_result: 'No Names',
				result_field: 'DiseaseDiseaseSymptomsId'
			});
		}
		$(document).on('click', '.dropdown-toggle', function() {
			$(this).parent().toggleClass('open');
		});
    </script>