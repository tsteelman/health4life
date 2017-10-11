<?php

App::uses('AppModel', 'Model');
App::uses('Date', 'Utility');
App::import('Model', 'FollowingPage');

/**
 * CommunityMember Model
 *
 * @property Community $Community
 * @property User $User
 */
class CommunityMember extends AppModel {
    /**
     * User types
     */

    const USER_TYPE_MEMBER = 1;
    const USER_TYPE_ADMIN = 2;
    const USER_TYPE_OWNER = 3;

    /**
     * status
     */
    const STATUS_INVITED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_NOT_APPROVED = 2;

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Community' => array(
            'className' => 'Community',
            'foreignKey' => 'community_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * Function to add community admin
     * 
     * @param int $communityId community id
     * @param int $userId user id
     * @return boolean
     */
    public function addCommunityAdmin($communityId, $userId) {
        $data = array(
            'community_id' => $communityId,
            'user_id' => $userId,
            'user_type' => self::USER_TYPE_OWNER,
            'status' => self::STATUS_APPROVED,
            'joined_on' => Date::getCurrentDateTime(),
        );
        $this->create();
        return $this->save($data);
    }

    /**
     * Function to invite members to a community
     * 
     * @param int $communityId
     * @param int $members
     * @return boolean
     */
    public function inviteCommunityMembers($communityId, $members) {
        foreach ($members as $userId) {
            $data[] = array(
                'community_id' => $communityId,
                'user_id' => $userId,
                'user_type' => self::USER_TYPE_MEMBER,
                'status' => self::STATUS_INVITED,
                'invited_by' => $this->invited_by
            );
        }
        return $this->saveMany($data);
    }

    /**
     * Function to get the list of ids of the members of a community
     * 
     * @param int $communityId
     * @return array
     */
    public function getCommunityMemberIds($communityId) {
        $communityMembers = $this->find('all', array(
            'conditions' => array('CommunityMember.community_id' => $communityId),
            'fields' => array('CommunityMember.user_id')
        ));

        return $communityMembers;
    }

    /**
     * function to get all the community members.
     * @param int $communityId, community's id
     * @return array
     */
    function getAllCommunityMembers($communityId) {
        $communityMembers = $this->find('all', array(
            'conditions' => array('CommunityMember.community_id' => $communityId),
            'fields' => array('CommunityMember.status', 'CommunityMember.user_id')
                )
        );
        return $communityMembers;
    }

    /**
     * Function to get the list of ids of the approved members of a community
     * 
     * @param int $communityId
     * @return array
     */
    public function getApprovedCommunityMemberIds($communityId) {
        $communityMemberIdList = $this->find('list', array(
            'conditions' => array(
                'community_id' => $communityId,
                'status' => self::STATUS_APPROVED,
            ),
            'fields' => array('user_id', 'user_id')
        ));

        return $communityMemberIdList;
    }

    /**
     * Function to check if user is approved member of a community
     * 
     * @param int $userId
     * @param int $communityId
     * @return boolean
     */
    public function isUserApprovedCommunityMember($userId, $communityId) {
        $count = $this->find('count', array(
            'conditions' => array(
                'CommunityMember.community_id' => $communityId,
                'CommunityMember.user_id' => $userId,
                'CommunityMember.status' => self::STATUS_APPROVED
            )
        ));
        $isUserApprovedCommunityMember = ($count > 0) ? true : false;
        return $isUserApprovedCommunityMember;
    }

    /**
     * Function to get the member status of a user in a community
     * 
     * @param int $userId
     * @param int $communityId
     * @return int
     */
    public function getCommunityMemberStatus($userId, $communityId) {
        $status = null;
        $communityMember = $this->find('first', array(
            'conditions' => array(
                'CommunityMember.community_id' => $communityId,
                'CommunityMember.user_id' => $userId
            )
        ));
        if (!empty($communityMember)) {
            $status = $communityMember['CommunityMember']['status'];
        }
        return $status;
    }

