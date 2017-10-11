<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('Friends', '/profile');
$this->Html->addCrumb('Import Contacts');
?>
<div class="container create_common">
    <div class="row">
        <div id="import_contact_message" class="alert hide">
            <div class="message"></div>
        </div>
        <div class="col-lg-9">
            <?php if (!isset($error)) : ?>
                <div id="import_contacts_wizard" class="wizard">
                    <ul class="steps">
                        <li class="active"></li>
                        <li></li>
                    </ul>
                    <div class="step-content">
                        <?php echo $this->Form->create(false); ?>
                        <?php echo $this->element('User.Friends/ImportContacts/step1'); ?>
                        <?php echo $this->element('User.Friends/ImportContacts/step2'); ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php echo $this->element('layout/rhs'); ?>
    </div>
</div>
<?php
$this->AssetCompress->script('import_contacts_compressed.js', array('block' => 'scriptBottom'));