<?php

App::uses('AppModel', 'Model');

/**
 * Post Model
 *
 * @property User $User
 */
class Post extends AppModel {
	
    /**
     * Post types
     */
    const POST_TYPE_TEXT = 'text';
    const POST_TYPE_LINK = 'link';
    const POST_TYPE_VIDEO = 'video';
    const POST_TYPE_IMAGE = 'image';
    const POST_TYPE_POLL = 'poll';
    const POST_TYPE_COMMUNITY = 'community';
    const POST_TYPE_EVENT = 'event';
    const POST_TYPE_HEALTH = 'health';
    const POST_TYPE_TEAM_PRIVACY_CHANGE = 'privacy_change';
    const POST_TYPE_QUESTION = 'question';
    const POST_TYPE_ECARD = 'ecard';
    const POST_TYPE_BLOG = 'blog';    
    
    /**
     * Posted in types
     */
    const POSTED_IN_TYPE_COMMUNITIES = 'communities';
    const POSTED_IN_TYPE_EVENTS = 'events';
    const POSTED_IN_TYPE_USERS = 'users';
    const POSTED_IN_TYPE_DISEASES = 'diseases';
    const POSTED_IN_TYPE_TEAM = 'team';

    /**
     * Post status constants
     */
    const STATUS_NORMAL = 0;
    const STATUS_ABUSE_REPORTED = 1;
    const STATUS_BLOCKED = 2;

    const IN_FAVORITE = '1';
    const NOT_IN_FAVORITE = '0';

    const IS_DELETED = 1;
    const NOT_DELETED = 0;

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'post_by',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * Function to return all the post type allowed in the application
     *
     * @return array
     */
    public static function getAllPostType() {
        return array(
            self::POST_TYPE_TEXT,
            self::POST_TYPE_LINK,
            self::POST_TYPE_VIDEO,
            self::POST_TYPE_IMAGE,
            self::POST_TYPE_POLL,
            self::POST_TYPE_COMMUNITY,
            self::POST_TYPE_EVENT,
            self::POST_TYPE_HEALTH,
            self::POST_TYPE_TEAM_PRIVACY_CHANGE,
            self::POST_TYPE_QUESTION,
            self::POST_TYPE_ECARD,
            self::POST_TYPE_BLOG
        );
    }
    
    /**
     * Function to add a post indicating that a new community is added
     *
     * @param array $communityData
     * @param string $clientIp
     * @param int $diseaseId
     * @return int
     */
    public function addNewCommunityPost($communityData, $clientIp, $diseaseId = null) {
        $this->create();
		$communityId = $communityData['id'];
		$postBy = $communityData['created_by'];
		$communityData = $this->__getFilteredCommunityData($communityData);
		$content = json_encode(array('community' => $communityData));
		if (!is_null($diseaseId)) {
			$postedIn = $diseaseId;
			$postedInType = self::POSTED_IN_TYPE_DISEASES;
		} else {
			$postedIn = $communityId;
			$postedInType = self::POSTED_IN_TYPE_COMMUNITIES;
		}
		$data = array(
			'post_by' => $postBy,
			'ip' => $clientIp,
			'post_type_id' => $communityId,
			'post_type' => self::POST_TYPE_COMMUNITY,
			'posted_in' => $postedIn,
			'posted_in_type' => $postedInType,
			'content' => $content,
		);
        $this->save($data);
		return $this->id;
    }

	/**
	 * Function to update posts of a community
	 *
	 * @param array $communityData
	 */
	public function updateCommunityPosts($communityData) {
		$communityId = $communityData['id'];
		$communityPosts = $this->getCommunityPosts($communityId);
		if (!empty($communityPosts)) {
			$communityData = $this->__getFilteredCommunityData($communityData);
			$content = json_encode(array('community' => $communityData));
			foreach ($communityPosts as $communityPost) {
				$data[] = array(
					'id' => $communityPost['Post']['id'],
					'content' => $content
				);
			}
			$this->saveMany($data);
		}
	}

	/**
	 * Function to get posts of a community
	 *
	 * @param int $communityId
	 * @return array
	 */
	public function getCommunityPosts($communityId) {
		$conditions = array(
			'Post.post_type_id' => $communityId,
			'Post.post_type' => self::POST_TYPE_COMMUNITY
		);
		$posts = $this->find('all', array('conditions' => $conditions));
		return $posts;
	}

