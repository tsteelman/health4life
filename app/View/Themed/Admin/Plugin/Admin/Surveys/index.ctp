<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Surveys');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __(h(' Survey List')); ?>
            <a href="/admin/surveys/add">
            <button class="btn btn-primary" style='float: right;' data-backdrop="static" data-keyboard="false">
                  <?php echo __("Add new survey"); ?>  
            </button>
            </a>
        </h1>
    </div><!--/.page-header-->
    <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->


            <div class="row-fluid">
                <div class="table-header">
                    <?php echo __(h('  Manage Survey List')); ?>
                   


                    <!--</div>-->
                </div>
                <div class="dataTables_wrapper" role="grid">
				<div class="row-fluid" >
					<div class="span6">
						
                                        </div>
                	<div class="span6">
                		<div class="dataTables_filter" id="sample-table-2_filter">
                		<?php 	echo $this->Form->create('Survey', array('action' => 'search', 'type' => 'get')); 
                		
                                if (isset($keyword)) {
                                    $search_word = $keyword;
                                } else {
                                    $search_word = '';
                                }
                                
                                echo $this->Form->input('survey_name', array('label' => false, 'class' => '', 
													'placeholder' => __('search'),'aria-controls' => 'sample-table-2', 
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
                            <th> <?php echo __(h('Id')); ?></th>
                            <th> <?php echo __(h('Name')); ?></th>
                            <th> <?php echo __(h('Key')); ?></th>
                            <th> <?php echo __(h('Status')); ?></th>
                            <th class="hidden-480"><i class="icon-time bigger-110 hidden-phone"></i><?php echo __('Created Date'); ?></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>  
                        <?php
                        if (isset($survey_list) && $survey_list != NULL) {
                            foreach ($survey_list as $row) {
                                ?>

                                <tr>
                                    <td><?php echo __(h($row['Survey']['id'])) ?></td>
                                    <td class="disease_name">
                                        <?php echo __(h($row['Survey']['name'])) ?>
                                    </td>
                                    <td><?php echo __(h($row['Survey']['survey_key'])) ?></td>
                                    <td><?php if($row['Survey']['status'] == 0) { ?>
                                                <span class="label label-warning arrowed-in arrowed-in-right">Pending</span>
                                         <?php } else { ?>
                                                <span class="label label-success arrowed-in arrowed-in-right">Published</span>
                                         <?php } ?>
                                    </td>
                                    <td class="hidden-phone"><?php echo __(h($row['Survey']['created'])) ?></td>

                                    <td class="td-actions">
                                        <div class="hidden-phone visible-desktop action-buttons">
                                            <a class="green" href="/admin/Surveys/editSurvey/<?php echo $row['Survey']['id']; ?>" data-toggle="modal" data-rel="tooltip" title="Edit">
                                                <i class="icon-pencil bigger-130"></i>
                                            </a>
                                                
                                            <a href="javascript:void(0)" class="red" data-rel="tooltip" title="Delete" onclick="showSurveyDeleteConfirmBox(<?php echo __(h($row['Survey']['id'])); ?>, this)">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>
                                            
                                            <a href="/admin/Surveys/addQuestion/<?php echo $row['Survey']['id'] ?>" class="blue" data-rel="tooltip" title="Preview">
                                                <i class="icon-zoom-in bigger-130"></i>
                                            </a>
                                            
                                            <a href="/admin/Surveys/showAnalytics/<?php echo $row['Survey']['id'] ?>" class="orange" data-rel="tooltip" title="Analytics">
                                                <i class="icon-bar-chart bigger-130"></i>
                                            </a>
                                        </div>

                                        <div class="hidden-desktop visible-phone">
                                            <div class="inline position-relative">
                                                <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown">
                                                    <i class="icon-caret-down icon-only bigger-120"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
                                                    <li>
                                                        <a class="green" href="#" data-toggle="modal" onclick="javascipt:editSurvey(<?php echo __(h($row['Survey']['id'])) ?>, this);"  data-target="#modal-add-disease" data-rel="tooltip" title="Edit">
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

<div id="modal-delete-survey" class="modal hide" tabindex="-1" style="width:480px !important;">
    <?php
    echo $this->Form->create('Survey', array(
        'action' => 'deleteSurvey',
        'class' => 'form-horizontal',
        'label' => false,
        'div' => false
    ));
    ?>
    <div class="modal-header header-color-blue">
        <button id="modal-header-close-btn" type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="white bigger"><?php echo __('Delete Survey'); ?></h4>
    </div>
    <div class="modal-body overflow-visible">
        <div class="row-fluid">
            <h5 class="smaller" style="color: #8089a0; text-align: center"> <?php echo __('Are you sure you want to delete this survey?'); ?></h5>
        </div>
        <div class="row-fluid">
            <?php echo $this->Form->input('delete_survey_id', array('type' => 'hidden', 'id' => 'delete_survey_id'));
            ?>
        </div>
    </div>
    <div class="modal-footer">
        <button id="btn-pop-up-cancel" class="btn btn-small btn-primary" data-dismiss="modal">
            <i class="icon-remove"></i>
            <?php echo __('Cancel'); ?>
        </button>
        <button type="submit"  id="modal-delete-done-btn" class="btn btn-small btn-primary">
            <i class="icon-ok"></i>
            <?php echo __('Done'); ?>
        </button>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<?php echo $this->jQValidator->validator(); ?>      
<script type="text/javascript">
            
//                                            $("ul.nav-list li").removeClass('active');
//                                            $("#survey-list-li").addClass('active');

                                            function showSurveyDeleteConfirmBox(delete_survey_id, _self) {
                                                $("#modal-delete-done-btn, #btn-pop-up-cancel").removeAttr("disabled");
                                                $("#modal-delete-survey").modal("show");
                                                $("#delete_survey_id").val(delete_survey_id);
                                            }

</script>
