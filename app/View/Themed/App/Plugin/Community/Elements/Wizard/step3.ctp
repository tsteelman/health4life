<div id="community_wizard_step3_common" class="<?php echo $step3CommonVisibilityClass; ?>">
	<div class="form-group invitaion_box">
		<?php echo $this->element('invite_friends'); ?>
	</div>
	<div class="form-group">
		<div class="col-lg-3 col-md-3 col-sm-3"><label>&nbsp;</label></div>
		<div class="col-lg-8 col-md-8 col-sm-8">   
			<?php echo $this->Form->input('member_can_invite', array('type' => 'checkbox', 'class' => 'input_chckbx')); ?>
			<span><?php echo __('Allow community members to invite members?'); ?></span>
		</div>
	</div>
</div>

<div id="community_wizard_step3_sitewide" class="<?php echo $step3SiteWideVisibilityClass; ?>">
	<div class="col-lg-12 alert alert-warning">All users will be notified, because this is a site wide community.</div>
	<br /><br /><br />
</div>

<div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label>&nbsp;</label></div>
    <div class="col-lg-8 col-md-8 col-sm-8">  
        <button type="button" class="btn btn-default btn-prev"><img src="/theme/App/img/back_arow.png" alt="<" /> <?php echo __('Back'); ?></button>
          <button type="button" class="btn btn-finish btn-next"><?php echo __($submitBtnTxt); ?></button>
    </div>
</div>