<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Symptoms', '/admin/Symptoms');
    $this->Html->addCrumb(__(h($this->data['Symptom']['name'])));
?>
<div class="page-content">
	<div class="page-header position-relative">
		<h1>
      		<?php echo __(h("Edit Symptom")); ?>
    	</h1>
	</div>
	<!--/.page-header-->
	
  <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

  <div class="row-fluid">
		<div class="span12">
			<!--PAGE CONTENT BEGINS-->
      		<?php
	      		echo $this->Form->create ( 'Symptom', array (
	      				'action' => 'edit',
	      				'class' => 'form-horizontal',
	      				'label' => false,
	      				'div' => false
	      		) );
      		?>
      
      		<div class="control-group">
				<label class="control-label"><?php echo __('Symptom name')?> </label>
				<div class="controls">
          		<?php
	          		echo $this->Form->input ( 'name', array (
	          				'class' => 'form-control',
	          				'label' => false,
	          				'div' => false,
	          				'style' => 'width:450px;'
	          		) );
	          		echo $this->Form->input ( "id", array (
	          				"type" => "hidden"
	          		) );
          		
          		?>
       			</div>
			</div>

			<div class="control-group">
				<label class="control-label"><?php echo __('Description')?> </label>
				<div class="controls">
        		<?php
	        		echo $this->Form->input ( 'description', array (
	        				'class' => 'form-control',
	        				'label' => false,
	        				'div' => false,
	        				'style' => 'width:450px;'
	        		) );
        		?>
        		</div>
			</div>
			<div class="modal-footer">
		        <?php $options = array('label' => 'Save', 'class' => 'btn btn-primary'); ?>
		        <?php echo $this->Form->end($options); ?>
      		</div>
		</div>
	</div>
</div>
<!--/.page-content-->

<?php echo $this->jQValidator->validator(); ?>

<script type="text/javascript">
	$("ul.nav-list li").removeClass('active');
	$("#symptom-list-li").addClass('active');         
</script>