	/**
     * Function to filter community data to save in post content
     *
     * @param array $communityData
     * @return array
     */
    private function __getFilteredCommunityData($communityData) {
        $fields = array('id', 'name', 'description');
        foreach ($communityData as $key => $community) {
            if (!in_array($key, $fields)) {
                unset($communityData[$key]);
            }
        }
        return $communityData;
    }

    /**
	 * Function to add a post indicating that a new event is added
	 *
	 * @param array $eventData
	 * @param type $clientIp
	 */
	public function addNewEventPost($eventData, $clientIp) {
		$this->create();
		$eventId = $eventData['id'];
		if (isset($eventData['disease_id']) && ($eventData['disease_id'] > 0)) {
			$postedInType = self::POSTED_IN_TYPE_DISEASES;
			$postedIn = $diseaseId = $eventData['disease_id'];
			$this->FollowingPage = ClassRegistry::init('FollowingPage');
			$rooms = $this->FollowingPage->getDiseasePostFollowingRooms($diseaseId);
		} else if (isset($eventData['community_id']) && ($eventData['community_id'] > 0)) {
			$postedInType = self::POSTED_IN_TYPE_COMMUNITIES;
			$postedIn = $communityId = $eventData['community_id'];
			$this->FollowingPage = ClassRegistry::init('FollowingPage');
			$rooms = $this->FollowingPage->getCommunityPostFollowingRooms($communityId);
			$this->CommunityDisease = ClassRegistry::init('CommunityDisease');
			$taggedDiseases = $this->CommunityDisease->getDiseasesOfPublicCommunity($communityId);
			if (!empty($taggedDiseases)) {
				foreach ($taggedDiseases as $diseaseId) {
					$rooms[] = "diseases/{$diseaseId}";
				}
			}
		} else {
			$postedInType = self::POSTED_IN_TYPE_EVENTS;
			$postedIn = $eventId;
		}
		$content = json_encode(array('event' => $eventData));
		$data = array(
			'post_by' => $eventData['created_by'],
			'ip' => $clientIp,
			'post_type_id' => $eventId,
			'post_type' => self::POST_TYPE_EVENT,
			'posted_in' => $postedIn,
			'posted_in_type' => $postedInType,
			'content' => $content,
		);
		$this->save($data);

		if (!empty($rooms)) {
			$postId = $this->id;
			$this->__realtimeNotifyNewEventPost($postId, $rooms);
		}
	}

	/**
	 * Function to realtime notify following rooms about new event
	 * 
	 * @param int $postId
	 * @param array $rooms
	 */
	private function __realtimeNotifyNewEventPost($postId, $rooms) {
		try {
			App::import('Vendor', 'elephantio/client');
			$elephant = new ElephantIO\Client(Configure::read('SOCKET.URL'), 'socket.io', 1, false, true, true);
			$elephant->init();
			foreach ($rooms as $room) {
				$elephant->emit('new_post', array(
					'room' => $room,
					'postId' => $postId
				));
			}
			$elephant->close();
		} catch (Exception $e) {
			return;
		}
	}

	/**
	 * Function to update the posts of an event
	 *
	 * @param array $eventData
	 */
	public function updateEventPosts($eventData) {
		$eventId = $eventData['id'];
		$eventPosts = $this->getEventPosts($eventId);
		if (!empty($eventPosts)) {
			$content = json_encode(array('event' => $eventData));
			foreach ($eventPosts as $eventPost) {
				$data[] = array(
					'id' => $eventPost['Post']['id'],
					'content' => $content
				);
			}
			$this->saveMany($data);
		}
	}

	/**
     * Function to get posts of an event
     *
     * @param int $eventId
     * @return array
     */
    public function getEventPosts($eventId) {
        $conditions = array(
            'Post.post_type_id' => $eventId,
            'Post.post_type' => self::POST_TYPE_EVENT,
        );
        $posts = $this->find('all', array('conditions' => $conditions));
        return $posts;
    }

