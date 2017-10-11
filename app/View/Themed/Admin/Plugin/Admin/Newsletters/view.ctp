<section class="grid_12">
    <div class="block-border">
        <!--<h1>Email Template: <?php // echo __($emailManagement['EmailTemplate']['template_name']);            ?></h1>-->
        <table class="table sortable no-margin" cellspacing="0" width="100%" style="margin-left: 0.33em; margin-bottom:-0.67em; ">
            <tr>
                <td class="padleft" width="100%" colspan="2">

                    <div id="email_body_value" style="display: none;">

                        <?php
                        if ($preview == false) {
                            echo $newsletter['Newsletter']['content'];
                        }
                        ?>
                    </div>
                    <div id="email_body_container">
                        <?php
                        if ($preview == false) {
                            echo $newsletter['Newsletter']['content'];
                        }
                        ?>
                    </div>                

                </td>
            </tr>
        </table>
    </div>
</section>
<script>

    $(function() {

        if ($("#email_body_container").val() != null) {
            $("#email_body_container").html($("#email_body_value").val());
        }


    });
</script>