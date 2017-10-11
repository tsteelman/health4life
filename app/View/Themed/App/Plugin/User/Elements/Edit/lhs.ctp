<?php
echo $this->element('User.Edit/change_photo_form');
$menuItems = array(
	array(
		'label' => __('Edit Profile'),
		'url' => '/user/edit'
	),
	array(
		'label' => __('Account Settings'),
		'url' => '/user/settings'
	),
	array(
		'label' => __('Privacy Settings'),
		'url' => '/user/privacy'
	),
	array(
		'label' => __('Edit Password'),
		'url' => '/user/password'
	),
	array(
		'label' => __('Invite Contacts'),
		'url' => '/user/invite',
		'disabled' => true
	),
	array(
		'label' => __('Email Settings'),
		'url' => '/user/email_settings'
	),
	array(
		'label' => __('Blocking'),
		'url' => '/user/blocking'
	)
);

if ($loggedin_user_type === User::ROLE_PATIENT || $loggedin_user_type === User::ROLE_CAREGIVER) {
	array_push($menuItems, array(
		'label' => __('Manage Diagnosis'),
		'url' => '/user/manage_diagnosis',
	));
} else {
	array_push($menuItems, array(
		'label' => __('Support Diagnosis'),
		'url' => '/user/manage_diagnosis',
	));
}

?>

<ul class="edit_profile_options">
	<?php
	foreach ($menuItems as $menuItem):
		if (!isset($menuItem['disabled'])) :
			?>
			<li>
				<h4>
					<?php
					$options = array();
					if ($this->request->here === $menuItem['url']) {
						$options['class'] = 'selected';
					}
					echo $this->Html->link($menuItem['label'], $menuItem['url'], $options);
					?>
				</h4>
			</li>
			<?php
		endif;
	endforeach;
	?>
</ul>