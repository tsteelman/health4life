<form id="manage_readings_form">
  <input type="hidden" name='record_type' value="<?php echo $record_type?>" />
  <div class="table-header">
      <h2><?php echo __('Manage your '); ?><?php echo $table_title; ?></h2>
  </div>
  <div class="clear_date"><button class="pull-right btn btn_clear" id="clear_button" >clear</button></div>
  <?php if ($record_type != 3): ?>
  <table id="readings_table" class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th width='30%'><?php echo __('Date'); ?></th>
        <th width='30%'><?php echo $table_title.' '; echo (!empty($unit))? "($unit)" : "" ?></th>
        <th width='15%'></th>
        <th width='15%'></th>
        <th width='10%'></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($readings as $keys => $reading): ?>
      <tr>
        <td>
          <?php echo date("m/d/Y h:i a", CakeTime::convert($keys, new DateTimeZone($userTimezone))) ;?>
        </td>
        <td>
          <div class="show_readings">
            <?php if($record_type == 5): ?>
            <?php echo $health_options[$reading['status']];?>
            <?php else: ?>
            <?php echo (is_array($reading))? $reading['status'] : $reading;?>
            <?php endif; ?>
          </div>
          <div class='show_edit_form hide'>
            <?php if($record_type == 5): ?>
            <select name="reading[<?php echo $keys;?>]">
              <?php foreach($health_options as $key => $option): ?>
              <option value="<?php echo $key;?>" <?php echo ($key == $reading['status'])? "selected='selected'": ""; ?>><?php echo $option; ?></option>
              <?php endforeach; ?>
            </select>
            <?php else: ?>
            <input type="text" name="reading[<?php echo $keys; ?>]" value="<?php echo (is_array($reading))? $reading['status'] : $reading;?>" />
            <div class="help-block hide" style="color:#D16E6C;font-size: 12px;"></div
            <?php endif; ?>
          </div>
        </td>
        <td>
          <a href="" class="edit_reading"><?php echo __('Edit'); ?></a>
          <a href="" class="cancel_reading hide"><?php echo __('Cancel'); ?></a>
        </td>
        <td>
          <a href="" class="save_form"><?php echo __('Save'); ?></a>
        </td>
        <td>
          <a href="" class="delete_form"><?php echo __('Delete'); ?></a>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php else: ?>
  <table id="readings_table" class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th width='18%'><?php echo __('Date'); ?></th>
        <th width='25%'><?php echo __('Systolic'); ?></th>
        <th width='25%'><?php echo __('Diastolic'); ?></th>
        <th width='8%'></th>
        <th width='6%'></th>
        <th width='6%'></th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 0; ?>
      <?php foreach ($readings as $keys => $reading): ?>
      <tr>
        <td>
          <?php echo date("m/d/Y h:i a", CakeTime::convert($keys, new DateTimeZone($userTimezone))) ;?>
        </td>
        <td>
          <div class="show_readings">
            <?php $mm_hh = explode('/', $reading); echo $mm_hh[0];?>
          </div>
          <div class='show_edit_form hide'>
            <input type="text" name="reading[<?php echo $keys; ?>][]" id="systolic-<?php echo $i++; ?>" value="<?php echo $mm_hh[0];?>" />
            <div class="help-block hide" style="color:#D16E6C;font-size: 12px;"></div
          </div>
        </td>
        <td>
          <div class="show_readings">
            <?php echo $mm_hh[1];?>
          </div>
          <div class='show_edit_form hide'>
            <input type="text" name="reading[<?php echo $keys; ?>][]" id="dystolic-<?php echo $i; ?>" value="<?php echo $mm_hh[1];?>" />
            <div class="help-block hide" style="color:#D16E6C;font-size: 12px;"></div
          </div>
        </td>
        <td>
          <a href="" class="edit_reading"><?php echo __('Edit'); ?></a>
          <a href="" class="cancel_reading hide"><?php echo __('Cancel'); ?></a>
        </td>
        <td>
          <a href="" class="save_form"><?php echo __('Save'); ?></a>
        </td>
        <td>
          <a href="" class="delete_form"><?php echo __('Delete'); ?></a>  
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif; ?>
</form>

