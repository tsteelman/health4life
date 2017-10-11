<div class="group">
        <div class="row notification">
            <div class="col-lg-9">
                <div class="event_list">
                    <div class="page-header">
                        <h3 class="pull-left"><?php echo $header; ?></h3>
                    </div>
                    <div id="searchPageList" class="content">
                        <div class="row" id="searchList">
                            <!--<div id="searchList" class="group_list">-->
                              <?php echo $results; ?>
                            <!--</div>-->                           
                        </div>
                              <?php echo $moreButton; ?>
                    </div>
                </div>
            </div>

            <?php echo $this->element('layout/rhs', array('list' => true)); ?>

        </div>
    </div>