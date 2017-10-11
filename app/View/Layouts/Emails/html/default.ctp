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
	<body style="width:100%; margin:0; padding:0; -webkit-text-size-adjust:none; -ms-text-size-adjust:none; background-color:#ffffff;">
		<table cellpadding="0" cellspacing="0" border="0" style="height:auto !important; margin:0; padding:0; width:100% !important; background-color:#f0f0f0;font-family:  sans-serif; ">
			<tr>
				<td>
					<div style="width:100% !important; margin:0 auto;">
						<table width="640"  cellpadding="0" cellspacing="0" border="0" style="background-color:#FFFFFF; margin:0 auto;  border:none; width: 100% !important;">
							<tr>
								<td width="100%">
									<table bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0" width="100%">
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
											<td cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff" style="text-align:center;">
												<img alt="header"src="<?php echo Router::url('/', true); ?>theme/App/img/welcome_mail_bnr.png" style="display:inline-block; max-width:100% !important; width:100% !important;" border="0" />
											</td>
										</tr>
									</table>
									<table align="center" width="100%" 
										   style="color: #454a4d; 
										   font-size: 13px;  
										   font-family:  sans-serif; border-right:1px solid #e1e1e1;
										   border-left:1px solid #e1e1e1;">
										<tbody>
											<tr>
												<td style="
													padding-bottom: 20px;padding-left: 20px; padding-right: 20px; word-wrap: break-word;
													">
														<?php echo $this->fetch('content'); ?>
												</td>
											</tr>
										</tbody>
									</table>
									<table bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
											<td width="100%" bgcolor="#2C589E" height="32px"
												style="padding-left: 20px; 
												font-size: 13px; color: #96accf; 
												border-radius: 0px 0px 4px 4px;">
												Copyright © 2013-2014 <?php echo Configure::read('App.name'); ?> All Rights Reserved
												<?php if(isset($unsubscribe) && !empty($unsubscribe)) { ?>
													<span style="padding-right: 20px; float: right"><a href="<?php echo $unsubscribe ; ?>" style="color: #96accf; text-decoration: none;">Unsubscribe</a></span>
												<?php } ?>				
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table> 
	</body>
</html>