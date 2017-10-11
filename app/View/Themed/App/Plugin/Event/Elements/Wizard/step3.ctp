<div id="event_wizard_step3_common" class="<?php echo $step3CommonVisibilityClass; ?>">
	<div class="form-group invitaion_box">
		<?php echo $this->element('invite_friends'); ?>
	</div>
	<div class="form-group invitaion_box">
		<?php echo $this->element('invite_community_friends'); ?>
	</div>
	<div class="form-group">
		<div class="col-lg-3 col-sm-3 col-md-3"><label> </label></div>
		<div class="col-lg-8">   
			<?php echo $this->Form->input('guest_can_invite', array('type' => 'checkbox', 'class' => 'input_chckbx')); ?>
			<span><?php echo __('Allow guests to invite other users?'); ?></span>
		</div>
	</div>
</div>

<div id="event_wizard_step3_sitewide" class="<?php echo $step3SiteWideVisibilityClass; ?>">
	<div class="col-lg-12 alert alert-warning">All users will be notified, because this is a site wide event.</div>
	<br /><br /><br />
</div>

<div class="form-group">
    <div class="col-lg-3 col-sm-3 col-md-3"><label> </label></div>
    <div class="col-lg-8 col-sm-8 col-md-8"> 
        <button type="button" class="btn btn-default btn-prev"><img src="/theme/App/img/back_arow.png" alt="<" /> <?php echo ('Back'); ?></button>
        <button type="button" class="btn btn-finish btn-next"><?php echo $finishBtnTxt; ?></button>
    </div>
</div>