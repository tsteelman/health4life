<div class="group">
    <div class="row notification">
        <div class="col-lg-9">  
			<div class="event_list">
                <div class="page-header">
                    <h3 class="pull-left">Condition with <?php echo stripslashes(htmlspecialchars($searchStr)); ?></h3>
                </div>
                <div id="searchDiseasePageList" class="content">
                    <div class="row">
                        <div id="searchDiseaseList" class="search_group_list">

                            <?php echo $this->element('Disease.disease_row', $diseases); ?>
                        </div>                           
                    </div>
                    <?php if ($this->params['paging']['Disease']['nextPage']): ?>
                        <div class="block" id="more_disease_button2">
                            <a data-spinner-color="#3581ED" data-size="l" data-style="expand-right" class="btn btn_more pull-right ladda-button more-arrow" id="load-disease-more" href="javascript:load_more_all_items(2,'disease')">
                                <span class="ladda-label">More</span>
                            </a>
                        </div> 
                    <?php endif; ?>
                </div>
            </div>
            <div class="event_list">
                <div class="page-header">
                    <h3 class="pull-left">People with <?php echo stripslashes(htmlspecialchars($searchStr)); ?></h3>
                </div>
                <div id="searchUserPageList" class="content">
                    <div class="row">
                        <div id="searchUserList" class="search_group_list">

                            <?php echo $this->element('users_row', $users); ?>
                        </div>                           
                    </div>
                    <?php if ($this->params['paging']['User']['nextPage']): ?>
                        <div class="block" id="more_user_button2">
                            <a data-spinner-color="#3581ED" data-size="l" data-style="expand-right" class="btn btn_more pull-right ladda-button more-arrow" id="load-user-more" href="javascript:load_more_all_items(2,'people')">
                                <span class="ladda-label">More</span>
                            </a>
                        </div> 
                    <?php endif; ?>
                </div>
            </div>
            <div class="event_list">
                <div class="page-header">
                    <h3 class="pull-left">Community with <?php echo stripslashes(htmlspecialchars($searchStr)); ?></h3>
                </div>
                <div id="searchCommunityPageList" class="content">
                    <div class="row">
                        <div id="searchCommunityList" class="search_group_list">

                            <?php echo $this->element('Community.community_row', $communities); ?>
                        </div>                           
                    </div>
                    <?php if ($this->params['paging']['Community']['nextPage']): ?>
                        <div class="block" id="more_community_button2">
                            <a data-spinner-color="#3581ED" data-size="c" data-style="expand-right" class="btn btn_more pull-right ladda-button more-arrow" id="load-community-more" href="javascript:load_more_all_items(2, 'community')">
                                <span class="ladda-label">More</span>
                            </a>
                        </div> 
                    <?php endif; ?>
                </div>
            </div>
            <div class="event_list">
                <div class="page-header">
                    <h3 class="pull-left">Hashtag with <?php echo stripslashes(htmlspecialchars($searchStr)); ?></h3>
                </div>
                <div id="searchHashtagPageList" class="content">
                    <div class="row">
                        <div id="searchHashtagList" class="search_group_list">

                            <?php echo $this->element('Hashtag.hashtags_row', $hashtags); ?>
                        </div>                           
                    </div>
                    <?php if ($this->params['paging']['Hashtag']['nextPage']): ?>
                        <div class="block" id="more_hashtag_button2">
                            <a data-spinner-color="#3581ED" data-size="c" data-style="expand-right" class="btn btn_more pull-right ladda-button more-arrow" id="load-hashtag-more" href="javascript:load_more_all_items(2, 'hashtag')">
                                <span class="ladda-label">More</span>
                            </a>
                        </div> 
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php echo $this->element('layout/rhs', array('list' => true)); ?>

    </div>
</div>