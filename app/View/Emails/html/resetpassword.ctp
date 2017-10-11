<h2 style="font-size: 24px; margin: 30px 0px 25px 0px;  font-weight: normal;">Reset Password</h2>

<table>
    <tr>
        <td>Hi <?php echo $email; ?>,</td>
    </tr>
    <tr>
        <td><?php echo __('You recently asked to reset your password in Patients4Life.'); ?></td>
    </tr>    
    <tr>
        <td><?php echo __('To reset your password, please click'); ?> <a style=" text-decoration: none;" href="<?php echo $link; ?>">here</a></td>
    </tr>
    <tr><td>&nbsp;</td></tr>	
</table>								
<p style="margin: 20px 0px 0px 0px;"><?php echo __('If you are not able to click the link above, please copy and paste the link below to your browser.'); ?></p>
<a style=" text-decoration: none;" href="<?php echo $link; ?>"><?php echo $link; ?></a>
<p style="margin: 30px 0px 0px 0px;"><?php echo __('Thanks'); ?></p>
<p style="margin: 1px 0px 0px 0px;"><?php echo __('Patients4Life Team'); ?> </p>