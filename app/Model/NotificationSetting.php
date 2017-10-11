<?php

App::uses('AppModel', 'Model');
App::import('Model', 'User');
/**
 * Notification SettingModel
 *
 */
class NotificationSetting extends AppModel {

    const SETTING_ON = 1;
    const SETTING_OFF = 0;
    const IMPERIAL = '1';
    const METRIC = '2';
    const TEMP_UNIT_CELSIUS = '1';
    const TEMP_UNIT_FAHRENHEIT = '2';

	/**
	 * Recommend friends frequencies
	 */
	const FREQUENCY_DAILY = 'daily';
	const FREQUENCY_WEEKLY = 'weekly';
	const FREQUENCY_MONTHLY = 'monthly';
	const FREQUENCY_YEARLY = 'yearly';
	const RECOMMEND_FRIENDS_DEFAULT_FREQUENCY = self::FREQUENCY_WEEKLY;

    static $notification_default = array('email_settings' =>
        array(//'news_letter' => self::SETTING_ON,
            'how_am_i_feeling' => self::SETTING_ON,
            'friends_request_reminder' => self::SETTING_ON,
            'site_wide_event' => self::SETTING_ON,
            'event_invitation' => self::SETTING_ON,
            'event_cancelation' => self::SETTING_ON,
            'event_update' => self::SETTING_ON,
            'friend_request' => self::SETTING_ON,
            'friend_request_approval' => self::SETTING_ON,
			'site_wide_community' => self::SETTING_ON,
            'community_invitation' => self::SETTING_ON,
            'community_removed' => self::SETTING_ON,
            'event_reminder' => self::SETTING_ON,
            'post_on_wall' => self::SETTING_ON,
            'comment_on_post' => self::SETTING_ON,
            'post_i_follow' => self::SETTING_ON,
            'group_request' => self::SETTING_ON,
            'my_group_activities' => self::SETTING_ON,
            'other_group_activities' => self::SETTING_ON,
            'event_rsvp' => self::SETTING_ON,
            'my_event_activity' => self::SETTING_ON,
            'other_event_activity' => self::SETTING_ON,
            'answered_my_question' => self::SETTING_ON,
            'answered_same_question' => self::SETTING_ON,
            'message' => self::SETTING_ON,
            'recommend_friends' => self::SETTING_ON
    ));
    static $unit_settings = array(
        'height_unit' => self::IMPERIAL,
        'weight_unit' => self::IMPERIAL,
        'temp_unit' => self::TEMP_UNIT_FAHRENHEIT
    );
	
	static $emailSettingsDescription = array( 0 => 'news_letter',
		4 => 'event_invitation', 5 => 'event_rsvp', 6 => 'event_cancelation', 7 => 'event_update', 9  => 'community_invitation', 
		10 => 'community_removed', 13  => 'friend_request', 14 => 'friend_request_approval' , 18 => 'message', 21 => 'friends_request_reminder',
		22 => 'how_am_i_feeling', 24 => 'group_request', 25 => 'post_on_wall', 26 => 'comment_on_post', 28 => 'event_reminder',
		31 => 'site_wide_event', 32 => 'site_wide_community', 72 => 'recommend_friends', 75 => 'answered_my_question'
	);
	
//	static $emailSettingsDescription = array(
//            'news_letter', 'how_am_i_feeling', 'friends_request_reminder', 'site_wide_event', 'event_invitation', 'event_cancelation',
//            'event_update', 'friend_request', 'friend_request_approval', 'site_wide_community', 'community_invitation',
//            'community_removed','event_reminder', 'post_on_wall', 'comment_on_post', 'post_i_follow',
//            'group_request', 'my_group_activities', 'other_group_activities', 'event_rsvp', 'my_event_activity',
//            'other_event_activity', 'answered_my_question', 'answered_same_question', 'message' ,'recommend_friends' 
//	);

