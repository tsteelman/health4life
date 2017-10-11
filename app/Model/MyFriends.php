<?php

App::uses('AppModel', 'Model');
App::import('Model', 'User');
App::uses('UserPrivacySettings', 'Lib');

/*
 * MyFriends Model
 */

class MyFriends extends AppModel {
    
    const STATUS_REQUEST_SENT = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_REQUEST_RECIEVED = 3;
    const STATUS_SAME_USER = 4;
    const ROLE_PATIENT = 1;
    
    function getFriendsList ($user_id) {
        
        $User = new User();
        $my_confirmed_friends = array();
        $my_friends_details = array();
        
        $friendsJson = $this->getFriendJson($user_id);
        
        if(!empty($friendsJson)) {
            $all_friends_list = json_decode($friendsJson['MyFriends']['friends'], TRUE);
            $friends = $all_friends_list['friends'];
            
            $i = 0;
            foreach ($friends as $my_friend) {
                $my_friends_details[$i] = array(
                    'friend_id' => $my_friend['user_id'],
                    'friend_status' => $my_friend['status']
                );
                $i++;
            }
            $i = 0;
            $my_confirmed_friends = array();
            foreach ($my_friends_details as $val) {
                if ($val['friend_status'] == MyFriends::STATUS_CONFIRMED) {
                    $friend = $User->find('first', array(
                        'conditions' => array('User.id' => $val['friend_id'])
                            )
                    );
                    
                    /*
                     * Adding the condition to check if the user exists
                     */
                    if(!empty($friend)) {                    
                        $my_confirmed_friends[$i] = array(
                            'friend_name' => Common::getUsername($friend['User']['username'], $friend['User']['first_name'], $friend['User']['last_name']),
                            'friend_id' => $friend['User']['id'],
                            'friend_image' => $friend['User']['profile_picture'],
                            'friend_type' => $friend['User']['type']
                        );
                        $i++;
                    }
                }
            }
            usort($my_confirmed_friends, array($this, 'sort_username'));
        } 
        return $my_confirmed_friends;    
    }
    
    //Function to get friend list of a particular user who are confirmed, waiting etc
    function getIdOfAllTypeFriendsList($user_id) {        

        $friendsJson = $this->getFriendJson($user_id);
        $my_friends_ids = array();

        if (!empty($friendsJson)) {
            $all_friends_list = json_decode($friendsJson['MyFriends']['friends'], TRUE);
            $friends = $all_friends_list['friends'];
			
			foreach ( $friends as $my_friend ) {
				array_push ( $my_friends_ids, $my_friend ['user_id'] );
			}
        }
        return $my_friends_ids;
    }
    
    //Function to get friends json text
    function getFriendJson($user_id) {
        $data = $this->find('first', array(
            'conditions' => array('MyFriends.my_id' => $user_id),
            'fields' => array('friends')
            )
        );
        return $data;
    }
    
    /**
	 * Function to get friendship status
	 * 
	 * $user_id
	 * $friend_id
	 */
    function getFriendStatus($user_id, $friend_id) {  

    	if($user_id == $friend_id){
    		return MyFriends::STATUS_SAME_USER;	
    	}
        $status = 0;
        $friendsJson = $this->getFriendJson($user_id);
        if(!empty($friendsJson)) {
            $all_friends_list = json_decode($friendsJson['MyFriends']['friends'], TRUE);
            foreach ($all_friends_list['friends'] as $friend) {
                if($friend['user_id'] == $friend_id) {
                    $status = $friend['status'];
                    break;
                }
            }
        }
        return $status;
    }
    
    //Function to get all friends status list of a user
    function getAllFriendsList($user_id) {
        $my_friends_list = array();
         $data = $this->find('first', array(
            'conditions' => array('MyFriends.my_id' => $user_id),
            'fields' => array('friends')
            )
        );
        if(!empty($data)) {
            $friends = array();
            $friends = json_decode($data['MyFriends']['friends'], TRUE);
            $my_friends_list = $friends['friends'];
        } 
        
        return $my_friends_list;
    }
    
    //Function to add friend
    function addFriend($user_id, $friend_id) {
        $this->setFriendStatus($user_id, $friend_id, MyFriends::STATUS_REQUEST_SENT);
        $this->setFriendStatus($friend_id, $user_id, MyFriends::STATUS_REQUEST_RECIEVED);
        
        //$this->updatePendingRequestCount($friend_id);
        $this->incrementPendingRequestCount($friend_id);
    }
    
