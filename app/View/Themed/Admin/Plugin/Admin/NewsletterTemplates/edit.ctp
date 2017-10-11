<?php
echo $this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Newsletter Templates', '/admin/NewsletterTemplates');
$this->Html->addCrumb(__(h($this->data['NewsletterTemplate']['template_name'])));
?>
<div class="page-content">
    <section class="grid_12">
        <div class="block-border">
	    <?php echo $this->Form->create('NewsletterTemplate', array('class' => 'block-content form', 'id' => 'EditTemplateForm', 'inputDefaults' => array('div' => false, 'label' => false))); ?>
            <h1><?php echo __('Newsletter Template name '); ?></h1>
            <table class="table sortable no-margin" cellspacing="0" width="100%" style="margin-left: 0.33em; margin-bottom:-0.67em; ">
                <tr style="height: 30px;"> 
		    <?php echo $this->Form->input('id'); ?> 
                </tr>
                <tr style="height: 30px;">
                    <td align="left" width="20%" valign="top"><?php echo __('Newsletter Template name '); ?></td>
                    <td class="" width="80%">
                        <div class="control-group">
			    <?php echo $this->Form->input('template_name', array('id' => 'template_name', 'value' => $this->data['NewsletterTemplate']['template_name'], 'class' => 'form-control', 'style' => 'width: 450px;')); ?>
                        </div>
                    </td>
                </tr>             
                <tr>
                    <td align="left" width="20%" valign="top">
                        <p> <b><?php echo __('tags : '); ?></b> </p> </td>
                    <td align="left" width="80%" valign="top">
                        <p> <?php echo __('|@username@| , |@site-url@| , |@site-name@|, |@link@|'); ?> <span class="help-inline" style='margin-left: 10px; color: #009fff;'>copy and paste the tags to the content.</span></p>
		    </td>
                </tr>
                <tr>
                    <td align="left" width="20%" valign="top">
			<?php echo __('Newsletter Body '); ?>
                    </td>
		    <td class="" width="80%"></td>

                </tr>
                <tr>
                    <td class="" width="98%" colspan="2">
			<?php echo $this->Form->input('template_body', array('id' => 'template_body', 'value' => $this->data['NewsletterTemplate']['template_body'], 'class' => 'field view_dialog ckeditor', 'style' => 'width: 450px;')); ?></td>
                </tr>

            </table>
            <table cellspacing="0" width="100%" style="margin-left: 0.33em; margin-top: 40px;">
                <tr>
                    <td width="100%" align="left">
			<?php
			echo $this->Form->submit(__('Save', true), array('div' => false, 'class' => 'btn btn-small btn-success', 'onclick' => 'check_form()', 'style' => 'float: right; margin-right: 45px; min-width: 90px; height: 35px !important;'));
			echo $this->Form->button(
				'Cancel', array(			    
			    'type' => 'button',
			    'onclick' => "window.location='/admin/NewsletterTemplates'",
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
    <?php echo $this->jQValidator->validator(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
	$("#newsletters-li a").trigger('click');
	$("ul.nav-list li").removeClass('active');
	$("#newsletters-templates-li").addClass('active');
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
</script>