<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Newsletters');
?>

 <?php 
// echo $this->element('last_communities');
// exit;
 
 
 ?>

<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __(' Newsletters'); ?>
            <button class="btn btn-primary" style='float: right;' onclick="window.location.href = '/admin/Newsletters/add'"><?php echo __(' Add new Newsletter'); ?></button>
        </h1>
    </div><!--/.page-header-->
    <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->


            <div class="row-fluid">
                <div class="table-header">
                    <?php echo __('  Manage Newsletters'); ?>

                    <?php echo $this->Form->create('Newsletter', array('action' => 'search', 'type' => 'get')); ?>
                    <table class="" width="30%" style='float: right; margin-top: -39px;'>

                        <?php
                        if (isset($keyword) && $keyword != NULL) {
                            $search_word = $keyword;
                        } else {
                            $search_word = '';
                        }
                        ?>
                        <tr>   
                            <td align="right"  valign="top">   
                                <?php echo $this->Form->input('newsletter_name', array('label' => false, 'class' => '', 'default' => $search_word, 'placeholder' => __('search'), 'style' => 'margin-top: 4px;width: 100%;', 'required' => false)); ?>
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
                            <th class="hidden-480"><i class="icon-time bigger-110 hidden-phone"></i> <?php echo __('Created'); ?></th>
                            <th> <?php echo __('Actions'); ?></th>
<!--                            <th></th>-->
                        </tr>
                    </thead>

                    <tbody>  
                        <?php
                        foreach ($newsletters as $row) {
                            ?>

                            <tr>
                                <td><?php echo __($row['Newsletter']['id']) ?></td>
                                <td>
                                    <?php echo __($row['Newsletter']['subject']) ?>
                                </td>                                
                                <td class="hidden-phone"><?php echo __($row['Newsletter']['created']) ?></td>

                                <td class="td-actions">
                                    <div class="hidden-phone visible-desktop action-buttons">
                                        <a class="blue" href="#" data-rel="tooltip" title="View" onclick="window.open('/admin/Newsletters/view/<?php echo $row['Newsletter']['id'] ?>', '_blank', 'scrollbars=1,width=800,top=150,left=300')">
                                            <i class="icon-zoom-in bigger-130"></i>
                                        </a>

                                        <a class="green" href="/admin/Newsletters/edit/<?php echo $row['Newsletter']['id'] ?>" data-rel="tooltip" title="Edit">
                                            <i class="icon-pencil bigger-130"></i>
                                        </a>

                                        <a class="red" href="/admin/Newsletters/delete/<?php echo $row['Newsletter']['id'] ?>" id='' data-rel="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete?');">
                                            <i class="icon-trash bigger-130"></i>
                                        </a>
                                        <a class="blue" href="/admin/Newsletters/sendNewsletter/<?php echo $row['Newsletter']['id'] ?>" id='' data-rel="tooltip" title="Send Newsletter">
                                            <i class="icon-upload bigger-130"></i>
                                            <!--/* icon-upload */-->
                                        </a>
                                    </div>

                                    <div class="hidden-desktop visible-phone">
                                        <div class="inline position-relative">
                                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-caret-down icon-only bigger-120"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
                                                <li>
                                                    <a href="#" class="tooltip-info" data-rel="tooltip" onclick="window.open('/admin/Newsletters/view/<?php echo $row['Newsletters']['id'] ?>', '_blank', 'height=500,width=800,top=150,left=300')" title="View">
                                                        <span class="blue">
                                                            <i class="icon-zoom-in bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="/admin/Newsletters/edit/<?php $row['Newsletter']['id'] ?>" class="tooltip-success" data-rel="tooltip" title="Edit">
                                                        <span class="green">
                                                            <i class="icon-edit bigger-120"></i>
                                                        </span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="/admin/Newsletters/delete/<?php $row['Newsletter']['id'] ?>" class="tooltip-error" data-rel="tooltip" title="Delete">
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
<script type="text/javascript">
                $(document).ready(function() {
                    $("#newsletters-li a").trigger('click');
//                    $("ul.nav-list li").removeClass('active');
//                    $("#newsletters-manage-li").addClass('active');
                });
</script>