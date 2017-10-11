<?php
    $this->Html->addCrumb('Dashboard');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo $title_for_layout; ?>
            <small>
                <i class="icon-double-angle-right"></i>
                <?php echo __('Overview'); ?>
            </small>
        </h1>
    </div><!--/.page-header-->

    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->

            <div class="alert alert-block alert-success">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon-remove"></i>
                </button>

                <i class="icon-ok green"></i>

                <?php echo __('Welcome to'); ?>
                <strong class="green">
                    <?php echo Configure::read('App.name'); ?>
                </strong>
                <?php echo __('Admin Panel'); ?>
            </div>

            <?php
            echo $this->Session->flash('success', array(
                'element' => 'success'
            ));
            ?>

            <div class="space-6"></div>

            <div class="row-fluid">
                <div class="infobox-container">
                    <div class="infobox infobox-green  ">
                        <div class="infobox-icon">
                            <i class="icon-user"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number"><a class='infobox-green' href="/admin/users"><?php echo __($usersCount); ?></a></span>
                            <span class="infobox-content"><?php echo __('registered users'); ?></span>
                        </div>
                    </div>

                    <div class="infobox infobox-blue  ">
                        <div class="infobox-icon">
                            <i class="icon-calendar"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number"><a class='infobox-blue' href="/admin/events"><?php echo __($eventsCount); ?></a></span>
                            <span class="infobox-content"><?php echo __('events created'); ?></span>
                        </div>
                    </div>

                    <div class="infobox infobox-pink  ">
                        <div class="infobox-icon">
                            <i class="icon-group"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number"><a class='infobox-pink' href="/admin/communities"><?php echo __($communityCount); ?></a></span>
                            <span class="infobox-content"><?php echo __('communities created'); ?></span>
                        </div>
                    </div>

                    <div class="infobox infobox-red  ">
                        <div class="infobox-icon">
                            <i class="icon-star"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number"><a class='infobox-red' href="/admin/Diseases/view/<?php echo $topDisease['id']; ?>"><?php echo __($topDisease['users']); ?></a></span>
                            <span class="infobox-content"><?php echo __('user(s) have ' . $topDisease['name']); ?></span>
                        </div>
                    </div>

                    <div class="infobox infobox-orange2  ">
                        <div class="infobox-icon">
                            <i class="icon-edit"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number"><a class='infobox-orange2' href="/admin/symptoms"><?php echo __($topTreatment[0]['users']);?></a></span>
                            <span class="infobox-content"><?php echo __('user(s) intakes ' . $topTreatment[0]['treatment']); ?></span>
                        </div>
                    </div>
					
					<?php if ($abuseReportsCount > 0) : ?>
	                    <div class="infobox infobox-orange2  ">
	                        <div class="infobox-icon">
	                            <i class="icon-edit"></i>
	                        </div>
	                        <div class="infobox-data">
	                            <span class="infobox-data-number"><a class='infobox-orange2' href="/admin/abuseReports"><?php echo $abuseReportsCount; ?></a></span>
	                            <span class="infobox-content"><?php echo __('abuse reports are waiting for clearance'); ?></span>
	                        </div>
	                    </div>
					<?php endif; ?>

                    <div class="space-6"></div>


                </div>


            </div><!--/row-->