    /**
     * Function to save the comments JSON for a post
     *
     * @param int $postId
     * @param array $commentsArray
     */
    public function savePostCommentsJSON($postId, $commentsArray) {
        $commentJSON = json_encode($commentsArray);
        $this->id = $postId;
        $this->saveField('comment_json_content', $commentJSON);
    }

    /**
     * Function to update the video data of multiple posts
     *
     * @param type $postVideosData
     * @return boolean
     */
    public function updatePostVideosData($postVideosData) {
        $this->recursive = -1;
        $postData = array();
        foreach ($postVideosData as $postVideoData) {
            $mediaId = $postVideoData['media_id'];
            $videoDetails = $postVideoData['video'];
            $post = $this->find('first', array(
                'conditions' => array(
                    'post_type_id' => $mediaId,
                    'post_type' => Post::POST_TYPE_VIDEO
                ),
                'fields' => array('id', 'content')
            ));
            if (!empty($post['Post'])) {
                $postId = $post['Post']['id'];
                $postJSONContent = $post['Post']['content'];
                $postContent = json_decode($postJSONContent, true);
                $postContent['additional_info']['video'] = $videoDetails;
                $postData[] = array(
                    'id' => $postId,
                    'content' => json_encode($postContent)
                );
            }
        }

        if (!empty($postData)) {
            return $this->saveAll($postData);
        } else {
            return false;
        }
    }

	/**
	 * Function to add a post indicating that a user has updated the heath status
	 * 
	 * @param int $userId
	 * @param string $clientIp
	 * @param int $healthStatus
	 * @param string $comment
	 * @return int
	 */
	public function addHealthStatusUpdatePost($userId, $clientIp, $healthStatus, $comment) {
		$this->create();
		$content = array(
			'health_status' => $healthStatus
		);
		if (!empty($comment)) {
			$content['health_status_comment'] = $comment;
		}
		$data = array(
			'post_by' => $userId,
			'ip' => $clientIp,
			'post_type' => self::POST_TYPE_HEALTH,
			'posted_in' => $userId,
			'posted_in_type' => self::POSTED_IN_TYPE_USERS,
			'content' => json_encode($content),
		);
		$this->save($data);
		return $this->id;
	}
        
        
    /**
     * Function to add a post indicating that the team privacy is changed
     *
     * @param array $teamData
     * @param type $clientIp
     */
    public function addNewTeamPost($teamData, $postBy, $clientIp) {
        $this->create();
        $teamId = $teamData['id'];        
        $teamData = $this->__getFilteredTeamData($teamData);
        $content = json_encode(array('privacy_change' => $teamData));
        $data = array(
            'post_by' => $postBy,
            'ip' => $clientIp,
            'post_type_id' => $teamId,
            'post_type' => self::POST_TYPE_TEAM_PRIVACY_CHANGE,
            'posted_in' => $teamId,
            'posted_in_type' => self::POSTED_IN_TYPE_TEAM,
            'content' => $content,
        );
        $this->save($data);
    }
    
    /**
     * Function to filter Team data to save in post content
     *
     * @param array $teamData
     * @return array
     */
    private function __getFilteredTeamData($teamData) {
        $fields = array('id', 'name', 'privacy');
        foreach ($teamData as $key => $team) {
            if (!in_array($key, $fields)) {
                unset($teamData[$key]);
            }
        }
        return $teamData;
    }

	/**
	 * Function to check if a disease event created post exists
	 * 
	 * @param int $diseaseId
	 * @param int $eventId
	 * @return bool
	 */
	public function diseaseEventPostExists($diseaseId, $eventId) {
		$eventDiseasePost = $this->find('first', array(
			'conditions' => array(
				'Post.post_type' => self::POST_TYPE_EVENT,
				'Post.post_type_id' => $eventId,
				'Post.posted_in_type' => self::POSTED_IN_TYPE_DISEASES,
				'Post.posted_in' => $diseaseId,
				'Post.is_deleted' => false
			)
		));
		$exist = (!empty($eventDiseasePost)) ? true : false;
		return $exist;
	}

