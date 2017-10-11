<?php

$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Events');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __(h('Event List')); ?>
        </h1>
    </div><!--/.page-header-->
    <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->


            <div class="row-fluid">
                <div class="table-header">
                    <?php echo __(h('Manage Event List')); ?>



                    <!--</div>-->
                </div>
                <div class="dataTables_wrapper" role="grid">
                    <div class="row-fluid" >
                        <div class="span6">
                            <div class="dataTables_length">

                                <?php
                                echo $this->Form->create('DiseaseFilter', array('class' => 'dataTables_length',
                                    'id' => 'EventFilterIndexForm',
                                    'url' => array('controller' => 'events', 'action' => 'index', 'type'=>'get'),
                                    'label' => false, 'div' => false));
                                echo $this->Form->input('filter', array('type' => 'select', 'label' => __('Add filter'), 'div' => false,
                                    'onchange' => 'javacript:filterEvents(this.value);', 'class' => 'dataTables_length',
                                    'style' => 'height: 32px;width: 200px;',
                                    'options' => array(0 => 'All (' . $all_events_count . ')',
                                        1 => 'Upcoming Events (' . $upcoming_events_count . ')',
                                        2 => 'Today (' . $today_events_count . ')',
                                        3 => 'Past Events (' . $past_events_count . ')'),
                                    'selected' => $filter));
                                echo $this->Form->end();
                                ?>

                            </div>
                        </div>
                        <div class="span6">
                            <div class="dataTables_filter" id="sample-table-2_filter">
                                <?php
//                                echo $this->Form->create('Events', array('action' => 'search', 'type' => 'get'));
                                echo $this->Form->create('Events', array('action' => 'index', 'type' => 'get'));

                                if (isset($keyword)) {
                                    $search_word = $keyword;
                                } else {
                                    $search_word = '';
                                }

                                echo $this->Form->input('event_name', array('label' => false, 'class' => '',
                                    'placeholder' => __('Search by event name'), 'aria-controls' => 'sample-table-2',
                                    'div' => false, 'style' => 'height : 22px; width : 225px',
                                    'required' => false, 'default' => $search_word));
                                echo $this->Form->input('filter', array('type' => 'hidden', 'value' => $filter));
                                echo $this->Form->submit(__('Search', true), array('div' => false, 'title' => 'search', 'class' => 'btn btn-small btn-inverse'));
                                echo $this->Form->end();
                                ?>

                            </div>
                        </div>
                    </div>
                    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                        <thead>

                            <tr>
                                <th> <?php echo __(h('Name')); ?></th>
                                <th> <?php echo __(h('Created By')); ?></th>
                                <th> <?php echo __(h('Type')); ?></th>
                                <th class="hidden-480"><i class="icon-time bigger-110 hidden-phone"></i><?php echo __('Starts on'); ?></th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>  
                            <?php
                            if (isset($event_list) && $event_list != NULL) {
                                foreach ($event_list as $row) {
                                    ?>

                                    <tr>
                                        <td class="disease_name">
                                            <?php echo __(h($row['Event']['name'])) ?>
                                        </td>
                                        <?php
                                        switch ($row['Event']['event_type']) {
                                            case Event::EVENT_TYPE_PUBLIC :
                                                $type = 'Public';
                                                break;
                                            case Event::EVENT_TYPE_PRIVATE :
                                                $type = 'Private';
                                                break;
                                            case Event::EVENT_TYPE_SITE :
                                                $type = 'Site Wide Event';
                                                break;
                                        }
                                        ?>
                                        <td style="text-align: left"><?php echo __(h($row['User']['username'])); ?></td>
                                        <td style="text-align: left"><?php echo __(h($type)); ?></td>
                                        <td class="hidden-phone"><?php echo Date::getUSFormatDateTime($row['Event']['start_date'], $timezone); ?></td>

                                        <td class="td-actions">
                                            <div class="hidden-phone visible-desktop action-buttons">
                                                <a class="green" href="/admin/Events/view/<?php echo $row['Event']['id']; ?>" data-toggle="modal" data-rel="tooltip" title="Edit">
                                                    <i class="icon-zoom-in bigger-130"></i>
                                                </a>  
                                            </div>

                                            <div class="hidden-desktop visible-phone">
                                                <div class="inline position-relative">
                                                    <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
                                                        <i class="icon-caret-down icon-only bigger-120"></i>
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
                                                        <li>
                                                            <a class="green" href="#" data-toggle="modal" onclick="javascipt:editDisease(<?php echo __(h($row['Disease']['id'] . ',' . $row['Disease']['parent_id'] . ',' . $row['Disease']['is_parent'])) ?>, this);"  data-target="#modal-add-disease" data-rel="tooltip" title="Edit">
                                                                <span class="green">
                                                                    <i class="icon-edit bigger-120"></i>
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
    function filterEvents(filter) {
//        $("#SubscribersFilter").val();
//        $('#EventFilterIndexForm').submit();
        var keyword = encodeURIComponent($( '#EventsEventName' ).val());       
	window.location.replace('/admin/events'+ '?event_name=' + keyword + '&filter=' + filter);
    }
</script>