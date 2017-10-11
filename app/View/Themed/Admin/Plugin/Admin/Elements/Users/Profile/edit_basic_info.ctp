<div class="tab-pane active" id="edit-basic">

	<?php
	$modelName = '';
	echo $this->Form->create($modelName, array(
		'id' => $formId,
		'class' => 'form-horizontal',
		'inputDefaults' => $inputDefaults
	));
	echo $this->Form->hidden('id');
	echo $this->Form->hidden('old_email');
	echo $this->Form->hidden('form_id', array('value' => 'basic_info'));
	?>
	<input type="hidden" value="cropfileName" name="cropfileName" id="cropfileName" />
	<input type="hidden" value="0" name="x1" id="x1" />
	<input type="hidden" value="0" name="y1" id="y1" />
	<input type="hidden" value="200" name="w" id="w" />
	<input type="hidden" value="200" name="h" id="h" />

	<h4 class="header blue bolder smaller"><?php echo __('Edit Basic Info'); ?></h4>

	<div class="row-fluid">

		<div class="span4">
			<div class="avatar_upload_container">
				<span> <?php echo __('Change Profile Picture'); ?></span>
				<div id="uploadPreview" class="col-lg-12">
					<?php echo $profileImg; ?>
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

		<div class="vspace"></div>

		<div class="span8">
			<div class="control-group">
				<label for="form-field-username" class="control-label"><?php echo __('Username'); ?></label>

				<div class="controls">
					<?php echo $this->Form->input('username', array('placeholder' => 'Username')); ?>
				</div>
			</div>

			<div class="control-group">
				<label for="form-field-first" class="control-label"><?php echo __('Name'); ?></label>

				<div class="controls">
					<?php echo $this->Form->input('first_name', array('placeholder' => 'First Name', 'class' => 'input-small')); ?>
					<?php echo $this->Form->input('last_name', array('placeholder' => 'Last Name', 'class' => 'input-small')); ?>
				</div>
			</div>


			<div class="control-group">
				<label for="form-field-email" class="control-label"><?php echo __('Email'); ?></label>

				<div class="controls">
					<span class="input-icon input-icon-right">
						<?php echo $this->Form->input('email', array('placeholder' => 'Email')); ?>
						<i class="icon-envelope"></i>
					</span>
				</div>
			</div>

			<div class="control-group">
				<label for="form-field-date" class="control-label"><?php echo __('Birth Date'); ?></label>

				<div class="controls">
					<div class="input-append">
						<?php echo $this->Form->input('date_of_birth', array('type' => 'text', 'readonly' => true, 'placeholder' => 'mm-dd-yyyy', 'data-date-format' => 'mm-dd-yyyy', 'class' => 'input-small date-picker')); ?>
						<span class="add-on">
							<i class="icon-calendar"></i>
						</span>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label"><?php echo __('Gender'); ?></label>
				<div class="controls">
					<div class="space-2"></div>

					<label class="inline" for="UserGenderM">
						<input type="radio" name="data[User][gender]" id="UserGenderM" value="M" <?php echo ($gender === 'M') ? 'checked' : ''; ?> />
						<span class="lbl"><?php echo __(' Male'); ?></span>
					</label>


					<label class="inline" for="UserGenderF">
						<input type="radio" name="data[User][gender]" id="UserGenderF" value="F" <?php echo ($gender === 'F') ? 'checked' : ''; ?>  />
						<span class="lbl"><?php echo __(' Female'); ?></span>
					</label>
				</div>
			</div>


			<div class="control-group">
				<label for="form-field-username" class="control-label"><?php echo __('Timezone'); ?></label>

				<div class="controls">
					<?php echo $this->Form->input('timezone', array('type' => 'select', 'options' => $timezoneList, 'class' => 'chosen-select')); ?>
				</div>
			</div>
			<div class="control-group">
				<label for="form-field-username" class="control-label"><?php echo __('Is Super Admin?'); ?></label>

				<div class="controls">
					<div class="space-2"></div>

					<label class="inline">
						<span class="lbl"> <?php echo $superAdminStatus; ?></span>
					</label>
				</div>
			</div>
		</div>
	</div>

	<?php
	echo $this->element('Admin.Users/Profile/action_buttons');
	echo $this->Form->end();
	?>
</div>

<?php
echo $this->AssetCompress->css('admin_edit_profile');
$this->AssetCompress->addScript(array(
	'bootstrap-datepicker.min.js',
	'vendor/chosen.jquery.min.js',
	'vendor/fineuploader-4.0.2.min.js',
	'vendor/bootbox.min.js',
	'vendor/jquery.imgareaselect.min.js',
	'profile.js'), 'admin_edit_profile.js');
echo $this->AssetCompress->includeJs('admin_edit_profile');