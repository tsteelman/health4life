<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
$this->Html->addCrumb('Privacy Settings');
?>
<div class="container">
  <div class="row edit">
    <div class="col-lg-3 edit_lhs">
         <div class="respns_header"><h2>Privacy Settings</h2></div>
      <?php echo $this->element('User.Edit/lhs'); ?>
    </div>
    <div class="col-lg-9">


      <div class="page-header">           
        <h2>
          <span><?php echo __('Privacy Settings'); ?></span>&nbsp;
        </h2>         
      </div>
      <?php
      echo $this->Form->create('', array(
      'inputDefaults' => $inputDefaults
      ));
      ?>
      <div class="form-group clearfix">
        <div class="col-lg-3 col-sm-3 privacy_settings_left"><label><?php echo __('Who can see "My Activity" ?'); ?> </label></div>
        <div class="col-lg-7 col-sm-7 privacy_settings_right">
          <?php 
          echo $this->Form->input('post_on_wall', array('options' => $options, 'default' => 2, 'value' => $privacyfields['post_on_wall'])); ?>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 col-sm-3 privacy_settings_left"><label><?php echo __('Who can see "My Friends" ? '); ?> </label></div>
        <div class="col-lg-7 col-sm-7 privacy_settings_right">
          <?php echo $this->Form->input('view_your_friends', array('options' => $options, 'default' => '2', 'value' => $privacyfields['view_your_friends'])); ?>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 col-sm-3 privacy_settings_left"><label><?php echo __('Who can see "My Health" ?'); ?> </label></div>
        <div class="col-lg-7 col-sm-7 privacy_settings_right">
          <?php echo $this->Form->input('view_your_health', array('options' => $options, 'default' => '2', 'value' => $privacyfields['view_your_health'])); ?>
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 col-sm-3 privacy_settings_left"><label><?php echo __('Who can see my "Blog" ?'); ?> </label></div>
        <div class="col-lg-7 col-sm-7 privacy_settings_right">
          <?php echo $this->Form->input('view_your_blog', array('options' => $options, 'default' => '2', 'value' => $privacyfields['view_your_blog'])); ?>
        </div>
      </div>        
      <!--div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left"><label><?php //echo __('Who can see "My Nutrition" ?'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right">
          <?php //echo $this->Form->input('view_your_nutrition', array('options' => $options, 'default' => '2', 'value' => $privacyfields['view_your_nutrition'])); ?>
        </div>
      </div-->
<!--      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left"><label><?php echo __('Who can see "My Communities" ?'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right">
          <?php //echo $this->Form->input('view_your_communities', array('options' => $options, 'default' => '2', 'value' => $privacyfields['view_your_communities'])); ?>
        </div>
      </div>-->
<!--      <div class="form-group clearfix">
        <div class="col-lg-3 privacy_settings_left"><label><?php echo __('Who can see "My Events" ?'); ?> </label></div>
        <div class="col-lg-7 privacy_settings_right">
          <?php //echo $this->Form->input('view_your_events', array('options' => $options, 'default' => '2', 'value' => $privacyfields['view_your_events'])); ?>
        </div>
      </div>-->
       <div class="form-group clearfix">
        <div class="col-lg-3 col-sm-3 privacy_settings_left"><label><?php echo __('Who can see my condition & medication?'); ?> </label></div>
        <div class="col-lg-7 col-sm-7 privacy_settings_right">
          <?php echo $this->Form->input('view_your_disease', array('options' => $options, 'default' => '2', 'value' => $privacyfields['view_your_disease'])); ?>
        </div>
      </div>
	  <div class="form-group clearfix">
		  <div class="col-lg-3 col-sm-3 privacy_settings_left"><label><?php echo __('Who can see you in search results?'); ?> </label></div>
		  <div class="col-lg-7 col-sm-7 privacy_settings_right">
			  <?php echo $this->Form->input('searchable_by', array('options' => $options)); ?>
		  </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-lg-3 col-sm-3 privacy_settings_left"><label>&nbsp;</label></div>
        <div class="col-lg-5 col-sm-5 ">
          <button type="submit" class="btn btn-next"><?php echo __('Save'); ?></button>
		  <button type="button" class="btn btn_clear settings_cancel">Cancel</button>
        </div>
      </div>
      <?php echo $this->Form->end(); ?>

    </div>
  </div>
</div>
