<div class="hashtag_list">
    <div class="dashboard_header">
        <input type="text" id="db_htag" class="form-control" placeholder="Search Hashtags">
    </div>
    <div class="hashtag_container">
        <?php if(!empty($trendingTags)): ?>
            <?php foreach($trendingTags as $trendItem): ?>
                <?php if($trendItem['type'] == "hashtag"):
                    $trends = $trendItem['content'];
                ?>
                <p class="<?php echo Common::getColorCode($trends['tagrank']);?> tags"> 
                    <a href="<?php echo Common::getHashtagUrl($trends['tagname']);?>">#<?php echo $trends['tagname'];?></a>
                </p>
                <?php endif; ?>
                
                <?php if($trendItem['type'] == "posts"): ?>
                <?php
                $post = $trendItem['content'];
                $postContent = json_decode($post['Post']['content']);
                $userProfileImg = Common::getUserThumb($post['User']['id'], 
                               $post['User']['type'], 'x_small', 
                               'user_x_small_thumb media-object normal_thumb');
            
                App::uses('HealthStatus', 'Utility');
                $healthStatus = (!empty($postContent->health_status)) ? $postContent->health_status : HealthStatus::STATUS_VERY_GOOD;
                $postedUserHealthStatus = HealthStatus::getHealthStatusText($healthStatus);
                $postedUserSmileyClass = HealthStatus::getFeelingSmileyClass($healthStatus);
                
                App::uses('HashTagUtil', 'Utility');
                $postText = HashTagUtil::convertHashTags($postContent->description);
                
                $profileLink = Common::getUserProfileLink($post['User']['username'], true, '', true);
                $diseasePage = '/condition/index/'.$post['Post']['posted_in'];
                
                ?>
                    <div class="hashtag_div"> 
                        <div class="media">
                            <a class="pull-left posted_user_thumb" href="<?php echo $profileLink; ?>">
                               <?php echo $userProfileImg; ?>
                                <?php if (!empty($postedUserSmileyClass)): ?>
                                    <span class="pull-right feeling_condition <?php echo $postedUserSmileyClass; ?>" title="<?php echo $postedUserHealthStatus; ?>"></span>
                                <?php endif; ?>                        
                            </a>
                            <div class="media-body cursor-hand" onclick="window.location.href='<?php echo $diseasePage; ?>'">
                                <h5><?php echo $post['User']['username']; ?></h5>
                                <p><?php echo $postText; ?></p>
                            </div>
                        </div>
                    </div>
                
                <?php endif; ?>
                
            <?php endforeach; ?>
                                
        <?php else: ?>
            <p class="text-center padding-10">No trending tags</p>
        <?php endif; ?>

    </div>
</div>