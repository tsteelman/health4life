<div class="form-group">
    <label>
        <?php echo __('Username'); ?>
        <span class="red_star_span"> *</span>
    </label>
	<!--Only alphanumeric and !@#$%^&*()?~-_ are allowed.-->
    <?php echo $this->Form->input('username', array('autocomplete' => 'off', 'class' => 'form-control', 'placeholder' => __('User Name'))); ?>
</div>
<div class="form-group">
    <label>
        <?php echo __('Email'); ?>
        <span class="red_star_span"> *</span>
    </label>
    <?php echo $this->Form->input('email', array('autocomplete' => 'off', 'type' => 'email', 'class' => 'form-control', 'placeholder' => __('Email address'))); ?>
</div>
<div class="form-group row" id="password">
    <label>
        <?php echo __('Password'); ?>
        <span class="red_star_span"> *</span>
    </label>
    <?php echo $this->Form->input('password', array('autocomplete' => 'off', 'type' => 'password', 'class' => 'form-control password', 'placeholder' => __('Six or more characters'))); ?>
</div>
<div class="form-group">
    <label>
        <?php echo __('Confirm Password'); ?>
        <span class="red_star_span"> *</span>
    </label>
    <?php echo $this->Form->input('confirm-password', array('type' => 'password', 'class' => 'form-control', 'placeholder' => __('Six or more characters'))); ?>
</div>
<div class="form-group">
    <input class="chck_box" id="agree_check" name="data[User][agree]"
           type="checkbox"> <span class="terms_conditions" onclick="$('#agree_check').click();">
        <?php echo __('I agree to the'); ?>&nbsp;<a target="_blank" href="/pages/terms_of_service"><?php echo __('Terms & Conditions'); ?></a>&nbsp;<?php echo __('and'); ?>&nbsp;<a target="_blank" href="/pages/terms_of_service"><?php echo __('Privacy Policy'); ?></a>
    </span>
</div>
<div class=" flt_lft btn_area">
    <button type="button" class="btn btn-next">Next &nbsp;<?php echo $this->Html->image('nxt_arow.png', array('alt' => 'Next')); ?></button>
</div>