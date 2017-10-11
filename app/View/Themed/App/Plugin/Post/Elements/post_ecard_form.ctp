<div id="posting_ecard_form" style="display:none;" class="form-group posting_options_form">
    <div>        
        <ul class="facelist ecard_facelist">
            <li class="token-input">
                <?php
                echo $this->Form->input('to_user', array('class' => 'blog_title_input',
'placeholder' => 'Type username'));
                echo $this->Form->hidden("ecard_to_userid", array('id' => 'EcardToUserId', 'class' => 'user_id_hidden'));
                ?>

            </li>
        </ul>
        <div id="ecard_user_list" class="result_list" style="display:none;"></div>        
    </div>
    
    <div>
        <?php  
            echo $this->Form->textarea('ecard_title', array('id' => 'PostEcardTitle', 
            'class' => 'blog_title_input', 'placeholder' => 'Type your message (optional)'));
        ?>
        
    </div> 
    <div class="ecard_preview text-left">
        <ul class="ecard_templates">
        <?php foreach ($eCardImages as $cardIndex =>$cardName) { ?>
        <li>
            <a data-ecard_id="<?php echo $cardIndex; ?>" class="ecard_link" href="javascript:void(0)"><?php echo $this->Html->image('/img/ecards/'.$cardName, array( 'alt' => '', 'class' => 'preview_img')); ?></a>
        </li>            
        <?php } ?>
        
        <?php
            // hidden fields to store link data
            echo $this->Form->hidden('ecard_selected');
        ?>
        </ul>
    </div>	   	
</div>