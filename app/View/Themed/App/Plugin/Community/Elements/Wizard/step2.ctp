<div class="form-group">
    <div class="col-lg-3 col-sm-3 col-md-3"><label> <?php echo __('Location'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-4 form-group_col col-sm-4 col-md-4">
        <?php
        echo $this->Form->input('country', array('options' => $countries,
            'empty' => __('Select Country'),
            'class' => 'form-control country',
            'data-rel' => 'country#state#city#zip',
            'onchange' => 'getStateList(this)'
        ));
        ?>
    </div>
    <div class="col-lg-4 form-group-col col-sm-4 col-md-4">
        <?php
        echo $this->Form->input('state', array('options' => $states,
            'empty' => __('Select State/Province'),
            'disabled' => $stateDisabled,
            'class' => 'form-control state',
            'data-rel' => 'country#state#city',
            'onchange' => 'getCityList(this)'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-3 col-sm-3 col-md-3"><label> </label></div>
    <div class="col-lg-4 form-group-col col-sm-4 col-md-4">
        <?php
        echo $this->Form->input('city', array('options' => $cities,
            'empty' => __('Select City'),
            'disabled' => $cityDisabled,
            'class' => 'form-control city'
        ));
        ?>
    </div>
    <div class="col-lg-4 form-group-col col-sm-4 col-md-4">
        <?php
        echo $this->Form->input('zip', array('placeholder' => __('Enter zip'),
			'data-rel' => "country#state#city#zip",
            'class' => 'form-control zip'
        ));
        ?>
		<span class="zip_validating"> </span>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-3 col-sm-3 col-md-3">
        <label> </label>
    </div>
    <div class="col-lg-8 col-sm-8 col-md-8">
        <div class=" flt_lft btn_area">
            <button type="button" class="btn btn-default btn-prev">
                <img src="/theme/App/img/back_arow.png" alt="<" /> <?php echo ('Back'); ?>
            </button>
            <button type="button" class="btn btn-next"><?php echo ('Next'); ?> </button>
        </div>
    </div>
</div>