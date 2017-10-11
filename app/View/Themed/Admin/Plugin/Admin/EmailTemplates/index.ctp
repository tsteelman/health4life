<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Email Templates');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __(' Email Templates'); ?>
            <button class="btn btn-primary" style='float: right;' onclick="window.location.href = '/admin/EmailTemplates/add'">Add new template</button>
        </h1>
    </div><!--/.page-header-->
    <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->


            <div class="row-fluid">
                <div class="table-header">
                    <?php echo __('  Manage Templates'); ?>

                    <?php echo $this->Form->create('EmailTemplate', array('action' => 'search', 'type' => 'get')); ?>
                    <table class="" width="30%" style='float: right; margin-top: -39px;'>
                        <tr>   

                            <?php
                            if (isset($keyword) && $keyword != NULL) {
                                $search_word = $keyword;
                            } else {
                                $search_word = '';
                            }
                            ?> 
                            <td align="right"  valign="top">   
                                <?php echo $this->Form->input('template_name', array('label' => false, 'class' => '', 'default' => $search_word, 'placeholder' => __('search'), 'style' => 'margin-top: 4px;width: 100%;', 'required' => false)); ?>
                            <td/>
                            <td align="right" width="76px" valign="top">
                                <?php echo $this->Form->submit(__('search', true), array('div' => false, 'title' => 'search', 'class' => 'btn btn-small btn-inverse', 'style' => 'float: right; margin-top: 3px;')); ?>
                            <td/>                       
                        </tr>  

                    </table>     
                    <?php echo $this->Form->end(); ?>

                    <!--</div>-->
                </div>

                <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                    <thead>

                        <tr>
                            <th> <?php echo __('Id'); ?></th>
                            <th> <?php echo __('Name'); ?></th>
                            <th> <?php echo __('Subject'); ?></th>
                            <th class="hidden-480"><i class="icon-time bigger-110 hidden-phone"></i> Created Date</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>  
                        <?php
                        foreach ($emailManagements as $row) {
							$row['EmailTemplate']['template_subject'] = str_replace("|@site-name@|", Configure::read ( 'App.name'), $row['EmailTemplate']['template_subject']);
                            ?>

                            <tr>
                                <td><?php echo __($row['EmailTemplate']['id']) ?></td>
                                <td>
                                    <?php echo __($row['EmailTemplate']['template_name']) ?>
                                </td>
                                <td><?php echo __($row['EmailTemplate']['template_subject']) ?></td>
                                <td class="hidden-phone"><?php echo Date::getUSFormatDateTime($row['EmailTemplate']['created'], $timezone); ?></td>

                                <td class="td-actions">
                                    <div class="hidden-phone visible-desktop action-buttons">
                                        <a class="blue" href="#" data-rel="tooltip" title="View" onclick="window.open('/admin/EmailTemplates/view/<?php echo $row['EmailTemplate']['id'] ?>', '_blank', 'height=500,width=800,top=150,left=300')">
                                            <i class="icon-zoom-in bigger-130"></i>
                                        </a>

                                        <a class="green" href="/admin/EmailTemplates/edit/<?php echo $row['EmailTemplate']['id'] ?>" data-rel="tooltip" title="Edit">
                                            <i class="icon-pencil bigger-130"></i>
                                        </a>

                                        <a class="red" href="/admin/EmailTemplates/delete/<?php echo $row['EmailTemplate']['id'] ?>" id='' data-rel="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete?');">
                                            <i class="icon-trash bigger-130"></i>
                                        </a>
                                    </div>

                                    <div class="hidden-desktop visible-phone">
                                        <div class="inline position-relative">
                                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-caret-down icon-only bigger-120"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
                                                <li>
                                                    <a href="/admin/EmailTemplates/delete/<?php $row['EmailTemplate']['id'] ?>" class="tooltip-info" data-rel="tooltip" title="View">
                                                        <span class="blue">
                                                            <i class="icon-zoom-in bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="/admin/EmailTemplates/delete/<?php $row['EmailTemplate']['id'] ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
                                                        <span class="green">
                                                            <i class="icon-edit bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="/admin/EmailTemplates/delete/<?php $row['EmailTemplate']['id'] ?>" class="tooltip-error" data-rel="tooltip" title="Delete">
                                                        <span class="red">
                                                            <i class="icon-trash bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php echo $this->element('pagination'); ?>
            </div>
        </div><!--/.span-->
    </div><!--/.page-content-->
</div> 
