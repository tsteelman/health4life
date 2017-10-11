<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Users');
?>
<div id="manage_users" class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __('Manage Users'); ?>
        </h1>
    </div><!--/.page-header-->
    <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->


            <div class="row-fluid">
                <div class="dataTables_wrapper" role="grid">
                    <div class="row-fluid" >
                        <div class="span6 pull-right">
                            <div class="dataTables_filter" id="sample-table-2_filter">
                                <?php
                                echo $this->Form->create('User', array('action' => 'search', 'type' => 'get'));

                                if (isset($keyword)) {
                                    $search_word = $keyword;
                                } else {
                                    $search_word = '';
                                }

                                echo $this->Form->input('username', array('label' => false, 'class' => '',
                                    'placeholder' => __('search'), 'aria-controls' => 'sample-table-2',
                                    'div' => false, 'style' => 'height : 22px; width : 225px',
                                    'required' => false, 'default' => $search_word));
                                echo $this->Form->submit(__('search', true), array('div' => false, 'title' => 'search', 'class' => 'btn btn-small btn-inverse'));
                                echo $this->Form->end();
                                ?>

                            </div>
                        </div>
                    </div>
                    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                        <thead>

                            <tr>
                                <th> <?php echo __(h('Username')); ?></th>
                                <th> <?php echo __(h('Full Name')); ?></th>
                                <th> <?php echo __(h('Email')); ?></th>
                                <th> <?php echo __(h('Type')); ?></th>
                                <th> <?php echo __(h('Joined On')); ?></th>
                                <th> <?php echo __(h('Status')); ?></th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>  
                            <?php
                            if (isset($user_list) && $user_list != NULL) {
                                foreach ($user_list as $row) {
                                    ?>

                                    <tr>
                                        <td><?php echo __(h($row['User']['username'])); ?></td>
                                        <td class="disease_name">
                                            <?php echo __(h($row['User']['first_name'] . ' ' . $row['User']['last_name'])); ?>
                                        </td>
                                        <td class="hidden-phone"><?php echo __(h($row['User']['email'])); ?></td>
                                        <td class="hidden-phone">
                                            <?php
                                            switch ($row['User']['type']) {
                                                case '1':
                                                    $type = 'Patient ';
                                                    break;
                                                case '2':
                                                    $type = 'Family ';
                                                    break;
                                                case '3':
                                                    $type = 'Caregiver ';
                                                    break;
                                                case '4':
                                                    $type = 'Other ';
                                                    break;
                                                default :
                                                    $type = 'Not Set';
                                                    break;
                                            }
                                            ?>
                                            <span class="label label_<?php echo $type;?>">
                                                <?php
                                                echo __($type);
                                                ?>
                                            </span>
                                            <?php
                                            ?>
                                        </td>
                                        <td class="hidden-phone"><?php echo Date::getUSFormatDateTime($row['User']['created'], $timezone); ?></td>
                                        <td><?php
                                            if ($row['User']['status'] == 1) {
                                                ?>
                                                <span class="label label-success arrowed-in arrowed-in-right">
                                                    <?php
                                                    echo __('Active');
                                                    ?>
                                                </span>
                                                <?php
                                            } else {
                                                ?>
                                                <span class="label label-warning arrowed-in arrowed-in-right">
                                                    <?php
                                                    echo __('Inactive');
                                                    ?>
                                                </span>
                                                <?php
                                            }
                                            ?></td>
                                        <td class="td-actions">
                                            <div class="visible-desktop action-buttons">
                                                <a class="green" href="/admin/Users/view/<?php echo $row['User']['id']; ?>" data-toggle="modal" data-rel="tooltip" title="View">
                                                    <i class="icon-zoom-in bigger-130"></i>
                                                </a>
                                            </div>

                                            <div class="hidden-desktop visible-phone">
                                                <div class="inline position-relative">
                                                    <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
                                                        <i class="icon-caret-down icon-only bigger-120"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php echo $this->element('pagination'); ?>
                </div>
            </div>
        </div><!--/.span-->
    </div><!--/.page-content-->
</div> 
<script>
    $(document).ready(function() {
		$("#users-li a").trigger('click');
    });
</script>