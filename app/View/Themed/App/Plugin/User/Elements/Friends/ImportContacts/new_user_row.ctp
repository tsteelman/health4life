<span>
    <input type="checkbox" value="<?php echo $user['email']; ?>" name="new_contacts[]" />
</span>
<?php if ($user['name'] !== '') : ?>
    <span class="owner"><?php echo $user['name']; ?></span>
    <span class="pull-right"><?php echo $user['email']; ?></span>
<?php else : ?>
    <span class="owner"><?php echo $user['email']; ?></span>
<?php endif; ?>