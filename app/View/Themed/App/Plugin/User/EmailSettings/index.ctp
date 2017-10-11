<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
$this->Html->addCrumb('Email Settings');
?>
<div class="container" id="email_settings">
  <div class="row edit">
    <div class="col-lg-3 edit_lhs">
         <div class="respns_header"><h2>Email Settings</h2></div>
      <?php echo $this->element('User.Edit/lhs'); ?>
    </div>
    <div class="col-lg-9">


      <div class="page-header">           
        <h2>
          <span><?php echo __('Email Settings'); ?></span>&nbsp;
        </h2>         
      </div>
      <?php
      echo $this->Form->create($model, array('inputDefaults' => $inputDefaults));
      $settings = $setting['email_settings'];
      if (!empty($settings['id'])) {
        echo $this->Form->input('id', array('type'=> 'hidden', 'value' => $settings['id']));
      }
      
      ?>
             
      <div class="form-group clearfix ">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Newsletter mail'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('news_letter', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['news_letter']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
        <div class="page-header">
            <h2 class="sub-head"><?php echo __('Reminder mails'); ?></h2>
        </div>       
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('How am I feeling today'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('how_am_i_feeling', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['how_am_i_feeling']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
          <div class="mail_frequecy">
            <?php echo __('Daily'); ?>
          </div>
        </div>
      </div>
      <div class="form-group clearfix ">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Friends request reminder mails'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('friends_request_reminder', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['friends_request_reminder']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
          <div class="mail_frequecy">
            <?php echo __('Daily'); ?>
          </div>
        </div>
      </div>
        <div class="page-header">
            <h2 class="sub-head"><?php echo __('Notification mails'); ?></h2>
        </div>       
      <div class="form-group clearfix">
		  <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Site Wide Event'); ?> </label></div>
		  <div class="col-lg-7 privacy_settings_right col-sm-7">
			  <div class="span3">
				  <label>
					  <?php echo $this->Form->checkbox('site_wide_event', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked' => ((empty($settings['site_wide_event'])) ? false : true))); ?>
					  <span class="lbl"></span>
				  </label>
			  </div>
		  </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Invitation for Event'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('event_invitation', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['event_invitation']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Event update'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('event_update', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['event_update']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Event reminder'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('event_reminder', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['event_reminder']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Event cancellation'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('event_cancelation', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['event_cancelation']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Friends Request'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('friend_request', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['friend_request']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Friends Request Approved'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('friend_request_approval', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['friend_request_approval']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Someone posts on my wall'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('post_on_wall', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['post_on_wall']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Someone comments on my post'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('comment_on_post', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['comment_on_post']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Other posts where I was involved in commenting'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('post_i_follow', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['post_i_follow']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Invitation to join community'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('community_invitation', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['community_invitation']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
		  <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Site Wide community'); ?> </label></div>
		  <div class="col-lg-7 privacy_settings_right col-sm-7">
			  <div class="span3">
				  <label>
					  <?php echo $this->Form->checkbox('site_wide_community', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked' => ((empty($settings['site_wide_community'])) ? false : true))); ?>
					  <span class="lbl"></span>
				  </label>
			  </div>
		  </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Community has been removed'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('community_removed', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['community_removed']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Community requests (for ones I own)'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('group_request', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['group_request']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Community activities for the ones  I own'); ?><br><p class="minor-desc"><?php echo __('(this includes post, comment, vote)');?></p> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('my_group_activities', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['my_group_activities']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Community activity for ones I am a member'); ?><br><p class="minor-desc"><?php echo __('(this includes post, comment, vote)');?></p> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('other_group_activities', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['other_group_activities']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Event RSVPs'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('event_rsvp', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['event_rsvp']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Event activity for events I created'); ?><br><p class="minor-desc"><?php echo __('(this includes post, comment)');?></p> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('my_event_activity', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['my_event_activity']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Event activity for events attending'); ?><br><p class="minor-desc"><?php echo __('(this includes post, comment)');?></p></label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('other_event_activity', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['other_event_activity']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Someone answers one of my questions'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('answered_my_question', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['answered_my_question']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Someone answers questions where I have also answered questions'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('answered_same_question', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['answered_same_question']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Someone sends me a message'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right col-sm-7">
          <div class="span3">
            <label>
              <?php echo $this->Form->checkbox('message', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked'=> ((empty($settings['message']))? false : true))); ?>
              <span class="lbl"></span>
            </label>
          </div>
        </div>
      </div>

      <div class="form-group clearfix">
		  <div class="col-lg-3 privacy_settings_left col-sm-5"><label><?php echo __('Friend recommendation emails'); ?> </label></div>
		  <div class="col-lg-7 privacy_settings_right col-sm-7">
			  <div class="span3">
				  <label>
					  <?php echo $this->Form->checkbox('recommend_friends', array('hiddenField' => false, 'class' => 'ace-switch ace-switch-3', 'checked' => ((empty($settings['recommend_friends'])) ? false : true))); ?>
					  <span class="lbl"></span>
				  </label>
			  </div>
			  <div class="col-lg-7 mail_frequecy_select col-sm-5 col-md-5">
				  <?php
				  $showHideFrequencyClass = empty($settings['recommend_friends']) ? 'hide' : '';
				  echo $this->Form->input('recommend_friends_frequency', array(
					  'class' => "form-control pull-left {$showHideFrequencyClass}",
					  'options' => $frequencyOptions
				  ));
				  ?>
			  </div>
		  </div>
      </div>
	  
      <div class="form-group clearfix">
        <div class="col-lg-3 col-sm-5 privacy_settings_left "><label>&nbsp;</label></div>
        <div class="col-lg-5 col-sm-7">
          <button type="submit" class="btn btn-next"><?php echo __('Save'); ?></button>
		  <button type="button" class="btn btn_clear settings_cancel">Cancel</button>
          
        </div>
      </div>
      <?php echo $this->Form->end(); ?>

    </div>
  </div>
</div>
