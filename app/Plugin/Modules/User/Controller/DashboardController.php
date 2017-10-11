<?php

/**
 * DashboardController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('HealthStatus', 'Utility');

/**
 * DashboardController for the frontend
 * 
 * DashboardController is used for to show user dashboard details
 *
 * @author 		Ajay Arjunan
 * @package 	User
 * @category	Controllers 
 */
class DashboardController extends UserAppController {

    /**
     * Variable to store the minimum dashboard image size
     */
    public $minimumImageSize = array('w' => '582', 'h' => '325');

    public $uses = array('User', 'Event', 'EventMember', 'CommunityMember',
                        'Community', 'MyFriends', 'HealthReading', 'FollowingPage',
                        'PatientDisease', 'Treatment', 'Post', 'Photo', 
                        'NotificationSetting', 'Disease', 'Hashtag', 'TeamMember');
    
    public $components = array(
        'Weather', 'Uploader'
    );

    public function index() {
		
        $currentUserDetails = array();

        $user = $this->Auth->user();
	
        $this->__setMyPhotosTileData($user);
		
        $currentUserDetails['userDetails'] = $this->User->getUsersData($user ['id']);
        $timezone = $this->Auth->user('timezone');
        $treatment = array();

        $this->weatherDetails($user['city'], $timezone);

        $myEvents = $this->getMyEvents($user ['id']);
        $myCommunity = $this->getMyCommunity($user ['id']);
        $myDiseases = $this->PatientDisease->getUserDisease($user ['id']);
        
        $diseaseIds = array();
        
        foreach ($myDiseases as $key => $disease) {
            if(in_array($disease['Diseases']['id'], $diseaseIds)) {
                unset($myDiseases[$key]);
            } else {
                $diseaseIds[] = $disease['Diseases']['id'];
                $disease_names[$key] = $disease['Diseases']['name'];
            }
        }
        
        $diseases = array();
		if (!empty($disease_names)) {
			foreach ($disease_names as $key => $value) {
				$diseases[] = $myDiseases[$key];
			}
		}
        
        $myDiseases = $diseases;
        
        $currentUserDetails['advertisementVideos'] = $this->Disease->getDiseaseAdVideo($diseaseIds);
        
        
        $currentUserDetails['userDiseaseDetails'] = "";
        foreach ($myDiseases as $disease ) {
            if(isset($disease['Diseases']['name'])) {
                $currentUserDetails['userDiseaseDetails'] = $disease['Diseases']['name'];
                break;
            }
        }
        
        $currentUserDetails['userTreatmentDetails'] = "";
        foreach ($myDiseases as $disease ) {
            if(isset($disease['Treatment']['name'])) {
                $currentUserDetails['userTreatmentDetails'] = $disease['Treatment']['name'];
                break;
            }
        }
/*
        $myFrinedsUserIdList = $this->MyFriends->getUserConfirmedFriendsIdList($user ['id']);
        
        $onlineFriends = $this->User->checkOnlineUsers($myFrinedsUserIdList, $user ['id']);
        $onlineFriendsJson = json_encode($onlineFriends);
*/        
        
        $is_same = true;

        $healthStatusList = HealthStatus::getHealthStatusList();
        $showHealthStatusSelector = true;
        $latestHealthStatus = $this->HealthReading->getLatestHealthStatus($this->Auth->user('id'));
        $user_details['feeling'] = HealthStatus::getFeelingSmileyClass($latestHealthStatus['health_status']);
        $user_details['feeling_date'] = CakeTime::format('Y-m-d', $latestHealthStatus['created'], false, $timezone);
        
        /*
         * Get the details to be shown in the Trending tile
         */       
        $trendingTags = $this->Hashtag->getDashboardHashTags($user ['id'], 4);
        shuffle($trendingTags);

        /*
         * Get the Team details to be displayed in the dashboard
         */
        $teamDetails = $this->getMyTeam($user ['id']);
        
        
        $this->set(compact('is_same', 'timezone', 'myEvents', 
                'myCommunity',
                'showHealthStatusSelector', 'healthStatusList', 
                'currentUserDetails', 'myDiseases', 'user_details', 
                    'trendingTags', 'teamDetails'));
        
    }

