<?php echo $this->element('menu/top_menu'); ?>

<div class="main-container container-fluid">

    <?php echo $this->element('menu/lhs_menu'); ?>

    <div class="main-content">
        <div class="container">
            <div class="breadcrumbs">
                <?php
                echo $this->Html->getCrumbList(array('lastClass' => 'active', 'class' => 'breadcrumb pull-left'));
                ?>
            </div>
        </div>
        <?php // echo $this->element('breadcrumb'); ?>

        <?php echo $content_for_layout; ?>

        <div class="ace-settings-container" id="ace-settings-container">
            <div class="btn btn-app btn-mini btn-warning ace-settings-btn" id="ace-settings-btn">
                <i class="icon-cog bigger-150"></i>
            </div>

            <div class="ace-settings-box" id="ace-settings-box">
                <div>
                    <div class="pull-left">
                        <select id="skin-colorpicker" class="hide">
                            <option data-class="default" value="#438EB9" />
                            <option data-class="skin-1" value="#222A2D" />
                            <option data-class="skin-2" value="#C6487E" />
                            <option data-class="skin-3" value="#D0D0D0" />
                        </select>
                    </div>
                    <span>&nbsp; Choose Skin</span>
                </div>

                <div>
                    <input type="checkbox" class="ace-checkbox-2" id="ace-settings-header" />
                    <label class="lbl" for="ace-settings-header"> Fixed Header</label>
                </div>

                <div>
                    <input type="checkbox" class="ace-checkbox-2" id="ace-settings-sidebar" />
                    <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                </div>

                <div>
                    <input type="checkbox" class="ace-checkbox-2" id="ace-settings-breadcrumbs" />
                    <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                </div>

                <div>
                    <input type="checkbox" class="ace-checkbox-2" id="ace-settings-rtl" />
                    <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                </div>
            </div>
        </div><!--/#ace-settings-container-->
    </div><!--/.main-content-->
    <!--audio playing after user login -->
<!--    <section>
        <audio id="bg_audio" preload="auto">
            <source src="../../assets/audios/financing_higher_education/conclusion/sounds_mp3/cn_section1/cn_section1_bg_audio.mp3" type='audio/mpeg; codecs="mp3"'>
            <source src="../../assets/audios/financing_higher_education/conclusion/sounds_ogg/cn_section1/cn_section1_bg_audio.ogg" type='audio/ogg; codecs="vorbis"'>
        </audio>
    </section>-->
    <!--/.audio playing after user login -->
</div><!--/.main-container-->

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
</a>
<script>
    $(document).ready(function() {
        $('.breadcrumbs ul.breadcrumb li:first').prepend('<i class="icon-home home-icon"></i>');
        $('.breadcrumbs ul.breadcrumb li').append('<span class="divider">' +
				'<i class="icon-angle-right arrow-icon"></i>' +
			'</span>');
        $('.breadcrumbs ul.breadcrumb li:last span').remove();
    });
</script>