<div class="community_members">
    <div class="disease_library_detail">
        <div class="row">
            <?php
            if (is_array($library) && !empty($library)) {
                foreach ($library as $video_key => $video_lib) {
                    if (!empty($video_lib)) {
                        $video_image = $video_lib['image'];
                        $video_src = $video_lib['src'];
                    }
                    ?>
                    <div class="col-lg-3" style="margin: 0px 0px 20px 0px;">
                        <div>
                            <img title="Watch video" class="library_video m_pointer img-responsive"
                                 src="<?php echo $video_image; ?>"
                                 data-src="<?php echo $video_src; ?>"
                                 style="width:200px;height:150px;"/>
                        </div>
                    </div>
                    <?php
                }
                ?> 

            <?php } else { ?>
                <div class="col-lg-12 alert alert-warning">
                    <div class="alert-content">No library files added for the disease</div>
                </div>     

            <?php } ?>




        </div>
        <!--        <div class="library_search">
                    <div class="col-lg-10">
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn_active pull-right">Search</button>
                    </div>
                </div>
                <div class="row library_tiles">
                    <div class="col-lg-4 libaray_treatment">
                        <div class="tile_header">
                            <h3>Treatment</h3>
                        </div>
                    </div>
                    <div class="col-lg-4 libaray_medicine">
                        <div class="tile_header">
                            <h3>Medicine</h3>
                        </div>
                    </div>
                    <div class="col-lg-4 libaray_surgery">
                        <div class="tile_header">
                            <h3>Surgery</h3>
                        </div>
                    </div>
                </div>
                <div class="row library_tiles">
                    <div class="col-lg-4 libaray_surveys">
                        <div class="tile_header">
                            <h3>Surveys</h3>
                        </div>
                    </div>
                    <div class="col-lg-4 libaray_video">        
                    </div>
                    <div class="col-lg-4 libaray_symptom">
                        <div class="tile_header">
                            <h3>Symptoms</h3>
                        </div>
                    </div>
                </div>-->
    </div>
</div>

<div id="videoModalone" class="bootbox modal fadeIn video_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="bootbox-close-button close" style="margin-top: -10px;">×</button>
                <div class="bootbox-body" style="margin-top: 10px;">
                    <div class="text-center"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...', 'style' => 'margin: 10% auto')); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #videoModalone {background-color:rgba(0,0,0,0.6);}
</style>

<script>
    var height = $(window).height();
    $(".bootbox-close-button").on('click', function() {
        $("#videoModalone").hide();
        $('#videoModalone .bootbox-body').html('<div class="text-center"><?php echo $this->Html->image('loader.gif', array('alt' => 'Loading...', 'style' => 'margin: 10% auto')); ?></div>');
    });
    $(document).ready(function() {
        $('#videoModalone').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $('.library_video').on('click', function() {
            $('#videoModalone').fadeIn();
//            $('#videoModalone').show();
            height = $(window).height();
            $('#videoModalone .bootbox-body').css('height', (height - (height / 5)));
            var link = $(this).attr('data-src');
            $.ajax({
                type: 'post',
                url: '/disease/diseases/getEmbedPlayer/',
                data: {'link': link},
                success: function(response) {
                    $("#videoModalone .modal-body .bootbox-body").html(JSON.parse(response));
                }
            });
        });
    });
</script>