    public function getCommunityMemberUserType($communityId, $UserId) {
        $type = NULL;
        $memberType = $this->find('first', array(
            'conditions' => array(
                'CommunityMember.community_id' => $communityId,
                'CommunityMember.user_id' => $UserId),
            'fields' => array('user_type')
        ));
        if (!empty($memberType)) {
            $type = $memberType['CommunityMember']['user_type'];
        }
        return $type;
    }

    /*
     * Function to get the id of community member table record.
     * 
     * @param int $user_id
     * @param int $community_id
     */

    public function changeCommunityMemberStatus($user_id, $community_id, $type) {
        $id = $this->find('first', array(
            'conditions' => array(
                'CommunityMember.community_id' => $community_id,
                'CommunityMember.user_id' => $user_id),
            'fields' => array('id')
        ));
		$this->FollowingPage = ClassRegistry::init('FollowingPage');
                //Community follow data
			$followCommunityData = array(
				'type' => FollowingPage::COMMUNITY_TYPE,
				'page_id' => $community_id,
				'user_id' => $user_id,
				'notification' => FollowingPage::NOTIFICATION_ON
			);
        if (isset($id['CommunityMember']['id'])) {
            $this->id = $id['CommunityMember']['id'];
//			//Community follow data
//			$followCommunityData = array(
//				'type' => FollowingPage::COMMUNITY_TYPE,
//				'page_id' => $community_id,
//				'user_id' => $user_id,
//				'notification' => FollowingPage::NOTIFICATION_ON
//			);
			
            switch ($type) {
				case 1:
					$this->set(array(
						'community_id' => $community_id,
						'user_id' => $user_id,
						'user_type' => CommunityMember::USER_TYPE_MEMBER,
						'status' => CommunityMember::STATUS_APPROVED,
						'joined_on' => Date::getCurrentDateTime()
					));
					$this->save(); //Save status in community_members table.
					$this->FollowingPage->followPage($followCommunityData);
					$this->Community->changeMemberCount($community_id, $type);
					break;
				case 2:
					$this->delete();					
					$this->FollowingPage->unFollowPage($followCommunityData);
					$this->Community->changeMemberCount($community_id, $type);
					break;
				case 3:
					$this->set(array(
						'community_id' => $community_id,
						'user_id' => $user_id,
						'user_type' => CommunityMember::USER_TYPE_MEMBER,
						'status' => CommunityMember::STATUS_NOT_APPROVED
					));
					$this->save();
			}
        } else if ($type == 1 || $type == 3) {
            $this->create();
            switch ($type) {
                case 1:
                    $this->set(array(
                        'community_id' => $community_id,
                        'user_id' => $user_id,
                        'user_type' => CommunityMember::USER_TYPE_MEMBER,
                        'status' => CommunityMember::STATUS_APPROVED,
                        'joined_on' => Date::getCurrentDateTime()
                    ));
					$this->FollowingPage->followPage($followCommunityData);
                    $this->Community->changeMemberCount($community_id, $type);
                    break;
                case 3:
                    $this->set(array(
                        'community_id' => $community_id,
                        'user_id' => $user_id,
                        'user_type' => CommunityMember::USER_TYPE_MEMBER,
                        'status' => CommunityMember::STATUS_NOT_APPROVED
                    ));
                    break;
            }
            $this->save();
        }
    }

    /**
     * Function to get a community member by community id and user id
     * 
     * @param int $communityId
     * @param int $userId
     * @return array
     */
    public function getCommunityMember($communityId, $userId) {
        return $this->find('first', array(
                    'conditions' => array(
                        'CommunityMember.community_id' => $communityId,
                        'CommunityMember.user_id' => $userId)
        ));
    }