    /**
     * Function to save the health status of a user
     */
    public function saveUserHealthStatus() {
        $data = $this->request->data;
        if (isset($_GET['health_status'])) {
            $healthStatus = $_GET['health_status'];
            $redirect = true;
            if ($this->Session->check('isFirstLoginToday')) {
                $this->Session->delete('isFirstLoginToday');
            }
        } elseif (isset($data['health_status'])) {
            $healthStatus = $data['health_status'];
        }
        $healthStatusList = array(
            HealthStatus::STATUS_VERY_GOOD,
            HealthStatus::STATUS_GOOD,
            HealthStatus::STATUS_NEUTRAL,
            HealthStatus::STATUS_BAD,
            HealthStatus::STATUS_VERY_BAD
        );
        if (isset($healthStatus) && ($healthStatus > 0) && (in_array($healthStatus, $healthStatusList))) {
            $userId = $this->Auth->user('id');
			$latestHealthStatus = $this->HealthReading->getLatestHealthStatus($userId);
			$currentHealthStatus = $latestHealthStatus['health_status'];
			$comment = (isset($data['health_status_comment'])) ? $data['health_status_comment'] : '';
			$this->HealthReading->addUserHealthStatus($userId, $healthStatus, $comment);
			$clientIp = $this->request->clientIp();
			$postId = $this->Post->addHealthStatusUpdatePost($userId, $clientIp, $healthStatus, $comment);

			if ((int) $currentHealthStatus !== (int) $healthStatus) {
				// notify team members if health status has changed
				$this->__notifyTeamMembersAboutHealthChange($currentHealthStatus, $healthStatus);
			}

			if (isset($redirect)) {
                $this->Session->setFlash('Your health status for today is updated successfully.', 'success');
                $this->redirect('/');
            } else {
                $feelingSmileyClass = HealthStatus::getFeelingSmileyClass($healthStatus);
                echo json_encode(array(
                    'smileyClass' => $feelingSmileyClass,
                    'postId' => $postId
                ));
                $this->layout = 'ajax';
            }
        } else {
            if (isset($redirect)) {
                $this->Session->setFlash('Invalid health status value.', 'error');
                $this->redirect('/');
            }
        }
        $this->autoRender = false;
    }

	/**
	 * Function to notify team members about health status change of patient
	 * 
	 * @param int $healthStatus
	 * @param int $newHealthStatus
	 */
	private function __notifyTeamMembersAboutHealthChange($healthStatus, $newHealthStatus) {
		$userType = (int) $this->Auth->user('type');
		if ($userType === User::ROLE_PATIENT) {
			$patientId = $this->Auth->user('id');
			$data = array(
				'patient_id' => $patientId,
				'health_status' => $healthStatus,
				'new_health_status' => $newHealthStatus
			);
			$this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
			$this->QueuedTask->createJob('PatientHealthChangeNotification', $data);
		}
	}

	/**
     * Function to get myCommunity
     * @param int $userId
     * @return array $myCommunity [ id, name, member_count ]
     */
    public function getMyCommunity($userId, $limit = 0) {
        $myCommunity = $this->CommunityMember->find('all', array(
            'conditions' => array(
                'CommunityMember.user_id' => $userId,
                'CommunityMember.status' => CommunityMember::STATUS_APPROVED
            ),
            'order' => array(
                'CommunityMember.joined_on' => 'desc'
            ),
            'fields' => array(
                'Community.id',
                'Community.name',
                'Community.member_count'
            ),
            'limit' => $limit
        ));

        return $myCommunity;
    }