    /**
     * Function saves notification settings for a user
     *
     * @param Array $params
     * @return boolean
     */
    public function saveEmailSettings($userId, $params) {//debug($params);
        if ($userId > 0) {
            $record = $this->find("first", array('conditions' => array('user_id' => $userId)));
            if (empty($record)) {
                $settings = $this->getDefaultSettings();
                $this->create();
            } else {
                if (empty($record['email_settings'])) {
                    $settings = $this->getDefaultSettings();
                } else {
                    $settings = json_decode($record['email_settings']);
                }
            }
            foreach (self::$notification_default['email_settings'] as $key => $value) {
            	
            	$settings['email_settings'][$key] = isset($params['NotificationSetting'][$key]) ? $params['NotificationSetting'][$key] : self::SETTING_OFF;				
            }
				
			/*
			 * Save newsletter settings in user table
			 */
            
            $User = new User ();
			if (isset ( $params ['NotificationSetting'] ['news_letter'] )) {
				
				if ($params ['NotificationSetting'] ['news_letter'] == self::SETTING_ON) {
					$User->subscribeNewsletter ( $userId );
				} else {
					$User->unsubscribeNewsletter ( $userId );
				}
			}else{
				$User->unsubscribeNewsletter ( $userId );
			}
			
            $record['NotificationSetting']['email_settings'] = json_encode($settings);
            $record['NotificationSetting']['user_id'] = $userId;
			
			if (isset($params['NotificationSetting']['recommend_friends_frequency'])) {
				if (isset($record['NotificationSetting']['recommend_friends_frequency'])) {
					$oldFrequency = $record['NotificationSetting']['recommend_friends_frequency'];
				} else {
					$oldFrequency = self::RECOMMEND_FRIENDS_DEFAULT_FREQUENCY;
				}
				$newFrequency = $params['NotificationSetting']['recommend_friends_frequency'];
				$record['NotificationSetting']['recommend_friends_frequency'] = $newFrequency;
				if ($oldFrequency !== $newFrequency) {
					$record['NotificationSetting']['frequency_changed_datetime'] = Date::getCurrentDateTime();
				}
			}

			
            $this->save($record);
            return TRUE;
        }
    }

    /**
     * function returns whether particular email notification setting is on/off
     *
     * @param Integer $userId
     * @param String $userId
     * @return Array
     */
    public function isEmailNotificationOn($userId, $notification) {
        $record = $this->find("first", array('conditions' =>
            array('user_id' => $userId)));
        if (empty($record)) {
            $settings = $this->getDefaultSettings();
        } else {
            if (empty($record['NotificationSetting']['email_settings'])) {
                $settings = $this->getDefaultSettings();
            } else {
                $settings = json_decode($record['NotificationSetting']['email_settings'], TRUE);
            }
        }
        if (isset($settings['email_settings'][$notification])) {
			return (bool) $settings['email_settings'][$notification];
		} else {
			return true;
		}
    }

	/**
     * function retrieves notification setting for a user
     *
     * @param Integer $userId
     * @return Array
     */
    public function getEmailSettingsByUserId($userId) {
        $record = $this->find("first", array('conditions' =>
            array('user_id' => $userId)));
        if (empty($record)) {
            $settings = $this->getDefaultSettings($userId);
            $settings['email_settings']['news_letter'] = self::SETTING_ON;
			$settings['recommend_friends_frequency'] = self::RECOMMEND_FRIENDS_DEFAULT_FREQUENCY;
        } else {
            if (!empty($record['NotificationSetting']['email_settings'])) {
                $settings = json_decode($record['NotificationSetting']['email_settings'], TRUE);
				
				// set default value for the settings which are not present
				$defaultSettings = $this->getDefaultSettings();
				$defaultEmailSettings = $defaultSettings['email_settings'];
				foreach ($defaultEmailSettings as $settingName => $value) {
					if (!isset($settings['email_settings'][$settingName])) {
						$settings['email_settings'][$settingName] = $value;
					}
				}
                
                /*
                 * Get news letter settings form user table
                 */
                $User = new User ();
                $settings['email_settings']['news_letter']  = $User->getNewsletterSetting ( $userId );
               
            } else {
                $settings = $this->getDefaultSettings($userId);
                $settings['email_settings']['news_letter'] = self::SETTING_ON;
            }
			$settings['recommend_friends_frequency'] = $record['NotificationSetting']['recommend_friends_frequency'];
        }
        return $settings;
    }

    /*
     * Function to get the user settings for measurements
     * 
     * @param Integer $userId
     * @return array
     */

    public function getUnitSettings($userId) {
        $record = $this->find('first', array(
            'conditions' => array('user_id' => $userId),
            'fields' => array('height_unit', 'weight_unit', 'temp_unit', 'sound_settings')
        ));
        if (empty($record)) {
            $unitSettings = $this->getDefaultUnitSettings();
        } else {
            $unitSettings = $record['NotificationSetting'];
        }
        return $unitSettings;
    }

    /*
     * Function to change unit settings
     * 
     * @param int $userId
     */

