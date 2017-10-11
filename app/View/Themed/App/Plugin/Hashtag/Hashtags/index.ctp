<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('HashTag', '');
?>
<div class="main_container">
    <div class="container">
<div class="hashtag_page">
        <div class="row mr0">
            
            <div class="col-lg-3">
                <div class="hashtag_box">
                    <h4>All Trending HashTags</h4>
 
                        <ul>
                            <?php foreach ($trendingTags as $key => $trends): ?>
                            <li>
                                <a class="" href="<?php echo Common::getHashtagUrl($key); ?>">#<?php echo $key; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
 
                </div>
            </div>
            
            <div class="col-lg-9">
                <div class="">
                    <h4 class="">Results for "#<?php echo isset($this->request->query['tag'])? ($this->request->query['tag']) : ""; ?>"</h4>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        
                        <?php if (!empty($posts)): ?>
                        <div class="event_wraper" id="post_list">
                        <?php echo $this->element('Post.post_list'); ?>
                        </div>
                        <?php else:?>
                        No Results found
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
</div>
    </div>
    <a href="#" class="back-to-top" style="display: none;">Top</a>
</div>