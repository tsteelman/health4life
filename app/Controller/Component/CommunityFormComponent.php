<?php

/**
 * CommunityFormComponent class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('Community', 'Model');
App::uses('Date', 'Utility');
App::import('Controller', 'Api');
App::import('Vendor', 'ImageTool');

/**
 * CommunityFormComponent for adding communities.
 * 
 * This class is used for community add form display and save.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Controller.Component
 * @category	Component 
 */
class CommunityFormComponent extends Component {

    /**
     * Constructor
     * 
     * Initialises the models
     */
    public function __construct() {
        $this->Community = ClassRegistry::init('Community');
        $this->CommunityMember = ClassRegistry::init('CommunityMember');
        $this->CommunityDisease = ClassRegistry::init('CommunityDisease');
		$this->Post = ClassRegistry::init('Post');
        $this->Country = ClassRegistry::init('Country');
		$this->FollowingPage = ClassRegistry::init('FollowingPage');
    }

    /**
     * Initialises the component
     * 
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
        $this->controller = $controller;
        $this->user = $controller->Auth->user();
    }

    /**
     * Sets the community form data on the controller
     */
    public function setFormData($communityId = null) {
        $formId = 'communityForm';
        $inputDefaults = array(
            'label' => false,
            'div' => false,
            'class' => 'form-control'
        );

        // current logged in user
        $user = $this->user;

        // community type
        $communityTypes = Community::getCommunityTypes();
		$communityTypeHintList = $list = array(
			__('Open community will be open for anyone to join.'),
			__('Closed community will be available only to invitees.')
		);
		if ($this->user['is_admin']) {
			$communityTypeHintList[] = __('Site wide community will be open for anyone to join.');
		} else {
			unset($communityTypes[Community::COMMUNITY_TYPE_SITE]);
		}
        $defaultType = Community::COMMUNITY_TYPE_OPEN;

        // location
        $countries = $this->Country->getAllCountries();
        $states = array();
        $cities = array();
        $stateDisabled = true;
        $cityDisabled = true;

        // for implementing search in friends list.
        $Api = new ApiController;
        $friendsList = $Api->getFriendList($user['id']);

        $title = __('Create Community');
        $submitBtnTxt = __('Create');
        if (!is_null($communityId) && $communityId > 0) {
            $title = __('Update Community');
            $submitBtnTxt = __('Update');

            // if edit community, set the already invited members as active by default
            $communityMembers = $this->CommunityMember->getCommunityMemberIds($communityId);
            $friendsListStatus = array();

            if (!empty($friendsList)) {
                foreach ($friendsList as $friend) {
                    foreach ($communityMembers as $member) {
                        if ($friend ['friend_id'] == $member ['CommunityMember'] ['user_id']) {
                            $friend ['status'] = 'invited';
                            break 1;
                        } else {
                            $friend ['status'] = 'not invited';
                        }
                    }
                    $friendsListStatus [] = $friend;
                }
                $friendsList = $friendsListStatus;
            }
        }
        $friendsListJson = json_encode(array('friends' => array('friend' => $friendsList)));
		
		// show common/site wide step3
		$step3SiteWideVisibilityClass = 'hide';
		$step3CommonVisibilityClass = '';

		// set data on controller
		$this->controller->set(compact('friendsListJson', 'friendsList', 'user', 'formId', 'inputDefaults', 'communityTypes', 'defaultType', 'countries', 'states', 'cities', 'stateDisabled', 'cityDisabled', 'title', 'submitBtnTxt', 'communityTypeHintList', 'step3SiteWideVisibilityClass', 'step3CommonVisibilityClass'));

        // set disease JSON on controller
        $this->__setDiseaseJSON();

        // validation
        $model = 'Community';
        $validations = $this->$model->validate;
        $this->controller->JQValidator->addValidation($model, $validations, $formId);
    }

    /**
     * Function to set disease JSON on controller
     * 
     * (This JSON will be used in the view for disease autocomplete search)
     */
    private function __setDiseaseJSON() {
        $diseaseModel = ClassRegistry::init('Disease');
        $diseaseJSON = $diseaseModel->getDiseaseJSON();
        $this->controller->set('diseaseJSON', $diseaseJSON);
    }

