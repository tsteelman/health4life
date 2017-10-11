<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Newsletters', '/admin/Newsletters');
$this->Html->addCrumb(__($title_for_layout));
?>
<?php echo $this->Html->script('ckeditor/ckeditor'); ?>
<div class="page-content">
    <section class="grid_12">
        <div class="block-border">
	    <?php echo $this->Form->create('Newsletter', array('class' => 'block-content form', 'id' => 'AddNewsletterForm', 'inputDefaults' => array('div' => false, 'label' => false))); ?>
	    <?php echo $this->Form->hidden('id') ?>
            <h1><?php echo __($title_for_layout); ?></h1>
            <table class="table sortable no-margin" cellspacing="0" width="100%" style="margin-left: 0.33em; margin-bottom:-0.67em; ">

                <tr>
                    <td align="left" width="20%" valign="top"><b><?php echo __('Newsletter Subject '); ?></b></td>
                    <td class="" width="80%">
                        <div class="control-group">
			    <?php echo $this->Form->input('subject', array('id' => 'newsletter_subject', 'class' => 'field form-control', 'style' => 'width: 450px;')); ?>
                        </div>
                    </td>
                </tr>
                <?php if (empty( $this->data)) { ?>
                <tr>
                    <td align="left" width="20%" valign="top">
                        <p> <b><?php echo __('Design Template'); ?></b> </p> </td>
                    <td align="left" width="80%" valign="top">

                    	<?php echo $this->Form->input('template_Id', array('type' => 'select', 'options' => $templateList, 'empty' => array( 0 => 'No template')))?>
                        <span class="help-inline" style='margin-left: 10px; color: #009fff;'>Please select any of the template form the list</span>


                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td align="left" width="20%" valign="top">
                        <p> <b><?php echo __('Predefined Tags'); ?></b> </p> </td>
                    <td align="left" width="80%" valign="top">

                    		<?php echo $this->Form->input('tags', array('type' => 'select', 'id' => 'select_tag', 'options' => $tagList, 'empty' => array('' => 'Select tag'))); ?>
                        <span class="help-inline" style='margin-left: 10px; color: #009fff;'>Please select tags from this box to insert to  the editor.</span>


                    </td>
                </tr>                
                <tr>
                    <td align="left" width="20%" valign="top">
                        <p> <b><?php echo __('Add '); echo Configure::read('App.name'); echo __(' related details'); ?></b> </p> </td>
                    <td align="left" width="80%" valign="top">
			<?php echo $this->Form->input('addDetails', array('type' => 'select', 'id' => 'insert_detail', 'options' => $detailsList, 'empty' => array('' => 'Select details'))) ?>


                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="left" width="100%" valign="top">
                    <p> <b><?php echo __('Newsletter Content'); ?></b> </p> </td>
                    </td>

                </tr>
                <tr>
                    <td colspan="2" class="" width="100%">

						<div class="control-group control-group">
                            <?php echo $this->Form->input('content', array('type' => 'textarea', 'id' => 'template_body', 'class' => 'form-control ckeditor', 'style' => 'height:800px;')); ?>

                        </div>
                    </td>
                </tr>

            </table>
            <table cellspacing="0" width="100%" style="margin-left: 0.33em; margin-top: 40px;">
                <tr>
                    <td width="100" align="left">

			<?php			
			echo $this->Form->button(
				'<i class="icon-save bigger-125"></i>Save and send newsletter', array(
			    'formaction' => Router::url(array(
				'controller' => 'Newsletters', 'action' => 'sendNewsletter'
			    )),
			    'onclick' => 'check_form()',
			    'class' => 'btn btn-small btn-purple',
			    'style' => 'float: right; margin-right: 10px;min-width: 90px; height: 35px !important;'
				)
			);
			echo $this->Form->submit(__('Save', true), array('div' => false, 'class' => 'btn btn-small btn-purple', 'onclick' => 'check_form()', 'style' => 'float: right; margin-right: 10px; min-width: 90px; height: 35px !important;'));
			
			echo $this->Form->button(
				'<i class="icon-eye-open"></i>Preview', array(
			    'type' => 'button',
			    'onclick' => "javascript:loadPreview();",
			    'class' => 'btn btn-small btn-info',
			    'data-target' => '#modal-newsletter-preview',
			    'data-toggle' => 'modal',
			    'style' => 'float: right; margin-right: 10px;min-width: 90px; height: 35px !important;'
				)
			);
			echo $this->Form->button(
				'<i class="icon-remove bigger-125"></i>Cancel', array(
						'type' => 'button',
						'onclick' => "window.location='/admin/Newsletters'",
						'class' => 'btn btn-small',
						'style' => 'float: right; margin-right: 10px;min-width: 90px; height: 35px !important;'
				)
			);
			?>

                    </td>               
                </tr>
            </table>

	    <?php echo $this->Form->end(); ?>
        </div>

    </section>    	

    <div id="modal-newsletter-preview" class="modal hide" tabindex="-1">
	<div class="modal-header">
	    <button id="modal-header-close-btn" type="button" class="close" data-dismiss="modal">&times;</button>
	    <h4 class="blue bigger"><?php echo __('Newsletter preview'); ?></h4>
	</div>

	<div class="modal-body overflow-visible">
	    <div class="row-fluid">

	    </div>
	</div>

	<div class="modal-footer">
	    <button id="btn-pop-up-cancel" class="btn btn-small btn-primary" data-dismiss="modal">
		<i class="icon-remove"></i>
		<?php echo __('Close'); ?>
	    </button>
	</div>
    </div>

    <div id="modal-insert-details" class="modal hide fade"  tabindex="-1">
	<div class="modal-header">
	    <button id="modal-header-close-btn" type="button" class="close" data-dismiss="modal">&times;</button>
	    <h4 class="blue bigger"><?php echo __('Insert details'); ?></h4>
	</div>

	<div class="modal-body overflow-visible">
	    <div class="row-fluid">

	    </div>
	</div>
	<div class="modal-footer">

	    <button id="btn-pop-up-cancel" class="btn btn-small btn-primary" data-dismiss="modal">
		<i class="icon-remove"></i>
		<?php echo __('Close'); ?>
	    </button>

	    <button id="button-inset-details" type="submit" class="btn btn-small btn-primary">
		<i class="icon-ok"></i>
		<?php echo __('Insert'); ?>
	    </button>   
	</div>
    </div>
    <?php echo $this->jQValidator->validator(); ?>
</div>

<?php echo $this->AssetCompress->script('bootbox.min') ?>

<script type="text/javascript">

    $(document).ready(function() {
	$("#newsletters-li a").trigger('click');
	$("ul.nav-list li").removeClass('active');
	$("#newsletters-manage-li").addClass('active');
	CKEDITOR.config.height = 450;
	CKEDITOR.config.fillEmptyBlocks = false;  
    });

    // newsletter template loading in content field
    $('#NewsletterTemplateId').change(function() {
	var template_id = $('#NewsletterTemplateId').val();

	$.ajax({
	    url: '/admin/Newsletters/getNewsletterTemplateContent',
	    data: {
		'id': template_id
	    },
	    type: 'GET',
	    success: function(result) {

		if (CKEDITOR.instances.template_body.checkDirty()) {
		    bootbox.confirm("Are you sure? You will lose the changes made in the editor.", function(confirmMsg) {
			if (confirmMsg) {
			    CKEDITOR.instances.template_body.setData(result);
			}
		    });
		} else {
		    CKEDITOR.instances.template_body.setData(result);
		}

	    }

	});
    });

    $("#select_tag").on("change", function() {
	CKEDITOR.instances.template_body.insertText($(this).val());
    });

    $("#insert_detail").on("change", function() {
	$('#modal-insert-details .modal-body').html('');
	var functionName = $('#insert_detail').val();

	if (functionName.length != 0) {
	    $('#insert_detail').prop('selectedIndex', 0);
	    $.ajax({
		url: '/admin/Newsletters/' + functionName,
		type: 'POST',
		success: function(result) {
		    $('#modal-insert-details .modal-body').html(result);
		    $('#modal-insert-details').modal('show');
		}
	    });
	}
    });
    function check_form() {
    	CKEDITOR.instances.template_body.updateElement();
    	var editorcontent = CKEDITOR.instances.template_body.getData().replace(/<[^>]*>/gi, '').trim();
    	if(editorcontent.length == 0){
    		$("textarea#template_body").val('');    		
    		$('#template_body').valid();
    		return false;
    	}
    }
   
    function loadPreview() {
	$('#modal-newsletter-preview .modal-body').html('');

	var HtmlData = CKEDITOR.instances.template_body.getData();
	$.ajax({
	    url: '/admin/Newsletters/getNewsletterPreview',
	    data: {
		'data': HtmlData
	    },
	    type: 'POST',
	    success: function(result) {
		$('#modal-newsletter-preview .modal-body').html(result);
	    }
	});

    }

    $("#button-inset-details").on("click", function() {
	$('#modal-insert-details').modal('hide');
	var details = $('#modal-insert-details .modal-body').html();
	CKEDITOR.instances.template_body.insertHtml(details);
    });

</script>