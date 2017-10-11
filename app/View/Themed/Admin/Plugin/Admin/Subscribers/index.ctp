<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Subscribers');
?>
<div class="page-content">
    <div class="page-header position-relative">
        <h1>
            <?php echo __('Manage Subscribers'); ?>
        </h1>
    </div><!--/.page-header-->
    <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

    <div class="row-fluid">
        <div class="span12">
            <!--PAGE CONTENT BEGINS-->


            <div class="row-fluid">
                <div class="table-header">
                    <?php echo __(h('  Manage Subscribers List')); ?>
                   


                    <!--</div>-->
                </div>
                <div class="dataTables_wrapper" role="grid">
				<div class="row-fluid" >
					<div class="span6">
						<div class="dataTables_length">
							
							<?php 
		                		echo $this->Form->create('SubscribersFilter', array('class' => 'dataTables_length',
														'id' => 'SubscribersFilterIndexForm',
														'url' => array('controller' => 'subscribers', 'action' => 'index'), 
														'label' => false, 'div' => false));
		                		echo $this->Form->input('filter', array('type' => 'select','label' =>  __('User type'),'div' => false, 
														'onchange' => 'javacript:filterSubscribers(this.value);', 'class' => 'dataTables_length',
														'style' => 'height: 32px; width: 100px;','default'=> $filter,
														'options' => array(0 => 'all', 1=> 'Patient', 2=> 'Family', 3=> 'Caregiver', 4 => 'Other' )));
		                		echo $this->Form->end();
		                	?>
		                
                		</div>
                	</div>
                	<div class="span6">
                		<div class="dataTables_filter" id="sample-table-2_filter">
                		<?php 	echo $this->Form->create('Subscribers', array('action' => 'index', 'type' => 'get')); 
                		
                                if (isset($keyword)) {
                                    $search_word = $keyword;
                                } else {
                                    $search_word = '';
                                }
                                
                                echo $this->Form->input('keyword', array('label' => false, 'class' => '', 
													'placeholder' => __('search by username or email'),'aria-controls' => 'sample-table-2', 
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
		                                <th> <?php echo __(h('Username')); ?></th>
		                                <th> <?php echo __(h('Full Name')); ?></th>
		                                <th> <?php echo __(h('Email')); ?></th> 
		                                <th> <?php echo __(h('Type')); ?></th>                               
		                                <th> <?php echo __(h('Status')); ?></th>
		                               
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
		                                        <td><?php
		                                            if ($row['User']['newsletter']) {
		                                                ?>                                                
		                                                <a  class="btn btn-mini btn-success" href="/admin/Subscribers/changeStatus?
<?php if ($this->Paginator->numbers()) { ?>page=<?php echo $this->Paginator->counter('{:page}'); }?>
&id=<?php echo __(h($row['User']['id'])); ?>&action=inactivate&filter=<?php echo $filter;?>
<?php if(isset($keyword)){echo __('&keyword=' . urlencode($keyword));}?>" title="activated">
																<i class="icon-ok bigger-120"></i>
															
														</a>
		                                                <?php
		                                            } else {
		                                                ?>
		                                                <a class="btn btn-mini btn-danger" href="/admin/Subscribers/changeStatus?
<?php if ($this->Paginator->numbers()) { ?>page=<?php echo $this->Paginator->counter('{:page}'); }?>
&id=<?php echo __(h($row['User']['id'])); ?>&action=activate&filter=<?php echo $filter;?>
<?php if(isset($keyword)){echo __('&keyword=' . urlencode($keyword));}?>" title="deactivated">
																<i class="icon-ban-circle bigger-120"></i>
																
														</a>
		                                                <?php
		                                            }
		                                            ?></td>
		                                        
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
    </div>
</div><!--/.page-content-->


<script type="text/javascript">
    $(document).ready(function(){
	$("#newsletters-li a").trigger('click');
    });

	
    function filterSubscribers(data){
    	var keyword = encodeURIComponent($( '#SubscribersKeyword' ).val());       
		window.location.replace('/admin/Subscribers'+ '?filter=' + data + '&keyword=' + keyword);
    }         
</script>

