<section class="grid_12">
    <div class="block-border">
        <!--<h1>Email Template: <?php // echo __($emailManagement['EmailTemplate']['template_name']);             ?></h1>-->
        <table class="table sortable no-margin" cellspacing="0" width="100%" style="margin-left: 0.33em; margin-bottom:-0.67em; ">
            <tr>
                <td class="padleft" width="100%" colspan="2">

                    <div id="email_body_value" style="display: none;">

                        <?php
                        if ($preview == false) {
                            echo $emailManagement['EmailTemplate']['template_body'];
                        }
                        ?>
                    </div>
                    <div id="email_body_container">
                        <?php
                        if ($preview == false) {
                            echo $emailManagement['EmailTemplate']['template_body'];
                        }
                        ?>
                    </div>                

                </td>
            </tr>
        </table>
    </div>
</section>
<script>
//    var links_length = document.getElementsByTagName('a').length;
//    for (var i = 0; i < links_length; i++) {
//        document.getElementsByTagName('a')[i].disabled = true;
//        document.getElementsByTagName('a')[i].removeAttribute('href');
//        document.getElementsByTagName('a')[i].style.textDecoration = 'none';
//        document.getElementsByTagName('a')[i].style.cursor = 'default';
//    }
    $(function() {

        if ($("#email_body_container").val() != null) {
            $("#email_body_container").html($("#email_body_value").val());
        }


    });
</script>