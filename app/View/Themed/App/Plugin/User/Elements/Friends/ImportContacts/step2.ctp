<div id="import_contact_step_2" class="hide">
    <div class="import_contact_header">
        <h2><?php echo __('Why not invite some people?'); ?></h2>
    </div>
    <div class="people_list">
        <div class="page-header"></div>
        <?php if (isset($newUsersCount) && ($newUsersCount > 0)): ?>
            <p><?php echo __("Stay in touch with your contacts who aren't on ". Configure::read ( 'App.name' )  ." yet. Invite them to connect with you."); ?></p>
            <div class="select_all">
                <?php
                echo $this->Form->checkbox('select_all_new_contacts');
                echo $this->Html->tag('label', 'Select All');
                ?>
                <span class="pull-right"><span id="selected_new_contacts_count">0</span> selected</span>
            </div>
            <div class="contact_list slim-scroll">
                <?php foreach ($newUsers as $newUser): ?>
                    <div class="indvdl_people">
                        <?php echo $this->element('User.Friends/ImportContacts/new_user_row', array('user' => $newUser)); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>
                <?php echo __('All of your contacts are on '. Configure::read ( 'App.name' ) . '.'); ?>
            </p>
        <?php endif; ?>
        <div class="block">
            <div class="flt_lft btn_area">
                <button class="btn btn_active" type="button" id="add_new_connections" disabled="disabled"><?php echo __('Add Connection(s)'); ?></button>
                or
                <button class="btn btn_clear" type="button" id="skip_step_2"><?php echo __('Skip this step > >'); ?></button>
            </div>
        </div>
    </div>
</div>