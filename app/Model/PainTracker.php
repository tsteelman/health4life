<?php

App::uses('AppModel', 'Model');
App::uses('Date', 'Utility');
App::import('Model', 'NotificationSetting');

/**
 * HealthReading Model
 *
 */
class PainTracker extends AppModel {

    const RECORD_TYPE_NUMBNESS = 1;
    const RECORD_TYPE_PINS_AND_NEEDLES = 2;
    const RECORD_TYPE_BURNING = 3;
    const RECORD_TYPE_STABBING = 4;
    const RECORD_TYPE_THROBBING = 5;


    //Parent array of body parts.

    private $bodyParts = array(
        1 => 'Head Area',
        2 => 'Chest Area',
        3 => 'Abdomen Area',
        4 => 'Pelvic Area',
        5 => 'Back Area',
        6 => 'Arm',
        7 => 'Legs'
    );
    private $painTypes = array(
        1 => 'Numbness',
        2 => 'Pins and Needles',
        3 => 'Burning',
        4 => 'Stabbing',
        5 => 'Throbbing'
    );
    private $bodySubParts = array(
        1 => array(
            1 => 'scalp',
            2 => 'eyes',
            3 => 'ear',
            4 => 'Nose',
            5 => 'Mouth',
            6 => 'Face',
            7 => 'Jaw',
            8 => 'Neck',
        ),
        2 => array(
            9 => 'chest',
            10 => 'Lateral Chest',
            11 => 'Sternum'
        ),
        3 => array(
            12 => 'Upper Abdomen',
            13 => 'Lower Abdomen'
        ),
        4 => array(
            14 => 'Pelvis',
            15 => 'Hip'
        ),
        5 => array(
            16 => 'Shoulder',
            17 => 'Upper Arm',
            18 => 'Elbow',
            19 => 'Forearm',
            20 => 'palm',
            21 => 'Finers',
        ),
        6 => array(
            22 => 'Thigh',
            23 => 'Knee',
            24 => 'Shin',
            25 => 'Foot',
            16 => 'Ankle',
            27 => 'Toes',
        ),
        7 => array(
            28 => 'Back',
            29 => 'Upper Spine',
            30 => 'Lower Spine'
        ),
        8 => array(
            31 => 'Buttock'
        )
    );

    public function getBodyPartsList() {
        return $this->bodyParts;
    }

    public function getPainTypes() {
        return $this->painTypes;
    }

    public function getBodySubParts() {
        return $this->bodySubParts;
    }

