<?php
    $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
    $this->Html->addCrumb('Surveys', '/admin/Surveys');
    $this->Html->addCrumb(__('Add Survey'));
?>
<div class="page-content">
  <div class="page-header position-relative">
    <h1>
      <?php echo __(h("Add Survey")); ?>
    </h1>
  </div><!--/.page-header-->
  <?php echo $this->Session->flash('flash', array('element' => 'warning')); ?>

  <div class="row-fluid">
    <div class="span12">
      <!--PAGE CONTENT BEGINS-->
      <?php
      echo $this->Form->create('Survey', array(
      'action' => 'add',
      'class' => 'form-horizontal',
      'label' => false,
      'div' => false
      ));
      ?>
      
      <div class="control-group">
        <label class="control-label">Survey name <span style="font-size: 22px; color: #CA1E34">*</span></label>
        <div class="controls">
          <?php
          echo $this->Form->input('name', array('class' => 'form-control',
          'label' => false,
          'div' => false));
          echo $this->Form->input("id", array("type" => "hidden"));
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
          'style' => 'width:450px;'));
          ?>
        </div>
      </div>
      <div class="control-group">
	        <label class="control-label"><?php echo __('Diseases') ?></label>
	        <div class="controls">
	        	<ul class="facelist">
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
							'value' => '',
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
                    $attributes = array('legend' => false,  'div' => true, 'label' => array(true, 'class' => 'lbl'));
                    echo $this->Form->radio('type', $options, $attributes);
                ?>
<!--                
                <?php
                    echo $this->Form->input('type', array(
                    'options' => array('Disease Information','Medication'),
                    'empty' => 'Select',
                    'label' => false,
                    'div' => false,
                    'style' => 'width:225px;'));   
                ?>
                -->
            </div>
      </div>
     
      <hr/>
      
      <div class="modal-footer">
        <?php $options = array('label' => 'Save', 'class' => 'btn btn-primary'); ?>
        <?php echo $this->Form->end($options); ?>
      </div>

    </div><!--/.page-content-->
  </div> 
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
      $('#editor1').html($('#DiseaseDescription').val());
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
     
     $(".lbl").on("click", function() {
         $("#Survey" + $(this).parents.children("label").attr("for")).click();
     });

  </script>


