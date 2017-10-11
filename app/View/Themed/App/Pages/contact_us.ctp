<div class="container">
    <div class="contactus_container">
        <div class="col-lg-12">
            <h2>Contact Us</h2>
        </div>
        <div class="col-lg-12 contact_by_faq">
            <div class="contactus_details">
                <h3>Frequently Asked Questions</h3>
                <p>You can find answers to the <a href="/pages/faq" class="owner">Frequently Asked Questions (FAQ) </a>for <?php echo Configure::read('App.name'); ?> here.</p>
            </div>
        </div>
        <div class="col-lg-12 contact_by_chat">
            <div class="contactus_details">
                <h3>Support Chat</h3>
                <p>Our Technical Support Executives will be available almost full-time to help you with any related issues. <a  id="chat" class="owner">Click here to chat.</a></p>
            </div>
        </div>
         <div class="col-lg-12 contact_by_email">
            <div class="contactus_details">
                <h3>E-Mail</h3>
                <p>
                    Do you have any questions? 
                    <a href="" data-toggle="modal" data-target="#email" class="owner">
                        Contact <?php echo Configure::read('App.name'); ?> team via email.
                    </a>
                </p>
            </div>
        </div>
         <div class="col-lg-12 contact_by_phone">
            <div class="contactus_details">
                <h3>Phone</h3>
                <p>Call us at <a href="callto&#58;+512-555-1212" class="owner">512-555-1212</a>. Our information specialists will answer your questions between 9:00 AM and 4:00 PM Central US Time, Monday through Friday, except federal holidays. This service is available in English and Spanish.</p>
            </div>
        </div>
          <div class="col-lg-12 contact_by_social">
            <div class="contactus_details">
                <h3>Social Media</h3>
                <p>Come and join <?php echo Configure::read('App.name'); ?> on various Social Media networks and spread the word.</p>
            </div>
        </div>
        <div class="col-lg-12 contact_by_postal">
            <div class="contactus_details">
                <h3>Postal Mail</h3>
                <p>You can contact <?php echo Configure::read('App.name'); ?> by post at the following address </p>
                <p><span><?php echo Configure::read('App.name'); ?>,</span><br/>
                <span><?php echo Configure::read('App.address'); ?></span></p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade email_contact_us" id="email" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Email</h4>
            </div>
            <div class="modal-body">
                <div id="contact_flash_success" style="display: none;" class="alert alert-success">
                    <button data-dismiss="alert" class="close" type="button" aria-hidden="true">×</button>
                    <div class="message"></div>
                </div>
                <div class="row">
                    <?php
                    echo $this->Form->create('ContactUsForm', array(
                        'id' => $formId,
                        'url' => array(
                            'controller' => 'pages',
                            'action' => 'contactUs'
                        ),
                        'inputDefaults' => array(
                            'label' => false,
                            'div' => false
                        ),
                        'default' => FALSE
                    ));
                    ?>
                    <div class="row">
                        <div class="form-group col-lg-6 pull-left">
                            <label><?php echo __('First Name'); ?></label>
                            <?php
                            if (isset($userData['first_name'])) {
                                echo $this->Form->input('firstName', array('class' => 'form-control', 'placeholder' => __('First Name'), 'value' => h($userData['first_name']), 'disabled' => 'disabled'));
                            } else {
                                echo $this->Form->input('firstName', array('class' => 'form-control', 'placeholder' => __('First Name')));
                            }
                            ?>
                        </div>
                        <div class="form-group col-lg-6 pull-left">
                            <label><?php echo __('Middle Name or Initial'); ?></label>
                            <?php
                            echo $this->Form->input('middleName', array('class' => 'form-control', 'placeholder' => __('Middle Name or Initial')));
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 pull-left">
                            <label><?php echo __('Last Name'); ?></label>
                            <?php
                            if (isset($userData['last_name'])) {
                                echo $this->Form->input('lastName', array('class' => 'form-control', 'placeholder' => __('Last Name'), 'value' => h($userData['last_name']), 'disabled' => 'disabled'));
                            } else {
                                echo $this->Form->input('lastName', array('class' => 'form-control', 'placeholder' => __('Last Name')));
                            }
                            ?>
                        </div>
                        <div class="form-group col-lg-6 pull-left">
                            <label><?php echo __('Suffix'); ?></label>
                            <?php
                            echo $this->Form->input('suffix', array('class' => 'form-control', 'placeholder' => __('Suffix')));
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 pull-left">
                            <label><?php echo __('Email'); ?></label>
                            <?php
                            if (isset($userData['email'])) {
                                echo $this->Form->input('email', array('class' => 'form-control', 'placeholder' => __('Email'), 'value' => h($userData['email']), 'disabled' => 'disabled'));
                            } else {
                                echo $this->Form->input('email', array('class' => 'form-control', 'placeholder' => __('Email')));
                            }
                            ?>
                        </div>
                        <div class="form-group col-lg-6 pull-left">
                            <label><?php echo __('Phone Number'); ?></label>
                            <?php
                            if (isset($userData['phone_number'])) {
                                echo $this->Form->input('phone', array('class' => 'form-control', 'placeholder' => __('Phone Number'), 'value' => h($userData['phone_number']), 'disabled' => 'disabled'));
                            } else {
                                echo $this->Form->input('phone', array('class' => 'form-control', 'placeholder' => __('Phone Number')));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 pull-left">
                            <label><?php echo __('Community Member Name'); ?></label>
                            <?php
                            if (isset($userData['username'])) {
                                echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => __('Username'), 'value' => h($userData['username']), 'disabled' => 'disabled'));
                            } else {
                                echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => __('Username')));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12 pull-left">
                            <label><?php echo __('Comment or Question'); ?></label>
                            <?php echo $this->Form->textarea('enquiry', array('class' => 'form-control', 'placeholder' => __('Comment or Question'), 'rows' => '6')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php
                echo $this->Form->button('Send', array(
                    'type' => 'submit',
                    'class' => 'btn btn-finish',
                    'id' => 'sendContactUsMail'
                ));
                echo $this->Form->button('Cancel', array(
                    'type' => 'reset',
                    'class' => 'btn btn-default btn-prev',
                    'data-dismiss' => 'modal'
                ));
                echo $this->Form->end();
                ?>

            </div>
        </div>
    </div>
</div>


<?php echo $this->jQValidator->validator(); ?>
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
                    window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
                    d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
                    _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
                    $.src='//v2.zopim.com/?2a6oP9IjZQFLgq9vRbzsqkp5PQsJ3Jlf';z.t=+new Date;$.
                    type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
        
</script>
<!--End of Zopim Live Chat Script-->

<?php if ($loggedIn) { ?>
                        <script type="text/javascript">
                        $zopim(function() {
                            $zopim.livechat.set({                             
                              name: '<?php echo $username; ?>',
                              email: '<?php echo $loggedin_user_email; ?>'
                            });
							$zopim.livechat.button.setPosition('bl')    //bottom left
							$zopim.livechat.window.setPosition('bl');
                        });
                        </script>
<?php } ?>

<script>
    $('.btn-default').click(function() {
        $("#ContactUsForm")[0].reset();
    });

    $("#sendContactUsMail").click(function() {
        var formValid = $("#ContactUsForm").valid();
        var formData = $("#ContactUsForm").serialize();
        if (formValid) {
            $.ajax({
                url: '/pages/contactUs',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(result) {
                    $("#ContactUsForm")[0].reset();
                    if (result.success == true) {
                        showAlert('contact_flash_success', result.message);
                    }
                    setTimeout(function() {
                        hideAlerts();
                    }, 2000)
                }

            });
        }
    });
    
    $('#chat').click(function(){
        $zopim.livechat.window.show(); 
    });
</script>