    /**
     * Function to get myEvents
     * Upcoming events with  STATUS_ATTENDING or STATUS_MAYBE_ATTENDING
     * @param int $userId
     * @return array $myEvents [name, start_date, id]
     */
    function    getMyEvents($userId, $limit = 0) {
        $now = date("Y-m-d H:i:s"); // Get current date and time

        $myEvents = $this->EventMember->find('all', array(
            'joins' => array(
                array(
                    'table' => 'events',
                    'alias' => 'Event',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Event.id = EventMember.event_id'
                    )
                )
            )
            ,
            'conditions' => array(
                'EventMember.user_id' => $userId,
//                'Event.repeat' => 0,
//                'Event.start_date >' => $now,
                array(
                    'OR' => array(
                        array(
                            'Event.repeat_end_type' => 1
                        ),
                        array(
                            'Event.end_date >=' => $now
                        )
                    )
                ),
                array(
                    'OR' => array(
                        array(
                            'EventMember.status' => EventMember::STATUS_ATTENDING
                        ),
                        array(
                            'EventMember.status' => EventMember::STATUS_MAYBE_ATTENDING
                        )
                    )
                )
            ),
            'order' => array(
                'EventMember.created' => 'desc'
            ),
            'fields' => array(
                'Event.name',
                'Event.start_date',
                'Event.id'
            ),
            'group' => array(
                'Event.id'
            ),
            'limit' => $limit
        ));

