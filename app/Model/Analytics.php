<?php

App::uses('AppModel', 'Model');

/**
 * City Model
 *
 * @property State $State
 */
class Analytics extends AppModel {

    const USER_TABLE = 'users';
    const EVENT_TABLE = 'events';
    const COMMUNITY_TABLE = 'communities';
    const DISEASE_TABLE = 'diseases';
    const MEDICATION_TABLE = 'treatments';
    const COUNTRY_TABLE = 'countries';
    const CITY_TABLE = 'cities';
    const PATIENTDISEASE_TABLE = 'patient_diseases';
    const EVENTDISEASE_TABLE = 'event_diseases';
    const COMMUNITYDISEASE_TABLE = 'community_diseases';
    const USERTREATMENT_TABLE = 'user_treatments';

    public $useTable = false;

    /*
     * Function to get the total number of users in the application
     */

    public function getUsersCount($array = FALSE, $type = 'Total') {

        $this->setSource(self::USER_TABLE);
        $conditions = array(
            'Analytics.type !=' => NULL,
            'Analytics.is_admin' => 0,
            'DATE(Analytics.created) !=' => '0000-00-00'
        );
        if (!$array) {
            $count = $this->find('count', array(
                'conditions' => $conditions
            ));
        } else {
            if ($type === 'Male') {
                $conditions[] = array('Analytics.gender' => 'M');
            } else if ($type === 'Female') {
                $conditions[] = array('Analytics.gender' => 'F');
            }

            $count = $this->find('all', array(
                'conditions' => $conditions,
                'fields' => array('COUNT(*) AS users', 'DATE(created) AS created_date'),
                'group' => array('DATE(Analytics.created)')
            ));
        }

        return $count;
    }

    /*
     * Function to get the total number of events in the application
     */

