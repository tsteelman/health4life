<?php

App::uses('AppModel', 'Model');

/**
 * Community Model
 *
 * @property Event $Event
 * @property CommunityDisease $CommunityDisease
 * @property CommunityMember $CommunityMember
 */
class Community extends AppModel {
    
    /**
     * Community types
     */
    const COMMUNITY_TYPE_OPEN = 1;
    const COMMUNITY_TYPE_CLOSED = 2;
    const COMMUNITY_TYPE_SITE = 3;

     /**
     * Cover slideshow enabled/disabled status
     */
    const COVER_SLIDESHOW_ENABLED = 1;
    const COVER_SLIDESHOW_DISABLED = 0;
    
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter the community name'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 50),
                'message' => 'Cannot be more than 50 characters long.'
            ),
            'remote' => array(
                'rule' => array('remote', '/api/checkExistingCommunityName', 'name'),
                'message' => 'This community name already exists.'
            )
        ),
        'description' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'message' => 'Cannot be more than 100 characters long.',
                'allowEmpty' => true
            )
        ),
        'country' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a country'
            )
        ),
        'state' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a state/province'
            )
        ),
        'city' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a city'
            )
        ),
        'zip' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 15),
                'message' => 'Zip cannot exceed 15 characters.',
				'allowEmpty' => true
            )
        ),
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'community_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'CommunityDisease' => array(
            'className' => 'CommunityDisease',
            'foreignKey' => 'community_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'CommunityMember' => array(
            'className' => 'CommunityMember',
            'foreignKey' => 'community_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    
    public $recrusion = 1;

    function getCommunity($communityId) {
        $community = $this->find('first', array(
            'conditions' => array('Community.id' => $communityId)
        ));

        return $community['Community'];
    }

    /**
     * Function to get community types
     * 
     * @return array
     */
    public static function getCommunityTypes() {
        $communityTypes = array(
            self::COMMUNITY_TYPE_OPEN => __('Open'),
            self::COMMUNITY_TYPE_CLOSED => __('Closed'),
            self::COMMUNITY_TYPE_SITE => __('Site wide')
        );
        return $communityTypes;
    }
    
    
    /*
     * Function to change the number of members in a community based on join or leave.
     * 
     * @param int $communityId
     * @param int type To identify if count is to be incremented or decremented.
     */
    public function changeMemberCount($communityId, $type) {
        $member_count = $this->find('first', array(
                                'conditions' => array('Community.id' => $communityId),
                                'fields' => array('member_count')
                            ));
        switch ( $type ) {
            case 1: 
                $member_count = $member_count['Community']['member_count'] + 1;
                break;
            case 2:
                $member_count = $member_count['Community']['member_count'] - 1;
                break;
        }
        $this->id = $communityId;
        $this->set('member_count', $member_count);
        $this->save(); //Save members count in communitys table.

        return $member_count;
    }

    /**
     * Function to update the discussion count of a community
     * 
     * @param int $communityId community id
     * @param string $action whether the count is to be incremented or decremented
     */
    public function updateDiscussionCount($communityId, $action) {
        // get current discussion count
        $this->recursive = -1;
        $fields = array('discussion_count');
        $community = $this->findById($communityId, $fields);

        // increment or decrement the count
        $discussionCount = $community['Community']['discussion_count'];
        switch ($action) {
            case 'INC':
                $discussionCount++;
                break;
            case 'DEC':
                $discussionCount--;
                break;
        }

        // update the new count
        $this->id = $communityId;
        $this->set('discussion_count', $discussionCount);
        $this->save();
    }

	/**
	 * Function to get community data from community id
	 * 
	 * @param int $communityId
	 * @return array
	 */
	public function getCommunityData($communityId) {
		$belongsTo = array(
			'City' => array(
				'className' => 'City',
				'foreignKey' => 'city',
				'fields' => array(
					'City.description'
				)
			),
			'State' => array(
				'className' => 'State',
				'foreignKey' => 'state',
				'fields' => array(
					'State.description'
				)
			),
			'Country' => array(
				'className' => 'Country',
				'foreignKey' => 'country',
				'fields' => array(
					'Country.short_name'
				)
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'created_by',
				'fields' => array(
					'User.username'
				)
			)
		);

		$this->bindModel(array('belongsTo' => $belongsTo), false);
		$communityData = $this->findById($communityId);

		return $communityData;
	}
	
	function getLastFiveCommunityDetails(){
		$communitiesList = $this->find('list', array(
				'order' => array('Community.created desc'),
				'limit' => 5
			
		));
		
		$communities = array();
		foreach ($communitiesList as $id => $communityName){
			$communities[] = $this->getCommunityData($id);
		}
		return $communities;
	}
        
        /**
    	 * Function to save a Community's cover slideshow enabled/disabled status
	 * 
	 * @param int $communityId
	 * @param int $status 
	 * @return boolean
	 */
	public function saveCommunityCoverSlideshowStatus($communityId, $status) {
		$this->id = $communityId;
		return $this->saveField('is_cover_slideshow_enabled', $status);
	}
}