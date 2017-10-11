<?php

/**
 * MangageHealth Controller class file.
 *
 * @author    Amith Hariharan <amit@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
class ManageHealthController extends UserAppController {

    public $uses = array('HealthReading', 'NotificationSetting', 'User', 'Post');
    public $components = array('HealthRecordsReading');

    public function index() {
        $userId = $this->Auth->user('id');
        $record_year = strftime("%Y", time());
        $isAjax = ($this->request->is('ajax')) ? true : false;
        $units = $this->NotificationSetting->getUnitSettings($userId);
        $health_options = array(1 => 'Very Bad', 2 => 'Bad', 3 => 'Neutral',
            4 => 'Good', 5 => 'Very Good');
        $userTimezone = $this->Auth->user('timezone');
        $todayInUserTimeZone= CakeTime::convert(time(), new DateTimeZone($userTimezone));

        $record_type = $this->request->query('record_type');
        if ($this->request->isPost()) {
            $record_type = $record_type = $this->request->data['record_type'];
        }
        $record_type = (!empty($record_type)) ? $record_type : 5;

        $unit = "";
        if ($record_type == HealthReading::RECORD_TYPE_WEIGHT) {
            $unit = ($units['weight_unit'] == 2)? 'Kg' : 'lbs';
        }
        elseif ($record_type == HealthReading::RECORD_TYPE_TEMPERATURE) {
            $unit = ($units['temp_unit'] == 1)? '°C' : '°F';
        }

        switch($record_type) {
            case 1:
                $table_title = "Weight";
                break;
            case 3:
                $table_title = "Blood Pressure";
                break;
            case 5:
                $table_title = "Health Status";
                break;
            case 4:
                $table_title = "Temperature";
                break;
            default :
                $table_title = "Health Status";
                break;
        }


        $records = $this->HealthRecordsReading->getHealthReadingForYear($record_type, $userId, $record_year);
        $readings = array();
        if (!empty($records)) {
            $readings = json_decode($records['HealthReading']['record_value'], true);
            ksort($readings);
            $readings = array_reverse($readings, TRUE);

            if ($this->request->isPost()) {
                $key_to_save = $this->request->data('key');
                $key_to_save = explode('[', $key_to_save);
                $key = rtrim($key_to_save[1], ']');

                if (isset($readings[$key])) {
                    if ($record_type != 5) {
                        if ($record_type == HealthReading::RECORD_TYPE_WEIGHT && $units['weight_unit'] == 2) {
                            $value = round($this->HealthReading->convertKilogramsToPounds($this->request->data('value1'), 2));
                        }
                        else if ($record_type == HealthReading::RECORD_TYPE_TEMPERATURE && $units['temp_unit'] == 1) {
                            $value = round(($this->request->data('value1') * 9 / 5 )+ 32, 2);
                        }
                        else if ($record_type == HealthReading::RECORD_TYPE_BP) {
                            $value = $this->request->data('value1').'/'.$this->request->data('value2');
                        }
                        else {
                            $value = $this->request->data('value1');

                        }
                        $readings[$key] = $value;
                    }
                    else {
                        $postId = $readings[$key]['post_id'];
                        $health_post = $this->Post->findById($postId);
                        if (!empty($health_post)) {
                            $status_content = json_decode($health_post['Post']['content'], true);
                            $status_content['health_status'] = $this->request->data('value1');
                            $health_post['Post']['content'] = json_encode($status_content);
                            //$this->Post->save($health_post);
                        }
                        $readings[$key]['status'] =  $this->request->data('value1');
                    }
                    $new_readings= json_encode($readings);
                    $records['HealthReading']['record_value'] = $new_readings;
                    $this->HealthReading->save($records);
                }
            }

            /*Convert to user units */
            foreach ($readings as $key => $record) {
                if ($record_type != 5) {
                    if ($record_type == HealthReading::RECORD_TYPE_WEIGHT && $units['weight_unit'] == 2) {
                        $value = round($this->HealthReading->convertPoundsToKilograms($record, 2),2);
                    }
                    else if ($record_type == HealthReading::RECORD_TYPE_TEMPERATURE && $units['temp_unit'] == 1) {
                        $value = round(($record - 32) * 5 / 9, 2);
                    }
                    else {
                        $value = $record;
                    }
                    $readings[$key] = $value;
                }
            }
        }

        $this->set(compact('readings', 'record_type', 'health_options', 'unit', 'table_title', 'userTimezone'));
        if ($isAjax) {
            $View = new View($this, false);
            echo $View->element('health_data_table');
            die();
        }
    }

    /**
     * Function deletes a health reading for time
     */
    public function delete() {
        $userId = $this->Auth->user('id');
        $record_year = strftime("%Y", time());
        $key_to_delete = $this->request->data('key');
        $key_to_delete = explode('[', $key_to_delete);
        $key = rtrim($key_to_delete[1], ']');
        $record_type = $this->request->data('record_type');
        $record_type = (!empty($record_type)) ? $record_type : 5;
        $records = $this->HealthRecordsReading->getHealthReadingForYear($record_type, $userId, $record_year);
        $readings = json_decode($records['HealthReading']['record_value'], true);
        if (isset($readings[$key])) {
            unset($readings[$key]);
        }
        if (!empty($readings)) {
            ksort($readings);
        }
        
        /** check_whether this is the latest entry if update latest entry field **/
        $latest = json_decode($records['HealthReading']['latest_record_value'], true);
        if (isset($latest[$key])) {
            unset($latest[$key]);
            /*update latest reading with last entered value */
            if (!empty($readings)) {
                end($readings);
                $latest[key($readings)] = array_pop($readings);
            }
            $records['HealthReading']['latest_record_value'] = json_encode($latest);
        }
        
        $readings = json_encode($readings);
        $records['HealthReading']['record_value'] = $readings;
        $this->HealthReading->save($records);
        die(json_encode(array('status', 'success')));
    }
}