<script type="text/javascript">
  $(document).ready(function() {
    $('#readings_table').DataTable({
      "bFilter": true,
      "bInfo": false,
      "bSort": false,
      "fnDrawCallback": function() {
        bindEditButtons();
      }
    });
    
  });
  
/**
 * binds every buttons in the table
 */
function bindEditButtons() {
  $("#readings_table_filter :input").datepicker({
    'onSelect': function() {
      $('#readings_table_filter :input').keyup().blur();
    }
  }).attr('readonly', 'readonly');
  
  $('.edit_reading').click(function() {
    $(this).parents('tr').find('.show_edit_form').removeClass('hide');
    $(this).parents('tr').find('.show_readings').addClass('hide');
    $(this).parent().find('.cancel_reading').removeClass('hide');
    $(this).addClass('hide');
    return false; 
  });

  $('.cancel_reading').click(function() {
    var option  = ['Very Bad', 'Bad', 'Neutral', 'Good', 'Very Good'];
    $(this).parents('tr').find('.show_edit_form, .help-block').addClass('hide');
    $(this).parents('tr').find('.show_readings').removeClass('hide');
    $(this).parent().find('.edit_reading').removeClass('hide');
    $(this).addClass('hide');
    var previous_value = ($(this).parents('tr').find('.show_readings').text()).trim();
    var input = $(this).parents('tr').find('.show_edit_form input');
    var select = $(this).parents('tr').find('.show_edit_form select');
    if (select.length > 0) {
      for (i in option) {
        if (option[i] == previous_value) {
          $(select, 'option:selected').val(i*1+1);
          break;
        }
      }
    }
    if(input.length > 0 && input.length == 1) {
      $(input).val(($(this).parents('tr').find('.show_readings').text()).trim());
    }
    else if (input.length == 2) {
      $(input).eq(0).val(($(this).parents('tr').find('.show_readings').eq(0).text()).trim());
      $(input).eq(1).val(($(this).parents('tr').find('.show_readings').eq(1).text()).trim());
    }

    return false;
  });

  $('.save_form').click(function() {
    var input = $(this).parents('tr').find('.show_edit_form input');
    var flag = 0;
    var value1 = ($(input).eq(0).val())? $(input).eq(0).val() : ""; 
    var value2 = ($(input).eq(1).val())? $(input).eq(1).val() : ""; 
    $(input).each(function(key, element) {
      if (!validate_input_data(element, key)) {
        flag = 1
      }
    });
    if (flag == 0) {
      if ($(input).length == 0) {
        input = $(this).parents('tr').find('.show_edit_form select');
        $(input).each(function() {
          $(this).parent().siblings().html($('option:selected', this).text());
          value1 = $('option:selected', this).val();
        });
      }
      else {
        $(input).each(function() {
          $(this).parent().siblings().html($(this).val());
        });
      }
      
      var key = $(input).eq(0).attr('name');

      $.ajax({
        async: true,
        data: {'key' : key, 'record_type' : <?php echo $record_type; ?>, 'value1': value1, 'value2': value2 },
        type: 'post',
        success: function(data) {
          $('#health_data_table').html(data);
        },
        url: '/user/manageHealth'
      });
      $(this).parents('tr').find('.cancel_reading').click();
    }
    
    return false;
  });

  $('.delete_form').click(function() {
    var input = $(this).parents('tr').find('.show_edit_form input');
    if ($(input).length == 0) {
      var input = $(this).parents('tr').find('.show_edit_form select');
    }
    var key = $(input).eq(0).attr('name');

    var obj_to_remove  = $(this).parents('tr');
    $('#delete_confirmed').unbind('click').click(function() {
      $(obj_to_remove).remove();
      $.ajax({
        async: true,
        data: {'key' : key, 'record_type' : <?php echo $record_type; ?> },
        type: 'post',
        success: function(data) {
          $('#health_data_table').html(data);
        },
        url: '/user/manageHealth/delete'
      });
    });
    show_delete_confirmation();
    return false;
  });

  $('.show_edit_form input').blur(function() {
    validate_input_data(this);
  });
  
  $('#clear_button').click(function() {
    $("#readings_table_filter :input").val('');
    $('#readings_table_filter :input').keyup().blur();
    return false;
  });
  
  if ($('#readings_table_next').hasClass('paginate_disabled_next')) {
    $('#readings_table_next').css('pointer-events', 'none');
  }
  else {
    $('#readings_table_next').css('pointer-events', '');
  }

  if ($('#readings_table_previous').hasClass('paginate_disabled_previous')) {
    $('#readings_table_previous').css('pointer-events', 'none');
  }
  else {
    $('#readings_table_previous').css('pointer-events', '');
  }
}

