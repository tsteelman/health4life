<?php
$videos = array(
        "https://www.youtube.com/watch?v=wyI7mtSFLdo",
        "https://www.youtube.com/watch?v=nXvD_cNRFM4"
);
$home_video =  $videos[array_rand($videos)];
$home_video =  $videos[1];

?>
<div class="container">
    <div class="site_descrption ">
        <div class=" marketing">
            <div class="hover_element col-lg-3 home_tiles home_connect"   
                data-html="true"
                
                 >
                <div class="contents">
                    <h2>Connect</h2>
                    <p><?php echo Configure::read('App.name'); ?> allows Patients, Caregivers, Family members, and Friends to
join together in what we call Caring Communities to provide information, support, comfort,
ideas, tools, tracking, and tips on how you can better manage and deal with your condition</p>
                </div>
                <img            
                class="img-responsive" data-src="holder.js/140x140" alt="140x140"  src="/theme/App/img/connect.png">
                
                
            </div>
            <div class="hover_element col-lg-3 home_tiles home_myhealth" data-html="true"
                   >
                <div class="contents">
                    <h2>Track &amp; Manage Health</h2>
                    <p>My Health provides an amazing set of tools
                        and modules to allow you to learn about your
                        condition, encourages you to upload your personal
                        information, input your health history as well
                        as your family's history, and so much more.</p>
                </div>
                <img 
                    class="img-responsive" data-src="holder.js/140x140" alt="140x140"  src="/theme/App/img/track.png">
                
            </div>
           
            <div class="hover_element col-lg-3 home_tiles home_learn"  data-html="true"
                 >
                <div class="contents">
                    <h2>Learn &amp; Empower</h2>
                    <p>Education, knowledge, wisdom, tips, diet recipes, exercise routines, how to make things better so you can live better - these and so much more are provided when you join <?php echo Configure::read ( 'App.name' ); ?>.</p>
                </div>
                <img 
                    class="img-responsive" data-src="holder.js/140x140" alt="140x140" src="/theme/App/img/learn.png">                
            </div>

        
         <div class="hover_element col-lg-3 home_tiles home_pmr ml_0" data-html="true"
                >
                <div class="contents">
                    <h2>Manage Medical Records</h2>
                    <p>My Patient Medical Records, or My PMR, provides a powerful set of features to upload, edit, store, retrieve, and send your medical records to your doctors and nurses.</p>
                </div>
                <img 
                    class="img-responsive" data-src="holder.js/140x140" alt="140x140"  src="/theme/App/img/manage.png">                
            </div>
    </div>
</div>
<div class=" marketing member_container">
    <div class="row">
        <a href="register" class="btn home_join ">
           <p> Join Us</p>
           <span>It's free</span>
        </a>
    </div>
    <div class="row home_patients">
        <ul id="flexiselDemo1"> 
            <?php foreach ($new_members_list as $new_member) { ?>
                <li>
                    <img src="/theme/App/img/tmp/<?php echo $new_member['User']['profile_picture']; ?>" class="<?php echo $new_member['User']['type']; ?> profile_brdr_5" />
                    <h5><?php echo h($new_member['User']['username']); ?></h5>
                    <p><?php echo h($new_member['Disease']); ?></p>
                    <span>
                        <?php
                        echo h($new_member['State']['description']) . ', '
                        . h($new_member['Country']['short_name']);
                        ?>
                    </span>
                </li>
            <?php } ?>
        </ul>
    </div>
</div> 
<div class="home_map">
    <?php echo $this->element('google_map'); ?>
</div>
<div class="home_video_container row" >
    <div class="col-lg-12" data-video="<?php echo $home_video; ?>" >
        <img class="img-responsive video_icon" src="/theme/App/img/video_icon.png">
        <img class="img-responsive" src="/theme/App/img/video_bg.png">
    </div>
<!--    <div class="col-lg-6 pull-left">
        <img class="img-responsive" data-src="holder.js/140x140" alt="140x140"  src="/theme/App//img/tmp/home_video_img.png" data-video="https://www.youtube.com/watch?v=nXvD_cNRFM4"  data-video_id="<?php echo Configure::read('App.HOME_VIDEO_ID'); ?>" />
    </div>
    <div class="col-lg-6 pull-left">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
             Indicators 
            <div class="carousel-inner">
                <div class="item " data-video="https://vimeo.com/86492665">
                    <img data-src="holder.js/900x500/auto/#555:#5a5a5a/text:Third slide" class="pull-right"  alt="Third slide" src="/theme/App/img/banner_1.png" style="top: 10%; left: 10%; width: 554px; height: 286px; -webkit-filter: blur(2px);">
                    <div class="container">
                        
                    </div>
                </div>
                <div class="item" data-video="https://vimeo.com/86492663">
                    <img data-src="holder.js/900x500/auto/#555:#5a5a5a/text:Third slide" class="pull-right" alt="Third slide" src="/theme/App/img/banner_3.png">
                   <div class="container">
                        
                    </div>
                </div>
                <div class="item active" data-video="https://vimeo.com/86492660">
                    <img data-src="holder.js/900x500/auto/#555:#5a5a5a/text:Third slide" alt="Third slide" class="pull-right" src="/theme/App/img/Banner_2.png">
                     <div class="container">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>
</div>
<script>

    $(window).load(function() {
        $("#flexiselDemo1").flexisel({
            visibleItems: 6,
            enableResponsiveBreakpoints: true,
            animationSpeed: 1000,
            autoPlay: true,
            autoPlaySpeed: 3000,
            pauseOnHover: false,
            responsiveBreakpoints: {
                portrait: {
                    changePoint: 480,
                    visibleItems: 1
                },
                landscape: {
                    changePoint: 640,
                    visibleItems: 2
                },
                tablet: {
                    changePoint: 769,
                    visibleItems: 3
                },
                screen5: {
                    changePoint: 1250,
                    visibleItems: 4
                }
            }
        });
        $(".home_patients").removeClass("home_patients");
    });
</script>


<!-- Connect Modal -->
<div class="modal fade" id="homeModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  
        <?php echo Configure::read ( 'App.name' ); ?> allows Patients, Caregivers, Family members, and Friends to
join together in what we call Caring Communities to provide information, support, comfort,
ideas, tools, tracking, and tips on how you can better manage and deal with your condition <br /> <br />
Once you have signed up and logged in (you only have to sign up once!), <?php echo Configure::read ( 'App.name' ); ?>
allows you to add, delete, and invite people into your on-line life. Lets get started!
     
</div>

<!-- My Health Modal -->
<div class="modal fade" id="homeModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        My Health provides an amazing set of tools and modules to allow you to learn about your condition, encourages you to upload your personal information, input your health history as well as your family's history, and so much more.  It helps you keep track of your medications, your treatments and therapies, your Health Plan.  By taking surveys, you help track how you feel.
      
</div>


<!-- My PMR Modal -->
<div class="modal fade" id="homeModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  
        My Patient Medical Records, or My PMR, provides a
        powerful set of features to upload, edit, store,
        retrieve, and send your medical records to your doctors and nurses.
     
</div>


<!-- Learn About Your Conditions Modal -->
<div class="modal fade" id="homeModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  
        Education, knowledge, wisdom, tips, diet recipes,
        exercise routines, how to make things better so
        you can live better - these and so much more are
        provided when you join <?php echo Configure::read ( 'App.name' ); ?>.
      
</div>