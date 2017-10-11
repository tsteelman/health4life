<div class="contact_persons col-lg-6">
    <input type="checkbox" class="pull-left" value="<?php echo $user['User']['id']; ?>" name="existing_contacts[]" />
    <div class="media">
        <a class="pull-left"> 
            <?php echo Common::getUserThumb($user['User']['id'], $user['User']['type'], 'small'); ?>  
        </a>
        <div class="media-body">
            <div class="pull-left">
                <h5>
                    <a class="owner">
                        <?php echo h($user['User']['username']); ?>
                    </a>
                </h5>
				<?php
				$locationLimit = 53;
				if (isset($user[0]['diseases']) && ($user[0]['diseases'] !== '')) {
					$locationLimit = 28;
					?>
					<span><?php echo h($user[0]['diseases']); ?></span><br/>
				<?php } ?>
				<?php if (isset($user[0]['location']) && ($user[0]['location'] !== '')) { ?>
					<span><?php echo h(String::truncate($user[0]['location'], $locationLimit, array('exact' => true))); ?></span>
				<?php } ?>
            </div>
        </div>
    </div>
</div>