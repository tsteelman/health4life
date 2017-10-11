<div class="row liked_user">
    <?php foreach ($likedUsers as $likedUser) : ?>
        <div class="col-lg-12 ">
            <div class="media">
                <a class="pull-left" href="<?php echo Common::getUserProfileLink($likedUser['name'], TRUE);?>">
                    <?php echo $likedUser['photo']; ?></a> 
                <div class="media-body pull-left">
                    <h5 class="owner"><a href="<?php echo Common::getUserProfileLink($likedUser['name'], TRUE);?>"
                                         class="owner">
                                             <?php echo $likedUser['name']; ?>
                    </a></h5>
                    <p><?php echo $likedUser['diseases']; ?></p>
                    <?php if(empty($likedUser['diseases'])) { ?>
                    	<p><?php echo $likedUser['location']; ?></p>
                    <?php } ?>
                </div>
                <?php if ($likedUser['friendBtnText'] !== '') : ?>
                    <div class="pull-right">
                        <?php
                        if ($likedUser['friendStatus'] === MyFriends::STATUS_REQUEST_RECIEVED) {
                            echo $this->Html->tag('button', $likedUser['friendBtnText'], $likedUser['friendBtnOptions']);
                            ?>
                            <div class="frnd_request_respond_box hide">
                                <?php
                                $likedUserId = $likedUser['userId'];
                                echo $this->Html->tag('button', 'Decline', array(
                                    'class' => 'btn btn_more pull-right ladda-button',
                                    'type' => 'button',
                                    'id' => sprintf('reject_button_%d', $likedUserId),
                                    'onclick' => sprintf("rejectFriend('%d', true)", $likedUserId),
                                    'data-spinner-color' => '#3581ED',
                                    'data-style' => 'expand-right',
                                ));
                                echo $this->Html->tag('button', 'Confirm', array(
                                    'class' => 'btn btn_more pull-right ladda-button',
                                    'type' => 'button',
                                    'id' => sprintf('accept_button_%d', $likedUserId),
                                    'onclick' => sprintf("approveFriend('%d', true)", $likedUserId),
                                    'data-spinner-color' => '#3581ED',
                                    'data-style' => 'expand-right',
                                ));
                                ?>
                            </div>
                            <?php
                        } else {
                            echo $this->Html->tag('button', $likedUser['friendBtnText'], array_merge($likedUser['friendBtnOptions'], array(
                                'class' => 'btn btn_more ladda-button',
                                'type' => 'button'
                            )));
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<script>
    /**
     * Adding y axis scrolling to the liked users pop-up
     */
    var modalBody = $('.likes_modal .modal-body');
    var maxHeight = '488px';
    modalBody.slimScroll({          //liked_user container
        color: '#BBDAEC',
        railColor:'#EBF5F7',
        size: '8px',
        height: maxHeight,
        distance: '1px',
        disableFadeOut: true,
        railVisible: true
    });
</script>