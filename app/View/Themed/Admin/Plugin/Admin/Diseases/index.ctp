<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Diseases');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __(h(' Disease List')); ?>
            <a href="/admin/diseases/add">
            <button class="btn btn-primary" style='float: right;' data-backdrop="static" data-keyboard="false"  onclick="javascipt:resetDiseaseIndexForm();">
                  <?php echo __("Add new disease"); ?>  
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
                    <?php echo __(h('  Manage Disease List')); ?>
                   


                    <!--</div>-->
                </div>
                <div class="dataTables_wrapper" role="grid">
				<div class="row-fluid" >
					<div class="span6">
						<div class="dataTables_length">
							
							<?php 
		                		echo $this->Form->create('DiseaseFilter', array('class' => 'dataTables_length',
														'id' => 'DiseaseFilterIndexForm',
														'url' => array('controller' => 'diseases', 'action' => 'index'), 
														'label' => false, 'div' => false));
		                		echo $this->Form->input('filter', array('type' => 'select','label' =>  __('Add filter'),'div' => false, 
														'onchange' => 'javacript:filterDiseases(this);', 'class' => 'dataTables_length',
														'style' => 'height: 32px;',
														'options' => array(0 => 'all', 1=> 'admin', 2=> 'users')));
		                		echo $this->Form->end();
		                	?>
		                
                		</div>
                	</div>
                	<div class="span6">
                		<div class="dataTables_filter" id="sample-table-2_filter">
                		<?php 	echo $this->Form->create('Disease', array('action' => 'search', 'type' => 'get')); 
                		
                                if (isset($keyword)) {
                                    $search_word = $keyword;
                                } else {
                                    $search_word = '';
                                }
                                
                                echo $this->Form->input('disease_name', array('label' => false, 'class' => '', 
													'placeholder' => __('search'),'aria-controls' => 'sample-table-2', 
													'div' => false, 'style' => 'height : 22px; width : 225px',
													'required' => false, 'default' => $search_word)); 
                                echo $this->Form->input('filter', array('type' => 'hidden', 'value' => $filter));
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
                            <th> <?php echo __(h('Parent')); ?></th>
                            <th class="hidden-480"><i class="icon-time bigger-110 hidden-phone"></i><?php echo __('Created Date'); ?></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>  
                        <?php
                        if (isset($disease_list) && $disease_list != NULL) {
                            foreach ($disease_list as $row) {
								if (is_null($row['Disease']['parent_id'])) {
									$row['Disease']['parent_id'] = 0;
								}
                                ?>

                                <tr>
                                    <td><?php echo __(h($row['Disease']['id'])) ?></td>
                                    <td class="disease_name">
                                        <?php echo __(h($row['Disease']['name'])) ?>
                                    </td>
                                    <!--<td><?php // echo __($row['Disease']['parent_id'])                                                     ?></td>-->
                                    <td style="text-align: center"><?php echo __(h($row['Disease']['parent_name'])) ?></td>
                                    <td class="hidden-phone"><?php echo __(h($row['Disease']['created'])) ?></td>

                                    <td class="td-actions">
                                        <div class="hidden-phone visible-desktop action-buttons">
                                          <a class="green" href="/admin/Diseases/view/<?php echo $row['Disease']['id']; ?>" data-toggle="modal" data-rel="tooltip" title="View">
                                                <i class="icon-zoom-in bigger-130"></i>
                                            </a>  
                                          <a class="green" href="/admin/Diseases/edit/<?php echo $row['Disease']['id']; ?>" data-toggle="modal" data-rel="tooltip" title="Edit">
                                                <i class="icon-pencil bigger-130"></i>
                                            </a>
                                                <!--                                        <a href="/admin/Diseases/delete/<?php // echo $row['Disease']['id'];                                             ?>" class="red" href=" " id='' data-rel="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete?');">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>-->
                                            <a href="javascript:void(0)" class="red" data-rel="tooltip" title="Delete" onclick="showDiseaseDeleteConfirmBox(<?php echo __(h($row['Disease']['id'])); ?>, this)">
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

<div id="modal-add-disease" class="modal hide" tabindex="-1">
    <?php
    echo $this->Form->create('Disease', array(
        'action' => 'index',
        'class' => 'form-horizontal',
        'label' => false,
        'div' => false
    ));
    ?>
    <div class="modal-header header-color-blue">
        <button id="modal-header-close-btn" type="button" class="close" data-dismiss="modal" onclick="javascipt:resetDiseaseIndexForm();" >&times;</button>
        <h4 class="white bigger"><?php echo __('Add new disease'); ?></h4>
    </div>
    <div class="modal-body overflow-visible">
        <div class="row-fluid">
            <div class="alert alert-warning text-center hide">Already a parent</div>
            <div class="control-group">
                <label  class = "control-label"><?php echo __('Parent disease'); ?></label>
                <div class = "controls">
                    <?php
                    echo $this->Form->input('parent_id', array(
                        'type' => 'select',
                        'options' => $parent_list,
						'default' => false,
						'empty' => 'none',
                        'div' => false,
                        'label' => false));
                    ?>  
                </div>
            </div>  

            <div class="control-group">
                <label class = "control-label"><?php echo __('Disease name'); ?></label>
                <div class = "controls">
                    <?php
                    echo $this->Form->input('name', array('class' => 'form-control',
                        'label' => false,
                        'div' => false));
                    ?>
                </div>
            </div>

            <?php echo $this->Form->input('id', array('type' => 'hidden'));
            ?>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-small btn-primary" data-dismiss="modal" onclick="javascipt:resetDiseaseIndexForm();">
            <i class="icon-remove"></i>
<?php echo __('Cancel'); ?>
        </button>

        <button type="submit" class="btn btn-small btn-primary">
            <i class="icon-ok"></i>
<?php echo __('Save'); ?>
        </button>                                
    </div>
<?php echo $this->Form->end(); ?>
</div>
<div id="modal-delete-disease-form" class="modal hide" tabindex="-1">
    <?php
    echo $this->Form->create('Disease', array(
        'action' => 'index',
        'class' => 'form-horizontal',
        'label' => false,
        'div' => false
    ));
    ?>
    <div class="modal-header header-color-blue">
        <button id="modal-header-close-btn" type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="white bigger"><?php echo __('Remove Disease'); ?></h4>
    </div>
    <div class="modal-body overflow-visible">
        <div class="row-fluid">
            <h5 class="smaller" style="color: #8089a0;"> <?php echo __('Please choose a disease you would like to replace'); ?><span class="deleting_disease_name"></span><?php echo __(' with. On updating, all the users who have this disease will be informed about the change via email.'); ?></h5>
        </div>
        <div class="row-fluid">
            <div class="control-group">
                <label  class = "control-label"><?php echo __('Replace '); ?><span class="deleting_disease_name"></span><?php echo __(' with:'); ?></label>
                <div class = "controls">
                    <?php
                    echo $this->Form->input('replace_disease_id', array(
                        'type' => 'select',
                        'options' => $all_disease_list,
                        'default' => '1',
                        'div' => false,
                        'required' => 'required',
                        'label' => false));
                    ?> 

                </div>
            </div>
            <?php echo $this->Form->input('delete_disease_id', array('type' => 'hidden', 'id' => 'delete_disease_id'));
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
            function filterDiseases(data){
            	$('#DiseaseFilterIndexForm').submit();
            }
//                                            $("ul.nav-list li").removeClass('active');
//                                            $("#disease-list-li").addClass('active');


                                            function resetDiseaseIndexForm() {

                                                /* Reset the form */
                                                $('#DiseaseParentId').val('');
                                                $("#DiseaseName").val('');
                                                $("#DiseaseId").val('');
                                                $("#DiseaseParentId").prop('disabled', false);
                                                $("#DiseaseParentId option").show();

                                                /* removing errors	*/
                                                $("#DiseaseIndexForm .controls .help-block").remove();
                                                $("#DiseaseIndexForm .control-group").removeClass("error");

                                                /* change header */
                                                $("#modal-add-disease .modal-header h4").html('Add new disease');

                                                /* hide alert box */
                                                $("#modal-add-disease .modal-body .alert").hide();
                                            }

                                            /* Function to edit disease*/
                                            function editDisease(id, parent_id, is_parent, _self) {
                                                var disease_name = $.trim($(_self).closest('tr').find('.disease_name').text());

                                                resetDiseaseIndexForm();
                                                $("#DiseaseId").val(id);
                                                $("#DiseaseName").val(disease_name);
												if(parent_id>0){
													$("#DiseaseParentId").val(parent_id);
												}
                                                $("#modal-add-disease .modal-header h4").html('Edit disease');
                                                if (is_parent) {
                                                    $("#DiseaseParentId").prop('disabled', true);
                                                    $("#modal-add-disease .modal-body .alert").show();
                                                } else {
                                                    $("#DiseaseParentId option").each(function() {
                                                        $(this).removeAttr('disabled');
                                                        if ($(this).val() == id) {
                                                            $(this).attr('disabled', "disabled");
                                                        }
                                                    });
                                                }

                                            }

                                            function showDiseaseDeleteConfirmBox(delete_disease_id, _self) {
                                                console.log(delete_disease_id);
                                                $("#modal-delete-done-btn, #btn-pop-up-cancel").removeAttr("disabled");
                                                var disease_name = $.trim($(_self).closest('tr').find('.disease_name').text());
                                                $(".deleting_disease_name").text(' "' + disease_name + '" ');
                                                var first_option = $("#modal-delete-disease-form select option").first();

                                                if (first_option.val() == delete_disease_id) {
                                                    console.log("option to delete");
                                                    first_option.removeAttr('selected');
                                                    first_option.next().attr('selected', 'selected');
                                                } else {
                                                    first_option.attr('selected', 'selected');
                                                }
                                                $("#modal-delete-disease-form").modal("show");
                                                $("#delete_disease_id").val(delete_disease_id);
                                                $("#modal-delete-disease-form select option").each(function() {
                                                    $(this).removeAttr('disabled');
                                                    if ($(this).val() == delete_disease_id) {
                                                        $(this).attr('disabled', "disabled");
                                                    }
                                                });
                                            }

                                            $("#DeleteDiseaseIndexForm").submit(function(event) {
                                                $("#modal-delete-done-btn, #btn-pop-up-cancel").attr('disabled', 'disabled');
                                            });
</script>
