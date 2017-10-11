<?php $className = (isset($list)) ? 'event_list_lhs' : 'event_lhs'; ?>
<div class="col-lg-3" id="rhs">
    <div class="<?php echo $className; ?>">   
<!--        <div id="invite_contacts_to_p4l" class="add_area advance_search_options">
            <h4>Invite Contacts</h4>
            <p>Invite your contacts to Patients4Life and connect with them.</p>
            <ul class="">
                <a href="/user/invite">
                    <button class="btn btn-invite-contacts col-md-offset-1" data-backdrop="static" data-keyboard="false" >Invite Friends</button>
                </a>
            </ul>
        </div>-->
        <?php echo $this->element('ads'); ?>
    </div>
</div>