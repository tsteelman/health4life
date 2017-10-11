<audio id="p4l_audio" preload="auto" style="display: none;">
    <source src="/audio/KDE-Sys-Log-In-Short.mp3" type='audio/mpeg; codecs="mp3"'>
    <source src="/audio/KDE-Sys-Log-In-Short.ogg" type='audio/ogg; codecs="vorbis"'>
    <object id="p4l_audio_ie8" classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" style="display: none;">
        <param name="URL" value="audio/KDE-Sys-Log-In-Short.mp3" />
        <param name="autoStart" value="false" />
        <param name="volume" value="100" />
        <param name="loop" value="false" />
        <param name="playCount" value="1" />
    </object>
</audio>
<?php // if (!$this->Session->check('visited')) { ?>
<?php if (!isset($_SESSION['visited'])) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            var p4l_audio = document.getElementById("p4l_audio");
            var p4l_audio_ie8 = document.getElementById("p4l_audio_ie8");
            if (Modernizr.audio) {
                p4l_audio.play();
            } else {
                p4l_audio_ie8.controls.play();
            }
        });
    </script>

    <?php
//    $this->Session->write('visited', true)
    $_SESSION['visited'] = true;
}
?>