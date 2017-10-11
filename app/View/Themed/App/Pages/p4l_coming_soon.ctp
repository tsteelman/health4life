<!--<div class="comingsoon_wraper p4l_comingsoon">
<div class="comingsoon_container">
<img src="/theme/App/img/logo.png">
<div class="comingsoon_text p4l_text">
COMING SOON
</div>
<div class="comingsoon_signup p4l_signup">
<h2>Sign up for our Beta launch</h2>
<input type="text" class="signup_input sigup_name">
<input type="text" class="signup_input sigup_email">
<button class="signup_button p4l_signup_button">SIGN UP</button>
</div>
</div>
</div>
-->

<div class="comingsoon_wraper p4l_comingsoon">
    <div class="comingsoon_container">
        <img src="/theme/App/img/logo.png">        
        <div class="comingsoon_text p4l_text">
            COMING SOON
        </div>
        <div class="comingsoon_signup p4l_signup">
            <h2>Sign up for our Beta launch</h2>

            <?php
                echo $this->Form->create('PrelaunchUser', array(
                                    'inputDefaults' => array(
                                            'label' => false                                            
                                            )
                                    ));
            ?>
            <div class="inputfield form-group">
            <?php
                echo $this->Form->input('name', array(
                                    'type' => 'text',
                                    'placeholder' => 'Your name',
                                    'class' =>'signup_input signup_name form-control'
                                ));
            ?>
            </div>    
            <div class="inputfield form-group">
            <?php
                echo $this->Form->input('email', array(
                                    'type' => 'email',
                                    'placeholder' => 'Your email',
                                    'class' =>'signup_input signup_email form-control'
                                ));
            ?>
            </div>
            <?php    
                echo $this->Form->input('SIGN UP', array(
                                    'type' => 'button',
                                    'class' => 'signup_button p4l_signup_button'
                                ));
                
                echo $this->Form->end();
            ?>
        </div>
        <div id="success_msg" style="display:none;color:black;">
            <h3>Thank you for subscribing!</h3>
        </div>
    </div>
    
<?php
    echo $this->Html->script('p4l_coming_soon');
    echo $this->jQValidator->validator();
?>

           

