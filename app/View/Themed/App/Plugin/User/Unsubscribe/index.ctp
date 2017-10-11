<?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>
<div class="signup_container" id="unsubscribe_container">
    <div class="thumbnail">
        <div class="page-header">
            <h1><?php echo __('Unsubscribe from newsletter'); ?></h1>                   
        </div>
        <div class="signup_fields">
        <?php if ( isset ( $alreadySubscribed )) {?>
        		<p><?php echo __('You have already unsubscribed newsletter.'); ?></p>
        <?php } else if ( isset ( $noEmail )) { ?>
                	
        		<p><?php echo __('No email to unsubscribe'); ?></p>
        	
        <?php } else { ?>
		        
		        	<p><?php echo __('Your email id is : '. $email); ?></p>
		            <p><?php echo __("We are sorry to find you are not any longer interested in our newsletter."); ?></p>
		            <?php
		            echo $this->Session->flash();
		            
		            if($message != NULL){
						echo __("<p>" .$message . "</p>");
					}else{
			            echo $this->Form->create('UnsubscribeForm', array(                
			                'inputDefaults' => array(
			                    'label' => false,
			                    'div' => false,
			                )
			            ));
			            ?>
			           
			            
			             <div class="form-group">
			           		 <button type="submit" class="btn btn_green"><?php echo __('Unsubscribe'); ?></button>
			             </div>
		            <?php echo $this->Form->end(); 
		            }?>
		        
        <?php } ?>
        	<a href="/user/email_settings"><?php echo __('Manage other email preferences'); ?></a>
        </div>
    </div>
</div>