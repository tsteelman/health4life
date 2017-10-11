<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), $dashboardUrl);
$this->Html->addCrumb('Email Templates', '/admin/EmailTemplates');
$this->Html->addCrumb(__('Add new template'));
?>
<div class="page-content">
    <section class="grid_12">
        <div class="block-border">
            <?php echo $this->Form->create('EmailTemplate', array('class' => 'block-content form', 'id' => 'AddTemplateForm', 'inputDefaults' => array('div' => false, 'label' => false))); ?>
            <h1><?php echo __('Add Email Template'); ?></h1>
            <table class="table sortable no-margin" cellspacing="0" width="100%" style="margin-left: 0.33em; margin-bottom:-0.67em; ">

                <tr style="height: 30px;">
                    <td align="left" width="20%" valign="top"><?php echo __('Email Template name '); ?></td>
                    <td class="" width="80%">
                        <div class="control-group">
                            <?php echo $this->Form->input('template_name', array('id' => 'template_name', 'class' => 'field form-control', 'style' => 'width: 450px;')); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="left" width="20%" valign="top"><?php echo __('Subject '); ?></td>
                    <td class="" width="80%">
                        <div class="control-group">
                            <?php echo $this->Form->input('template_subject', array('id' => 'template_subject', 'class' => 'field form-control', 'style' => 'width: 450px;')); ?>
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
                    <td align="left" width="20%" valign="top"><?php echo __('Email Body '); ?></td>
                    <td class="" width="80%">

                        <a href="#" onclick="showAddContentEditor()" style='float: right; margin-right: 200px;'> <?php echo __('Edit'); ?></a>
                        <div class="control-group control-group">
                            <?php echo $this->Form->input('template_body', array('id' => 'template_body', 'class' => 'form-control', 'style' => 'width: 450px;display: none;')); ?>
                        </div>
                    </td>
                </tr>
                <tr> 
                    <td align="left" width="98%" valign="top" colspan="2" style='min-height: 200px; height: auto;'>
                        <iframe id="email_body_preview" width='100%' style="border: 0; min-height: 200px" src="<?php echo FULL_BASE_URL; ?>/admin/EmailTemplates/view?preview=true"></iframe>
                    </td>
                </tr>
            </table>
            <table cellspacing="0" width="100%" style="margin-left: 0.33em; margin-top: 40px;">
                <tr>
                    <td width="100" align="left">
                        <?php
                        echo $this->Form->submit(__('Save', true), array('div' => false, 'class' => 'btn btn-small btn-success', 'style' => 'float: right; margin-right: 45px; min-width: 90px; height: 35px !important;'));
                        ?>
                        <button class="btn btn-small btn-success" onclick="window.location.href = '<?php echo FULL_BASE_URL; ?>/admin/EmailTemplates/';" style='float: right; margin-right: 10px;min-width: 90px; height: 35px !important;'><?php echo __('Cancel'); ?></button>
                        <input id="go-back-url" type="hidden" value="<?php echo FULL_BASE_URL; ?>/admin/EmailTemplates/index.php"/>
                    </td>               
                </tr>
            </table>

            <?php echo $this->Form->end(); ?>
        </div>
        <div id="modal-form" class="modal hide" tabindex="-1">
            <div class="modal-header">
                <button id="modal-header-close-btn" type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><?php echo __('Add Email Content'); ?></h4>
            </div>
            <p> <?php echo __('Tags :  |@username@| , |@site-url@| , |@site-name@|, |@link@|'); ?></p>
            <div class="modal-body overflow-visible">
                <div class="row-fluid">
                    <div class="wysiwyg-editor" id="editor1"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="btn-pop-up-cancel" class="btn btn-small btn-primary" data-dismiss="modal">
                    <i class="icon-remove"></i>
                    <?php echo __('Cancel'); ?>
                </button>

                <button id="btn-pop-up-save" class="btn btn-small btn-primary">
                    <i class="icon-ok"></i>
                    <?php echo __('Save'); ?>
                </button>
            </div>
        </div>
    </section>
    <?php echo $this->jQValidator->validator(); ?>
</div>

<script>
                            $(function() {
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
                                                {name: 'createLink', className: 'btn-pink'},
                                                {name: 'unlink', className: 'btn-pink'},
                                                null,
                                                null,
                                                'foreColor',
                                                null,
                                                {name: 'undo', className: 'btn-grey'},
                                                {name: 'redo', className: 'btn-grey'}
                                            ]
                                });


                                $("#btn-pop-up-save").click(function() {
                                    $("#email_body_preview").contents().find('#iframe_contents').html($('#editor1').html());
                                    $("#template_body").val($('#editor1').html());
                                    $('#modal-form').hide();
                                });
                                $("#btn-pop-up-cancel, #modal-header-close-btn").click(function() {
                                    $('#modal-form').hide();
                                });


                            });
                            function showAddContentEditor() {
                                $('#editor1').html($("#email_body_preview").contents().find('#email_body_value').html());
                                $('#modal-form').show();
                            }
</script>
<script type="text/javascript">

    $("ul.nav-list li").removeClass('active');
    $("#email-templates-li").addClass('active');

</script>