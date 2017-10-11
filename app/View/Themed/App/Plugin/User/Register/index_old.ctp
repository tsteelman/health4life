<?php 
$videos = array(
        "https://www.youtube.com/watch?v=wyI7mtSFLdo",
        "https://www.youtube.com/watch?v=nXvD_cNRFM4"
);
$home_video =  $videos[array_rand($videos)];
?>

<div id="div_role_select" class="container role_selection">
    <div class="thumbnail">
        <div class="page-header text-center"><h1 >Choose your Role</h1></div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3" id="role_1" data-title="<?php echo __('Patient SignUp'); ?>">
                <div class="role_desc">
                    <div class="">
                        <?php echo $this->Html->image('patient_role.png', array('alt' => 'Patient', 'class' => 'img-circle')); ?>
                    </div>
                    <div class="caption">
                        <h4 >Patient</h4>
                        <p ><button type="button" data-role="1"  class="btn patient_btn role_btn">Select</button> </p>
                        <a href="/register" class="change_role">Change Role</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3" id="role_2" data-title="<?php echo __('Family SignUp'); ?>">
                <div class="role_desc">
                    <div class="">
                        <?php echo $this->Html->image('family_role.png', array('alt' => 'Family & Friends', 'class' => 'img-circle')); ?>
                    </div>
                    <div class="caption">
                        <h4 >Family</h4>
                        <p ><button type="button" data-role="2" class="btn family_btn role_btn">Select</button> </p>
                        <a href="/register" class="change_role">Change Role</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3" id="role_3" data-title="<?php echo __('Caregiver SignUp'); ?>">
                <div class="role_desc">
                    <div class="">
                        <?php echo $this->Html->image('caregiver_role.png', array('alt' => 'Caregiver', 'class' => 'img-circle')); ?>
                    </div>
                    <div class="caption">
                        <h4 >Caregiver</h4>
                        <p ><button type="button" data-role="3" class="btn caregiver_btn role_btn">Select</button> </p>
                        <a href="/register" class="change_role">Change Role</a>
                    </div>
                </div>
            </div>		

            <div class="col-lg-3 col-md-3 col-sm-3" id="role_4" data-title="<?php echo __('Friend SignUp'); ?>">
                <div class="role_desc">
                    <div class="">
                        <?php echo $this->Html->image('other_role.png', array('alt' => 'Other', 'class' => 'img-circle')); ?>
                    </div>
                    <div class="caption">
                        <h4 >Friend</h4>
                        <p ><button type="button" data-role="4" class="btn other_btn role_btn">Select</button> </p>
                        <a href="/register" class="change_role">Change Role</a>
                    </div>
                </div>
            </div>	

        </div>
    </div>
    <div class="home_video_container row" >
    <div class="col-lg-12" data-video="<?php echo $home_video; ?>" >
        <img class="img-responsive video_icon" src="/theme/App/img/video_icon.png">
        <img class="img-responsive" src="/theme/App/img/video_bg.png">
    </div></div>
</div>


<div id="div_role_choosen" class="container role_choosen hide">
    <div class="thumbnail">
        <div class="page-header">
            <div class="text-center">
                <h1>Patient</h1>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row role_selection">
            <div class="col-lg-3 col-md-3 col-sm-3" id="role_sel_desc"><h4></h4></div>	

            <div class="col-lg-7 col-sm-7 col-md-7" id="role_sel_form">
                <div class="form_content">
                    <?php echo $this->element('User.Register/form'); ?>
                </div>
            </div>		
        </div>
    </div>
</div>
<?php
$this->jQValidator->printCountryZipRegexScriptBlock();
echo $this->AssetCompress->css('facelist');
echo $this->AssetCompress->script('register_view.js');
?>
<script type="text/javascript">
    $(document).on('blur','.disease_search', function() {
          
        if ($(this).next('.disease_id_hidden').val().trim() == "") {
          $(this).val('');
        }
      });
</script>