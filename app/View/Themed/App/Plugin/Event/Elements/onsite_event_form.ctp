<div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label> <?php echo __('Location'); ?><span class="red_star_span"> *</span></label></div>
    <div class="col-lg-8 col-md-8 col-sm-8">
        <?php echo $this->Form->textarea('location', array('class' => 'form-control', 'placeholder' => 'Enter address')); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label> </label></div>
    <div class="col-lg-4 col-sm-4 col-md-4 form-group-col">
        <?php
        echo $this->Form->input('country', array('options' => $countries,
            'empty' => 'Select Country',
            'class' => 'form-control country',
            'data-rel' => 'country#state#city#zip',
            'onchange' => 'getStateList(this)'
        ));
        ?>
    </div>
    <div class="col-lg-4 col-sm-4 col-md-4 form-group-col">
        <?php
        echo $this->Form->input('state', array('options' => $states,
            'empty' => 'Select State/Province',
            'disabled' => $stateDisabled,
            'class' => 'form-control state',
            'data-rel' => 'country#state#city',
            'onchange' => 'getCityList(this)'
        ));
        ?>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-3 col-md-3 col-sm-3"><label> </label></div>
    <div class="col-lg-4 col-sm-4 col-md-4 form-group-col">
        <?php
        echo $this->Form->input('city', array('options' => $cities,
            'empty' => 'Select City',
            'disabled' => $cityDisabled,
            'class' => 'form-control city'
        ));
        ?>
    </div>
    <div class="col-lg-4 col-sm-4 col-md-4 form-group-col">
        <?php
        echo $this->Form->input('zip', array('placeholder' => 'zip',
			'data-rel' => "country#state#city#zip",
            'class' => 'form-control zip'
        ));
        ?>
    </div>
</div>