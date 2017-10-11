<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Symptoms');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __(' Symptom List'); ?>
            <a href="/admin/symptoms/add">
            <button class="btn btn-primary" style='float: right;' btn-lg" data-backdrop="static" data-keyboard="false"  onclick="javascipt:resetSurveyIndexForm();">
                  <?php echo __("Add new symptom"); ?>  
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
                    <?php echo __(h('  Manage Symptom List')); ?>
                   


                    <!--</div>-->
                </div>
                <div class="dataTables_wrapper" role="grid">
				<div class="row-fluid" >
					<div class="span6">
						
                                        </div>
                	<div class="span6">
                		<div class="dataTables_filter" id="sample-table-2_filter">
                		<?php 	echo $this->Form->create('Symptom', array('action' => 'search', 'type' => 'get')); 
                		
                                if (isset($keyword)) {
                                    $search_word = $keyword;
                                } else {
                                    $search_word = '';
                                }
                                
                                echo $this->Form->input('symptom_name', array('label' => false, 'class' => '', 
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
                            <th> <?php echo __(h('Description')); ?></th>                         
                            <th class="hidden-480"><i class="icon-time bigger-110 hidden-phone"></i><?php echo __('Created Date'); ?></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>  
                        <?php
                        if (isset($symptoms_list) && $symptoms_list != NULL) {
                            foreach ($symptoms_list as $row) { 
                                ?>

                                <tr>
                                    <td><?php echo __(h($row['Symptom']['id'])) ?></td>
                                    <td class="disease_name">
                                        <?php echo __(h($row['Symptom']['name'])) ?>
                                    </td>
                                    <td class="symptom_desription"><?php echo __(h($row['Symptom']['description'])) ?></td>
                                    <td class="hidden-phone"><?php echo Date::getUSFormatDateTime($row['Symptom']['created'], $timezone); ?></td>

                                    <td class="td-actions">
                                        <div class="hidden-phone visible-desktop action-buttons">
                                            <a class="green" href="/admin/Symptoms/edit/<?php echo $row['Symptom']['id']; ?>" data-toggle="modal" data-rel="tooltip" title="Edit">
                                                <i class="icon-pencil bigger-130"></i>
                                            </a>
                                                
                                            <a href="/admin/Symptoms/delete/<?php echo $row['Symptom']['id'] ?>" class="red" data-rel="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this symptom?');">
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