    function addPainTrackingValues($loggedInUserId, $painType, $severity, $pos_x, $pos_y, $bodyPartMain, $time) {

        $userId = $loggedInUserId;
        if (isset($time) && $time != NULL) {
            $yearFromDate = getdate($time);
            $year = $yearFromDate['year'];
        } else {
            $year = Date::getCurrentYear();
        }
        $is_value_present = false;
        $healthRecords = array();
        
        $newrecord[$time][0] = array(
            'severity' => $severity,
            'pos_x' => $pos_x,
            'pos_y' => $pos_y,
            'bodyPartMain' => $bodyPartMain
        );
        $painRecordValue = array(
            'severity' => $severity,
            'pos_x' => $pos_x,
            'pos_y' => $pos_y,
            'bodyPartMain' => $bodyPartMain
        );

        $data = $this->getUserPainRecords($userId, $painType, $year);
        if (!empty($data)) {
            $painRecords = json_decode($data['PainTracker']['value'], TRUE);
        } else {
            $painRecords = null;
        }

        if (isset($painRecords[$bodyPartMain]) && !empty($painRecords[$bodyPartMain]) && $painRecords[$bodyPartMain] != NULL) {
            foreach ($painRecords[$bodyPartMain] as $key => $record) {
                if ($key == $time) {
                    $is_value_present = true;
                    $painRecords[$bodyPartMain][$key][] = $painRecordValue;
                }
            }
            if ($is_value_present != true) {
                $painRecords[$bodyPartMain][$time][] = $painRecordValue;
            }
        } else {
            $painRecords[$bodyPartMain] = $newrecord;
        }

        $conditions = array(
            'user_id' => $userId,
            'year' => $year,
            'type' => $painType
        );
        $painRecordsJson = json_encode($painRecords);


        if ($this->hasAny($conditions)) {
            $id = $this->find('first', array(
                'conditions' => $conditions,
                'fields' => array('id')
                    )
            );
            $id = $id['PainTracker']['id'];
            $result['success'] = $this->updatePainTrackerRow($id, $painRecordsJson);
        } else {
            $result['success'] = $this->saveNewPainTrackerRow($userId, $painRecordsJson, $painType, $year);
        }
        return $result;
    }

//    function updatePainTrackerRow($id, $recordsJSON, $latestRecordJson) {
    function updatePainTrackerRow($id, $recordsJSON) {
        $data = array(
            'id' => $id,
            'value' => $recordsJSON
        );
        if ($this->save($data)) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

//    function saveNewPainTrackerRow($user_id, $recordsJSON, $record_type, $record_year, $latestRecordJson) {
    function saveNewPainTrackerRow($user_id, $recordsJSON, $record_type, $record_year) {
        $this->create();
        $data = array(
            'user_id' => $user_id,
            'type' => $record_type,
            'year' => $record_year,
            'value' => $recordsJSON
        );
        if ($this->save($data)) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    function getUserPainRecords($userId, $type, $year = NULL) {
        if ($year == NULL) {
            $year = Date::getCurrentYear();
        }

        $data = $this->find('first', array(
            'conditions' => array(
                'user_id' => $userId,
                'year' => $year,
                'type' => $type
            ),
            'fields' => array('value')
                )
        );
        return $data;
    }

    function getUserAllPainRecords($userId, $year = NULL) {
        if ($year == NULL) {
            $year = Date::getCurrentYear();
        }

        $data = $this->find('all', array(
            'conditions' => array(
                'user_id' => $userId,
                'year' => $year
            )
                )
        );
        return $data;
    }

    function getLatestPainRecords($userId, $type, $year = NULL) {
        if ($year == NULL) {
            $year = Date::getCurrentYear();
        }

        $data = $this->find('first', array(
            'conditions' => array(
                'user_id' => $userId,
                'year' => $year,
                'type' => $type
            ),
            'fields' => array('latest_value')
                )
        );
        return $data;
    }

    function updateLatestValues($userId, $year = NULL) {
        $allRecords = $this->getUserAllPainRecords($userId, $year);
        $latestValuesByType = array();
        foreach ($allRecords as $painRecordsRow) {
            $painRecordAllValues = json_decode($painRecordsRow['PainTracker']['value'], TRUE);
            foreach ($painRecordAllValues as $bodyPart => $timeArray) {
                $latestTimeKeyBodypart = max(array_keys($timeArray));
                foreach ($timeArray[$latestTimeKeyBodypart] as $latestPainValues) {
                    $latestValuesByType[$latestTimeKeyBodypart][] = $latestPainValues;
                }
            }
            $latestTimeKey = max(array_keys($latestValuesByType));
            $latestValue[$latestTimeKey] = $latestValuesByType [$latestTimeKey];
            $latestValuesByTypeJson = json_encode($latestValue);

            $data = array(
                'id' => $painRecordsRow['PainTracker']['id'],
                'latest_value' => $latestValuesByTypeJson
            );
            $this->save($data);
            $latestValuesByType = NULL;
            $latestValuesByTypeJson = NULL;
            $latestValue = NULL;
        }
    }

    function updateLatest($userId, $lastPainTypeArray = NULL, $timestamp = NULL) {

        if (isset($timestamp) && $timestamp != NULL) {
            $yearFromDate = getdate($timestamp);
            $year = $yearFromDate['year'];
        } else {
            $year = Date::getCurrentYear();
        }

        if (!is_null($lastPainTypeArray)) {
            foreach ($lastPainTypeArray as $type => $lastPainTypeValue) {

                $painTracker = $this->find('first', array(
                    'conditions' => array('type' => $type,
                        'user_id' => $userId,
                        'year' => $year),
                    'fields' => array('id')
                ));

                $lastValuesByTypeJson = json_encode(array($timestamp => $lastPainTypeValue));

                $id = $painTracker ['PainTracker']['id'];
                $data = array(
                    'id' => $id,
                    'latest_value' => $lastValuesByTypeJson
                );

                $this->save($data);
            }
        } else {
            return false;
        }
    }

    function getPainTrackerGraphData($userId, $userTimezone, $record_year) {
        $bodyPartsArray = $this->getBodyPartsList();
        $painTypes = $this->getPainTypes();
        $recordsAll = $this->getUserAllPainRecords($userId, $record_year);
        $arrayByBodyPart = array();

        foreach ($recordsAll as $allPainData) {
            $currentPainType = $allPainData['PainTracker']['type'];
            $painDetails = json_decode($allPainData['PainTracker']['value'], TRUE);
            foreach ($painDetails as $bodyPart => $records) {
//                $eachPartValue = json_decode($records['PainTracker']['value'], TRUE);
                foreach ($records as $time => $painData) {
                    $timeInUserTimeZone = CakeTime::convert($time, new DateTimeZone($userTimezone));
                    $allSeverity = 0;
                    $allSeverityLength = count($painData);
                    foreach ($painData as $severity) {
                        if (!is_null($severity['severity'])) {
                            $allSeverity += intval($severity['severity']);
                        } else {
                            $allSeverityLength --;
                        }
                    }
                    if (!is_null($allSeverity) && $allSeverityLength > 0 && $allSeverity > 0) {
                        $arrayByBodyPart[$bodyPart][$currentPainType][$timeInUserTimeZone] = $allSeverity / $allSeverityLength;
                    } else {
                        $arrayByBodyPart[$bodyPart][$currentPainType][$timeInUserTimeZone] = NULL; //$allSeverity
                    }
                }
            }
        }
        $resultData = array(
            'bodyPartsArray' => $bodyPartsArray,
            'painTypes' => $painTypes,
            'arrayByBodyPart' => $arrayByBodyPart,
        );
        return $resultData;
    }

}