    /**
     * Saves a community
     */
    public function saveCommunity() {
        $userId = $this->user['id'];
        $postData = $this->controller->request->data;
        $communityPostData = $postData['Community'];
        $communityData = array();

        $this->isNewRecord = true;
        if (isset($communityPostData['id']) && $communityPostData['id'] > 0) {
            $communityData['id'] = $communityPostData['id'];
            $this->isNewRecord = false;
        }

        // location
        $communityData['country'] = $communityPostData['country'];
        $communityData['state'] = $communityPostData['state'];
        $communityData['city'] = $communityPostData['city'];
        $communityData['zip'] = $communityPostData['zip'];

		// community created user id
		$communityData['created_by'] = $userId;

		if ($this->isNewRecord === true) {
            // set member count as 1 by default
            $communityData['member_count'] = 1;
        }

        // other details
        $communityData['name'] = $communityPostData['name'];
        $communityData['description'] = $communityPostData['description'];
        $communityData['type'] = $communityPostData['type'];
        $communityData['member_can_invite'] = $communityPostData['member_can_invite'];
        $communityData['tags'] = $communityPostData['tags'];
        
        // save data
        if ($this->Community->save($communityData, array('validate' => false))) {
            $msgTxt = ($this->isNewRecord) ? __('created') : __('updated');
            $message = "The community has been {$msgTxt} successfully";
            $communityId = $this->Community->id;
            $this->__saveCommunityDiseases($communityId);
            $this->__saveCommunityImage($communityId, $communityPostData['image']);
            $communityData['id'] = $communityId;
            if ($this->isNewRecord === true) {
				$this->CommunityMember->addCommunityAdmin($communityId, $userId);
				
				//Community follow data
				$followCommunityData = array(
					'type' => FollowingPage::COMMUNITY_TYPE,
					'page_id' => $communityId,
					'user_id' => $userId,
					'notification' => FollowingPage::NOTIFICATION_ON
				);
				$this->FollowingPage->followPage($followCommunityData);
				
				$this->__addNewCommunityPost($communityData);
				if (intval($communityData['type']) === Community::COMMUNITY_TYPE_SITE) {
					// add site wide community notification task to job queue
					ClassRegistry::init('Queue.QueuedTask')->createJob('SiteWideCommunityNotification', $communityData);
				}
			} else {
                $this->__updateCommunityPosts($communityData);
                if ($communityData['type'] == Community::COMMUNITY_TYPE_OPEN) {
                    // update all not approved members to approved.
                    $this->CommunityMember->approveAllMembers($communityId);
                }
            }

			$communityType = intval($communityData['type']);
			if ($this->isNewRecord === false && $communityType === Community::COMMUNITY_TYPE_CLOSED && !empty($this->diseases)) {
				$this->Post->deleteCommunityDiseasesPosts($communityId, $this->diseases);
			} else {
				if (!empty($this->diseases) && ($communityType !== Community::COMMUNITY_TYPE_CLOSED)) {
					$this->__addDiseaseCommunityPosts($communityData);
				}
			}

			if (isset($postData['friend_id'])) {
                $Api = new ApiController;
                $Api->constructClasses();
                $Api->inviteMembersToCommunity($communityId, $postData['friend_id'], $userId);
                $message .= __(' and the invitees have been notified');
            }

            $message .= '.';
            $this->controller->Session->setFlash($message, 'success');
            
            if(isset($communityPostData['refer']) && (substr($communityPostData['refer'], 0, 9) == 'condition')) {
                preg_match('~index/(.*?)/communities~', $communityPostData['refer'], $disease_id);
                return $this->controller->redirect('/community/details/index/' . $communityId . '?f=' . $disease_id[1] );
            }
            
            return $this->controller->redirect('/community/details/index/' . $communityId);
        }
    }

    /**
     * Function to add a post indicating that a new community is added
     * 
     * @param array $communityData
     */
    private function __addNewCommunityPost($communityData) {
        $clientIp = $this->controller->request->clientIp();
        $this->Post->addNewCommunityPost($communityData, $clientIp);
    }

    /**
     * Function to update posts of a community
     * 
     * @param array $communityData
     */
    private function __updateCommunityPosts($communityData) {
        $this->Post->updateCommunityPosts($communityData);
    }

	/**
	 * Function to add posts in disease page about community
	 * 
	 * @param array $communityData
	 */
	private function __addDiseaseCommunityPosts($communityData) {
		$clientIp = $this->controller->request->clientIp();
		$newDiseasePosts = $this->Post->addDiseaseCommunityPosts($communityData, $clientIp, $this->diseases);
		if (!empty($newDiseasePosts)) {
			$this->__realtimeNotifyNewDiseaseCommunityPosts($newDiseasePosts);
		}
	}

