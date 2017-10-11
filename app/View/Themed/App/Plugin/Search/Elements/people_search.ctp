<?php
 $this->AssetCompress->script('jquery.autopager', array('block' => 'scriptBottom'));
?>
<input type="hidden" name="search_string" id="search_string" value="<?php if(isset($searchStr)){echo stripslashes(htmlspecialchars($searchStr));; }?>" />
<input type="hidden" name="" id="search_name" value="<?php if(isset($searchStrName)){echo $searchStrName; }?>" />
<input type="hidden" name="" id="search_gender" value="<?php if(isset($searchStrGender)){echo $searchStrGender; }?>" />
<input type="hidden" name="" id="search_age" value="<?php if(isset($searchStrAge)){echo $searchStrAge; }?>" />
<?php if(!empty($searchStrLocation[0])){
for($i=0; $i<count($searchStrLocation[0]);$i++){ ?>
    <input type="hidden" class="locationList" id="<?php echo 'search_location_'.$i; ?>" value="<?php {echo $searchStrLocation[0][$i]; }?>" />
<?php }
}
?>

<?php if(!empty($searchStrDisease[0])){
for($i=0; $i<count($searchStrDisease[0]);$i++){ ?>
    <input type="hidden" class="diseaseList" id="<?php echo 'search_disease_'.$i; ?>" value="<?php {echo $searchStrDisease[0][$i]; }?>" />
<?php }
}
?>

<?php if(!empty($searchStrSymptoms[0])){
for($i=0; $i<count($searchStrSymptoms[0]);$i++){ ?>
    <input type="hidden" class="symptomsList" id="<?php echo 'search_symptoms_'.$i; ?>" value="<?php {echo $searchStrSymptoms[0][$i]; }?>" />
<?php }
}
?>

