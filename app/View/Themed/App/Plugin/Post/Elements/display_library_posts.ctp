<?php
$this->AssetCompress->script('jquery.timeago', array('block' => 'scriptBottom'));
if ($this->Paginator->hasNext()) {
    $this->AssetCompress->script('jquery.autopager', array('block' => 'scriptBottom'));
}
?>
<div class="discussion_area">
    <?php
    if ($hasPostPermission === true) {
//        echo $this->element('Post.form');
        ?>
        <div class="event_wraper <?php echo (empty($posts)) ? 'hide' : ''; ?>" id="post_container">
            <?php if ($hasFilterPermission === true) : ?>
                <div class="filter_option">
                    <div class="btn-toolbar">
                        <div class="btn-group pull-right">
                            <input id="filter_value_hidden" type="hidden" value="0" />
                            <button class="edit_area btn  dropdown-toggle" data-toggle="dropdown">                                                            
                                <div class="filter"></div>
                            </button>

                            <ul class="dropdown-menu" id="post_filter">
                                <?php foreach ($filterOptions as $value => $option) : ?>
                                    <li><a data-filter_value="<?php echo $value; ?>"><?php echo $option; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div id="post_list">
                <?php
                if (!empty($posts)) {
                    echo $this->element('Post.post_list');
                }
                ?>
            </div>
            <div id="post_loading" class="hide">
                <span>
                    <?php echo $this->Html->image('load_more.gif', array('width' => 24, 'height' => 24)); ?>
                    <label>Loading, please wait...</label>
                </span>
            </div>
        </div>
        <?php
        if (empty($posts)) :
            if (isset($noPostsMessage) && $noPostsMessage != NULL) {
                echo $this->element('warning', array('message' => __($noPostsMessage)));
            } else {
                echo $this->element('warning', array('message' => __('No discussion started yet!')));
            }
        endif;
    }
    ?>
</div>