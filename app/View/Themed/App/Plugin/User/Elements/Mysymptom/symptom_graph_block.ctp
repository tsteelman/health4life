<div class="col-lg-4">
	<div class="graph_div">
		<a href="/mysymptom/<?php echo $user_details['username']; ?>/<?php echo urlencode(($symptom['id'])) ?>">
			<h3 title="<?php echo __(h($symptom['name'])); ?>"><?php echo __(h($symptom['name'])); ?> </h3>
		</a>
		<div class="mysymptom_weeklygraph_label"></div>
		<div class="row">

			<div id="<?php echo str_replace(' ', '_', __(h($symptom['name']))); ?>"  class="col-lg-11 symptom_graph" style="height: 165px;"  >
			</div>
			<div id="<?php echo str_replace(' ', '_', __(h($symptom['name']))) . "_label"; ?>" class="col-lg-1 severity_border pull-right"></div>
		</div>
		<a class="view_more pull-right" href="/mysymptom/<?php echo $user_details['username']; ?>/<?php echo urlencode(($symptom['id'])) ?>" class="pull-right">
			<?php echo __('View graphs'); ?> 
		</a>
	</div>
</div>