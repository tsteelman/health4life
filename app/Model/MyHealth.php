<?php

App::uses('AppModel', 'Model');
App::uses('Date', 'Utility');

/**
 * MyHealth Model
 *
 * @property User $User
 */
class MyHealth extends AppModel {
    /**
     * Health status values
     */

    const STATUS_VERY_GOOD = 5;
    const STATUS_GOOD = 4;
    const STATUS_NEUTRAL = 3;
    const STATUS_BAD = 2;
    const STATUS_VERY_BAD = 1;

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * Function to check if the user has set the health status today
     * 
     * @param int $userId
     * @param string $timezone
     * @return boolean
     */
    public function isHealthStatusSetToday($userId, $timezone) {
        $today = Date::getCurrentDate($timezone);
        $offset = Date::getTimeZoneOffsetText($timezone);
        $query = array(
            'conditions' => array(
                "{$this->alias}.user_id" => $userId,
                "CONVERT_TZ({$this->alias}.created, '+00:00', '{$offset}') LIKE" => "$today%"
            )
        );
        $count = $this->find('count', $query);
        $isHealthStatusSetToday = ($count > 0) ? true : false;
        return $isHealthStatusSetToday;
    }

	/**
	 * Function to add the current health status of the user
	 * 
	 * @param int $userId
	 * @param int $healthStatus
	 * @param string $comment
	 * @return boolean
	 */
	public function addUserHealthStatus($userId, $healthStatus, $comment) {
		$this->create();
		$data = array(
			'user_id' => $userId,
			'health_status' => $healthStatus,
			'comment' => $comment
		);
		return $this->save($data, false);
	}

    /**
     * Function to get the user health status list per day
     * 
     * @param int $userId
     * @param string $timezone
     * @return array
     */
    public function getUserHealthStatusData($userId, $timezone) {
        $data = array();
        $this->recursive = -1;
        $healthData = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.user_id" => $userId,
            ),
            'order' => array("{$this->alias}.created ASC"),
        ));
        if (!empty($healthData)) {
            foreach ($healthData as $key => $health) {
                $created = $health['MyHealth']['created'];
                $status = $health['MyHealth']['health_status'];
                $createdDate = CakeTime::format('Y-m-d', $created, false, $timezone);
                if ($key === 0) {
                    $pointStart = strtotime($created);
                }
                $healthStatusData[$createdDate] = $status;
            }
            $data['chartData'] = array_values($healthStatusData);
            $data['pointStart'] = $pointStart;
        }
        return $data;
    }

    /**
     * Function to get the list of health statuses
     * 
     * @return array
     */
    public static function getHealthStatusList() {
        $healthStatusList = array(
            array
                (
                'text' => 'Very good',
                'value' => self::STATUS_VERY_GOOD,
                'image' => 'very_good_smiley.png',
                'class' => 'health_vg'
            ),
            array(
                'text' => 'Good',
                'value' => self::STATUS_GOOD,
                'image' => 'good_smiley.png',
                'class' => 'health_g',
            ),
            array(
                'text' => 'Neutral',
                'value' => self::STATUS_NEUTRAL,
                'image' => 'neutral_smiley.png',
                'class' => 'health_n'
            ),
            array(
                'text' => 'Bad',
                'value' => self::STATUS_BAD,
                'image' => 'bad_smiley.png',
                'class' => 'health_b'
            ),
            array(
                'text' => 'Very bad',
                'value' => self::STATUS_VERY_BAD,
                'image' => 'very_bad_smiley.png',
                'class' => 'health_vb'
            )
        );
        return $healthStatusList;
    }
	
	public function getTodaysStatus($userId, $timezone) {

        $today = Date::getCurrentDate($timezone);
        $offset = Date::getTimeZoneOffsetText($timezone);
        $query = array(
            'conditions' => array(
                "{$this->alias}.user_id" => $userId,
                "CONVERT_TZ({$this->alias}.created, '+00:00', '{$offset}') LIKE" => "$today%"
            ),
            'order' => array("{$this->alias}.id" => 'DESC')
		);

		$status = $this->find('first', $query);
		
		if (!empty($status)) {
			return $status['MyHealth']['health_status'];
		} else {
			return null;
		}
	}
        
        /**
	 * Returns the latest health status of a user.
	 * 
	 * @param array $feelingStatus
	 * @return string
	 */
        public function getLatestHealthStatus($userId) {

        $query = array(
            'conditions' => array(
                "{$this->alias}.user_id" => $userId
            ),
            'order' => array("{$this->alias}.id" => 'DESC'),
            'fields' => array('created','health_status')
		);

		$status = $this->find('first', $query);		
		if (!empty($status)) {
			return $status['MyHealth'];
		} else {
			return null;
		}
	}

	/**
	 * Returns the smiley class for different health statuses
	 * 
	 * @param int $feelingStatus
	 * @return string
	 */
	public function getFeelingSmileyClass($healthStatus) {
		$feelingClass = null;
		switch ($healthStatus) {
			case self::STATUS_VERY_BAD:
				$feelingClass = 'feeling_very_bad';
				break;
			case self::STATUS_BAD:
				$feelingClass = 'feeling_bad';
				break;
			case self::STATUS_NEUTRAL:
				$feelingClass = 'feeling_neutral';
				break;
			case self::STATUS_GOOD:
				$feelingClass = 'feeling_good';
				break;
			case self::STATUS_VERY_GOOD:
				$feelingClass = 'feeling_very_good';
				break;
		}

		return $feelingClass;
	}

	/**
	 * Returns the health status text for different health statuses
	 * 
	 * @param int $healthStatus
	 * @return string
	 */
	public static function getHealthStatusText($healthStatus) {
		switch ($healthStatus) {
			case self::STATUS_VERY_BAD:
				$healthStatusText = 'very bad';
				break;
			case self::STATUS_BAD:
				$healthStatusText = 'bad';
				break;
			case self::STATUS_NEUTRAL:
				$healthStatusText = 'neutral';
				break;
			case self::STATUS_GOOD:
				$healthStatusText = 'good';
				break;
			case self::STATUS_VERY_GOOD:
				$healthStatusText = 'very good';
				break;
			default :
				$healthStatusText = '';
		}

		return $healthStatusText;
	}
}