    /*
     * Function to approve friends request.
     * 
     * @param int $user_id
     * @param int $friend_id
     */
    function approveFriend($user_id, $friend_id) {
    	$userStatus = $this->getFriendStatus($user_id, $friend_id);
    	$friendStatus = $this->getFriendStatus($user_id, $friend_id);
    	if($userStatus == MyFriends::STATUS_REQUEST_RECIEVED){
//     		$this->updatePendingRequestCount($user_id);
    		$this->decrementPendingRequestCount($user_id);
    	}else if($friendStatus == MyFriends::STATUS_REQUEST_RECIEVED){
//     		$this->updatePendingRequestCount($friend_id);
    		$this->decrementPendingRequestCount($friend_id);
    	}
    	
        $this->setFriendStatus($user_id, $friend_id, MyFriends::STATUS_CONFIRMED);
        $this->setFriendStatus($friend_id, $user_id, MyFriends::STATUS_CONFIRMED);

    }

    /*
     * Function to reject friends request.
     * 
     * @param int $user_id
     * @param int $friend_id
     */
    function rejectFriend($user_id, $friend_id) {
        $this->setFriendStatus($user_id, $friend_id, 0);
        $this->setFriendStatus($friend_id, $user_id, 0);
        
//         $this->updatePendingRequestCount($user_id);
        $this->decrementPendingRequestCount($user_id);
    }
    
    /*
     * Function to remove friend.
     * 
     * @param int $user_id
     * @param int $friend_id
     */
    function removeFriend($user_id, $friend_id) {
        $this->setFriendStatus($user_id, $friend_id, 0);
        $this->setFriendStatus($friend_id, $user_id, 0);
    }

    /*
     * Function to set status of friends.
     *
     * @param int $user_id
     * @param int $friend_id
     * @param int $status Friendship status
     */
    function setFriendStatus($user_id, $friend_id, $status) {
        $is_in_friends_list = FALSE;
        $id = array();
        $new_friends_list = array();
        $changeFriendStatus = array(
            'user_id' => $friend_id,
            'status' => $status
        );
        $current_friends = $this->getAllFriendsList($user_id);
        
        foreach ($current_friends as $friend) {
            if($friend['user_id'] == $friend_id) {
                $is_in_friends_list = TRUE;
                $friend = $changeFriendStatus;
            }
            if($friend['status'] == 0) {
                continue;
            } else {
                $new_friends_list[] = $friend;
            }
        }
        if($is_in_friends_list === FALSE) {
            array_push($new_friends_list, $changeFriendStatus);
        }
        $friendsData['friends'] = $new_friends_list;
        $friendsJSON = json_encode($friendsData);
        
        $id = $this->findByMy_id($user_id);
        
        $this->saveFriendsList($id, $user_id, $friendsJSON);
    }
    
    /*
     * Function to save friends list to db
     * 
     * @param array $friendsJSON containing the list of friends
     * @param int $id row id in table
     * @param int $user_id
     */
    function saveFriendsList($id, $user_id, $friendsJSON){
       if(!empty($id)) {
            $data = array(
                'id' => $id['MyFriends']['id'],
                'my_id' => $user_id,
                'friends' => $friendsJSON
            );
        } else {
            $this->create();
            $data = array(
                'my_id' => $user_id,
                'friends' => $friendsJSON
            );
        }
        $result = $this->save($data);
    }
    
    /**
     * Function to get list of pending invites
     * @param int $user_id
     * 
     * return array
     */
    function getPendingFriendsList($user_id){
    	$pending_invites = array();
    	
    	$friendsJson = $this->getFriendJson($user_id);
    	
    	if(!empty($friendsJson)) {
    		$all_friends_list = json_decode($friendsJson['MyFriends']['friends'], TRUE);
    		$friends = $all_friends_list['friends'];
    		$my_friends_details = array();
    		foreach ($friends as $my_friend) {
    			$my_friends_details[] = array(
    					'friend_id' => $my_friend['user_id'],
    					'friend_status' => $my_friend['status']
    			);
    		}
    		$my_confirmed_friends = array();
    		foreach ($my_friends_details as $friend) {
    			if ($friend['friend_status'] == MyFriends::STATUS_REQUEST_RECIEVED) {
    				$pending_invites[] = $friend['friend_id']; 
    			}
    		}
    	} else {
    	
    	}
    	return $pending_invites;
    }
    