    public function changeUnitSetting($values, $userId) {
        $record = $this->findByUser_id($userId);
        if (!empty($record)) {
            $this->id = $record['NotificationSetting']['id'];
            if (isset($this->id)) {
                $data = $record['NotificationSetting'];
            }
        } else {
            $this->create();
            $data = array(
                'user_id' => $userId,
                'email_settings' => json_encode($this->getDefaultSettings()),
                'height_unit' => 1,
                'weight_unit' => 1,
                'temp_unit' => 2,
				'sound_settings' => 1
            );
        }
        foreach ($values as $measurement => $value) {
            switch ($measurement) {
                case 'height':
                    $data['height_unit'] = $value;
                    break;
                case 'weight':
                    $data['weight_unit'] = $value;
                    break;
                case 'temp':
                    $data['temp_unit'] = $value;
                    break;
				case 'music' :
					$data['sound_settings'] = $value;
					break;
            }
        }
        $this->save($data);
        return TRUE;
    }

    /**
     * returns default settings
     *
     * @param Integer $userId
     * @return type
     */
    private function getDefaultSettings() {
        return self::$notification_default;
    }

    /**
     * returns default measuring unit settings
     *
     * @param Integer $userId
     * @return type
     */
    private function getDefaultUnitSettings() {
        return self::$unit_settings;
    }

	/**
	 * Function to get the notification count of a user
	 * 
	 * @param int $userId
	 * @return int 
	 */
	public function getUserNotificationCount($userId) {
		$record = $this->findByUserId($userId);
		if (!empty($record)) {
			$count = $record['NotificationSetting']['notification_count'];
		} else {
			$count = 0;
		}

		return $count;
	}

	/**
	 * Function to unset the notification count of a user
	 * 
	 * @param int $userId 
	 */
	public function unsetUserNotificationCount($userId) {
		App::uses('Date', 'Utility');
		$now = Date::getCurrentDateTime();
		$db = $this->getDataSource();
		$now = $db->value($now, 'string');

		$fields = array(
			'notification_count' => 0,
			'notification_last_viewed' => $now
		);
		$conditions = array('user_id' => $userId);
		$this->updateAll($fields, $conditions);
	}

	/**
	 * Remove a friend user from user's recommended users list
	 * 
	 * @param int $userId
	 * @param int $friendId
	 */
	public function removeUserFromRecommendedUsers($userId, $friendId) {
		$model = $this->findByUserId($userId);
		if (!empty($model)) {
			$settings = $model[$this->alias];
			if (!empty($settings['recommended_users'])) {
				$recommendedUsers = $settings['recommended_users'];
				$recommendedUsersList = explode(',', $recommendedUsers);
				$friendKey = array_search($friendId, $recommendedUsersList);
				if ($friendKey !== false) {
					unset($recommendedUsersList[$friendKey]);
					$recommendedUsers = join(',', $recommendedUsersList);
					$this->id = $model[$this->alias]['id'];
					$this->saveField('recommended_users', $recommendedUsers);
				}
			}
		}
	}

	/**
	 * Function to get frequency options
	 * 
	 * @return array
	 */
	public static function getFrequencyOptions() {
		$frequencyOptions = array(
			self::FREQUENCY_DAILY => __('Daily'),
			self::FREQUENCY_WEEKLY => __('Weekly'),
			self::FREQUENCY_MONTHLY => __('Monthly'),
			self::FREQUENCY_YEARLY => __('Yearly'),
		);
		return $frequencyOptions;
	}
	
	/**
	 * Function to get the notification music setting of the user
	 * 
	 * @param int $userId
	 * @return int 
	 */
	public function getNotificationMusicSetting($userId) {
		$record = $this->findByUserId($userId);
		if (!empty($record)) {
			$status = $record['NotificationSetting']['sound_settings'];
		} else {
			$status = 1;
		}
		return $status;
	}
	
	/**
	 * Function to unsubscribe email settings
	 * 
	 * @param int $userId
	 * @param int $setting
	 * @return boolean
	 */
	public function unsubscribeEmailSettings($userId, $setting) {
		$record = $this->find("first", array('conditions' => array('user_id' => $userId)));
		if(!empty($record)) {
			if($setting == 0) {
				$User = new User();
				$User->unsubscribeNewsletter($userId);
			} else {
				$emailSettings = json_decode($record['NotificationSetting']['email_settings'], true);
				$description = self::$emailSettingsDescription[$setting];
				$emailSettings['email_settings'][$description] = self::SETTING_OFF;	

				$record['NotificationSetting']['email_settings'] = json_encode($emailSettings);
				$record['NotificationSetting']['user_id'] = $userId;
				$this->save($record);
				return TRUE;
			}
		}
	}
}