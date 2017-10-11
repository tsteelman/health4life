<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('Friends', '/profile');
$this->Html->addCrumb('Import Contacts');
?>
<div class="container">
	
    <div class="messages">
        <div class="row">
            <div class="col-lg-9">
                <div class="page-header">
                    <h3>Import Contacts</h3> 
                </div>

                <div class="message_search_field">
                    <div>
                        Upload a contacts file and invite your friends to Patients4Life. We currently support contact files from the following applications:
                    </div>
                     <div>
                        
                        <ul>
                            <li>Google Contacts</li>
                            <li>Microsoft Outlook</li>
                            <li>Outlook Express</li>
                            <li>Yahoo! Mail</li>
                            <li>Mac OS X Address Book</li>
                        </ul>                        
                    </div>                    
                     <?php
                        echo $this->Form->create('CsvImportForm', array('type' => 'file'));
                        echo $this->Form->input('csv_file', array('label' => 'Upload Contact File','type' => 'file'));
                      ?>
                    <div style="font-size:11px;">
                    Please note:  File formats must be .csv or .vcf. Some non-ASCII characters may not be supported in the .csv or vCard format. As a result languages such as Chinese, Japanese, Hebrew, etc are not supported.
                    </div>
                    <?php
                        echo $this->Form->submit(__('Submit',true), array('class'=>'btn_active btn'));
                        echo $this->Form->end();
                        ?>

                </div>
            </div>
            <?php echo $this->element('layout/rhs', array('list' => true)); ?>
        </div>
    </div>
</div>