    /**
    * Function to get count of pending invites
    * @param int $user_id
    * @param int $status
    *
    * return integer
    */
    function getFriendsStatusCount($user_id, $status = self::STATUS_CONFIRMED){
    	$friends_array = $this->getUserFriendsList($user_id, $status);
    	
    	return count($friends_array);
    }    
    
    /**
    * Function to get the friends list full details 
    * @param int $user_id
    *
    * return array
    */
    function getFriendsListFullDetails($user_id, $logged_in_user, $status = self::STATUS_CONFIRMED) {
    	
    	$friends_array = $this->getUserFriendsList($user_id, $status);
    	$friends_id_array = array();
    	$friends_listing = array();
    	if(!empty($friends_array)) {
    		foreach ($friends_array as $friend)
    		{
    			$friends_id_array[] = $friend['friend_id'];
    		}
    	}
    	//$User = ClassRegistry::init('User');
    	$User = new User();
    	$friends_listing = $User->getFullUserDetails($friends_id_array);
        
        $i = 0;
        foreach ($friends_listing as $friend) {
            $mutualFriends = $this->getMutualFriends($logged_in_user, $friend['User']['id']);
            $privacy = new UserPrivacySettings($friend['User']['id']);				
			$diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');
			/**
			 * 'who can see my conditions' privacy setting
			 */
			if (
					$diseaseViewPermittedTo === $privacy::PRIVACY_PRIVATE 
					&& $friend['User']['id'] != $logged_in_user
			) {
				$friends_listing[$i][0]['diseases'] = NULL;
			} elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
				$friendStatus = (int) $this->getFriendStatus($logged_in_user, $friend['User']['id']);
				if (($friendStatus != MyFriends::STATUS_CONFIRMED)) {
					$friends_listing[$i][0]['diseases'] = NULL;
				}
			} 
			$friends_listing[$i]['mutual_friends_count'] = $mutualFriends;
            $i++;
        }

