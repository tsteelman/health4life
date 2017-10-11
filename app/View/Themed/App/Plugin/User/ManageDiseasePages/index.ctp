<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Profile', '/profile');
$this->Html->addCrumb('Manage Disease Pages');
?><div class="container">
    <div class="row edit">
        <div class="col-lg-3 edit_lhs">
            <?php echo $this->element('User.Edit/lhs'); ?>
        </div>
        <div class="col-lg-9">
            <div class="page-header">           
                <h2><span><?php echo __('Manage Disease Pages'); ?></span></h2>
            </div>
            <?php if (!empty($followList)) : ?>
                <div id="disease_follow_lists">
                    <?php
                    foreach ($followList as $follow) :
                        ?>                
                        <div class="form-group" id="follow_disease_<?php echo $follow['Disease']['id'] ?>">
                            <h4>
                                <a class="owner" href="<?php echo __(h(Configure::read('Url.condition') . 'index/' . $follow['Disease']['id'])); ?>">
                                    <?php echo __(h($follow['Disease']['name'])); ?>
                                </a>
                            </h4>
                            <div class="row">
                                <div class="col-lg-2">
                                    <label>Notification</label>
                                </div>
                                <div class="col-lg-2">
                                    <label>
                                        <input type="checkbox" id="disease_notification_status_<?php echo $follow['Disease']['id']; ?>" 
											   data-disease-id="<?php echo $follow['Disease']['id']; ?>"
												<?php if($follow['FollowingPage']['notification']): ?>
											   checked="checked"											    
											    <?php endif; ?>
											   class="disease_notification ace-switch ace-switch-3"> 
                                        <span class="lbl"></span>
                                    </label>
                                </div>
                                <div class="col-lg-8">
                                    <?php if ($follow['Disease']['description'] != '') { ?>
                                        <span class="disease_list_user_row"><?php echo __(h(htmlspecialchars_decode($follow['Disease']['description']))); ?></span>
                                    <?php } ?>
                                    <div class="pull-right">
										<button class="btn disease_follow_btn btn_normal pull-right" style="display:none;" data-disease-id="<?php echo $follow['Disease']['id']; ?>">
							Follow</button>
                                        <button class="btn disease_unfollow_btn btn_normal pull-right" data-disease-id="<?php echo $follow['Disease']['id']; ?>">
                                            Unfollow</button>	
						

                                    </div>
                                </div>

                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
                <?php
            else:
                echo __("You are not following any disease pages.");
            endif;
            ?>
        </div>
    </div>
</div>
<?php //echo $this->element('User.Blocking/confirm_unblock_dialog');  ?>