        return $myEvents;
    }

    /**
     * Function to get weather details of a city
     *
     * @param int $cityId
     * 
     */
    public function weatherDetails($cityId, $timezone) {
        $user = $this->Auth->user();
        $userUnitsSettings = $this->NotificationSetting->getUnitSettings($user ['id']);
        $tempUnit = $userUnitsSettings['temp_unit'];
        $weather = $this->Weather->fetchWeather( $cityId, $timezone, $tempUnit);

        $this->set(compact('weather','tempUnit'));
    }

    public function getOnlineFriendsHTML() {

        $this->autoRender = false;

        $user = $this->Auth->user();
        $myFrinedsUserIdList = $this->MyFriends->getUserConfirmedFriendsIdList($user ['id']);
        $onlineFriends = $this->User->checkOnlineUsers($myFrinedsUserIdList, $user ['id']);

        $this->set(compact('onlineFriends'));
        $view = new View($this, false);
        $HTML = $view->element('Dashboard/online_friends');
        echo ($HTML);
    }

    public function getOnlineFriendsJSON() {

        $this->autoRender = false;

        $user = $this->Auth->user();
        $myFrinedsUserIdList = $this->MyFriends->getUserConfirmedFriendsIdList($user ['id']);
        $onlineFriends = $this->User->checkOnlineUsers($myFrinedsUserIdList, $user ['id']);
        $onlineFriendsJson = json_encode($onlineFriends);

        echo ($onlineFriendsJson);
    }

    /**
     * Function to set the my photos (Sonya touch) tile data on the view
     * 
     * @param array $user
     */
    private function __setMyPhotosTileData($user) {
        $userPhotos = $this->Photo->getUserDashboardPhotos($user['id']);
        $photos = array();
        $defaultPhotoId = 0;
        if (!empty($userPhotos)) {
                $photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/dashboard_image/';
                foreach ($userPhotos as $userPhoto) {
                        $photoId = $userPhoto['Photo']['id'];
                        $photo = $photoPath . $userPhoto['Photo']['file_name'];
                        $photos[] = array(
                                'id' => $photoId,
                                'src' => $photo,
                        );
                        if (intval($userPhoto['Photo']['is_default']) === 1) {
                                $defaultPhoto = $photo;
                                $defaultPhotoId = $photoId;
                        }
                }
                if (!isset($defaultPhoto)) {
                        $defaultPhoto = $photos[0]['src'];
                }
        } else {
                $defaultPhoto = '/theme/App/img/dashboard_default.jpg';
        }

        // set data on view
        if (isset($user['is_dashboard_slideshow_enabled'])) {
                $isSlideShowEnabled = $user['is_dashboard_slideshow_enabled'];
        } else {
                $isSlideShowEnabled = User::DASHBOARD_SLIDESHOW_DISABLED;
        }
        $this->request->data['User']['is_dashboard_slideshow_enabled'] = $isSlideShowEnabled;
        $this->request->data['User']['default_photo_id'] = $defaultPhotoId;
        $this->request->data['User']['default_photo'] = $defaultPhoto;
        $this->set(compact('photos', 'defaultPhoto', 'defaultPhotoId'));
    }

    /**
     * Function to save the user's dashboard slideshow enabled/disabled status
     */
    public function saveImageSettings() {
            $result = array();
            $userId = $this->Auth->user('id');
            $status = $this->request->data['User']['is_dashboard_slideshow_enabled'];
            if ($this->User->saveUserDashboardSlideshowStatus($userId, $status)) {
                    $result['success'] = true;
                    $this->Session->write('Auth.User.is_dashboard_slideshow_enabled', $status);
            }

            $defaultImgName = '';
            if (intval($status) === User::DASHBOARD_SLIDESHOW_DISABLED) {
                    if ($this->request->data['User']['default_photo_id'] > 0) {
                            $defaultPhotoId = $this->request->data['User']['default_photo_id'];
                            $this->Photo->unsetUserDashboardDefaultPhoto($userId);
                            $this->Photo->makePhotoDefault($defaultPhotoId);
                    } elseif ($this->request->data['User']['default_photo'] !== '') {
                            $defaultImgSrc = $this->request->data['User']['default_photo'];
                            $defaultImgParts = explode('/', $defaultImgSrc);
                            $defaultImgRawName = end($defaultImgParts);
                            $defaultImgName = substr($defaultImgRawName, 0, strpos($defaultImgRawName, '?'));
                    }
            }

            if (isset($this->request->data['User']['images'])) {
                    $images = $this->request->data['User']['images'];
                    $photos = $this->Photo->addDashboardPhotos($images, $userId, $defaultImgName);
                    if (!isset($defaultPhotoId)) {
                            $defaultPhoto = $this->Photo->getUserDashboardDefaultPhoto($userId);
                            if (!empty($defaultPhoto)) {
                                    $defaultPhotoId = $defaultPhoto['Photo']['id'];
                            }
                    }
                    if (!empty($photos)) {
                            $result = array(
                                    'success' => true,
                                    'photos' => $photos
                            );
                    }
            }

            if (isset($defaultPhotoId)) {
                    $result['defaultPhotoId'] = $defaultPhotoId;
            }

            if (isset($this->request->data['User']['deleted_photos'])) {
                    $photoList = $this->request->data['User']['deleted_photos'];
                    if ($this->Photo->deletePhotos($photoList)) {
                            $result['deletedPhotos'] = true;
                    }
            }

            $this->autoRender = false;
            echo json_encode($result);
    }

    /**
     * Function to upload dashboard image to a temporary folder
     */
    public function uploadPhoto() {
            $this->autoRender = false;

            $uploadPath = Configure::read('App.UPLOAD_PATH');
            $uploadUrl = Configure::read('App.UPLOAD_PATH_URL');

            $uploader = new $this->Uploader();
            $uploader->allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
            $uploader->inputName = 'qqfile';
            $uploader->chunksFolder = 'chunks';

            $method = $_SERVER['REQUEST_METHOD'];
            if ($method === 'POST') {
                    header('Content-Type: text/plain');
                    $result = $uploader->handleUpload($uploadPath);
                    $fileName = $uploader->getUploadName();
                    $imgPath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;

                    // image dimension
                    list($imageWidth, $imageHeight) = getimagesize($imgPath);

                    $result['imageWidth'] = $imageWidth;
                    $result['imageHeight'] = $imageHeight;
                    $result['fileName'] = $fileName;
                    $result['fileUrl'] = $uploadUrl . '/tmp/' . $fileName;

                    /*
                     * If not a valid image format
                     */
                    if(!exif_imagetype($imgPath)) {
                        unset($result);
                        $result['image_type_error'] = true;
                    }

                    echo json_encode($result);
            } else {
                    header('HTTP/1.0 405 Method Not Allowed');
            }
            exit;
    }

    public function cropPhoto() {
            $this->autoRender = false;
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method === 'POST') {
                    try {
                            $uploadPath = Configure::read('App.UPLOAD_PATH');
                            $uploadUrl = Configure::read('App.UPLOAD_PATH_URL');
                            $data = $this->request->data;

                            $x1 = $data["x1"];
                            $y1 = $data["y1"];
                            $width = $data["w"];
                            $height = $data["h"];

                            if ($width <= 0) {
                                    $width = $this->minimumImageSize['w'];
                                    $x1 = 0;
                            }

                            if ($height <= 0) {
                                    $height = $this->minimumImageSize['h'];
                                    $y1 = 0;
                            }

                            if ($width > 0 && $height > 0) {
                                    App::import('Vendor', 'ImageTool');
                                    $fileName = $data['fileName'];
                                    $imgPath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;
                                    $cropOptions = array(
                                            'input' => $imgPath,
                                            'output' => $imgPath,
                                            'width' => $width,
                                            'height' => $height,
                                            'enlarge' => false,
                                            'keepRatio' => false,
                                            'paddings' => false,
                                            'output_width' => $this->minimumImageSize['w'],
                                            'output_height' => $this->minimumImageSize['h'],
                                            'top' => $y1,
                                            'left' => $x1,
                                    );
                                    ImageTool::crop($cropOptions);



                                    $result['success'] = true;
                                    $result['fileUrl'] = $uploadUrl . '/tmp/' . $fileName;
                                    $result['fileName'] = $fileName;
                            } else {
                                    throw new Exception("Image Not cropped");
                            }
                    } catch (Exception $e) {
                            $result['success'] = false;
                            $result['message'] = $e->getMessage();
                    }

                    $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
                    echo $result;
            } else {
                    header('HTTP/1.0 405 Method Not Allowed');
            }
            exit;
    }

    /**
     * Function to get chat message data
     */
    public function getChatMessageData() {
            $data = $this->request->data;
            $fromUserId = $data['from'];
            $fromUser = $this->User->findById($fromUserId);
            $timezone = $this->Auth->user('timezone');
            $userThumb = Common::getUserThumb($fromUserId, $fromUser['User']['type'], 'x_small', 'media-object');
            $message = str_replace('<br/>', ' ', $data['message']);

//                '/<img[^>]+src\s*=\s*["\']?([^"\' ]+)[^>]*>/'                
            preg_match_all( '/<img[^>]+src\s*=\s*["\']?([^"\' ]+)(.)(gif)[^>]*>/', $message, $extracted_data );
            if(isset($extracted_data[1]) && !empty($extracted_data[1])) {
                $extracted_images = $extracted_data[1];
                foreach ($extracted_images as $image) {
                    $message = str_replace($image.'.gif', $image.'.png', $message);
                }
            }

            $message = String::truncate(String::stripLinks($message), 55, array('exact' => true, 'html' => true));
            $sent = CakeTime::format($data['sent'], "%l.%M %P", false, $timezone);

            $result = array(
                    'userId' => $fromUserId,
                    'name' => $fromUser['User']['username'],
                    'message' => $message,
                    'sent' => $sent,
                    'userThumb' => $userThumb
            );

            $this->autoRender = false;
            $view = new View($this, false);
            $view->set($result);
            echo $view->element('Dashboard/Photos/chat_notification');
    }
        
    /**
     * Function to get team list to be displayed in Dashboard
     * @param int $userId
     * @return array
     */
    public function getMyTeam($userId, $limit = 0) {

        return $this->TeamMember->getUserApprovedTeams($userId);
    }        
}