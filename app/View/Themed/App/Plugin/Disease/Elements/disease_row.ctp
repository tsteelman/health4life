<?php
if (!empty($diseases)) {
    foreach ($diseases as $disease) {
        ?>
        <div class="friends_list">
            <div class="media">
                <div class="row">
                    <div class="pull-left">
                        <h5>
                            <a class="owner" href="<?php echo __(h(Configure::read('Url.condition').'index/' . $disease['Disease']['id'])); ?>">
                                <?php echo __(h($disease['Disease']['name'])); ?>
                            </a>
                        </h5>
                        <?php if ($disease['Disease']['description'] != '') { ?>
                            <span class="disease_list_user_row"><?php echo __(h(htmlspecialchars_decode($disease['Disease']['description']))); ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div></div>
			<?php 
		}
	}else{ 
		?>
			<div class="friends_list">
				<div class="text-center friends_noresult_padding">
					<?php echo __('Sorry, no results containing all your search terms were found.');?>
				</div>
			</div>
		<?php 
	}
?>