	/**
	 * Function to realtime notify disease following rooms about new community
	 * 
	 * @param array $newDiseasePosts
	 */
	private function __realtimeNotifyNewDiseaseCommunityPosts($newDiseasePosts) {
		try {
			App::import('Vendor', 'elephantio/client');
			$elephant = new ElephantIO\Client(Configure::read('SOCKET.URL'), 'socket.io', 1, false, true, true);
			$elephant->init();
			foreach ($newDiseasePosts as $diseasePost) {
				$diseaseId = $diseasePost['diseaseId'];
				$postId = $diseasePost['postId'];
				$rooms = $this->FollowingPage->getDiseasePostFollowingRooms($diseaseId);
				foreach ($rooms as $room) {
					$elephant->emit('new_post', array(
						'room' => $room,
						'postId' => $postId
					));
				}
			}
			$elephant->close();
		} catch (Exception $e) {
			return;
		}
	}

	/**
	 * Function to save community image to a permanent folder
     * 
     * @param int $communityId
     * @param string $imageName
     * @return array
     * @throws Exception
     */
    private function __saveCommunityImage($communityId, $imageName) {
        $result['success'] = false;
        try {
            if (isset($imageName) && !empty($imageName)) {

                $uploadPath = Configure::read("App.UPLOAD_PATH");
                $thumbnailPath = Configure::read("App.COMMUNITY_IMG_PATH");
                $tmpImage = $uploadPath . DIRECTORY_SEPARATOR . $imageName;

                if (file_exists($tmpImage)) {
                    $tmpFile = new File($tmpImage);
                    if (!file_exists($thumbnailPath)) {
                        mkdir($thumbnailPath, 0777);
                    }

                    /*
					 * Move the cropped image to permanent folder
					 */
                    $originalThumb = $thumbnailPath . DIRECTORY_SEPARATOR . Common::getCommunityThumbName($communityId);
                    $tmpFile->copy($originalThumb, true);

                    /*
                     * remove the initial image
                     */
                    $tmpFile->delete();

                    $result['success'] = true;
                } else {
                    throw new Exception("Uploaded file does not exist");
                }
            }
        } catch (Exception $e) {
            $result['success'] = false;
            $result['msg'] = $e->getMessage();
        }

        return $result;
    }

	/**
	 * Saves community diseases
	 * 
	 * @param int $communityId
	 */
	private function __saveCommunityDiseases($communityId) {
		$updatedDiseaseIdArray = array();
		$oldDiseaseIdArray = array();

		// if edit community, save existing diseases in temporary variable
		if (!$this->isNewRecord) {
			$existingDiseases = $this->CommunityDisease->findAllByCommunityId($communityId);
		}

		// save edited and new diseases
		if (isset($this->controller->request->data['CommunityDisease'])) {
			$communityDiseases = $this->controller->request->data['CommunityDisease'];
			foreach ($communityDiseases as $communityDisease) {
				if ($communityDisease['disease_id'] > 0) {
					// data to be saved
					$diseaseId = $communityDisease['disease_id'];
					$communityDiseaseId = isset($communityDisease['id']) ? $communityDisease['id'] : null;
					$data[] = array(
						'id' => $communityDiseaseId, // to update existing
						'disease_id' => $diseaseId,
						'community_id' => $communityId,
					);
					$updatedDiseaseIdArray[] = $diseaseId;
				}
			}

			// save multiple records
			if (!empty($data)) {
				$this->diseases = $updatedDiseaseIdArray;
				$this->CommunityDisease->saveMany($data, array('validate' => false));
			}
		}

		// if edit community, delete removed diseases
		if (isset($existingDiseases)) {
			if (!empty($existingDiseases)) {
				foreach ($existingDiseases as $communityDisease) {
					$oldDiseaseId = $communityDisease['Disease']['id'];
					$oldDiseaseIdArray[] = $oldDiseaseId;
					if (!empty($updatedDiseaseIdArray)) {
						if (!in_array($oldDiseaseId, $updatedDiseaseIdArray)) {
							$deletedDiseaseIdArray[] = $oldDiseaseId;
						}
					} else {
						$deletedDiseaseIdArray[] = $oldDiseaseId;
					}
				}

				if (!empty($deletedDiseaseIdArray)) {
					$this->CommunityDisease->deleteAll(array(
						'Community.id' => $communityId,
						'Disease.id' => $deletedDiseaseIdArray), false);
					$this->Post->deleteCommunityDiseasesPosts($communityId, $deletedDiseaseIdArray);
				}
			}
		}
	}
}