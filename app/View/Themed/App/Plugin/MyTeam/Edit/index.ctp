<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb(__('My Team'), '/myteam');
$this->Html->addCrumb($teamName, $teamUrl);
$this->Html->addCrumb(__('Edit'));
?>
<div class="container">
    <div class="myteam create_form edit_team">
        <div class="row">     
			<?php echo $this->element('lhs'); ?>
			<div class="col-lg-9">
                            <?php echo $this->element('MyTeam.approve_decline_privacy_chane_box', array('teamId' => $team['id'])); ?>
                            <div class="page-header">
                                <h3><?php echo __('Edit Team'); ?></h3> 
                            </div>                            
				<div class="discussion_area create_team_section " >
					<?php
					echo $this->Form->create('Team', array(
						'id' => $formId,
						'inputDefaults' => $inputDefaults,
						'method' => 'POST',
						'enctype' => 'multipart/form-data',
					));
					echo $this->Form->hidden('id');
					?>
					<div class="create_team_details">
						<div class="row">
							<div class="team_step_3 col-lg-7">

								<?php echo $this->element('MyTeam.photo_upload'); ?>

								<div class="team_form">

									<div class="form-group">
										<label><?php echo __('Team Name'); ?><span class="red_star_span"> *</span></label><span class="team_span"><?php echo __('(50 characters)'); ?></span>
										<?php echo $this->Form->input('name'); ?>
									</div>

									<div class="form-group">
                                                                            <label><?php echo __('Short Description'); ?></label><span class="team_span"><?php echo __('(150 characters)'); ?></span>
										<?php echo $this->Form->textarea('about', array('class' => 'form-control')); ?>
									</div>
                                                                        <div class="form-group">
                                                                                <label>Team privacy</label>
                                                                                <span>
                                                                                    <?php
                                                                                    echo $this->Html->image('/img/calendar_tooltip_icon_small.png', array(
                                                                                            'alt' => '?',
                                                                                            'id' => 'team_privacy_help',
                                                                                            'data-content' => $this->Html->nestedList($teamPrivacyHintList)
                                                                                    ));
                                                                                    ?>
                                                                                    <div id="team_privacy_popover"></div>
                                                                                </span>
                                                                                <?php 
                                                                                        if ( $this->request->data['Team']['privacy'] == 3 ) {
                                                                                            $disabled = true;
                                                                                            $this->request->data['Team']['privacy'] = 2;
                                                                                        } else {
                                                                                            $disabled = false;                                                                                            
                                                                                        }
                                                                                        
                                                                                        $options = array( 1 => 'Public', 2=> 'Private');
                                                                                        
                                                                                        echo $this->Form->input('privacy', array(
                                                                                                'type' => 'select',
                                                                                                'options' => $options,
                                                                                                'class' => 'form-control',
                                                                                                'disabled' => $disabled
                                                                                ));
                                                                                if ( $disabled ) {
                                                                                    if ( isset( $isPatient )) {
                                                                                        echo '<i>Team organizer wants to make this group public<i>';
                                                                                    } else {
                                                                                        echo '<i>Awaiting approval from patient to make the team as public</i>';
                                                                                    }
                                                                                }
                                                                                ?>
                                                                        </div>                                                                        
								</div>

								<div class="team_for">
									<button class="btn continue_btn" type="submit"><?php echo ('Update'); ?></button>  
									<button class="btn btn_clear" type="button" id="cancel_edit" data-href="<?php echo $teamUrl; ?>">
										<?php echo ('Cancel'); ?>
									</button>   
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<?php
echo $this->jQValidator->validator();
echo $this->AssetCompress->script('team', array('block' => 'scriptBottom'));