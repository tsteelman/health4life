<?php foreach ($tasks as $task) : ?>
	<div style="width: 100%;float: left;margin-top: 10px;padding-top: 10px;">
		<div style="width: 100%;clear:both;">
			<div style="width:18%;float:left;"><?php echo __('Title'); ?></div>
                        <div style="width:80%;float:left;">
                            <div style="float:left;">
                                <div style="float:left;">:</div>
                                <div style="margin-left: 10px;">
                                    <?php echo h($task['name']); ?>
                                </div>
                            </div>
                        </div>
		</div>
		<div style="width: 100%;clear:both;">
			<div style="width:18%;float:left;"><?php echo __('Type'); ?></div>
                        <div style="width:80%;float:left;">
                            <div style="float:left;">
                                <div style="float:left;">:</div>
                                <div style="margin-left: 10px;">
                                    <?php echo $task['type']; ?>
                                </div>
                            </div>
                        </div>
		</div>
		<div style="width: 100%;clear:both;">
			<div style="width:18%;float:left;"><?php echo __('Status'); ?></div>
                        <div style="width:80%;float:left;">
                            <div style="float:left;">
                                <div style="float:left;">:</div>
                                <div style="margin-left: 10px;">
                                    <?php echo $task['status']; ?>
                                </div>
                                
                            </div>
                        </div>
		</div>
		<?php if (!empty($task['description'])) : ?>
			<div style="width: 100%;clear:both;">
				<div style="width:18%;float:left;"><?php echo __('Description'); ?></div>
                                <div style="width:80%;float:left;">
                                    <div style="float:left;">
                                        <div style="float:left;">:</div>
                                        <div style="margin-left: 10px;">
                                            <?php echo h($task['description']); ?>
                                        </div>
                                    </div>
                                </div>
			</div>
		<?php endif; ?>
		<div style="width: 100%;clear:both;">
			<div style="width:18%;float:left;"><?php echo __('Assigned to'); ?></div>
                        <div style="width:80%;float:left;">
                            <div style="float:left;">
                                    <div style="float:left;">:</div>
                                    <div style="margin-left: 10px;">
                                        <?php if (isset($task['assignee'])) : ?>
                                                <?php echo h($task['assignee']); ?>
                                        <?php else: ?>
                                                <?php echo __('No one is assigned'); ?>
                                        <?php endif; ?>
                                    </div>
                            </div>
                        </div>
		</div>
	</div>
<?php endforeach; ?>