/**
 * Function to show delete confirmation
 */
function show_delete_confirmation() {
    $('#delete_confirmation').click();
}
  
/**
 * function to valdate user input
 */
function validate_input_data(obj, key) {
  var title  = "<?php echo strtolower($table_title); ?>";
  if (isNaN($(obj).val())) {
      $(obj, '.help-block').siblings().removeClass('hide').html('Please enter a valid '+title);
      return false;
   }
   else if (validate_form_data(obj, key)) {
     $(obj, '.help-block').siblings().addClass('hide');
     return true;
   } 
   else  {
     $(obj, '.help-block').siblings().removeClass('hide').html(error);
     return false;
   } 
}

var MIN_COMMON = 0;
var MAX_WEIGHT_POUNDS = 1000;
var MAX_WEIGHT_KG = 454;//500
var MAX_HEIGHT_CM = 336;//1000
var MAX_HEIGHT_FEET = 10;
var MAX_HEIGHT_INCH = 12;
var MAX_TEMPERATURE_F = 300;
var MAX_TEMPERATURE_C = 149;
var MAX_BP_DIASTOLIC = 200;
var MAX_BP_SYSTOLIC = 200;
var error = '';

/**
 * Comment
 */
function validate_form_data(input, key) {
  error = "";
  value = $(input).val()*1;
  if (value <= 0 || value == "") {
      error = 'Please enter a value greater than 0';
      return false;
  }
  <?php if ($record_type == 1): ?>
    unit = "<?php echo $unit;?>";
    if (unit == 'Kg' && value > MAX_WEIGHT_KG) {
      error = 'Please enter a value less than ' + MAX_WEIGHT_KG + unit;
    }
    else if (unit == 'lbs' && value > MAX_WEIGHT_POUNDS) {
      error = 'Please enter a value less than ' + MAX_WEIGHT_POUNDS + unit;
    }
  <?php elseif ($record_type == 3): ?>
    var id = $(input).attr('id').split('-');
    if (id[0] == 'systolic' && value > MAX_BP_SYSTOLIC) {
      error = 'Please enter a systolic value less than ' + MAX_BP_SYSTOLIC;
    }
    else if (value > MAX_BP_DIASTOLIC && id[0] == 'dystolic') {
      error= 'Please enter a diastolic value less than ' + MAX_BP_DIASTOLIC;
    }
  <?php elseif ($record_type == 4): ?>
    unit = "<?php echo $unit;?>"
    if (unit == '°C' && value > MAX_TEMPERATURE_C) {
      error = 'Please enter a value less than ' + MAX_TEMPERATURE_C;
    }
    else if (unit == '°F' && value > MAX_TEMPERATURE_F) {
      error = 'Please enter a value less than ' + MAX_TEMPERATURE_F;
    }
  <?php endif; ?>
  return (error)? false : true;
}
</script>


<a id="delete_confirmation" style="display:none;" data-toggle="modal" data-target="#delete_success"  href="#"><?php echo __('Change'); ?></a>
<!-- Modal -->
<div class="modal email_invitation" id="delete_success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog password_verify">
        <div class="modal-content">
        	<div class="modal-header">
		        
		        <h4 class="modal-title"><?php echo __('Manage Health Readings'); ?></h4>
		    </div>
            <div class="modal-body">
                <div class="row">
                  <p id="message_box"><?php echo __('Are you sure you want to delete this entry ?' ); ?></p>
                	<div class="form-group"> 
                  </div>                        
                </div>
            </div>
            <div class="modal-footer">
              <div id="confirm-delete">
                <input type="hidden" id="selected-diagnosis" value=""/>
                <button id="delete_confirmed" type="button" class="btn btn_add" data-dismiss="modal">Yes</button>
                <button id="no_delete" type="button" class="btn btn_add" data-dismiss="modal">No</button>
              </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
