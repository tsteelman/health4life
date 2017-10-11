<ul class="edit_profile_options manage_health_data">
  <li>
    <h4>
      <a href="/user/manage_health?record_type=5" class="<?php echo ($record_type == 5)? 'selected' : '';?>" value="status">Health Status</a>
    </h4>
  </li>
  <li>
    <h4>
      <a href="/user/manage_health?record_type=1" value="weight" class="<?php echo ($record_type == 1)? 'selected' : '';?>">Weight</a>
    </h4>
  </li>
  <li><h4><a href="/user/manage_health?record_type=3" value="bp" class="<?php echo ($record_type == 3)? 'selected' : '';?>">Blood Pressure</a></h4></li>
  <li><h4><a href="/user/manage_health?record_type=4" value="temp" class="<?php echo ($record_type == 4)? 'selected' : '';?>">Temperature</a></h4></li>
</ul>
