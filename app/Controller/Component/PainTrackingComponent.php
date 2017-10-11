<?php

/**
 * PostingComponent class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('HealthReading', 'Model');
App::uses('PainTracker', 'Model');
App::uses('Common', 'Utility');
App::uses('Date', 'Utility');
App::import('Controller', 'Api');
App::uses('UserPrivacySettings', 'Lib');

/**
 * PainTrackingComponent for handling posting.
 *
 * This class is used to handle PainTracking and related functionalities.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Controller.Component
 * @category	Component
 */
class PainTrackingComponent extends Component {

    /**
     * Constructor
     *
     * Initialises the models
     */
    public function __construct() {
        $this->User = ClassRegistry::init('User');
        $this->HealthReading = ClassRegistry::init('HealthReading');
        $this->PainTracker = ClassRegistry::init('PainTracker');
        $this->NotificationSetting = ClassRegistry::init('NotificationSetting');
    }

    /**
     * Initialises the component
     *
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
        $this->controller = $controller;
//        $this->clientIp = $controller->request->clientIp();
//        $user = $controller->Auth->user();
//        $this->user = $user;
//        $this->currentUserId = $user['id'];
    }

    public function addPainTracking($save_pain_array = NULL, 
            $last_pain_array = NULL, $loggedInUserId, $timestamp = NULL) {
        
        $painTypes = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7);

        $lastPainTypeArray =  array();
        
        $result['success'] = true;

        if ($timestamp == NULL) {
            $timestamp = time();
        }
//to use when time is added via js
//        else {
//            $dateSql = Date::JSDateToMySQL($dateJs);
//            $timezone = $this->User->getTimezone($loggedInUserId);
//            $currentTimeStamp = CakeTime::convert(time(), new DateTimeZone($timezone));
//            $currentTime = date('G:i:s', $currentTimeStamp);
//            if (isset($timeJs) && $timeJs != NULL) {
//                $timeSql = Date::JSTimeToMySQL($timeJs);
//                $newDateSql = $dateSql . ' ' . $timeSql;
//            } else {
//                $newDateSql = $dateSql . ' ' . $currentTime;
//            }
//
//
////            $dateSqlTimezone = CakeTime::toServer($dateSql, $timezone);
//            $dateSqlTimezone = CakeTime::toServer($newDateSql, $timezone);
//            $date = strtotime($dateSqlTimezone); //timestamp
//        }
        /* use below code if want to change save flow */
        if (isset($save_pain_array) && $save_pain_array != NULL) {
            /*          $type_seperated = array();
              foreach ($save_pain_array as $pain_array) {
              $type_seperated[$pain_array['pain_type']][] = array(
              'severity' => $pain_array['severity'],
              'selected_body_main_part' => $pain_array['selected_body_main_part'],
              'pos_x' => $pain_array['pos_x'],
              'pos_y' => $pain_array['pos_y']
              );
              }
              } */ 
            
            /*
             * If no modification is done to data
             */
            if ( $save_pain_array != 'empty'){
                foreach ($save_pain_array as $pain_array) {
                    if (isset($pain_array) && !empty($pain_array) && $pain_array != NULL) {
                        if ( intval($pain_array['severity']) != 0) {
                            $result = $this->PainTracker->addPainTrackingValues($loggedInUserId, 
                                    $pain_array['pain_type'], $pain_array['severity'], $pain_array['pos_x'], 
                                    $pain_array['pos_y'], $pain_array['selected_body_main_part'], $timestamp);
                        } else {
                            $result = $this->PainTracker->addPainTrackingValues($loggedInUserId, 
                                    $pain_array['pain_type'], null, null, null, $pain_array['selected_body_main_part'], $timestamp);
                            
                            /*
                             * Updating last values
                             */
                            $latestValue ['pos_x'] = null;
                            $latestValue ['pos_y'] = null;
                            $latestValue ['severity'] = null;
                            $latestValue ['bodyPartMain'] = $pain_array ['selected_body_main_part']; 
                            
                            $lastPainTypeArray [ $pain_array['pain_type'] ][] = $latestValue;
                        }
                        unset( $painTypes [ $pain_array['pain_type'] ]);                        
                    }
                }
            }   
            
//            foreach ( $painTypes as $painType ){
//                $latestPainData = $this->PainTracker->getLatestPainRecords($loggedInUserId, $painType);
//                if (isset($latestPainData ['PainTracker'] ['latest_value']) &&
//                        $latestPainData ['PainTracker'] ['latest_value'] != NULL) {
//
//                    $latestPainArray = json_decode($latestPainData['PainTracker']['latest_value'], TRUE);
//                    $latestRecordKey = max(array_keys($latestPainArray));
//
//                    if ( $latestRecordKey != $timestamp ) {
//                        foreach ( $latestPainArray [ $latestRecordKey ] as $painData) {
//                            if ( is_null ( $painData ['severity'] ) ){
//                                break;
//                            }
//                            $result = $this->PainTracker->addPainTrackingValues($loggedInUserId, 
//                                $painType, null, null, null, $painData [ 'bodyPartMain' ], $timestamp);
//                        }
//                    }
//                }
//            }            
            
            if ( $last_pain_array != 'empty') {
                foreach ($last_pain_array as $pain_array) {
                    if (isset($pain_array) && !empty($pain_array) && $pain_array != NULL) {
                        $latestValue ['pos_x'] = $pain_array ['pos_x'];
                        $latestValue ['pos_y'] = $pain_array ['pos_y'];
                        $latestValue ['severity'] = $pain_array ['severity'];
                        $latestValue ['bodyPartMain'] = $pain_array ['selected_body_main_part'];                   

                            $lastPainTypeArray [ $pain_array['pain_type'] ][] = $latestValue;
    //                        $result = $this->PainTracker->addPainTrackingValues($loggedInUserId, 
    //                                $pain_array['pain_type'], $pain_array['severity'], $pain_array['pos_x'], 
    //                                $pain_array['pos_y'], $pain_array['selected_body_main_part'], $timestamp);

    //                    unset( $painTypes [ $pain_array['pain_type'] ]);                        
                    }
                }
            }
//                debug($latestPainTypeArray);
            //$this->PainTracker->updateLatestValues($loggedInUserId);
            $this->PainTracker->updateLatest($loggedInUserId, $lastPainTypeArray, $timestamp);
        } 
        return $result;
    }

}