	/**
	 * Function to add posts in disease pages about event
	 * 
	 * @param array $eventData
	 * @param string $clientIp
	 * @param array $diseases
	 */
	public function addDiseaseEventPosts($eventData, $clientIp, $diseases) {
		$eventId = $eventData['id'];
		foreach ($diseases as $diseaseId) {
			if (!$this->diseaseEventPostExists($diseaseId, $eventId)) {
				$eventData['disease_id'] = $diseaseId;
				$this->addNewEventPost($eventData, $clientIp);
			}
		}
	}

	/**
	 * Function to delete event posts from diseases pages
	 * 
	 * @param int $eventId
	 * @param array $diseases
	 */
	public function deleteEventDiseasesPosts($eventId, $diseases) {
		$conditions = array(
			'Post.post_type' => self::POST_TYPE_EVENT,
			'Post.post_type_id' => $eventId,
			'Post.posted_in_type' => self::POSTED_IN_TYPE_DISEASES,
			'Post.posted_in' => $diseases
		);
		$fields = array('Post.is_deleted' => true);
		$this->updateAll($fields, $conditions);
	}

	/**
	 * Function to soft delete the posts related to an event
	 * 
	 * @param int $eventId
	 */
	public function deleteEventPosts($eventId) {
		$conditions = array(
			'OR' => array(
				array(
					'Post.post_type' => self::POST_TYPE_EVENT,
					'Post.post_type_id' => $eventId,
				),
				array(
					'Post.posted_in_type' => self::POSTED_IN_TYPE_EVENTS,
					'Post.posted_in' => $eventId
				)
			)
		);
		$fields = array('Post.is_deleted' => true);
		$this->updateAll($fields, $conditions);
	}

	/**
	 * Function to check if a disease community created post exists
	 * 
	 * @param int $diseaseId
	 * @param int $communityId
	 * @return bool
	 */
	public function diseaseCommunityPostExists($diseaseId, $communityId) {
		$communityDiseasePost = $this->find('first', array(
			'conditions' => array(
				'Post.post_type' => self::POST_TYPE_COMMUNITY,
				'Post.post_type_id' => $communityId,
				'Post.posted_in_type' => self::POSTED_IN_TYPE_DISEASES,
				'Post.posted_in' => $diseaseId,
				'Post.is_deleted' => false
			)
		));
		$exist = (!empty($communityDiseasePost)) ? true : false;
		return $exist;
	}

	/**
	 * Function to add posts in disease pages about community
	 * 
	 * @param array $communityData
	 * @param string $clientIp
	 * @param array $diseases
	 * @return array
	 */
	public function addDiseaseCommunityPosts($communityData, $clientIp, $diseases) {
		$newDiseasePosts = array();
		$communityId = $communityData['id'];
		foreach ($diseases as $diseaseId) {
			if (!$this->diseaseCommunityPostExists($diseaseId, $communityId)) {
				$postId=$this->addNewCommunityPost($communityData, $clientIp, $diseaseId);
				$newDiseasePosts[]=array(
					'diseaseId'=>$diseaseId,
					'postId'=>$postId,
				);
			}
		}
		return $newDiseasePosts;
	}

	/**
	 * Function to delete community posts from diseases pages
	 * 
	 * @param int $communityId
	 * @param array $diseases
	 */
	public function deleteCommunityDiseasesPosts($communityId, $diseases) {
		$conditions = array(
			'Post.post_type' => self::POST_TYPE_COMMUNITY,
			'Post.post_type_id' => $communityId,
			'Post.posted_in_type' => self::POSTED_IN_TYPE_DISEASES,
			'Post.posted_in' => $diseases
		);
		$fields = array('Post.is_deleted' => true);
		$this->updateAll($fields, $conditions);
	}

	/**
	 * Function to soft delete the posts related to a community
	 * 
	 * @param int $communityId
	 */
	public function deleteCommunityPosts($communityId) {
		$conditions = array(
			'OR' => array(
				array(
					'Post.post_type' => self::POST_TYPE_COMMUNITY,
					'Post.post_type_id' => $communityId,
				),
				array(
					'Post.posted_in_type' => self::POSTED_IN_TYPE_COMMUNITIES,
					'Post.posted_in' => $communityId
				)
			)
		);
		$fields = array('Post.is_deleted' => true);
		$this->updateAll($fields, $conditions);
	}
}