<?php if(!empty($searchStrTreatment[0])){
for($i=0; $i<count($searchStrTreatment[0]);$i++){ ?>
    <input type="hidden" class="treatmentList" id="<?php echo 'search_treatment_'.$i; ?>" value="<?php {echo $searchStrTreatment[0][$i]; }?>" />
<?php }
}
?>
<div class="group">
    <div class="row notification">
        <div class="col-lg-9">
            <div class="event_list">            
                <div id="searchPageList" class="content">
                    <div class="row">
                    	<?php

                        if(isset($header)) {?>
				    <div class="advance_search">
					<div class="advance_search_header">
					    <?php echo $this->element('error', array('id' => 'advanced_flash_error', 'style' => 'display:none; color: red'));?>
					    <p class="pull-left"><?php echo $header; ?></p>
					    <span id="advance_search" class="pull-right owner advance_search_closed">Advance search</span>
					</div>
					<div id="advanced_form" class="search_form clearfix">
					    <?php
						echo $this->Form->create('AdvancedSearch',array('action' => '/search',
						    'inputDefaults' => array('label' => false, 'div' => false)));
					    ?>
					    <div class="form-group clearfix">
						<div class="col-lg-6">
						    <label>Name</label>
						    <?php echo $this->Form->input('keyword_name', array('class' => 'form-control', 'id' => 'keyword_name')); ?>
						</div> 
						<div id="advanced_location_container" class="col-lg-6 select_plus">
							<div class="form-group facelist-form-group">
								<label>Location</label>
								<ul class="facelist">
									<li class="token-input">
										<?php
										echo $this->Form->input('keyword_location', array(
											'type' => 'text',
											'id' => 'keyword_location'
										));
										echo $this->Form->hidden('search_city_id', array('id' => 'search_city_id'));
										?>
									</li>
								</ul>
								<div class="result_list" style="display:none;"></div>
							</div>
						</div> 
					    </div>
					    <div class="form-group clearfix">
						<div id="advanced_diagnosis_container" class="col-lg-6 select_plus">
							<div class="form-group facelist-form-group">
								<label>Diagnosis</label>
								<ul class="facelist">
									<li class="token-input">
										<?php
										echo $this->Form->input('keyword_disease', array(
											'type' => 'text',
											'id' => 'keyword_disease'
										));
										echo $this->Form->hidden('search_disease_id', array('id' => 'search_disease_id'));
										?>
									</li>
								</ul>
								<div class="result_list" style="display:none;"></div>
							</div>
						</div> 
						<div class="col-lg-6">
						    <label>Age</label>
						    <?php 
						    echo $this->Form->input('keyword_age', array('class' => 'form-control', 'type' => 'select',
							'options' => array('10 - 20', '20 - 30', '30 - 40', '40 - 50', '50 - 60', '60 - 70', '70 - 80', '80 - 90', '90 - 100', 'Above 100'),
							'empty' => 'Any', 'id' => 'keyword_age'));?>
						</div> 
					    </div>
					     <div class="form-group clearfix">
						<div id="advanced_treatment_container" class="col-lg-6 select_plus">
							<div class="form-group facelist-form-group">
									<label>RX Treatment</label>
									<ul class="facelist">
										<li class="token-input">
											<?php
											echo $this->Form->input('keyword_treatment', array(
												'type' => 'text',
												'id' => 'keyword_treatment'
											));
											echo $this->Form->hidden('search_treatment_id', array('id' => 'search_treatment_id'));
											?>
										</li>
									</ul>
									<div class="result_list" style="display:none;"></div>
								</div>
						</div> 
						<div class="col-lg-6">
						    <label>Gender</label>
						    <div class="block">
							<label>
							    <?php echo $this->Form->input('keyword_male', array('type' => 'checkbox', 'value' => 'M', 'id' => 'keyword_male')); ?>
							    Male
							</label>
							<label>
							    <?php echo $this->Form->input('keyword_female', array('type' => 'checkbox', 'value' => 'F', 'id' => 'keyword_female')); ?>
							    Female
							</label>
							<?php echo $this->Form->input('keyword_gender', array('type' => 'hidden', 'id' => 'keyword_gender')); ?>
						    </div>
						</div> 
					    </div>
					     <div class="form-group clearfix">
						<div id="advanced_symptoms_container" class="col-lg-6 select_plus">
						    <div class="form-group facelist-form-group">
								<label>Symptoms</label>
								<ul class="facelist">
									<li class="token-input">
										<?php
										echo $this->Form->input('keyword_symptom', array(
											'type' => 'text',
											'id' => 'keyword_symptom'
										));
										echo $this->Form->hidden('search_symptom_id', array('id' => 'search_symptom_id'));
										?>
									</li>
								</ul>
								<div class="result_list" style="display:none;"></div>
							</div>
						</div> 
					    </div>
					    <div class="form-group clearfix">
						<?php echo $this->Form->button('Find Friends', array('type'=>'button', 'id' => 'keywords_find', 'class' => 'btn btn_active'));?>
						<?php echo $this->Form->button('Clear', array('type'=>'button', 'id' => 'keywords_clear', 'class' => 'btn btn_clear'));?>
					    </div>
					    <?php echo $this->Form->end(); ?>
					</div>
				    </div>
			<div id="searchList" class="event_wraper">
				<?php echo $results; ?>
			</div>
                            <?php
                        } else {
                            echo $results;
                        }
                        ?>
                   	
            </div>
        </div>
	<?php echo $moreButton; ?>
</div>
<div class="recommended_users_list event_list">            
            <div id="recommended_user_loading">
	<span>
		<?php echo $this->Html->image('load_more.gif', array('width' => 24, 'height' => 24)); ?>
		<label>Loading, please wait...</label>
	</span>
</div>  
 </div>

        </div>
<?php echo $this->element('layout/rhs', array('list' => true)); ?>
    </div>

<?php
$this->AssetCompress->script('search', array('block' => 'scriptBottom'));
?>
<script type="text/javascript">
	$("#advanced_form").hide();
	$("#advance_search").hide();
	$(".col-lg-3 .event_list_lhs").removeClass('event_list_lhs');
	$(function() {
		$.extend({
			getUrlVars: function() {
				var vars = [], hash;
				var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
				for (var i = 0; i < hashes.length; i++)
				{
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
				}
				return vars;
			},
			getUrlVar: function(name) {
				return $.getUrlVars()[name];
			}
		});

		if (typeof $.getUrlVar("keyword") !== "undefined" || typeof $.getUrlVar("name") !== "undefined" ||
				typeof $.getUrlVar("gender") !== "undefined" || typeof $.getUrlVar("age") !== "undefined" ||
				typeof $.getUrlVar("disease") !== "undefined" || typeof $.getUrlVar("location") !== "undefined" ||
				typeof $.getUrlVar("symptoms") !== "undefined" || typeof $.getUrlVar("treatment") !== "undefined") {
			$("#advance_search").show();
		}
	});
</script>