    /**
     * Function to approve a community member
     * 
     * @param int $communityId
     * @param int $userId
     */
    public function approve($communityId, $userId) {
        $communityMember = $this->getCommunityMember($communityId, $userId);
        if (!empty($communityMember)) {
            $this->id = $communityMember['CommunityMember']['id'];
            $data = array(
                'status' => self::STATUS_APPROVED,
                'joined_on' => Date::getCurrentDateTime()
            );

            // update community member
            if ($this->save($data)) {

                // increment community member count
                $this->Community->changeMemberCount($communityId, 1);
            }
        }
    }

    /**
     * Function to reject a community member invitation
     * 
     * @param int $communityId
     * @param int $userId
     */
    public function reject($communityId, $userId) {
        $communityMember = $this->getCommunityMember($communityId, $userId);
        if (!empty($communityMember)) {
            $id = $communityMember['CommunityMember']['id'];
            $this->delete($id);
        }
    }

    /**
     * Function to check if a user has manage permission on a community
     * 
     * @param int $userId user id
     * @param int $communityId community id
     * @return boolean
     */
    public function hasManagePermission($userId, $communityId) {
        $hasCommunityManagePermission = false;
        $userType = $this->getCommunityMemberUserType($communityId, $userId);
        $adminUserTypes = array(
            self::USER_TYPE_OWNER,
            self::USER_TYPE_ADMIN
        );

        if (!is_null($userType) && in_array($userType, $adminUserTypes)) {
            $hasCommunityManagePermission = true;
        }

        return $hasCommunityManagePermission;
    }
/**
 * Function to approve all members while the admin changes the community type to public.
 * @param int $communityId
 */
    public function approveAllMembers($communityId) {
        $notApprovedMembers = $this->find('all', array(
            'conditions' => array('CommunityMember.community_id' => $communityId,
                'CommunityMember.status' => self::STATUS_NOT_APPROVED),
            'fields' => array('CommunityMember.id','CommunityMember.user_id')
                )
        );

		$this->FollowingPage = ClassRegistry::init('FollowingPage');

        if (isset($notApprovedMembers) && $notApprovedMembers != NULL) {
            foreach ($notApprovedMembers as $key => $member) {
                $this->id = $member['CommunityMember']['id'];
				$userId = $member['CommunityMember']['user_id'];				
				
                $this->saveField('status', self::STATUS_APPROVED);
				//Community follow data
				$followCommunityData = array(
					'type' => FollowingPage::COMMUNITY_TYPE,
					'page_id' => $communityId,
					'user_id' => $userId,
					'notification' => FollowingPage::NOTIFICATION_ON
				);
				$this->FollowingPage->followPage($followCommunityData);
                $this->Community->changeMemberCount($communityId, 1);
            }
        }
    }
    
    /*
     * Function to get list of communities based on user and status
     * 
     * @param int $userId
     * @param int $status
     * @return array
     */
    public function getCommunityList ($userId, $status) {
        $communityIds = $this->find('list', array(
            'conditions' => array(
                'CommunityMember.user_id' => $userId,
                'CommunityMember.status' => $status
                ),
            'fields' => array('CommunityMember.community_id')
        ));
        return $communityIds;
	}

	/**
	 * Function to get approved members of a community
	 * 
	 * @param int $communityId
	 * @return array
	 */
	public function getCommunityMembers($communityId) {
		$this->unbindModel(array(
			'belongsTo' => array('Community')
		));
		$query = array(
			'conditions' => array(
				'CommunityMember.community_id' => $communityId,
				'CommunityMember.status' => self::STATUS_APPROVED
			),
			'fields' => array('User.id', 'User.username', 'User.type'),
			'order' => array('User.username' => 'asc')
		);
		$members = $this->find('all', $query);

		return $members;
	}
        
    /**
     * Function to get the list of ids of the members of a community
     * 
     * @param int $communityId
     * @return array
     */
    public function getCommunityMemberIdsList($communityId) {
        $communityMembers = $this->find('list', array(
            'conditions' => array('CommunityMember.community_id' => $communityId , 'CommunityMember.status' => self::STATUS_APPROVED ),
            'fields' => array('CommunityMember.user_id')
        ));

        return $communityMembers;
    }
}