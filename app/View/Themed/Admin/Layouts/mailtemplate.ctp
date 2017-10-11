<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @link  verified in http://putsmail.com/
 */
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style type="text/css">
a {
	color: #007fcc;
}
</style>
</head>
<body>	
	<table align="center" width="100%" height="100%" cellpadding="0" cellspacing="0" 
		bgcolor="#f0f0f0" style="font-family:  sans-serif;">
		<tbody>
			<tr><td height="20"></td></tr>
			<tr>
                            <td>
                                    <table align="center" width="640" cellpadding="0" cellspacing="0"
                                            bgcolor="#FFFFFF" style="font-family:  sans-serif;">
                                            <tbody>
                                                    <tr>
                                                            <td cellpadding="0" cellspacing="0" bgcolor="#2C589E"
                                                                    style="border-radius: 4px 4px 0px 0px;">
                                                                            <img alt="Welcome to <?php echo Configure::read('App.name'); ?>"
																			src="<?php echo Router::url('/', true); ?>theme/App/img/<?php echo Configure::read('App.newsletterLogo'); ?>"
                                                                            style="padding-top: 10px; padding-right: 30px; padding-bottom: 10px; 
                                                                                    padding-left: 30px; display: block;" />
                                                            </td>
                                                    </tr>
                                                    <tr>
                                                            <td><img alt="header" style="display: block;"
                                                                    src="<?php echo Router::url('/', true); ?>theme/App/img/welcome_mail_bnr.png">
                                                            </td>
                                                    </tr>
                                                    <tr>
                                                            <td>
                                                                    <table align="center" width="640" 
                                                                            style="padding-left: 30px; padding-right: 30px; color: #454a4d; 
                                                                                    font-size: 13px; padding-bottom: 30px; table-layout: fixed; 
                                                                                    font-family:  sans-serif; border-right:1px solid #e1e1e1;
                                                                                    border-left:1px solid #e1e1e1;">
                                                                            <tbody>
                                                                                    <tr>
                                                                                            <td style="word-wrap: break-word;">
                                                                            <?php echo $this->fetch('content'); ?>
                                                                    </td>
                                                                                    </tr>
                                                                            </tbody>
                                                                    </table>
                                                            </td>
                                                    </tr>

                                                    <tr>
                                                            <td bgcolor="#2C589E" height="32px"
                                                                    style="padding-left: 30px; 
                                                                            font-size: 13px; color: #96accf; 
                                                                            border-radius: 0px 0px 4px 4px;">
                                                                    Copyright © 2013-2014 <?php echo Configure::read('App.name'); ?> All Rights Reserved
                                                            </td>
                                                    </tr>
                                            </tbody>
                                    </table>
                            </td>
			</tr>
			<tr><td height="20"></td></tr>
		</tbody>
	</table>
</body>
</html>

<script type="text/javascript">
    onload=function()
    {
		enableScrolling();
    }
    function enableScrolling()
    {
		document.body.scroll = "yes"; // To enable scrolling in IE admin side
    }
 </script> 