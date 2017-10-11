<?php

/**
 * Privacy Settings library class
 *
 * This class contain functions
 * to save and retreive the privacy settings to/from DB.
 */
App::uses('User', 'Model');

class UserPrivacySettings {

	private $privacyFields = array();

	const PRIVACY_PRIVATE = 1;
	const PRIVACY_FRIENDS = 2;
	const PRIVACY_PUBLIC = 3;

	function __construct($userId = "")
	{
            $this->privacyFields = array(
            "post_on_wall" => self::PRIVACY_FRIENDS,
            "view_your_friends" => self::PRIVACY_FRIENDS,
            "view_your_activity" => self::PRIVACY_FRIENDS,
            "view_your_team" => self::PRIVACY_FRIENDS,
            "view_your_health" => self::PRIVACY_FRIENDS,
            "view_your_nutrition" => self::PRIVACY_FRIENDS,
            "view_your_communities" => self::PRIVACY_FRIENDS,
            "view_your_events" => self::PRIVACY_FRIENDS,
            "view_your_disease" => self::PRIVACY_FRIENDS,
            "view_your_blog" => self::PRIVACY_FRIENDS,                
        );
		if (!empty($userId))
		{
			try
			{
				$userModel = new User();
				$userObj = $userModel->findById($userId);
                                
				if ($userObj && isset($userObj['User']['privacy_settings']))
				{
					$privacySerialized = $userObj['User']['privacy_settings'];
					$privacySettings = unserialize($privacySerialized);
					if (!empty($privacySettings))
					{
						foreach ($privacySettings as $key => $value)
						{
							$this->__set($key, $value);
						}
					}
				}
			}
			catch(Exception $exc)
			{
				// do nothing
			}
		}
	}

	//PHP magic method for getting privacy field value
	public function __get($name)
	{
		if (array_key_exists($name, $this->privacyFields))
			return $this->privacyFields[$name];
		else
			return self::PRIVACY_FRIENDS;
	}

	//PHP magic method for setting privacy field value
	public function __set($name, $value)
	{
		if (array_key_exists($name, $this->privacyFields))
			$this->privacyFields[$name] = $value;
		else
			$this->privacyFields[$name] = self::PRIVACY_PRIVATE;
	}

	/**
	 * Sets privacy field values and saves the privacy object to DB
	 * @param string $userId id of the user
	 * @param array $data privacy fields value from the privacy form
	 * @return boolean status to indicate whether privacy object has been saved or not
	 */
	function setPrivacyValue($userId, $data)
	{

		if ($userId > 0)
		{
			foreach($data['User'] as $key => $value)
			{
				$this->privacyFields[$key] = $value;
			}

			$privacySerialized = serialize($this->privacyFields);

			try
			{
				$userModel = new User();
				$userModel->id = $userId;
				$privacyData = array(
					'privacy_settings' => $privacySerialized,
					'searchable_by' => $data['User']['searchable_by']
				);
				$userModel->save($privacyData, false);
			}
			catch(Exception $exc)
			{
				return false;
			}

			return true;
		}
		else
			return false;
	}

	/**
	 * Returns all privacy fields
	 * @return array All privacy fields
	 */
	public function getPrivacySettings()
	{
		return $this->privacyFields;
	}

	/**
	 * Function to get the users who have allowed to view their 
	 * health and activity feeds
	 *  
	 * @param array $users
	 * @return array
	 */
	public function getFeedAllowedUsers($users) {
		$userModel = new User();
		$usersPrivacySettings = $userModel->find('list', array(
			'conditions' => array('id' => $users),
			'fields' => array('id', 'privacy_settings')
		));
		$activityFeedAllowedUsers = array();
		$healthFeedAllowedUsers = array();
		foreach ($usersPrivacySettings as $followingUserId => $privacySettingStr) {
			$privacySettings = unserialize($privacySettingStr);
			$activityViewPermittedTo = self::PRIVACY_FRIENDS;
			if (!empty($privacySettings['post_on_wall'])) {
				$activityViewPermittedTo = (int) $privacySettings['post_on_wall'];
			}
			if ($activityViewPermittedTo !== self::PRIVACY_PRIVATE) {
				$activityFeedAllowedUsers[] = $followingUserId;
			}
			$healthViewPermittedTo = self::PRIVACY_FRIENDS;
			if (!empty($privacySettings['view_your_health'])) {
				$healthViewPermittedTo = (int) $privacySettings['view_your_health'];
			}
			if ($healthViewPermittedTo !== self::PRIVACY_PRIVATE) {
				$healthFeedAllowedUsers[] = $followingUserId;
			}
		}

		$feedAllowedUsers['activity_feed_allowed_users'] = $activityFeedAllowedUsers;
		$feedAllowedUsers['health_feed_allowed_users'] = $healthFeedAllowedUsers;

		return $feedAllowedUsers;
	}
}