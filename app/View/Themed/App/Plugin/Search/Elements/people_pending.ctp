<?php

    $this->AssetCompress->script('jquery.autopager', array('block' => 'scriptBottom'));

?>
<div class="group">
    <div class="row notification">
        <div class="col-lg-9">
            <div class="event_list">            
                <div id="searchPageList" class="content">
                    <div class="row">
                    	<?php if(isset($header)) {?>
                            <div class="pending">
                                <p><?php echo $header; ?></p>
                            </div>
                            <div id="searchList" class="event_wraper">
                                <?php echo $results; ?>
                            </div>
                            <?php
                        } else {
                            echo $results;
                        }
                        ?>

                    </div>
                </div>
                <?php echo $moreButton; ?>
            </div>
             <div class="recommended_users_list event_list">            
              <div id="recommended_user_loading">
	<span>
		<?php echo $this->Html->image('load_more.gif', array('width' => 24, 'height' => 24)); ?>
		<label>Loading, please wait...</label>
	</span>
</div>
 </div>
           
        </div>
        <?php echo $this->element('layout/rhs', array('list' => true)); ?>
    </div>

    <?php
    $this->AssetCompress->script('search', array('block' => 'scriptBottom'));
    ?>
    <script type="text/javascript">
        $(".col-lg-3 .event_list_lhs").removeClass('event_list_lhs');
    </script>
