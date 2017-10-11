<h2 style="font-size: 24px; margin: 30px 0px 25px 0px;  font-weight: normal;"><?php echo __('Activate account'); ?></h2>

<table>
    <tr>
        <td><?php echo __('Hi') . ' ' . $username; ?>,</td>
    </tr>
    <tr>
        <td><?php echo __('Please click '); ?> <a style=" text-decoration: none;" href="<?php echo $link; ?>"><?php echo __('here');?></a><?php echo __(' to active your account.'); ?></td>
    </tr>
    <tr><td>&nbsp;</td></tr>	
</table>								
<p style="margin: 20px 0px 0px 0px;"><?php echo __('If you are not able to click the link above, please copy and paste the link below to your browser.'); ?></p>
<a style=" text-decoration: none;" href="<?php echo $link; ?>"><?php echo $link; ?></a>
<p style="margin: 30px 0px 0px 0px;"><?php echo __('Thanks'); ?></p>
<p style="margin: 1px 0px 0px 0px;"><?php echo __('Patients4Life Team'); ?> </p>