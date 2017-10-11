<div class="role_container">
	<?php foreach ($userTypesData as $userTypeData) : ?>
		<div class="roles <?php echo $userTypeData['roleClass']; ?>" data-user_type="<?php echo $userTypeData['userType']; ?>">
			<div class="media">
				<a class="pull-left">
					<?php echo $this->Html->image($userTypeData['roleImgPath'], array('alt' => $userTypeData['roleName'], 'class' => 'img-circle')); ?>
				</a>
				<div class="media-body">
					<h3><?php echo $userTypeData['roleName']; ?></h3>
					<p><?php echo $userTypeData['roleDescription']; ?></p>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>