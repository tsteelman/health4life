<?php
  $this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
  $this->Html->addCrumb('My Profile', '/profile');
  $this->Html->addCrumb('Invite Contacts');
?>
<div class="container">
    <div class="row edit">
        <div class="col-lg-3 edit_lhs">
             <div class="respns_header"><h2>Invite Contacts</h2></div>
            <?php echo $this->element('User.Edit/lhs'); ?>
        </div>
        <div class="col-lg-9">
            <div class="page-header">           
                <h2>
                    <span><?php echo __('Invite Contacts'); ?></span>&nbsp;
                </h2>         
            </div>

<!--            <div class="">   
                <div class="">            
                    <p>Invite your contacts to Patients4Life and connect with them using the options below.</p>
                    <ul class="addcontact_icons">
                        <li ><a class="contact_by_fb" href="<?php //echo $fbContactLink;     ?>"></a></li> 
                        <li ><a class="contact_by_google pull-left" title="Invite from Google+" href="<?php echo $googleContactLink; ?>"></a><span>Invite from Google+</span></li>
                        <li ><a class="contact_by_twitter pull-left" title="Import contacts from a file"  href="/import"></a><span>Import contacts from a file</span></li>
                        <li ><a class="contact_by_mail pull-left" title="Invite using email address" data-toggle="modal" data-target="#emailInviteFriends"  href="#"></a><span>Invite using email address</span></li>
                    </ul>
                </div>        
            </div>-->
        </div>
    </div>
</div>
<?php echo $this->element('layout/email_invite_model'); ?>
