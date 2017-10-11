<?php

/**
 * PostingComponent class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('HealthReading', 'Model');
App::uses('Common', 'Utility');
App::uses('Date', 'Utility');
App::import('Controller', 'Api');
App::uses('UserPrivacySettings', 'Lib');

/**
 * PostingComponent for handling posting.
 *
 * This class is used to handle posting and related functionalities.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Controller.Component
 * @category	Component
 */
class HealthReadingComponent extends Component {

    /**
     * Constructor
     *
     * Initialises the models
     */
    public function __construct() {
        $this->User = ClassRegistry::init('User');
        $this->HealthReading = ClassRegistry::init('HealthReading');
    }

    /**
     * Initialises the component
     *
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
//        print_r($controller);
//        exit;
        $this->controller = $controller;
        $this->clientIp = $controller->request->clientIp();
//        $user = $controller->Auth->user();
//        $this->user = $user;
//        $this->currentUserId = $user['id'];
    }

    public function addHealthRecord($loggedInUserId, $recordType, $dateJs, $value1, $value2) {

        $dateSql = Date::JSDateToMySQL($dateJs);
        $timezone = $this->User->getTimezone($loggedInUserId);
        $dateSqlTimezone = CakeTime::toServer($dateSql, $timezone);
        $date = strtotime($dateSqlTimezone);
        $result = $this->HealthReading->addNewRecord($loggedInUserId, $recordType, $date, $value1, $value2);
        return $result;
    }

    public function getHealthReadingsWithUnit($type = null, $unit = null, $value1, $value2 = NULL) {
        switch ($type) {
            case 1:
                if ($unit == 1) {
                    $result = $value1 . ' lbs';
                } else {
                    $result = $value1 . ' Kg';
                }
                break;
            case 2:
                if ($unit == 1) {
                    $result = $value1 . "'" . $value2 . '"';
                } else {
                    $result = $value1 . ' cm';
                }
                break;
            case 3:
                if ($unit == 1) {
                    $result = $value1 . '⁰C';
                } else {
                    $result = $value1 . '⁰F';
                }
                break;
            case 4:
                $result = $value1 . '/' . $value2;
        }
        return $result;
    }

}

