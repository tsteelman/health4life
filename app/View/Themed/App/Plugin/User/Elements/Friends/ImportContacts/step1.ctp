<div id="import_contact_step_1">
    <div class="import_contact_header">
        <h2>
            <?php echo __('Connect with people you know on '.Configure::read ( 'App.name' )); ?>
        </h2>
    </div>
    <div class="people_list">
        <div class="page-header"></div>
        <p>
            <?php
            echo __('We found %d people you know on '.Configure::read ( 'App.name' ) , $existingUsersCount);
            if ($existingUsersCount > 0) {
                echo __(" Select the people you'd like to connect to.");
            }
            ?>
        </p>
        <?php if ($existingUsersCount > 0): ?>
            <div class="select_all">
                <?php
                echo $this->Form->checkbox('select_all_existing_contacts');
                echo $this->Html->tag('label', 'Select All', array('for' => 'select_all_existing_contacts'));
                ?>
                <span class="pull-right"><span id="selected_existing_contacts_count">0</span> selected</span>
            </div>
            <div class="contact_list slim-scroll">
                <?php foreach ($existingUsers as $existingUser): ?>
                    <div>
                        <?php echo $this->element('User.Friends/ImportContacts/existing_user_row', array('user' => $existingUser)); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="block">
            <div class="flt_lft btn_area">
                <button class="btn btn_active" type="button" id="add_existing_connections" disabled="disabled"><?php echo __('Add Connection(s)'); ?></button>
                or
                <button class="btn btn_clear" type="button" id="skip_step_1"><?php echo __('Skip this step > >'); ?></button>
            </div>
        </div>
    </div>
</div>