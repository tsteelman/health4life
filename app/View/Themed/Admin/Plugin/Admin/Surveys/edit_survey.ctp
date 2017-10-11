<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Surveys', '/admin/Surveys');
    $this->Html->addCrumb(__(h($survey_details['name'])));
?>
<div class="page-content">
  <div class="page-header position-relative">
    <h1>
      <?php echo __(h("Edit Survey")); ?>
        <a href="/admin/Surveys/addQuestion/<?php echo $id ?>">
            <button class="btn btn-primary" style='float: right;' data-backdrop="static" data-keyboard="false"  onclick="">
                  <?php echo __("View Questions"); ?>  
            </button>
        </a>
    </h1>
  </div><!--/.page-header-->
  <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

  <div class="row-fluid">
    <div class="span12">
      <!--PAGE CONTENT BEGINS-->

      <?php
      echo $this->Form->create('Survey', array(
      'action' => 'editSurvey',
      'class' => 'form-horizontal',
      'label' => false,
      'div' => false
      ));
      ?>
      
      <div class="control-group">
        <label class="control-label">Survey name</label>
        <div class="controls">
          <?php
          echo $this->Form->input('name', array('class' => 'form-control',
          'label' => false,
          'value' => $survey_details['name'],
          'div' => false));
          echo $this->Form->input("id", array("type" => "hidden", "value" => $id));
          ?>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Description</label>
        <div class="controls">
          <?php
          echo $this->Form->input('description', array('class' => 'form-control',
          'label' => false,
          'div' => false,
          'value' => $survey_details['description'],
          'style' => 'width:450px;'));
          ?>
        </div>
      </div>
      
      <div class="control-group">
	        <label class="control-label"><?php echo __('Diseases') ?></label>
	        <div class="controls">
	        	<ul class="facelist">
                                    <?php	        			
	        			if(!empty($diseases)){
							foreach ($diseases as $disease){								
                                    ?>
	        				<li id="bit-<?php echo $disease['Disease']['id']; ?>" class="token">
	        					<span>
	        						<span><span><span><?php echo __(h($disease['Disease']['name']));?></span></span></span>
	        					</span>
	        					<span class="x"> .x</span>
	        				</li>
                                    <?php } } ?>
                            <li class="token-input">
		
		        <?php
			        echo $this->Form->input("SurveyDisease", array(
			        		'type' => 'text',
			        		'class' => 'disease_input',			        		
			        		'placeholder'=>'Multiple diseases can be linked',
							'div' => false,
							'label' => false,
							'style' => 'width:200px;'
			        ));

                                echo $this->Form->hidden("Disease_Id", array(
							'class' => 'disease_id',	
							//'value' => $diseases,
							'div' => false,
							'label' => false						
				));
		        ?>
                            </li>
		        </ul>
		        
		        <div class="result_list" style="display:none;"></div>
	        </div>
      </div>
      
      <div class="control-group">
            <label class="control-label">Show survey in :</label>
            <div class="controls radio-input" style="margin-top: 5px;">
                
                <?php
                    $options = array('0' => ' Disease Information', '1' => ' Medication');
                    $attributes = array('legend' => false, 'div' => true, 'value' => $survey_details['type'], 'label' => array(true, 'class' => 'lbl'));
                    echo $this->Form->radio('type', $options, $attributes);
                ?>
<!--                
                <?php
                    echo $this->Form->input('type', array(
                    'options' => array(0 =>'Disease Information',1 => 'Medication'),
                    'empty' => 'Select',
                    'label' => false,
                    'value' => $survey_details['type'],
                    'div' => false,
                    'style' => 'width:225px;'));   
                ?>
                -->
            </div>
      </div>
      
      <hr/>
      
      <div class="modal-footer">
          <?php echo $this->Form->submit(__('Save', true), array('div' => false, 'class' => 'btn btn-primary', 'style' => 'float: right; margin-right: 45px; min-width: 90px; height: 35px !important;')); ?>
          <?php echo $this->Form->submit(__('Cancel', true), array('onclick' =>"window.location.href = '/admin/Surveys';" , 'type' => 'button', 'div' => false, 'class' => 'btn btn-primary', 'style' => 'float: right; margin-right: 10px; min-width: 90px; height: 35px !important;')); ?>
      </div>
      
      <div style="display:none;" id="description_content">
        <?php echo $survey_details['description']; ?>
      </div>
      
    </div><!--/.page-content-->
  </div> 
  <script type="text/javascript" src="/theme/App/js/vendor/fineuploader-4.0.2.min.js"></script>
  <script type="text/javascript" src="/theme/Admin/js/image_upload.js"></script>
  <link rel="stylesheet" href="/theme/App/css/fineuploader-4.0.2.min.css"/>
  <script type="text/javascript">

    $(document).ready(function() {
        
      $('.disease_input').each(function() {
            initFaceList('#'+$(this).attr('id'));
      });
      $('#editor1').ace_wysiwyg({
        speech_button: false,
        toolbar:
                [
                  'font',
                  null,
                  'fontSize',
                  null,
                  {name: 'bold', className: 'btn-info'},
                  {name: 'italic', className: 'btn-info'},
                  {name: 'strikethrough', className: 'btn-info'},
                  {name: 'underline', className: 'btn-info'},
                  null,
                  {name: 'insertunorderedlist', className: 'btn-success'},
                  {name: 'insertorderedlist', className: 'btn-success'},
                  {name: 'outdent', className: 'btn-purple'},
                  {name: 'indent', className: 'btn-purple'},
                  null,
                  {name: 'justifyleft', className: 'btn-primary'},
                  {name: 'justifycenter', className: 'btn-primary'},
                  {name: 'justifyright', className: 'btn-primary'},
                  {name: 'justifyfull', className: 'btn-inverse'},
                  null,
                  //{name: 'createLink', className: 'btn-pink'},
                  //{name: 'unlink', className: 'btn-pink'},
                  null,
                  null,
                  'foreColor',
                  null,
                  {name: 'undo', className: 'btn-grey'},
                  {name: 'redo', className: 'btn-grey'}
                ]
      }).prev().addClass('wysiwyg-toolbar center');
      initEventPhotoUploader();
      $('#editor1').html($('#description_content').text());
      $("ul.nav-list li").removeClass('active');
      $('#survey-list-li').addClass('active');
    });
    
    function initFaceList(input) {
        var result_field = $(input).next('.disease_id').attr('id');
        $(input).facelist('/api/searchConditions', properties = {
            matchContains: true,
            minChars: 2,
            selectFirst: false,
            intro_text: 'Type Name',
            no_result: 'No Names',
            result_field: 'SurveyDiseaseId'
        });
    }
    
    $('.x').click(function() {
	      var id = $(this).parent().attr('id');
	      var id_to_remove = id.split("-");
	      id_to_remove = id_to_remove[1];
              $.ajax({
                    url: '/admin/Surveys/dissociateDisease',
                    type: 'POST',
                    data : { 'id' : id_to_remove}
              });
	      var selected = $(this).parents('.facelist').find('.disease_id').val();
	      ids = selected.split(",");
	      var new_value = "";
	      for (id in ids) {
	        if (ids[id] !== id_to_remove && ids[id] != "") {
	          new_value += ids[id] + ','
	        }
	      }
	      $(this).parents('.facelist').find('.disease_id').val(new_value);
	      $(this).parent().remove();
    });
    
  </script>


