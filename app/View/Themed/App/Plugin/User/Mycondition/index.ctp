<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
if ($is_same) {
  $this->Html->addCrumb('My Profile', '/profile');
}
else {
  $this->Html->addCrumb($user_details['username']."'s profile", Common::getUserProfileLink($user_details['username'], true));
}
$this->Html->addCrumb('Condition');
?>
<?php $this->extend('Profile/view'); ?>

<div class="content">
    <div class="row">
        <div class="group_list">
            <?php
            if(!empty($diseases)){
	            foreach ($diseases as $disease) {
	                ?>
	                <div>
	                    <div class="disease_list">
	                        <div class="media">
	                            <div class="media-body">
	                                <div class="col-lg-7 pull-left">
	                                    <h5>
	                                        <a href="<?php echo Configure::read('Url.condition') ?>index/<?php echo $disease['Diseases']['id'] ?>"
	                                           class="owner">
	                                               <?php echo __(h($disease['Diseases']['name'])); ?>
	                                        </a>
	                                    </h5>
	                                </div>
									<?php if($disease['PatientDisease']['diagnosis_date'] > '0000-00-00 00:00:00'):?>
										<div class="col-lg-5 pull-right">
											<span class="ladda-spinner">
												<?php echo __('Year of Diagnosis:'); ?>
											</span>
											<?php 
												echo CakeTime::format($disease['PatientDisease']['diagnosis_date'], '%Y');
											?>
										</div>
									<?php endif; ?>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <?php
	            }
            } else {
				echo __('No Conditions Found.');
			}
            ?>
        </div>
    </div>
</div>