    	return $friends_listing;
    } 

    /**
    * Function to get the friends list full details
    * @param int $user_id
    *
    * return array
    */
    function getUserFriendsList($user_id, $status = self::STATUS_CONFIRMED) {
        
    	$friends_array = array();
    	
    	$friendsJson = $this->getFriendJson($user_id);
    	 
    	if(!empty($friendsJson)) {
    		$all_friends_list = json_decode($friendsJson['MyFriends']['friends'], TRUE);
    		$friends = $all_friends_list['friends'];
    		
    		foreach ($friends as $my_friend) {
    			if ($my_friend['status'] == $status) {
	    			$friends_array[] = array(
        					'friend_id' => $my_friend['user_id'],
        					'friend_status' => $my_friend['status']
	    			);
    			}
    		}
    	}
    
    	return $friends_array;
    }
    
    /*
     * Function to get the count and list of mutual friends based on parameter
     * 
     * @param int $user_id
     * @param int $friend_id
     * @param boolean $count
     * 
     */
    function getMutualFriends($user_id, $friend_id, $count = TRUE) {

		$User = new User();
		$user_friends_list = array();
		$friend_friends_list_ = array();
		$mutual_friends_id_list = array();

		$user_friends_list = $this->getUserFriendsList($user_id);
		$friend_friends_list = $this->getUserFriendsList($friend_id);

		foreach ($user_friends_list as $user_friend) {
			foreach ($friend_friends_list as $friend_friend) {

				if ($friend_friend['friend_id'] == $user_friend['friend_id']) {
					$mutual_friends_id_list[] = $friend_friend['friend_id'];
				}
			}
		}
		if ($count) {
			$mutual_friends_count = count($mutual_friends_id_list);
			return $mutual_friends_count;
		} else {
			$mutual_friends_list = $User->getFullUserDetails($mutual_friends_id_list);
			$i = 0;
			foreach ($mutual_friends_list as $friend) {
				$privacy = new UserPrivacySettings($friend['User']['id']);
				$diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');
				/**
				 * 'who can see my conditions' privacy setting
				 */
				if (
						$diseaseViewPermittedTo === $privacy::PRIVACY_PRIVATE && $friend['User']['id'] != $user_id
				) {
					$mutual_friends_list[$i][0]['diseases'] = NULL;
				} elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
					$friendStatus = (int) $this->getFriendStatus($user_id, $friend['User']['id']);
					if (($friendStatus != MyFriends::STATUS_CONFIRMED)) {
						$mutual_friends_list[$i][0]['diseases'] = NULL;
					}
				}
			}
			return $mutual_friends_list;
		}
	}
    
    /*
    * Function check if two users are friends
    *
    * @param int $user_id
    * @param int $friend_id
    * 
    * @return boolean
    */
  	function isFriend($user_id, $friend_id ) {
    	$status = $this->getFriendStatus($user_id, $friend_id);
    	
    	if($status == MyFriends::STATUS_CONFIRMED)
    		return true;
    	else 
    		return false;
    	
    }
	
    /**
     * Function to increment peding request count
     * @param int $user_id
     */
    function updatePendingRequestCount($user_id){
    	$pendingFriendsCount = $this->getFriendsStatusCount(
    				$user_id, MyFriends::STATUS_REQUEST_RECIEVED
    		);
    	$user['MyFriends']['pending_request_count'] = $pendingFriendsCount;
    	$this->id =$this->findByMy_id($user_id);
    	$this->save($user);  		
    }   
    
    /**
     * Functin to get pending request count
     * @param int $user_id
     */
    function getPendingRequestCount($user_id) {
		$id =  $this->findByMy_id($user_id);
		return ($id ['MyFriends'] ['pending_request_count']);
	}

	/**
	 * Function to get the confirmed friends ids of a user
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getUserConfirmedFriendsIdList($userId) {
		$userConfirmedFriendsList = array();
		$userFriendsData = $this->getFriendJson($userId);
		if (!empty($userFriendsData)) {
			$userFriendsJSON = $userFriendsData['MyFriends']['friends'];
			$userFriendsList = json_decode($userFriendsJSON, true);
			$userFriends = $userFriendsList['friends'];
			foreach ($userFriends as $userFriend) {
				if ($userFriend['status'] === MyFriends::STATUS_CONFIRMED) {
					$userConfirmedFriendsList[] = $userFriend['user_id'];
				}
			}
		}
		return $userConfirmedFriendsList;
	}

	/**
	 * Function to increment pending request count
	 * 
	 * @param int $userId
	 */
	public function incrementPendingRequestCount($userId) {
		$myFriend = $this->findByMy_id($userId);
		$pendingRequestCount = $myFriend['MyFriends']['pending_request_count'];
		$pendingRequestCount++;
		$this->id = $myFriend['MyFriends']['id'];
		$this->saveField('pending_request_count', $pendingRequestCount);
		$this->__realTimeNotifyUser($userId, $pendingRequestCount);
	}

	/**
	 * Function to realtime notify user on receiving a new friend request
	 * 
	 * Emit 'notify_user' event to users socket for realtime notification
	 * 
	 * @param int $userId
	 * @param int $pendingRequestCount
	 */
	private function __realTimeNotifyUser($userId, $pendingRequestCount) {
		App::import('Vendor', 'elephantio/client');
		$elephant = new ElephantIO\Client(Configure::read('SOCKET.URL'), 'socket.io', 1, false, true, true);
		$elephant->init();
		$elephant->emit('notify_user', array(
			'user_id' => $userId,
			'notification_name' => 'pending_friend_requests_count',
			'notification_count' => $pendingRequestCount
		));
		$elephant->close();
	}

	/**
	 * Function to decrement pending request count
	 * 
	 * @param int $userId
	 */
	public function decrementPendingRequestCount($userId) {
		$myFriend = $this->findByMy_id($userId);
		$pendingRequestCount = $myFriend['MyFriends']['pending_request_count'];
		$pendingRequestCount--;
		$this->id = $myFriend['MyFriends']['id'];
		$this->saveField('pending_request_count', $pendingRequestCount);
		$this->__realTimeNotifyUser($userId, $pendingRequestCount);
	}
        
        /*
         * Function to get patient friend list of user.
         * 
         * @param int $user_id
         * return array
         */
        function getPatientFriendsList ($user_id) {
            $User = new User();
            $my_patient_friends = array();
            $my_friends_details = array();

            $friendsJson = $this->getFriendJson($user_id);

            if(!empty($friendsJson)) {
                $all_friends_list = json_decode($friendsJson['MyFriends']['friends'], TRUE);
                $friends = $all_friends_list['friends'];

                $i = 0;
                foreach ($friends as $my_friend) {
                    $my_friends_details[$i] = array(
                        'friend_id' => $my_friend['user_id'],
                        'friend_status' => $my_friend['status']
                    );
                    $i++;
                }
                $i = 0;
                foreach ($my_friends_details as $val) {
                    if ($val['friend_status'] == MyFriends::STATUS_CONFIRMED) {
                        $friend = $User->find('first', array(
                            'conditions' => array('User.id' => $val['friend_id'] , 'User.type' => self::ROLE_PATIENT)
                                )
                        );
                        if(!empty($friend)) {
                            $my_patient_friends[$i] = array(
                                'friend_name' => Common::getUsername($friend['User']['username'], $friend['User']['first_name'], $friend['User']['last_name']),
                                'friend_id' => $friend['User']['id'],
                                'friend_image' => $friend['User']['profile_picture'],
                                'friend_type' => $friend['User']['type']
                            );
                        }
                        $i++;
                    }
                }
                usort($my_patient_friends, array($this, 'sort_username'));
            } 
            return $my_patient_friends;    
        }
        
        /*
         * Function to sort the friends name.
         * 
         */
        function sort_username($a, $b)
        {
          return strnatcasecmp($a['friend_name'], $b['friend_name']);
	  }

	/**
	 * Function to get the list of ids of friends of friends of a user
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getFriendsofFriends($userId) {
		$friendsOfFriends = array();
		$friends = $this->getUserConfirmedFriendsIdList($userId);
		if (!empty($friends)) {
			$this->User = ClassRegistry::init('User');
			$nonAdminFriends = $this->User->find('list', array(
				'conditions' => array(
					'User.id' => $friends,
					'User.is_admin' => 0,
				)
			));
		}
		if (!empty($nonAdminFriends)) {
			$nonAdminFriendIds = array_keys($nonAdminFriends);
			$friendsFriendsData = $this->findAllByMyId($nonAdminFriendIds);
		}
		if (!empty($friendsFriendsData)) {
			$allFriendsOfFriends = array();
			foreach ($friendsFriendsData as $friendFriendsData) {
				$friendFriendsJSON = $friendFriendsData['MyFriends']['friends'];
				$friendFriendsList = json_decode($friendFriendsJSON, true);
				$friendFriends = $friendFriendsList['friends'];
				foreach ($friendFriends as $friendFriend) {
					if ($friendFriend['status'] === self::STATUS_CONFIRMED) {
						$allFriendsOfFriends[] = $friendFriend['user_id'];
					}
				}
			}

			if (!empty($allFriendsOfFriends)) {
				// remove duplicate values
				$uniqueFriendsOfFriends = array_unique($allFriendsOfFriends);

				// exclude user from the list
				$userIndex = array_search($userId, $uniqueFriendsOfFriends);
				if ($userIndex > -1) {
					unset($uniqueFriendsOfFriends[$userIndex]);
				}

				// exclude user's friends from the list
				$otherFriends = array_diff($uniqueFriendsOfFriends, $friends);
				$friendsOfFriends = array_values(array_filter($otherFriends));
			}
		}
		return $friendsOfFriends;
	}
	
	 /**
    * Function to get full details of my friends
    * @param int $user_id
    *
    * return array
    */
    function getMyFriendsDetails($user_id, $status = self::STATUS_CONFIRMED) {
    	
    	$friends_array = $this->getUserFriendsList($user_id, $status);
    	$friends_id_array = array();
    	$friends_listing = array();
    	if(!empty($friends_array)) {
    		foreach ($friends_array as $friend)
    		{
    			$friends_id_array[] = $friend['friend_id'];
    		}
    	}
    	$User = new User();
    	$friends_listing = $User->getFullUserDetails($friends_id_array);
        
        $i = 0;
        foreach ($friends_listing as $friend) {
            $mutualFriends = $this->getMutualFriends($user_id, $friend['User']['id']);
            $friends_listing[$i]['mutual_friends_count'] = $mutualFriends;
            $i++;
        }
    	return $friends_listing;
    } 
}