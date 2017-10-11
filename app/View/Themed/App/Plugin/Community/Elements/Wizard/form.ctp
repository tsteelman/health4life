<div class="container create_common">
    <div class="row mr_0">
        <div class="col-lg-9">
            <div class="page-header">
                <h3>
                    <span><?php echo $title; ?></span>
                </h3>
            </div>
            <div class="thumbnail">
                <button type="button" class="close pull-right" id="close_community_wizard">&times;</button>
                <div class="page-header"></div>
                <?php
				echo $this->element('Community.Wizard/diagnosis_form', array(
					'index' => 'index',
					'options' => $inputDefaults,
					'id' => 'sample_diagnosis_record',
					'hide' => true
				));
				echo $this->Form->create('Community', array(
                    'id' => $formId,
                    'inputDefaults' => $inputDefaults,
                    'method' => 'POST',
                    'enctype' => 'multipart/form-data',
                    'data-backurl' => $backUrl
                ));
                echo $this->Form->hidden('id');
                if(isset($refer)){
                    echo $this->Form->hidden('refer', array('value' => substr($refer, 1)));
                }
                ?>
                <div class="wizard" id="communityWizard">
                    <ul class="steps">
                        <li data-target="#step1" class="active"></li>
                        <li data-target="#step2"></li>
                        <li data-target="#step3"></li>
                    </ul>
                    <div class="step-content">
                        <div class="step-pane active" id="step1">
                            <?php 
                            if(isset($disease)) {
                                echo $this->element('Community.Wizard/step1', array('disease' => $disease)); 
                            } else {
                                echo $this->element('Community.Wizard/step1'); 
                            }
                            ?>
                        </div>
                        <div class="step-pane" id="step2">
                            <?php echo $this->element('Community.Wizard/step2'); ?>
                        </div>
                        <div class="step-pane" id="step3">
                            <script type="text/javascript">
                                var friendList = <?php echo $friendsListJson; ?>;
                            </script>
                            <?php echo $this->element('Community.Wizard/step3'); ?>

                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <?php echo $this->element('layout/rhs'); ?>
    </div>
</div>
<?php
$this->jQValidator->printCountryZipRegexScriptBlock();
echo $this->jQValidator->validator();
echo $this->Html->scriptBlock(
        "var form = '#{$formId}';
         var diseaseJSON = {$diseaseJSON};"
);
$this->AssetCompress->script('community', array('block' => 'scriptBottom'));