    public function getEventsCount($array = FALSE, $type = 'Total') {
        $this->setSource(self::EVENT_TABLE);

        $conditions = array(
            'Analytics.event_type' => array(1, 2, 3),
            'Analytics.repeat' => 0,
            'DATE(Analytics.start_date) !=' => '0000-00-00'
        );
        if (!$array) {
            $count = $this->find('count', array(
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'INNER',
                        'conditions' => array('User.id = Analytics.created_by')
                    )
                ),
                'conditions' => $conditions
            ));
        } else {
            if ($type === 'Public') {
                $conditions[] = array('Analytics.event_type' => 1);
            } else if ($type === 'Private') {
                $conditions[] = array('Analytics.event_type' => 2);
            }

            $count = $this->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'INNER',
                        'conditions' => array('User.id = Analytics.created_by')
                    )
                ),
                'conditions' => $conditions,
                'fields' => array('COUNT(*) AS events', 'DATE(Analytics.start_date) AS date'),
                'group' => array('DATE(Analytics.start_date)')
            ));
        }

        return $count;
    }

    /*
     * Function to get the count of all the communities in the application.
     */

    public function getCommunityCount($array = FALSE, $type = 'Total') {
        $this->setSource(self::COMMUNITY_TABLE);
        $conditions = array('DATE(Analytics.created) !=' => '0000-00-00');
        if (!$array) {
            $count = $this->find('count', array(
                'conditions' => $conditions
            ));
        } else {
            if ($type === 'Open') {
                $conditions[] = array('Analytics.type' => 1);
            } else if ($type === 'Closed') {
                $conditions[] = array('Analytics.type' => 2);
            }

            $count = $this->find('all', array(
                'conditions' => $conditions,
                'fields' => array('COUNT(*) AS communities', 'DATE(Analytics.created) AS date'),
                'group' => array('DATE(Analytics.created)')
            ));
        }

        return $count;
    }

    /*
     * Function to get disease user count
     * 
     * @param int disease_id
     * @param string $gender male/female
     * @return int count
     */

    public function getDiseaseUserCount($disease_id = NULL, $gender = FALSE) {
        $this->setSource(self::PATIENTDISEASE_TABLE);

        $joins = array(
            array(
                "table" => self::DISEASE_TABLE,
                "alias" => "Disease",
                "type" => "INNER",
                "conditions" => array(
                    "Analytics.disease_id = Disease.id"
                )
            )
        );

        $result = array();
        if ($disease_id == NULL) {
            $conditions = array();
        } else {
            $conditions = array('Analytics.disease_id' => $disease_id);
        }

        if ($gender) {
            if ($gender == 'Male') {
                $conditions[] = array('USER.gender' => 'M');
            } else if ($gender == 'Female') {
                $conditions[] = array('USER.gender' => 'F');
            }

            $joins[] = array(
                "table" => self::USER_TABLE,
                "alias" => "USER",
                "type" => "INNER",
                "conditions" => array(
                    "Analytics.patient_id = USER.id"
                )
            );
        }

        $result_array_unordered = $this->find('all', array(
            'joins' => $joins,
            'conditions' => $conditions,
            'fields' => array('Analytics.disease_id', 'COUNT(DISTINCT Analytics.patient_id) AS users', 'Disease.name'),
            'group' => array('Analytics.disease_id'),
            'order' => array('COUNT(DISTINCT Analytics.patient_id) DESC')
        ));

        foreach ($result_array_unordered as $unordered) {
            $result[] = array(
                'id' => intval($unordered['Analytics']['disease_id']),
                'name' => $unordered['Disease']['name'],
                'users' => intval($unordered[0]['users'])
            );
        }

        return $result;
    }

    /*
     * Function to find the disease with most number of users.
     */

    public function getTopDiseases($limit = 1) {

        $diseaseUserCountArray = $this->getDiseaseUserCount();
        $result = array();
        $i = 0;
        foreach ($diseaseUserCountArray as $value) {
            if ($i < $limit) {
                $result[] = $value;
            } else {
                break;
            }
            $i++;
        }
        if ($limit == 1) {
            return $result[0];
        } else {
            return $result;
        }
    }

    public function getCountryUserCount() {
        $this->setSource(self::USER_TABLE);

        $result = array();

        $result_unordered = $this->find('all', array(
            'joins' => array(
                array(
                    'table' => self::COUNTRY_TABLE,
                    'alias' => 'Country',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Country.id = Analytics.country'
                    )
                )
            ),
            'conditions' => array('Analytics.country !=' => NULL),
            'fields' => array('Analytics.country', 'Country.short_name', 'COUNT(*) AS users'),
            'group' => array('Analytics.country'),
            'order' => array('COUNT(*) DESC')
        ));

        foreach ($result_unordered as $unordered) {
            $result[] = array(
                'id' => $unordered['Analytics']['country'],
                'name' => $unordered['Country']['short_name'],
                'users' => $unordered[0]['users']
            );
        }

        return $result;
    }

    public function getTopCountries($limit) {
        $countyUserCount = $this->getCountryUserCount();
        $i = 0;
        foreach ($countyUserCount as $count) {
            if ($i < $limit) {
                $result[] = $count;
            } else {
                break;
            }
            $i++;
        }

        return $result;
    }

    public function getDiseaseGenderAnalytics($disease_id) {
        $this->setSource(self::PATIENTDISEASE_TABLE);

        $maleCount = $this->getDiseaseUserCount($disease_id, 'Male');
        if (isset($maleCount[0]['users'])) {
            $male = $maleCount[0]['users'];
        } else {
            $male = 0;
        }
        $femaleCount = $this->getDiseaseUserCount($disease_id, 'Female');
        if (isset($femaleCount[0]['users'])) {
            $female = $femaleCount[0]['users'];
        } else {
            $female = 0;
        }


        $data = array(
            array(
                0 => 'Male',
                1 => $male
            ),
            array(
                0 => 'Female',
                1 => $female
            ),
        );

        return $data;
    }

    public function getDiseaseAgeAnalytics($disease_id) {
        $this->setSource(self::PATIENTDISEASE_TABLE);
        $data = $this->find('all', array(
            'joins' => array(
                array(
                    'table' => self::USER_TABLE,
                    'alias' => 'USER',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Analytics.patient_id = USER.id'
                    )
                )
            ),
            'conditions' => array('Analytics.disease_id' => $disease_id),
            'fields' => array(
                'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                    Between 0 AND 18  THEN 1 ELSE 0 END) AS kids',
                'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                    Between 19 AND 24 THEN 1 ELSE 0 END) AS junior',
                'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                    Between 25 AND 34 THEN 1 ELSE 0 END) AS adult',
                'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                    Between 35 AND 59  THEN 1 ELSE 0 END) AS middle',
                'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                    > 60  THEN 1 ELSE 0 END) AS senior'
            )
        ));

        return $data[0][0];
    }

    public function getDiseaseAgeTreatmentAnalytics($disease_id) {
        $this->setSource(self::PATIENTDISEASE_TABLE);

        $data = $this->find('all', array(
            'conditions' => array(
                'Analytics.disease_id' => $disease_id
            ),
            'fields' => array('Analytics.id')
        ));

        $ids = array();
        foreach ($data as $id_value) {
            $ids[] = $id_value['Analytics']['id'];
        }

        $this->setSource(self::USERTREATMENT_TABLE);

        $result = array();

        if (!empty($ids)) {
            $treatment = $this->find('all', array(
                'conditions' => array(
                    'Analytics.patient_disease_id' => $ids
                ),
                'fields' => array('Analytics.patient_disease_id')
            ));
            $treatment_patient_diseas_ids = array();
            foreach ($treatment as $id) {
                $treatment_patient_diseas_ids[] = $id['Analytics']['patient_disease_id'];
            }

            $notreatment_patient_diseas_ids = array();
            $notreatment_patient_diseas_ids = array_diff($ids, $treatment_patient_diseas_ids);

            $this->setSource(self::PATIENTDISEASE_TABLE);

            $treatment_result = $this->find('all', array(
                'joins' => array(
                    array(
                        'table' => self::USER_TABLE,
                        'alias' => 'USER',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Analytics.patient_id = USER.id'
                        )
                    )
                ),
                'conditions' => array('Analytics.id' => $treatment_patient_diseas_ids),
                'fields' => array(
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        Between 0 AND 18  THEN 1 ELSE 0 END) AS kids',
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        Between 19 AND 24 THEN 1 ELSE 0 END) AS junior',
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        Between 25 AND 34 THEN 1 ELSE 0 END) AS adult',
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        Between 35 AND 59  THEN 1 ELSE 0 END) AS middle',
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        > 60  THEN 1 ELSE 0 END) AS senior'
                )
            ));

            $notreatment_result = $this->find('all', array(
                'joins' => array(
                    array(
                        'table' => self::USER_TABLE,
                        'alias' => 'USER',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Analytics.patient_id = USER.id'
                        )
                    )
                ),
                'conditions' => array('Analytics.id' => $notreatment_patient_diseas_ids),
                'fields' => array(
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        Between 0 AND 18  THEN 1 ELSE 0 END) AS kids',
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        Between 19 AND 24 THEN 1 ELSE 0 END) AS junior',
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        Between 25 AND 34 THEN 1 ELSE 0 END) AS adult',
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        Between 35 AND 59  THEN 1 ELSE 0 END) AS middle',
                    'SUM(CASE WHEN (TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) 
                        > 60  THEN 1 ELSE 0 END) AS senior'
                )
            ));
            $result['treatments'] = $treatment_result[0][0];
            $result['no_treatments'] = $notreatment_result[0][0];
        }



        return $result;
    }

    public function getDiseaseEventsCount($disease_id) {
        $this->setSource(self::EVENTDISEASE_TABLE);

        $data = $this->find('count', array(
            'conditions' => array('Analytics.disease_id' => $disease_id)
        ));

        return $data;
    }

    public function getDiseaseCommunitiesCount($disease_id) {
        $this->setSource(self::COMMUNITYDISEASE_TABLE);

        $data = $this->find('count', array(
            'conditions' => array('Analytics.disease_id' => $disease_id)
        ));

        return $data;
    }

    public function getTopDiseaseCountry($disease_id) {
        $this->setSource(self::PATIENTDISEASE_TABLE);

        $data = $this->find('all', array(
            'joins' => array(
                array(
                    'table' => self::USER_TABLE,
                    'alias' => 'USER',
                    'type' => 'INNER',
                    'conditions' => array('Analytics.patient_id = USER.id')
                ),
                array(
                    'table' => self::COUNTRY_TABLE,
                    'alias' => 'COUNTRY',
                    'type' => 'INNER',
                    'conditions' => array('USER.country = COUNTRY.id')
                )
            ),
            'conditions' => array('Analytics.disease_id' => $disease_id),
            'fields' => array('COUNT(DISTINCT USER.id) AS users', 'COUNTRY.short_name as name'),
            'group' => array('COUNTRY.id'),
            'order' => array('COUNT(DISTINCT USER.id) DESC'),
            'limit' => 10
        ));

        return $data;
    }

    public function getDiseaseUserLocation($disease_id) {
        $this->setSource(self::PATIENTDISEASE_TABLE);

        $data = $this->find('all', array(
            'joins' => array(
                array(
                    'table' => self::USER_TABLE,
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array('Analytics.patient_id = User.id')
                ),
                array(
                    'table' => self::CITY_TABLE,
                    'alias' => 'City',
                    'type' => 'INNER',
                    'conditions' => array('User.city = City.id')
                )
            ),
            'conditions' => array(
                'Analytics.disease_id' => $disease_id,
                'City.latitude !=' => '',
                'City.longitude !=' => ''
            ),
            'fields' => array('User.type', 'City.latitude', 'City.longitude')
        ));

        $result = array();
        foreach ($data as $value) {
            $result[] = array(
                'type' => intval($value['User']['type']),
                'lat' => floatval($value['City']['latitude']),
                'lng' => floatval($value['City']['longitude'])
            );
        }

        return $result;
    }

    public function getTopTreatments($limit) {
        $this->setSource(self::USERTREATMENT_TABLE);

        $data = $this->find('all', array(
            'joins' => array(
                array(
                    'table' => self::MEDICATION_TABLE,
                    'alias' => 'Treatment',
                    'type' => 'INNER',
                    'conditions' => array('Treatment.id = Analytics.treatment_id')
                )
            ),
            'conditions' => array('Analytics.patient_disease_id !=' => NULL),
            'fields' => array('COUNT(DISTINCT Analytics.user_id) AS users', 'Treatment.name'),
            'group' => array('Analytics.treatment_id'),
            'order' => array('users DESC'),
            'limit' => $limit
        ));

        $result = array();
        foreach ($data as $record) {
            $result[] = array(
                'users' => $record[0]['users'],
                'treatment' => $record['Treatment']['name']
            );
        }
        return $result;
    }

    public function getDiseaseTreatments($diseaseId) {
        $this->setSource(self::PATIENTDISEASE_TABLE);

        $patient_disease_id = $this->find('all', array(
            'conditions' => array('Analytics.disease_id' => $diseaseId),
            'fields' => array('Analytics.id')
        ));

        $result = array();

        if (!empty($patient_disease_id)) {
            foreach ($patient_disease_id as $id) {
                $patient_disease_id_array[] = $id['Analytics']['id'];
            }

            $this->setSource(self::USERTREATMENT_TABLE);

            $result = $this->find('all', array(
                'joins' => array(
                    array(
                        'table' => self::MEDICATION_TABLE,
                        'alias' => 'Treatment',
                        'type' => 'INNER',
                        'conditions' => array('Analytics.treatment_id = Treatment.id')
                    )
                ),
                'conditions' => array('Analytics.patient_disease_id' => $patient_disease_id_array),
                'fields' => array('COUNT(DISTINCT Analytics.user_id) AS users', 'Treatment.name'),
                'group' => array('Analytics.treatment_id')
            ));
        }

        return $result;
    }

}

?>