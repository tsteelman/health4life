<?php 
if (isset($postedInDetails)) { ?>


<div style="overflow: hidden; margin-bottom: 10px;"> 
<div class="library_posted_in_text pull-left" style="margin-right: 10px;"> 
        <?php if(isset($postedInDetails['posted_in_type']) && $postedInDetails['posted_in_type'] != NULL) { 
        echo "posted in ".$postedInDetails['posted_in_type'];
    }
    ?>
</div>
<!--    <div class="library_posted_in_type pull-left"><?php // echo $postedInDetails['posted_in_type']; ?>
    </div>-->
<div class="owner library_posted_in_name pull-left">
        <?php
        if (isset($postedInDetails['posted_in_name']) && $postedInDetails['posted_in_name'] != NULL) {
            ?>
            <a class="owner" href="<?php echo $postedInDetails['posted_in_url']; ?>">
                <?php echo $postedInDetails['posted_in_name']; ?>
            </a>
        <?php } else {
            ?>
            <div class="library_posted_in_text">
                <?php echo 'which has been removed.'; ?>

            </div>
            <?php
        }
        ?>
    </a>
</div>
</div>

    <?php
}
?>