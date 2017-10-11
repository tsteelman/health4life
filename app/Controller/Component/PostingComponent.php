<?php

/**
 * PostingComponent class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('Post', 'Model');
App::uses('Common', 'Utility');
App::uses('Date', 'Utility');
App::uses('UserPrivacySettings', 'Lib');
App::uses('Photo', 'Model');

/**
 * PostingComponent for handling posting.
 *
 * This class is used to handle posting and related functionalities.
 *
 * @author 	Greeshma Radhakrishnan
 * @package 	Controller.Component
 * @category	Component
 */
class PostingComponent extends Component {

    const DISABLED_ATTR = 'disabled';
    const LATEST_COMMENTS_COUNT = 3;
    const POSTS_PER_PAGE = 15;
    const ANSWERS_LIMIT = 5;
    const COMMENTS_LIMIT = 5;
    const LATEST_ANSWERS_COUNT = 3;
    const IS_DELETED = 1;
    const NOT_DELETED = 0;

    /**
     * Filter values
     */
    const MOST_RECENT_FILTER = 0;
    const MOST_ACTIVE_FILTER = 1;
    const MY_ACTIVITY_FILTER = 2;
    const POLL_FILTER = 3;
    const TEXT_FILTER = 4;
    const LINKS_FILTER = 5;
    const IMAGES_FILTER = 6;
    const VIDEOS_FILTER = 7;
    const HEALTH_STATUS_FILTER = 8;
    const NEW_UPDTES_FILTER = 9;

    /**
     * Error message shown on doing some action on a deleted post
     */
    const POST_DELETED_ERROR = 'Oops... This post seems to be deleted!';

    public $hasLikePermission = false;
    public $hasCommentPermission = false;

    /**
     * Current user permissions in visited profile
     * 
     * @var array 
     */
    protected $_permissions = array();

    /**
     * Other components used by this component
     * 
     * @var array
     */
    public $components = array('EmailTemplate', 'EmailQueue', 'HashTag');

    /**
     * Constructor
     *
     * Initialises the models
     */
    public function __construct() {
        $this->Post = ClassRegistry::init('Post');
        $this->Poll = ClassRegistry::init('Poll');
        $this->PollChoices = ClassRegistry::init('PollChoices');
        $this->PollVote = ClassRegistry::init('PollVote');
        $this->Comment = ClassRegistry::init('Comment');
        $this->Like = ClassRegistry::init('Like');
        $this->User = ClassRegistry::init('User');
        $this->Community = ClassRegistry::init('Community');
        $this->Event = ClassRegistry::init('Event');
        $this->Disease = ClassRegistry::init('Disease');
        $this->MyFriends = ClassRegistry::init('MyFriends');
        $this->Media = ClassRegistry::init('Media');
        $this->Notification = ClassRegistry::init('Notification');
        $this->AbuseReport = ClassRegistry::init('AbuseReport');
        $this->Team = ClassRegistry::init('Team');
        $this->Photo = ClassRegistry::init('Photo');
        $this->FollowingPage = ClassRegistry::init('FollowingPage');
        $this->Answer = ClassRegistry::init('Answer');
        $this->HealthReading = ClassRegistry::init('HealthReading');
        $this->PatientDisease = ClassRegistry::init('PatientDisease');
    }

    /**
     * This function is called after the controller's beforeFilter 
     * method but before the controller executes the current action handler.
     * 
     * @param Controller $controller 
     */
    public function startup(Controller $controller) {
        foreach ($this->components as $componentName) {
            App::import('Component', $componentName);
            $componentClass = "{$componentName}Component";
            $this->$componentName = new $componentClass(new ComponentCollection());
            $this->$componentName->startup($controller);
        }
    }

    /**
     * Initialises the component
     *
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
        $this->controller = $controller;
        $this->clientIp = $controller->request->clientIp();
        $user = $controller->Auth->user();
        $this->user = $user;
        $this->currentUserId = (int) $user['id'];
        $this->user['has_anonymous_permission'] = $this->User->getUserAnonymousPermission($user['id']);
    }

    /**
     * Function to set data on the posting form
     *
     * @param array $options
     */
    public function setFormData($options = array()) {
        $formId = 'postForm';
        $inputDefaults = array(
                'label' => false,
                'div' => false,
                'class' => 'form-control'
        );
        $placeHolderText = 'Type something';
        $isCommunityPost = false;
        $form = ClassRegistry::init('PostForm');

        if (isset($options['community_id'])) {
                $communityId = $options['community_id'];
                $defaultData = array(
                        'posted_in' => $options['community_id'],
                        'posted_in_type' => Post::POSTED_IN_TYPE_COMMUNITIES
                );
                $isCommunityPost = true;
                $placeHolderText = 'Add more text';
        } elseif (isset($options['event_id'])) {
                $eventId = $options['event_id'];
                $defaultData = array(
                        'posted_in' => $options['event_id'],
                        'posted_in_type' => Post::POSTED_IN_TYPE_EVENTS
                );
        } elseif (isset($options['user_id'])) {
                $defaultData = array(
                        'posted_in' => $options['user_id'],
                        'posted_in_type' => Post::POSTED_IN_TYPE_USERS
                );
        } elseif (isset($options['disease_id'])) {
                $defaultData = array(
                        'posted_in' => $options['disease_id'],
                        'posted_in_type' => Post::POSTED_IN_TYPE_DISEASES
                );
        } elseif (isset($options['team_id'])) {
                $defaultData = array(
                        'posted_in' => $options['team_id'],
                        'posted_in_type' => Post::POSTED_IN_TYPE_TEAM
                );
        }

        $defaultData['posted_in_room'] = $defaultData['posted_in_type'] . "/" . $defaultData['posted_in'];

        // check anonymous posting permission
        $showAnonymousCheckbox = $this->user['has_anonymous_permission'];

        /*
         * Set the eCard images
         */
        $eCardImages = $this->getEcardImages();

        $model = 'Post';
        $validations = $form->validate;
        $this->controller->JQValidator->addValidation($model, $validations, $formId);
        $this->controller->request->data = array('Post' => $defaultData);
        $this->controller->set(compact('formId', 'inputDefaults', 
                'isCommunityPost', 'placeHolderText', 
                'showAnonymousCheckbox', 'followingUsers', 
                'taggedDiseases', 'eCardImages'));
    }

    /**
     * Creates a post
     *
     * @return array
     */
    public function createPost() {
        $requestData = $this->controller->request->data;
        if (isset($requestData['Post'])) {
            $postData = $requestData['Post'];
            $contentData = array();
            $mediaIdArray = array();

            /*
             * Check if a valid Post type is coming
             */
            if(!in_array($postData['post_type'], Post::getAllPostType())) {
                $error = true;
                $errorMessage = __('Invalid post type selected!');
                $errorType = 'fatal';                    
            }


            // check for posting permission
            $postPermission = true;
            if ($postData['posted_in_type'] == Post::POSTED_IN_TYPE_USERS) {
                if ($this->currentUserId != $postData['posted_in']) {
                    $privacy = new UserPrivacySettings($postData['posted_in']);
                    $settings = array($privacy::PRIVACY_PUBLIC);
                    $isFriend = $this->MyFriends->getFriendStatus($postData['posted_in'], $this->currentUserId);
                    if ($isFriend == MyFriends::STATUS_CONFIRMED) {
                            array_push($settings, $privacy::PRIVACY_FRIENDS);
                    }
                    if (!in_array($privacy->__get('post_on_wall'), $settings)) {
                            $postPermission = false;
                    }
                }
                $userId = $this->currentUserId;
                $profileId = $postData['posted_in'];
                $permissions = $this->User->getUserPermissionsInProfile($userId, $profileId);
                if ($postPermission === true) {
                        $postPermission = $permissions['messaging'];
                }
            }
            if ($postPermission === false) {
                $error = true;
                $errorMessage = __('You dont have permission to post on this profile!');
                $errorType = 'fatal';
            } elseif (isset($postData['is_anonymous']) && ((bool) $postData['is_anonymous'] === true)) {
                $anonymousPostPermission = $this->user['has_anonymous_permission'];
                if ($anonymousPostPermission === true && isset($permissions['anonymous_messaging'])) {
                        $anonymousPostPermission = $permissions['anonymous_messaging'];
                }
                if ($anonymousPostPermission === false) {
                        $error = true;
                        $errorMessage = __('You dont have permission to post anonymously');
                        if ($postData['posted_in_type'] == Post::POSTED_IN_TYPE_USERS) {
                                $errorMessage .= __(' on this profile!');
                        } else {
                                $errorMessage .= __('!');
                        }
                        $errorType = 'fatal';
                }
            }

            if (!isset($postData['is_anonymous'])) {
                    $postData['is_anonymous'] = false;
            }

            // data to be saved
            $data = array(
                'post_by' => $this->currentUserId,
                'ip' => $this->clientIp,
                'posted_in' => $postData['posted_in'],
                'posted_in_type' => $postData['posted_in_type'],
                'post_type' => $postData['post_type'],
                'is_anonymous' => $postData['is_anonymous']
            );

            if (isset($postData['title']) && ($postData['title'] !== '')) {
                $contentData['title'] = $postData['title'];
            }
            if (isset($postData['description']) && ($postData['description'] !== '')) {
                $contentData['description'] = $postData['description'];
            }
            if ((bool) $postData['is_anonymous'] === false) {
                $latestHealthStatus = $this->HealthReading->getLatestHealthStatus($this->currentUserId);
                if (!empty($latestHealthStatus)) {
                    $contentData['health_status'] = $latestHealthStatus['health_status'];
                }
            }
            
            switch ($postData['post_type']) {
                case Post::POST_TYPE_BLOG:
                    $contentData['title'] = $postData['title'];
                    /*
                     * Save all the images coming in the content
                     */
                    $postContent = $postData['description'];
                    preg_match_all('/< *img[^>a-zA-Z0-9=]*src *= *["\']?([^"\']*)/i',
                            $postData['description'], $img_tags);
                    if(is_array($img_tags) && !empty($img_tags)) {
                        $index = 0;
                        foreach($img_tags[1] as $imageData) {
                            
                            $fileName = Common::uploadSavePhoto($imageData);
                            $filePath = "/uploads/post_photos/".$fileName;
                            $photos[] = $fileName;
                            $postContent = str_replace($imageData,
                                    $filePath, $postContent);
                            $index++;
                        }
                        
                        $contentData['description'] = $postContent;
                        
                        if(!empty($photos)) {
                        
                            $savedPhotos = $this->Photo->saveBlogPhotos($photos, 
                            $this->currentUserId,
                            $postData['posted_in_type'],
                            $postData['posted_in']);    
                        }
                        
                    }
                                     
                    
                    break;
                
                case Post::POST_TYPE_ECARD:
                    $contentData['title'] = $postData['ecard_title'];
                    $contentData['ecards'] = trim($postData['ecard_selected'], ",");
                    $data['posted_in'] = $postData['ecard_to_userid'];
                    break;                
            }
            
            if (isset($postData['link_url']) && 
                    ($postData['link_url'] !== '')) {
                $contentData['additional_info'] = array(
                    'link_title' => $postData['link_title'],
                    'link_url' => $postData['link_url'],
                    'link_page_url' => $postData['link_page_url'],
                    'link_cannonical_url' => $postData['link_cannonical_url'],
                    'link_description' => $postData['link_description'],
                    'link_image' => $postData['link_image'],
                    'link_video' => $postData['link_video'],
                    'link_video_iframe' => $postData['link_video_iframe']
                );
            } elseif (isset($postData['Photo']) && 
                    !empty($postData['Photo'])) {
                $photos = $postData['Photo'];
                $savedPhotos = $this->Photo->savePostPhotos($photos, 
                        $this->currentUserId,
                        $postData['posted_in_type'],
                        $postData['posted_in']);
                if (is_array($savedPhotos) && !empty($savedPhotos)) {
                        $data['post_type_id'] = join(",", $savedPhotos);
                } else {
                        $error = true;
                        $errorMessage = __('No photos to upload');
                }
            } elseif (isset($postData['video_file_name']) && 
                    ($postData['video_file_name'] !== '')) {
                $videoFileName = $postData['video_file_name'];
                $videoInfo = $this->uploadVideo($videoFileName);
                if ($videoInfo === false) {
                        $error = true;
                        $errorMessage = __('Failed to upload video.');
                } else {
                        $userId = $this->currentUserId;
                        $mediaId = $this->Media->addVideo($videoInfo, $userId);
                        if ($mediaId > 0) {
                                array_push($mediaIdArray, $mediaId);
                                $videoInfo['media_id'] = $mediaId;
                                $contentData['additional_info']['video'] = $videoInfo;
                        }
                }
            } elseif (isset($requestData['Post']['poll_options']) && 
                    $requestData['Post']['poll_options'][0] != NULL) {
                $pollResult = $this->createPoll($requestData['Post']);
                if (!isset($pollResult['error'])) {
                        $data['post_type_id'] = $pollResult['poll_id'];
                } else {
                        $error = true;
                        $errorMessage = __($pollResult['error_message']);
                }
            }

            $data['content'] = json_encode($contentData);

            if (!empty($mediaIdArray)) {
                $mediaIdList = join(',', $mediaIdArray);
                $data['post_type_id'] = $mediaIdList;
            }

            // if the post is created in a community, check if community exists
            if ($data['posted_in_type'] == Post::POSTED_IN_TYPE_COMMUNITIES) {
                $communityId = $postData['posted_in'];
                if (!$this->Community->exists($communityId)) {
                        $error = true;
                        $errorMessage = __('This community is deleted!');
                        $errorType = 'fatal';
                }
            }
            // if the post is created in an event, check if event exists
            elseif ($data['posted_in_type'] == Post::POSTED_IN_TYPE_EVENTS) {
                $eventId = $postData['posted_in'];
                if (!$this->Event->exists($eventId)) {
                        $error = true;
                        $errorMessage = __('This event is deleted!');
                        $errorType = 'fatal';
                }
            }
			
            if (!isset($error)) {
				
                /*
                 * Validating the inputs of blog and e-Card
                 */
                switch ($postData['post_type']) {
                        case Post::POST_TYPE_BLOG:
                                        if(!isset($contentData['title']) || $contentData['title'] == '') {
                                                $error = true;
                                                $errorMessage = __('Please specify a title for the blog!');
                                        } else if(!isset($contentData['description']) || $contentData['description'] == '') {
                                                $error = true;
                                                $errorMessage = __('Please provide the blog content!');
                                        }
                                        break;

                        case Post::POST_TYPE_ECARD:
                                        if(!isset($data['posted_in'])|| $data['posted_in'] == '') {
                                                $error = true;
                                                $errorMessage = __('Please specify a proper username to send e-Card!');
                                        } else if(!isset($contentData['ecards']) || $contentData['ecards'] == '') {
                                                $error = true;
                                                $errorMessage = __('Please choose atleast one e-Card!');
                                        }
                                        break;                
                }

                if (!isset($error)) {

                        /*
                         * Parse the post for any hashtags
                         */
                        if (!empty($contentData['description'])) {
                                        $hashtagIds = $this->HashTag->parseText($contentData['description']);
                                        if (!empty($hashtagIds)) {
                                                        $data['hashtag_ids'] = implode(",", $hashtagIds);
                                        }
                        }
                        // save data
                        if ($this->Post->save($data, false)) {

                                $successMessage = __('Successfully posted');

                                // if the post is created in a community,
                                // increment the discussion count of the community
                                if (isset($communityId)) {
                                        $this->Community->updateDiscussionCount($communityId, 'INC');
                                }

                                $postId = $this->Post->id;
                                $post = $this->Post->findById($postId);

                                // add post site notification adding task to queue
                                ClassRegistry::init('Queue.QueuedTask')->createJob('PostNotification', $post['Post']);

                                $result = array(
                                        'success' => true,
                                        'message' => $successMessage,
                                        'data' => $this->getPostDisplayData($post, 'other'),
                                        'postId' => $postId,
                                        'postInfo' => $this->__preparePostInfo($post['Post'])
                                );
                        } else {
                                $result = array(
                                                'error' => true,
                                                'message' => __('Failed to create post')
                                );
                        }
                } else {
                        $result = array(
                                'error' => true,
                                'message' => $errorMessage
                        );
                }
            } else {
                $result = array(
                    'error' => $error,
                    'message' => $errorMessage,
                    'errorType' => (isset($errorType) ? $errorType : 'normal')
                );
            }
        } else {
            $result = array(
                    'error' => true,
                    'message' => __('No data posted')
            );
        }

        return $result;
    }

    /**
     * Function to get the rooms following a post added page
     * 
     * @param array $post
     */
    public function getPostPageFollowingRooms($post) {
            $rooms = array();
            switch ($post['posted_in_type']) {
                    case Post::POSTED_IN_TYPE_EVENTS:
                            $eventId = $post['posted_in'];
                            $rooms[] = "events/{$eventId}";
                            $followingUsers = $this->FollowingPage->getEventFollowingUsers($eventId);
                            $this->EventDisease = ClassRegistry::init('EventDisease');
                            $taggedDiseases = $this->EventDisease->getDiseasesOfPublicEvent($eventId);
                            break;
                    case Post::POSTED_IN_TYPE_COMMUNITIES:
                            $communityId = $post['posted_in'];
                            $rooms[] = "communities/{$communityId}";
                            $followingUsers = $this->FollowingPage->getCommunityFollowingUsers($communityId);
                            $this->CommunityDisease = ClassRegistry::init('CommunityDisease');
                            $taggedDiseases = $this->CommunityDisease->getDiseasesOfPublicCommunity($communityId);
                            break;
                    case Post::POSTED_IN_TYPE_USERS:
                            $profileUserId = (int) $post['posted_in'];
                            $rooms[] = "users/{$profileUserId}";
                            $user = $this->User->findById($profileUserId, array('privacy_settings'));
                            $privacySettingStr = $user['User']['privacy_settings'];
                            $privacySettings = unserialize($privacySettingStr);
                            $followingUsers = array();
                            if (!empty($privacySettings['post_on_wall'])) {
                                    $activityViewPermittedTo = (int) $privacySettings['post_on_wall'];
                            } else {
                                    $activityViewPermittedTo = UserPrivacySettings::PRIVACY_FRIENDS;
                            }
                            if ($activityViewPermittedTo !== UserPrivacySettings::PRIVACY_PRIVATE) {
                                    $followingUsers = $this->FollowingPage->getProfileFollowingUsers($profileUserId);
                            }
                            if ($profileUserId !== (int) $post['post_by']) {
                                    array_push($followingUsers, $profileUserId);
                            }
                            break;
                    case Post::POSTED_IN_TYPE_DISEASES:
                            $diseaseId = $post['posted_in'];
                            $rooms[] = "diseases/{$diseaseId}";
                            $followingUsers = $this->FollowingPage->getDiseaseFollowingUsers($diseaseId);
                            break;
            }

            if (!empty($followingUsers)) {
                    foreach ($followingUsers as $followingUserId) {
                            $rooms[] = "newsfeed/{$followingUserId}";
                    }
            }
            if (!empty($taggedDiseases)) {
                    foreach ($taggedDiseases as $diseaseId) {
                            $rooms[] = "diseases/{$diseaseId}";
                    }
            }
            return $rooms;
    }

    /**
     * Function to upload post video file
     *
     * @param type $videoFileName
     * @return mixed array or boolean
     */
    protected function uploadVideo($videoFileName) {
            $uploadPath = Configure::read('App.UPLOAD_PATH');
            $videoTmpPath = $uploadPath . DIRECTORY_SEPARATOR . $videoFileName;

            // upload the video to permanent location on our server
            $videoUploadPath = Configure::read('App.POST_VIDEO_PATH');

            if (!is_dir($videoUploadPath)) {
                    // create video upload directory if it does not already exist
                    @mkdir($videoUploadPath);
            }

            $videoPath = $videoUploadPath . DIRECTORY_SEPARATOR . $videoFileName;
            if (Common::moveFile($videoTmpPath, $videoPath)) {
                    // upload the video to vimeo
                    $videoInfo = $this->uploadVideoToVimeo($videoPath);
                    if (isset($videoInfo['video_id'])) {
                            $videoInfo['file_name'] = $videoFileName;
                            return $videoInfo;
                    } else {
                            $this->log(sprintf('Failed to upload %s to vimeo', $videoFileName), 'debug');
                    }
            } else {
                    $this->log(sprintf('Failed to move video %s to permanent folder', $videoFileName), 'debug');
            }
            return false;
    }

    /**
     * Function to upload post video file to vimeo
     *
     * @param string $videoFilePath
     * @return mixed array or boolean
     */
    protected function uploadVideoToVimeo($videoFilePath) {
            App::import('Vendor', 'phpVimeo');
            $vimeoConfig = Configure::read('API.Vimeo');
            $vimeo = new phpVimeo($vimeoConfig);
            try {
                    $videoId = $vimeo->upload($videoFilePath);
                    if ($videoId > 0) {
                            $vimeo->call('vimeo.videos.setPrivacy', array('video_id' => $videoId, 'privacy' => 'disable'));
                            $videoInfo = array(
                                    'video_id' => $videoId,
                                    'thumbnail_url' => $vimeo->getThumbnailUrl($videoId)
                            );
                            return $videoInfo;
                    }
            } catch (VimeoAPIException $e) {
                    $this->log(sprintf('VimeoAPIException: %s', $e->getMessage()), 'debug');
            }
            return false;
    }

    /**
     * Function to get post display data from post details
     *
     * @param array $post
     * @param string $displayPage
     * @return array
     */
    public function getPostDisplayData($post, $displayPage = "other") {
            $timezone = $this->user['timezone'];
            $data = array();
            // to identify the post showing page
            $data['displayPage'] = $displayPage;
            if (!empty($post) && isset($post['Post']) && !empty($post['Post'])) {
                    $postData = $post['Post'];
                    $content = json_decode($postData['content'], true);

                    if (isset($content['title'])) {
                            $data['title'] = h(urldecode($content['title']));
                    } else {
                            $data['title'] = '';
                    }

                    if (isset($content['description'])) {
                            $description = $content['description'];
                            $data['description'] = $this->__getPostDisplayText($description);
                            $data['truncatedDescription'] = $this->__getTruncatedPostDisplayText($description);
                    } else {
                            $data['description'] = '';
                    }

                    $data['question'] = '';
                    $data['deleteButtonTitle'] = __('Delete Post');

                    if (isset($content['additional_info'])) {
                        $additionalInfo = $content['additional_info'];
                        if (isset($additionalInfo['link_video_iframe'])) {
                                $videoEmbedCode = $additionalInfo['link_video_iframe'];
                                $videoEmbedCode = $this->getWmodeVideoEmbedCode($videoEmbedCode);
                                $additionalInfo['link_video_iframe'] = $videoEmbedCode;
                        }
                        $additionalInfo = h($additionalInfo);
                        $data['additional_info'] = $additionalInfo;
                    } else {
                        $data['additional_info'] = '';
                    }

                    $showHealthSmiley = true;
                    $showDisease = true;
                    $data['postedIn'] = '';
                    switch ($postData['post_type']) {
                        case Post::POST_TYPE_TEXT:
                            $data['iconClass'] = 'discussion_comment';
                            $data['element'] = 'Post.text_post';
                            break;
                        case Post::POST_TYPE_QUESTION:
                            $data['element'] = 'Post.question_post';
                            $data['question'] = $this->__getPostDisplayText($content['question']);
                            $answerCount = $postData['answer_count'];
                            $data['answerCount'] = $answerCount;
                            $showMoreAnswersLink = false;
                            $answers = array();
                            if ($answerCount > 0) {
                                    $limit = self::LATEST_ANSWERS_COUNT;
                                    if ($answerCount > $limit) {
                                            $showMoreAnswersLink = true;
                                    }
                                    $answers = $this->getPostAnswersData($postData['id'], $limit);
                            }
                            $data['answers'] = $answers;
                            $data['showMoreAnswersLink'] = $showMoreAnswersLink;
                            $data['deleteButtonTitle'] = __('Delete Question');
                            break;
                        case Post::POST_TYPE_LINK:
                            $data['iconClass'] = 'discussion_link';
                            $data['element'] = 'Post.text_post';
                            break;
                        case Post::POST_TYPE_VIDEO:
                            $data['iconClass'] = 'discussion_video';
                            $data['element'] = 'Post.video_post';
                            if (isset($content['additional_info']['video']) && (!empty($content['additional_info']['video']))) {
                                    $video = $content['additional_info']['video'];
                                    $data['video'] = $video;
                            }
                            break;
                        case Post::POST_TYPE_IMAGE:
                            $data['iconClass'] = 'discussion_image';
                            $data['element'] = 'Post.image_post';
                            $photoIds = explode(",", $postData['post_type_id']);
                            $data['postPhotos'] = $this->Photo->find('all', array(
                                    'conditions' => array('Photo.id' => $photoIds)));
                            break;
                        case Post::POST_TYPE_POLL:
                            $data['poll_details'] = $this->Poll->getPoll($postData['post_type_id']);
                            $data['poll_name'] = NULL;
                            if (isset($content['description'])) {
                                    $data['poll_name'] = $content['description'];
                            }
                            $data['is_user_voted'] = $this->PollVote->isUserVoted($data['poll_details']['Poll']['id'], $this->currentUserId);
                            $data['vote_details'] = $this->PollChoices->getVoteDetails($data['poll_details']['Poll']['id']);
                            $data['can_vote'] = $this->hasLikePermission;
                            $data['iconClass'] = 'discussion_poll';
                            $data['element'] = 'Post.poll_post';
                            break;
                        case Post::POST_TYPE_COMMUNITY:
                            $showHealthSmiley = false;
                            $showDisease = false;
                            $data['iconClass'] = 'discussion_community';
                            $data['element'] = 'Post.community_post';

                            $community = $content['community'];
                            if ($postData['posted_in_type'] !== Post::POSTED_IN_TYPE_COMMUNITIES) {
                                    $data['communityUrl'] = sprintf('/community/details/index/%d', $community['id']);
                            }
                            $data['communityImage'] = Common::getCommunityThumb($community['id']);
                            $data['communityName'] = $community['name'];
                            $data['communityDescription'] = String::truncate($community['description'], 215, array('exact' => false));
                            if (($displayPage !== Post::POSTED_IN_TYPE_DISEASES) && ($postData['posted_in_type'] === Post::POSTED_IN_TYPE_DISEASES)) {
                                    $diseaseId = $postData['posted_in'];
                                    $diseaseName = $this->Disease->getDiseaseName($diseaseId);
                                    $data['postedIn'] = __('%s disease', h($diseaseName));
                            }
                            break;
                        case Post::POST_TYPE_EVENT:
                            $showHealthSmiley = false;
                            $showDisease = false;
                            $data['iconClass'] = 'discussion_event';
                            $data['element'] = 'Post.event_post';

                            $event = $content['event'];
                            $data['eventUrl'] = sprintf('/event/details/index/%d', $event['id']);
                            $data['eventImage'] = Common::getEventThumb($event['id']);
                            $data['eventName'] = String::truncate($event['name'], 42);
                            $data['isRepeating'] = $event['repeat'];
                            $data['eventStartDate'] = CakeTime::nice($event['start_date'], $timezone, '%a, %B %e, %l:%M %p');
                            $data['eventDescription'] = String::truncate($event['description'], 215, array('exact' => false));
                            if (($displayPage !== Post::POSTED_IN_TYPE_DISEASES) && ($postData['posted_in_type'] === Post::POSTED_IN_TYPE_DISEASES)) {
                                    $diseaseId = $postData['posted_in'];
                                    $diseaseName = $this->Disease->getDiseaseName($diseaseId);
                                    $data['postedIn'] = __('%s disease', h($diseaseName));
                            }
                            if (($displayPage !== Post::POSTED_IN_TYPE_COMMUNITIES) && ($postData['posted_in_type'] === Post::POSTED_IN_TYPE_COMMUNITIES)) {
                                    $communityId = $postData['posted_in'];
                                    $community = $this->Community->getCommunity($communityId);
                                    $data['postedIn'] = __('%s community', h($community['name']));
                            }
                            break;
                        case Post::POST_TYPE_HEALTH:
                            $data['iconClass'] = 'discussion_health';
                            $data['element'] = 'Post.health_status_post';
                            App::uses('HealthStatus', 'Utility');
                            $data['healthStatus'] = HealthStatus::getHealthStatusText($content['health_status']);
                            $data['smileyClass'] = HealthStatus::getFeelingSmileyClass($content['health_status']);
                            if (isset($content['health_status_comment'])) {
                                    $data['healthStatusComment'] = nl2br(h($content['health_status_comment']));
                            } else {
                                    $data['healthStatusComment'] = '';
                            }
                            break;
                        case Post::POST_TYPE_TEAM_PRIVACY_CHANGE:
                            $showHealthSmiley = false;
                            $showDisease = false;
                            $data['iconClass'] = 'discussion_myteam';
                            $data['element'] = 'Post.team_privacy_change_post';
                            $team = $content['privacy_change'];
                            $data['teamName'] = $team['name'];
                            if ($team['privacy'] == Team::TEAM_PUBLIC) {
                                    $data['teamPrivacy'] = 'Public';
                            } else {
                                    $data['teamPrivacy'] = 'Private';
                            }
                            break;

                        case Post::POST_TYPE_BLOG:
                            $showDisease = false;
                            $showHealthSmiley = false;
                            $data['iconClass'] = 'discussion_blog';
                            $data['element'] = 'Post.blog_post';
                            break; 

                        case Post::POST_TYPE_ECARD:
                            $showDisease = false;
                            $showHealthSmiley = false;
                            $data['iconClass'] = 'discussion_ecard';
                            $data['element'] = 'Post.ecard_post';
                            $ecards = $content['ecards'];   
                            $eCardSel = array();
                            if(isset($ecards) && $ecards != "") {
                                $eCardImages = explode(",", $ecards);
                                foreach ($eCardImages as $cardName) {
                                    if(trim($cardName) != "")
                                    $eCardSel[] = '/img/ecards/'.$this->getEcardImages($cardName);
                                }
                            }     
                            $data['ecards'] = $eCardSel;
                            break;                                  
                    }

                    $data['room'] = $postData['posted_in_type'] . '/' . $postData['posted_in'];

                    if ($postData['posted_in_type'] === Post::POSTED_IN_TYPE_USERS) {
                            $profileId = (int) $postData['posted_in'];
                            if (empty($this->_permissions)) {
                                    $userId = $this->currentUserId;
                                    $this->_permissions = $this->User->getUserPermissionsInProfile($userId, $profileId);
                            }
                            $this->hasCommentPermission = $this->_permissions['messaging'];
                    }

                    $userFavoritePosts = $this->User->getFavoritePostIds($this->currentUserId);
                    if ($userFavoritePosts == NULL) {
                            $userFavoritePosts = array();
                    }

                    $postedUserId = (int) $postData['post_by'];
                    $this->postBy = $postedUserId;
                    $postedUser = $post['User'];

                    $userThumbSizeClass = 'small';
                    $userThumbClass = 'media-object';
                    $data['postedUserSmileyClass'] = '';
                    $data['postedUserHealthStatus'] = '';
                    $data['postedUserDiseaseName'] = '';
                    if (isset($postData['is_anonymous']) && $postData['is_anonymous'] === true) {
                            $postedUserLink = Common::getAnonymousUserLink();
                            $postedUserThumb = Common::getAnonymousUserThumb($userThumbSizeClass, $userThumbClass);
                            $postedUserProfileUrl = 'javascript:void(0)';
                            $postedUserThumbCursorClass = 'cursor-default';
                    } else {
                            $showHoverCard = ($this->currentUserId > 0) ? true : false;
                            $postedUserLink = Common::getUserProfileLink($postedUser['username'], false, 'owner', $showHoverCard);
                            $postedUserThumb = Common::getUserThumb($postedUserId, $postedUser['type'], $userThumbSizeClass, $userThumbClass, 'img', $postedUser['username']);
                            $postedUserProfileUrl = Common::getUserProfileLink($postedUser['username'], true);
                            $postedUserThumbCursorClass = '';

                            // show posted user's health status at the time of posting
                            if ($showHealthSmiley === true) {
                                    App::uses('HealthStatus', 'Utility');
                                    $healthStatus = (!empty($content['health_status'])) ? $content['health_status'] : HealthStatus::STATUS_VERY_GOOD;
                                    $data['postedUserHealthStatus'] = HealthStatus::getHealthStatusText($healthStatus);
                                    $data['postedUserSmileyClass'] = HealthStatus::getFeelingSmileyClass($healthStatus);
                            }

                            // show posted user's disease based on privacy
                            if (($showDisease === true) && $this->__canCurrentUserViewDiseaseOfUser($postedUserId)) {
                                    $data['postedUserDiseaseName'] = $this->PatientDisease->getUserDiseaseName($postedUserId);
                            }
                    }
                    $data['postedUserName'] = $postedUser['username'];
                    $data['postedUserLink'] = $postedUserLink;
                    $data['postedUserThumb'] = $postedUserThumb;
                    $data['postedUserProfileUrl'] = $postedUserProfileUrl;
                    $data['postedUserThumbCursorClass'] = $postedUserThumbCursorClass;
                    $data['postedTimeAgo'] = Date::timeAgoInWords($postData['created'], $timezone);
                    $data['postedTimeISO'] = Date::getISODate($postData['created']);
                    $data['postCreatedTime'] = CakeTime::nice($postData['created'], $timezone);
                    $data['postId'] = $postData['id'];
                    $data['canDeletePost'] = $this->__canCurrentUserDeletePost($postData);
                    if (in_array($postData['post_type'], array(Post::POST_TYPE_EVENT, Post::POST_TYPE_COMMUNITY, Post::POST_TYPE_HEALTH))) {
                            $data['canReportAbusePost'] = false;
                    } else {
                            $data['canReportAbusePost'] = ((int) $postData['post_by'] !== $this->currentUserId);
                    }
                    $likeCount = $postData['like_count'];
                    $data['likeCount'] = $likeCount;
                    $lastLikedUsers = $this->__getLastLikedUsers($content);
                    $data['lastLikedUsers'] = $lastLikedUsers;
                    $data['likedUsersClass'] = ($likeCount > 0) ? '' : 'hide';
                    $data['othersLikeCount'] = $likeCount - count($lastLikedUsers);
                    $isLiked = $this->__isCurrentUserLikedPost($content);
                    $isFavorite = (in_array($postData['id'], $userFavoritePosts)) ? true : false;
                    $data['likeBtnClass'] = ($isLiked) ? 'unlike_btn' : 'like_btn';
                    $data['likeBtnText'] = ($isLiked) ? __('Unlike') : __('Like');
                    $data['favoritebtnClass'] = ($isFavorite) ? __('favorite') : __('not_favorite');
                    $data['favoritebtnTitle'] = ($isFavorite) ? __('Remove from my library') : __('Add to my library');
                    $data['showLikeBox'] = $this->hasLikePermission;
                    $commentCount = $postData['comment_count'];
                    $data['commentCount'] = $commentCount;
                    $data['showSeeAllComments'] = ($commentCount > self::LATEST_COMMENTS_COUNT) ? true : false;
                    $data['commentFormClass'] = ($this->hasCommentPermission === true) ? '' : 'hide';
                    $data['latestComments'] = $this->__getDisplayDataFromCommentsJSON($postData['comment_json_content']);

                    // check anonymous commenting permission
                    if (($postData['posted_in_type'] === Post::POSTED_IN_TYPE_USERS) && ($this->currentUserId !== $profileId)) {
                            $showAnonymousCheckbox = false;
                    } else {
                            $showAnonymousCheckbox = $this->user['has_anonymous_permission'];
                            if (($showAnonymousCheckbox === true) && (isset($this->_permissions['anonymous_messaging']))) {
                                    $showAnonymousCheckbox = $this->_permissions['anonymous_messaging'];
                            }
                    }
                    $data['showAnonymousCheckbox'] = $showAnonymousCheckbox;
                    $data['currentUsername'] = $this->user['username'];
            }

            return $data;
    }

    /**
     * Function to check if currently logged in user can view the disease of
     * the specified user
     * 
     * @param int $userId
     * @return boolean
     */
    private function __canCurrentUserViewDiseaseOfUser($userId) {
            $userId = (int) $userId;
            $currentUserId = $this->currentUserId;
            if ($userId === $currentUserId) {
                    $viewDisease = true;
            } else {
                    $viewDisease = false;
                    $privacy = new UserPrivacySettings($userId);
                    $diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');
                    if ($diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC) {
                            $viewDisease = true;
                    } elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
                            $friendStatus = (int) $this->MyFriends->getFriendStatus($currentUserId, $userId);
                            if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
                                    $viewDisease = true;
                            }
                    }
            }
            return $viewDisease;
    }

    /**
     * Function to get the data of the answers of a post
     * 
     * @param int $postId
     * @param int $limit
     * @return array 
     */
    public function getPostAnswersData($postId, $limit = null) {
            $answers = $this->Answer->getPostAnswers($postId, $limit);
            $answersData = array();
            if (!empty($answers)) {
                    foreach ($answers as $answerData) {
                            $answersData[] = $this->getAnswerDisplayData($answerData);
                    }
            }
            return $answersData;
    }

    /**
     * Function to get abuse post display data from post details
     *
     * @param array $post
     * @param bool $showComments
     * @return array
     */
    public function getAbusePostDisplayData($post, $showComments = false) {
            $timezone = $this->user['timezone'];
            $data = array();
            if (!empty($post) && isset($post['Post']) && !empty($post['Post'])) {
                    $postData = $post['Post'];
                    $content = json_decode($postData['content'], true);

                    if (isset($content['title'])) {
                            $data['title'] = h(urldecode($content['title']));
                    } else {
                            $data['title'] = '';
                    }

                    if (isset($content['description'])) {
                            $data['description'] = $this->__getPostDisplayText($content['description']);
                    } else {
                            $data['description'] = '';
                    }

                    if (isset($content['additional_info'])) {
                            $additionalInfo = $content['additional_info'];
                            if (isset($additionalInfo['link_video_iframe'])) {
                                    $videoEmbedCode = $additionalInfo['link_video_iframe'];
                                    $videoEmbedCode = $this->getWmodeVideoEmbedCode($videoEmbedCode);
                                    $additionalInfo['link_video_iframe'] = $videoEmbedCode;
                            }
                            $additionalInfo = h($additionalInfo);
                            $data['additional_info'] = $additionalInfo;
                    } else {
                            $data['additional_info'] = '';
                    }

                    switch ($postData['post_type']) {
                            case Post::POST_TYPE_TEXT:
                                    $data['element'] = 'Admin.Post/text_post';
                                    break;
                            case Post::POST_TYPE_LINK:
                                    $data['element'] = 'Admin.Post/text_post';
                                    break;
                            case Post::POST_TYPE_VIDEO:
                                    $data['element'] = 'Admin.Post/video_post';
                                    if (isset($content['additional_info']['video']) && (!empty($content['additional_info']['video']))) {
                                            $video = $content['additional_info']['video'];
                                            $data['video'] = $video;
                                    }
                                    break;
                            case Post::POST_TYPE_IMAGE:
                                    $data['element'] = 'Admin.Post/image_post';
                                    $photoIds = explode(",", $postData['post_type_id']);
                                    $data['postPhotos'] = $this->Photo->find('all', array(
                                            'conditions' => array('Photo.id' => $photoIds)));
                                    break;
                            case Post::POST_TYPE_POLL:
                                    $data['poll_details'] = $this->Poll->getPoll($postData['post_type_id']);
                                    $data['poll_name'] = NULL;
                                    if (isset($content['description'])) {
                                            $data['poll_name'] = $content['description'];
                                    }
                                    $data['vote_details'] = $this->PollChoices->getVoteDetails($data['poll_details']['Poll']['id']);
                                    $data['element'] = 'Admin.Post/poll_post';
                                    break;
                    }

                    $postedUserId = $postData['post_by'];
                    $this->postBy = $postedUserId;
                    $postedUser = $post['User'];
                    $postedUserLink = Common::getUserAdminProfileLink($postedUserId, $postedUser['username']);
                    $userThumbSizeClass = 'small';
                    $userThumbClass = 'media-object';
                    $postedUserThumb = Common::getUserThumb($postedUserId, $postedUser['type'], $userThumbSizeClass, $userThumbClass);
                    $data['postedUserLink'] = $postedUserLink;
                    $data['postedUserThumb'] = $postedUserThumb;
                    $data['postedTimeAgo'] = Date::timeAgoInWords($postData['created'], $timezone);
                    $data['postId'] = $postData['id'];
                    $likeCount = $postData['like_count'];
                    $data['likeCount'] = $likeCount;

                    $query = array(
                            'conditions' => array(
                                    'Comment.post_id' => $postData['id']
                            )
                    );
                    if ($showComments === true) {
                            $commentsList = array();
                            $comments = $this->Comment->find('all', $query);
                            if (!empty($comments)) {
                                    foreach ($comments as $comment) {
                                            $commentsList[] = $this->getCommentDisplayData($comment);
                                    }
                            }
                            $data['comments'] = $commentsList;
                            $data['commentCount'] = count($comments);
                    } else {
                            $data['commentCount'] = $this->Comment->find('count', $query);
                    }
            }

            return $data;
    }

    public function getLibraryPostDisplayData($post) {
            $timezone = $this->user['timezone'];
            $data = array();
            if (!empty($post) && isset($post['Post']) && !empty($post['Post'])) {
                    $postData = $post['Post'];
                    $content = json_decode($postData['content'], true);

                    if (isset($content['title'])) {
                            $data['title'] = h(urldecode($content['title']));
                    } else {
                            $data['title'] = '';
                    }
                    if (isset($content['description'])) {
                            $data['description'] = $this->__getPostDisplayText($content['description']);
                    } else {
                            $data['description'] = '';
                    }
                    if (isset($content['additional_info'])) {
                            $additionalInfo = $content['additional_info'];
                            if (isset($additionalInfo['link_video_iframe'])) {
                                    $videoEmbedCode = $additionalInfo['link_video_iframe'];
                                    $videoEmbedCode = $this->getWmodeVideoEmbedCode($videoEmbedCode);
                                    $additionalInfo['link_video_iframe'] = $videoEmbedCode;
                            }
                            $additionalInfo = h($additionalInfo);
                            $data['additional_info'] = $additionalInfo;
                    } else {
                            $data['additional_info'] = '';
                    }

                    $data['postedUserLink'] = '';
                    $showDisease = true;
                    switch ($postData['post_type']) {
                            case Post::POST_TYPE_TEXT:
                                    $data['iconClass'] = 'discussion_comment';
                                    $data['element'] = 'Post.text_post_library';
                                    break;
                            case Post::POST_TYPE_LINK:
                                    $data['iconClass'] = 'discussion_link';
                                    $data['element'] = 'Post.text_post_library';
                                    break;
                            case Post::POST_TYPE_VIDEO:
                                    $data['iconClass'] = 'discussion_video';
                                    $data['element'] = 'Post.video_post_library';
                                    if (isset($content['additional_info']['video']) && (!empty($content['additional_info']['video']))) {
                                            $video = $content['additional_info']['video'];
                                            $data['video'] = $video;
                                    }
                                    break;
                            case Post::POST_TYPE_IMAGE:
                                    $data['iconClass'] = 'discussion_image';
                                    $data['element'] = 'Post.image_post_library';
                                    $photoIds = explode(",", $postData['post_type_id']);
                                    $data['postPhotos'] = $this->Photo->find('all', array(
                                            'conditions' => array('Photo.id' => $photoIds)));
                                    break;
                            case Post::POST_TYPE_POLL:
                                    $data['poll_details'] = $this->Poll->getPoll($postData['post_type_id']);
                                    $data['poll_name'] = NULL;
                                    if (isset($content['description'])) {
                                            $data['poll_name'] = $content['description'];
                                    }
                                    $data['is_user_voted'] = $this->PollVote->isUserVoted($data['poll_details']['Poll']['id'], $this->currentUserId);
                                    $data['vote_details'] = $this->PollChoices->getVoteDetails($data['poll_details']['Poll']['id']);
                                    $data['iconClass'] = 'discussion_poll';
                                    $data['element'] = 'Post.poll_post_library';
                                    break;
                            case Post::POST_TYPE_COMMUNITY:
                                    $data['iconClass'] = 'discussion_community';
                                    $data['element'] = 'Post.community_post_library';

                                    $community = $content['community'];
                                    $data['communityImage'] = Common::getCommunityThumb($community['id']);
                                    $data['communityName'] = $community['name'];
                                    $data['communityDescription'] = String::truncate($community['description'], 215, array('exact' => false));
                                    break;
                            case Post::POST_TYPE_EVENT:
                                    $data['iconClass'] = 'discussion_event';
                                    $data['element'] = 'Post.event_post_library';

                                    $event = $content['event'];
                                    $data['eventUrl'] = sprintf('/event/details/index/%d', $event['id']);
                                    $data['eventImage'] = Common::getEventThumb($event['id']);
                                    $data['eventName'] = String::truncate($event['name'], 42);
                                    $data['isRepeating'] = $event['repeat'];
                                    $data['eventStartDate'] = CakeTime::nice($event['start_date'], $timezone, '%a, %B %e, %l:%M %p');
                                    $data['eventDescription'] = String::truncate($event['description'], 215, array('exact' => false));
                                    break;
                            case Post::POST_TYPE_HEALTH:
                                    $data['iconClass'] = 'discussion_health';
                                    $data['element'] = 'Post.health_status_post_library';
                                    App::uses('HealthStatus', 'Utility');
                                    $data['healthStatus'] = HealthStatus::getHealthStatusText($content['health_status']);
                                    if (isset($content['health_status_comment'])) {
                                            $data['healthStatusComment'] = nl2br(h($content['health_status_comment']));
                                    } else {
                                            $data['healthStatusComment'] = '';
                                    }

                                    $showHoverCard = ($this->currentUserId > 0) ? true : false;
                                    $data['postedUserLink'] = Common::getUserProfileLink($post['User']['username'], false, 'owner', $showHoverCard);

                                    break;
                    }
                    $userFavoritePosts = $this->User->getFavoritePostIds($this->currentUserId);
                    if ($userFavoritePosts == NULL) {
                            $userFavoritePosts = array();
                    }
                    $postedUserId = $postData['post_by'];
                    $this->postBy = $postedUserId;
                    $postedUser = $post['User'];

                    $userThumbSizeClass = 'small';
                    $userThumbClass = 'media-object';
                    $data['postedUserDiseaseName'] = '';
                    if (isset($postData['is_anonymous']) && $postData['is_anonymous'] === true) {
                            $postedUserLink = Common::getAnonymousUserLink();
                            $postedUserThumb = Common::getAnonymousUserThumb($userThumbSizeClass, $userThumbClass);
                            $postedUserProfileUrl = 'javascript:void(0)';
                            $postedUserThumbCursorClass = 'cursor-default';
                    } else {
                            $showHoverCard = ($this->currentUserId > 0) ? true : false;
                            $postedUserLink = Common::getUserProfileLink($postedUser['username'], false, 'owner', $showHoverCard);
                            $postedUserThumb = Common::getUserThumb($postedUserId, $postedUser['type'], $userThumbSizeClass, $userThumbClass);
                            $postedUserProfileUrl = Common::getUserProfileLink($postedUser['username'], true);
                            $postedUserThumbCursorClass = '';

                            // show posted user's disease based on privacy
                            if (($showDisease === true) && $this->__canCurrentUserViewDiseaseOfUser($postedUserId)) {
                                    $data['postedUserDiseaseName'] = $this->PatientDisease->getUserDiseaseName($postedUserId);
                            }
                    }

                    $data['postedUserName'] = Common::getUsername($postedUser['username'], $postedUser['first_name'], $postedUser['last_name']);
                    $data['postedUserThumb'] = $postedUserThumb;
                    $data['postedUserLink'] = $postedUserLink;
                    $data['postedUserProfileUrl'] = $postedUserProfileUrl;
                    $data['postedUserThumbCursorClass'] = $postedUserThumbCursorClass;
                    $data['postedTimeAgo'] = Date::timeAgoInWords($postData['created'], $timezone);
                    $data['postedTimeISO'] = Date::getISODate($postData['created']);
                    $data['postCreatedTime'] = CakeTime::nice($postData['created'], $timezone);
                    $data['postId'] = $postData['id'];
                    $data['canDeletePost'] = $this->__canCurrentUserDeletePost($postData);
                    $likeCount = $postData['like_count'];
                    $data['likeCount'] = $likeCount;
                    $lastLikedUsers = $this->__getLastLikedUsers($content);
                    $data['lastLikedUsers'] = $lastLikedUsers;
                    $data['likedUsersClass'] = ($likeCount > 0) ? '' : 'hide';
                    $data['othersLikeCount'] = $likeCount - count($lastLikedUsers);
                    $isLiked = $this->__isCurrentUserLikedPost($content);
                    $isFavorite = (in_array($postData['id'], $userFavoritePosts)) ? true : false;
                    $data['likeBtnClass'] = ($isLiked) ? 'unlike_btn' : 'like_btn';
                    $data['likeBtnText'] = ($isLiked) ? __('Unlike') : __('Like');
                    $data['favoritebtnClass'] = ($isFavorite) ? __('favorite') : __('not_favorite');
                    $data['favoritebtnTitle'] = ($isFavorite) ? __('Remove from my library') : __('Add to my library');
                    $data['showLikeBox'] = false;
                    $commentCount = $postData['comment_count'];
                    $data['commentCount'] = $commentCount;
                    $data['showSeeAllComments'] = false;
                    $data['commentFormClass'] = 'hide';
                    $data['latestComments'] = $this->__getDisplayDataFromCommentsJSON($postData['comment_json_content']);

                    $data['postedInDetails'] = $this->getPostedInDetails($postData['posted_in_type'], $postData['posted_in']);
            }

            return $data;
    }

    /**
     * Function to get the video embed code with wmode attribute
     *
     * @param string $code
     * @return string
     */
    public function getWmodeVideoEmbedCode($code) {
            if ((strpos($code, '<object') !== false) && (strpos($code, '<embed') !== false)) {
                    $replaceText = '';
                    if (strpos($code, '<param name="wmode"') === false) {
                            $replaceText.='<param name="wmode" value="opaque" />';
                    }
                    $replaceText.='<embed wmode="opaque"';
                    $code = str_replace('<embed ', $replaceText, $code);
            } elseif (strpos($code, '<iframe') !== false) {
                    if (preg_match('/src=\"(.*?)\"/', $code, $matches)) {
                            if (isset($matches[1])) {
                                    $src = $matches[1];
                                    if (strpos($src, 'wmode') === false) {
                                            $wmodePrefix = (strpos($src, '?') === false) ? '?' : '&';
                                            $wmodeParam = "{$wmodePrefix}wmode=opaque";
                                            $wmodeSrc = $src . $wmodeParam;
                                            $code = str_replace($src, $wmodeSrc, $code);
                                    }
                            }
                    }
            }
            return $code;
    }

    /**
     * Function to check if the current user can delete a post
     *
     * @param array $postData
     * @return boolean
     */
    private function __canCurrentUserDeletePost($postData) {
            // post owner can delete post
            $postedUserId = $postData['post_by'];
            if ($postData['is_deleted'] == POST::NOT_DELETED) {//if the post is already deleted, then return false.
                    $canDeletePost = ($postedUserId == $this->currentUserId) ? true : false;

                    if ($canDeletePost === false) {
                            // if the post is in a community, community owner also can delete post
                            if ($postData['posted_in_type'] == Post::POSTED_IN_TYPE_COMMUNITIES) {
                                    $communityId = $postData['posted_in'];
                                    $community = $this->__getModelById('Community', $communityId);
                                    $communityOwnerId = (int) $community['Community']['created_by'];
                                    if ($communityOwnerId === $this->currentUserId) {
                                            $canDeletePost = true;
                                    }
                            }
                            // if the post is in a community event, community owner also can delete post
                            elseif ($postData['posted_in_type'] == Post::POSTED_IN_TYPE_EVENTS) {
                                    $eventId = $postData['posted_in'];
                                    $event = $this->Event->findById($eventId);
                                    if (isset($event['Community'])) {
                                            $communityOwnerId = (int) $event['Community']['created_by'];
                                            if ($communityOwnerId === $this->currentUserId) {
                                                    $canDeletePost = true;
                                            }
                                    }
                            }
                            // if the post is in a profile page, profile owner also can delete post
                            elseif ($postData['posted_in_type'] == Post::POSTED_IN_TYPE_USERS) {
                                    $profileId = (int) $postData['posted_in'];
                                    if ($profileId === $this->currentUserId) {
                                            $canDeletePost = true;
                                    }
                            }
                    }
            } else {
                    $canDeletePost = FALSE;
            }
            return $canDeletePost;
    }

    /**
     * Function to get display data from post comments json
     *
     * @param json $commentsJSON
     * @return array
     */
    private function __getDisplayDataFromCommentsJSON($commentsJSON) {
            $data = array();
            $timezone = $this->user['timezone'];
            if (!is_null($commentsJSON) && ($commentsJSON !== '')) {
                    $comments = json_decode($commentsJSON, true);
                    if (!empty($comments)) {
                            foreach ($comments as $comment) {
                                    $commentedUserId = (int) $comment['created_by'];
                                    $commentedUserHealthStatus = '';
                                    $commentedUserSmileyClass = '';
                                    if (isset($comment['is_anonymous']) && ($comment['is_anonymous'] === true)) {
                                            $commentedUserName = '';
                                            $commentedUserLink = Common::getAnonymousUserLink();
                                            $commentedUserThumb = Common::getAnonymousUserThumb('x_small', 'media-object');
                                            $commentedUserProfileUrl = 'javascript:void(0)';
                                            $commentedUserThumbCursorClass = 'cursor-default';
                                    } else {
                                            $commentedUserName = $comment['created_user_name'];
                                            $showHoverCard = ($this->currentUserId > 0) ? true : false;
                                            $commentedUserLink = Common::getUserProfileLink($commentedUserName, false, 'owner', $showHoverCard);
                                            $commentedUserThumb = Common::getUserThumb($commentedUserId, $comment['created_user_type'], 'x_small', 'media-object', 'img', $commentedUserName);
                                            $commentedUserProfileUrl = Common::getUserProfileLink($commentedUserName, true);
                                            $commentedUserThumbCursorClass = '';

                                            // show commented user's health status at the time of commenting
                                            App::uses('HealthStatus', 'Utility');
                                            $healthStatus = (!empty($comment['health_status'])) ? $comment['health_status'] : HealthStatus::STATUS_VERY_GOOD;
                                            $commentedUserHealthStatus = HealthStatus::getHealthStatusText($healthStatus);
                                            $commentedUserSmileyClass = HealthStatus::getFeelingSmileyClass($healthStatus);
                                    }
                                    $data[] = array(
                                            'commentedUserName' => $commentedUserName,
                                            'commentedUserLink' => $commentedUserLink,
                                            'commentedUserThumb' => $commentedUserThumb,
                                            'commentedUserProfileUrl' => $commentedUserProfileUrl,
                                            'commentedUserThumbCursorClass' => $commentedUserThumbCursorClass,
                                            'commentedTimeAgo' => Date::timeAgoInWords($comment['created'], $timezone),
                                            'commentedTimeISO' => Date::getISODate($comment['created']),
                                            'commentText' => $this->__getCommentDisplayText($comment['comment_text']),
                                            'truncatedCommentText' => $this->__getTruncatedCommentDisplayText($comment['comment_text']),
                                            'commentId' => $comment['id'],
                                            'canDelete' => $this->__canCurrentUserDeleteComment($comment),
                                            'canReportAbuse' => ($this->currentUserId !== $commentedUserId),
                                            'commentedUserHealthStatus' => $commentedUserHealthStatus,
                                            'commentedUserSmileyClass' => $commentedUserSmileyClass
                                    );
                            }
                    }
            }

            return $data;
    }

    /**
     * Function to get the comment display text
     * 
     * @param string $commentText
     * @return string
     */
    private function __getCommentDisplayText($commentText) {
            $commentText = preg_replace("/[\r\n]+/", "\n", $commentText);
            $commentText = nl2br(h($commentText));
            return $commentText;
    }

    /**
     * Function to get the truncated comment display text
     * 
     * @param string $commentText
     * @return string
     */
    private function __getTruncatedCommentDisplayText($commentText) {
            $maxLength = ($this->layout === '2_column') ? 75 : 185;
            $truncatedDisplayText = '';
            if (strlen($commentText) > $maxLength) {
                    $options = array('exact' => false, 'html' => true, 'ellipsis' => false);
                    $truncatedText = String::truncate($commentText, $maxLength, $options);
                    $truncatedDisplayText = $this->__getCommentDisplayText($truncatedText);
            }
            return $truncatedDisplayText;
    }

    /**
     * Function to get the post display text
     * 
     * @param string $postText
     * @return string
     */
    private function __getPostDisplayText($postText) {
            $postText = preg_replace("/[\r\n]+/", "\n", $postText);
            App::import('helper', 'Text');
            $textHelper = new TextHelper(new View());
            $postText = nl2br($textHelper->autoLinkUrls(urldecode($postText)));
            
            App::uses('HashTagUtil', 'Utility');
            $postText = HashTagUtil::convertHashTags($postText);
            return $postText;
    }

    /**
     * Function to get the truncated post display text
     * 
     * @param string $postText
     * @return string
     */
    private function __getTruncatedPostDisplayText($postText) {
            $maxLength = ($this->layout === '2_column') ? 150 : 350;
            $truncatedPostDisplayText = '';
            if (strlen($postText) > $maxLength) {
                    $options = array('exact' => false, 'html' => true, 'ellipsis' => false);
                    $truncatedPostText = String::truncate($postText, $maxLength, $options);
                    $truncatedPostDisplayText = $this->__getPostDisplayText($truncatedPostText);
            }
            return $truncatedPostDisplayText;
    }

    /**
     * Function to check if the current user can delete a comment
     *
     * @return boolean
     */
    private function __canCurrentUserDeleteComment($comment) {
            $commentedUserId = $comment['created_by'];
            $isCommentOwner = ($this->currentUserId == $commentedUserId);
            $isPostOwner = ($this->currentUserId == $this->postBy);
            $canDelete = ($isCommentOwner || $isPostOwner) ? true : false;
            return $canDelete;
    }

    /**
     * Function to check if current user liked a post
     *
     * @param array $postContent
     * @return boolean
     */
    private function __isCurrentUserLikedPost($postContent) {
            $isLiked = false;
            if (isset($postContent['liked_users_list'])) {
                    $likedUsersList = $postContent['liked_users_list'];
                    if ($likedUsersList !== '') {
                            $likedUsersArray = explode(',', $likedUsersList);
                            if (in_array($this->currentUserId, $likedUsersArray)) {
                                    $isLiked = true;
                            }
                    }
            }

            return $isLiked;
    }

    /**
     * Function to get the detail of last liked 2 users
     *
     * @param array $postContent
     * @return array
     */
    private function __getLastLikedUsers($postContent) {
            $lastLikedUserNames = array();
            if (isset($postContent['liked_users_list'])) {
                    $likedUsersList = $postContent['liked_users_list'];
                    if ($likedUsersList !== '') {
                            $likedUsersArray = explode(',', $likedUsersList);

                            // check if the current user has liked the post
                            $currentUserKey = array_search($this->currentUserId, $likedUsersArray);
                            if ($currentUserKey !== false) {
                                    // add the current user as the first liked user
                                    $userDetail = $this->User->getUserDetails($this->currentUserId);
                                    $name = Common::getUsername($userDetail['user_name'], $userDetail['first_name'], $userDetail['last_name']);
                                    $lastLikedUserNames[] = $name;

                                    // remove current user from liked users list
                                    unset($likedUsersArray[$currentUserKey]);
                                    $likedUsersArray = array_values($likedUsersArray);

                                    $otherUsersCount = count($likedUsersArray);
                                    if ($otherUsersCount > 0) {
                                            $lastUserIndex = $otherUsersCount - 1;
                                            // if other users have liked, add the last liked user's info
                                            if (isset($likedUsersArray[$lastUserIndex])) {
                                                    $lastUserId = $likedUsersArray[$lastUserIndex];
                                                    $userDetail = $this->User->getUserDetails($lastUserId);
                                                    $name = Common::getUsername($userDetail['user_name'], $userDetail['first_name'], $userDetail['last_name']);
                                                    $lastLikedUserNames[] = $name;
                                            }
                                    }
                            } else {
                                    $lastLikedUsers = array_slice($likedUsersArray, -2, 2, true);
                                    $lastLikedUsersData = $this->User->getUsersData($lastLikedUsers);
                                    foreach ($lastLikedUsersData as $userData) {
                                            $userDetail = $userData['User'];
                                            $name = Common::getUsername($userDetail['username'], $userDetail['first_name'], $userDetail['last_name']);
                                            $lastLikedUserNames[] = $name;
                                    }
                            }
                    }
            }

            return $lastLikedUserNames;
    }

    /**
     * Function to like a post
     *
     * @param int $postId
     * @return array
     */
    public function likePost($postId) {
            $result = array();
            $post = $this->__getModelById('Post', $postId);
            if (!empty($post) && ($post['Post']['is_deleted'] == self::NOT_DELETED)) {//or check the 'is_deleted' => self::NOT_DELETED
                    try {
                            // add entry in likes table
                            $this->Like->create();
                            $data = array(
                                    'post_id' => $postId,
                                    'created_by' => $this->currentUserId,
                                    'ip' => $this->clientIp
                            );
                            $this->Like->save($data);
                            $error = false;
                    } catch (Exception $e) {
                            $error = true;
                    }

                    if (!$error) {
                            // update post liked users list
                            $result = $this->__updateLikedUsersList($post['Post']);
                    }
            } else {
                    $result = array(
                            'error' => true,
                            'message' => __(self::POST_DELETED_ERROR)
                    );
            }

            return $result;
    }

    /**
     * Function to unlike a post
     *
     * @param int $postId
     * @return array
     */
    public function unlikePost($postId) {
            $result = array();
            $like = $this->Like->find('first', array(
                    'conditions' => array(
                            'Like.post_id' => $postId,
                            'Like.created_by' => $this->currentUserId
                    ),
            ));
            if (!empty($like)) {
                    try {
                            $likeId = $like['Like']['id'];
                            $likedUserId = $like['Like']['created_by'];

                            // delete entry from likes table
                            $this->Like->delete($likeId);
                            $error = false;
                    } catch (Exception $e) {
                            $error = true;
                    }

                    if (!$error) {
                            // update post liked users list
                            $post = $like['Post'];
                            $result = $this->__updateLikedUsersList($post, $likedUserId);
                    }
            } else {
                    $result = array(
                            'error' => true,
                            'message' => __(self::POST_DELETED_ERROR)
                    );
            }

            return $result;
    }

    /**
     * Function to update the list of liked user ids in posts table and
     * return the result
     *
     * @param array $post
     * @param int $userId
     * @return array
     */
    private function __updateLikedUsersList($post, $userId = 0) {
            $likeCount = $post['like_count'];
            $contentJSON = $post['content'];
            $content = json_decode($contentJSON, true);
            $likedUsersArray = array();

            // existing last liked users list
            if (isset($content['liked_users_list'])) {
                    $likedUsersList = $content['liked_users_list'];
                    if ($likedUsersList !== '') {
                            $likedUsersArray = explode(',', $likedUsersList);
                    }
            }

            if ($userId > 0) {
                    // remove the currently unliked user from the liked users list
                    $key = array_search($userId, $likedUsersArray);
                    unset($likedUsersArray[$key]);
                    $likeCount--;
                    $likedUserId = null;
            } else {
                    // add the currently liked user to the liked users list
                    array_push($likedUsersArray, $this->currentUserId);
                    $likeCount++;
                    $likedUserId = $this->currentUserId;
            }

            // update post table with the new liked users list
            $likedUsersList = join(',', $likedUsersArray);
            $content['liked_users_list'] = $likedUsersList;
            $this->Post->id = $post['id'];
            $this->Post->saveField('content', json_encode($content));

            // save post like notification job to queue
            $post['content'] = $content;
            $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
            $this->QueuedTask->createJob('PostLikeNotification', array(
                    'post' => $post,
                    'likedUsersArray' => $likedUsersArray,
                    'likedUserId' => $likedUserId
            ));

            // return the result
            $lastLikedUsers = $this->__getLastLikedUsers($content);
            $userDetail = $this->User->getUserDetails($this->currentUserId);
            $currentUsername = Common::getUsername($userDetail['user_name'], $userDetail['first_name'], $userDetail['last_name']);

            $result = array(
                    'success' => true,
                    'postId' => $post['id'],
                    'likeCount' => $likeCount,
                    'lastLikedUsers' => $lastLikedUsers,
                    'othersLikeCount' => $likeCount - count($lastLikedUsers),
                    'currentUsername' => $currentUsername,
                    'postInfo' => $this->__preparePostInfo($post)
            );
            return $result;
    }

    /**
     * Function to get the details of the list of users who liked a post
     *
     * @param int $postId
     * @return array
     */
    public function getLikedUsersList($postId) {
            $post = $this->__getModelById('Post', $postId);
            $likedUsersList = array();
            if (!empty($post) && ($post['Post']['is_deleted'] == self::NOT_DELETED)) {
                    $contentJSON = $post['Post']['content'];
                    $content = json_decode($contentJSON, true);
                    if (isset($content['liked_users_list']) && ($content['liked_users_list'] !== '')) {
                            $this->userFriendsStatusArr = $this->_getCurrentUserFriendsStatusArray();
                            $likedUsersArray = explode(',', $content['liked_users_list']);
                            $likedUsers = $this->User->getUsersData($likedUsersArray);
                            foreach ($likedUsers as $likedUser) {
                                    $userDetail = $likedUser['User'];
                                    $likedUserId = $userDetail['id'];
                                    $likedUserFriendshipStatus = $this->__getLikedUserFriendshipStatus($likedUserId);
                                    $friendBtnText = $this->__getLikedUserFriendButtonText($likedUserFriendshipStatus);
                                    if ($likedUserFriendshipStatus === '') {
                                            $friendBtnOptions = array(
                                                    'id' => sprintf('add_button_%d', $likedUserId),
                                                    'onclick' => sprintf("addFriend('%d', true)", $likedUserId),
                                                    'data-spinner-color' => '#3581ED',
                                                    'data-style' => 'expand-right',
                                            );
                                    } else {
                                            if ($likedUserFriendshipStatus == MyFriends::STATUS_REQUEST_RECIEVED) {
                                                    $friendBtnOptions = array(
                                                            'class' => 'btn btn_more frnd_request_respond_btn',
                                                            'type' => 'button'
                                                    );
                                            } else {
                                                    $friendBtnOptions = array('disabled' => 'disabled');
                                            }
                                    }

                                    $Api = new ApiController ();

                                    // add user disease based on that users privacy settings
                                    $disease = NULL;
                                    $privacy = new UserPrivacySettings($likedUserId);
                                    $diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');

                                    if (
                                                    $diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC || $likedUserId == $this->currentUserId
                                    ) {
                                            $disease = $this->User->getUserDiseases($likedUserId);
                                    } elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
                                            $friendStatus = (int) $this->MyFriends->getFriendStatus($this->currentUserId, $likedUserId);
                                            if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
                                                    $disease = $this->User->getUserDiseases($likedUserId);
                                            }
                                    }

                                    $likedUsersList[] = array(
                                            'userId' => $likedUserId,
                                            'name' => Common::getUsername($userDetail['username'], $userDetail['first_name'], $userDetail['last_name']),
                                            'photo' => Common::getUserThumb($likedUserId, $userDetail['type'], 'x_small', 'media-object'),
                                            'diseases' => $disease,
                                            'location' => Common::getUserLocation($likedUser),
                                            'friendBtnText' => $friendBtnText,
                                            'friendBtnOptions' => $friendBtnOptions,
                                            'friendStatus' => $likedUserFriendshipStatus
                                    );
                            }
                    }
            }

            return $likedUsersList;
    }

    /**
     * Function to get the friends status array for the current user
     *
     * @return array
     */
    protected function _getCurrentUserFriendsStatusArray() {
            $userFriendsStatus = array();
            $userFriends = $this->MyFriends->getAllFriendsList($this->currentUserId);
            if (!empty($userFriends)) {
                    foreach ($userFriends as $friend) {
                            $friendId = $friend['user_id'];
                            $friendStatus = $friend['status'];
                            $userFriendsStatus[$friendId] = $friendStatus;
                    }
            }

            return $userFriendsStatus;
    }

    /**
     * Function to get the friendship status of current user with a liked user
     *
     * @param int $likedUserId
     * @return int
     */
    private function __getLikedUserFriendshipStatus($likedUserId) {
            if ($likedUserId != $this->currentUserId) {
                    $friendStatus = '';
                    if (!empty($this->userFriendsStatusArr)) {
                            $friendsStatusArr = $this->userFriendsStatusArr;
                            if (array_key_exists($likedUserId, $friendsStatusArr)) {
                                    $friendStatus = $friendsStatusArr[$likedUserId];
                            }
                    }
            } else {
                    $friendStatus = MyFriends::STATUS_SAME_USER;
            }

            return $friendStatus;
    }

    /**
     * Function to get the friend button text for a liked user
     *
     * @param int $friendshipStatus
     * @return string
     */
    private function __getLikedUserFriendButtonText($friendshipStatus) {
            $friendBtnText = '';
            if ($friendshipStatus === '') {
                    $friendBtnText = 'Add Friend';
            } else if ($friendshipStatus == MyFriends::STATUS_CONFIRMED) {
                    $friendBtnText = 'Friends';
            } else if ($friendshipStatus == MyFriends::STATUS_REQUEST_SENT) {
                    $friendBtnText = 'Request Sent';
            } else if ($friendshipStatus == MyFriends::STATUS_REQUEST_RECIEVED) {
                    $friendBtnText = 'Respond to friend request';
            }

            return $friendBtnText;
    }

    /**
     * Function to add a comment on a post
     *
     * @return array
     */
    public function addComment() {
            $requestData = $this->controller->request->data;
            if (isset($requestData['Comment']['comment_text']) && !empty($requestData['Comment']['comment_text'])) {
                    $commentData = $requestData['Comment'];
                    $postId = $commentData['post_id'];
                    if ($this->Post->exists($postId)) {
                            // check for commenting permission
                            $postData = $this->Post->findById($postId);
                            $post = $postData['Post'];
                            $postStatus = (int) $post['status'];
                            if ($post['is_deleted'] === true) {
                                    $error = true;
                                    $errorMessage = self::POST_DELETED_ERROR;
                                    $errorType = 'postDeleted';
                            } elseif ($postStatus === Post::STATUS_ABUSE_REPORTED) {
                                    $error = true;
                                    $errorMessage = __('This post is reported as abuse.');
                                    $errorType = 'postDeleted';
                            } elseif ($postStatus === Post::STATUS_BLOCKED) {
                                    $error = true;
                                    $errorMessage = __('This post is blocked by the admin.');
                                    $errorType = 'postDeleted';
                            } else {
                                    $commentPermission = true;
                                    if ($post['posted_in_type'] === Post::POSTED_IN_TYPE_USERS) {
                                            $userId = $this->currentUserId;
                                            $profileId = $post['posted_in'];
                                            $permissions = $this->User->getUserPermissionsInProfile($userId, $profileId);
                                            $commentPermission = $permissions['messaging'];
                                    }
                                    if ($commentPermission === false) {
                                            $error = true;
                                            $errorMessage = __('You dont have permission to comment on this profile!');
                                            $errorType = 'fatal';
                                    } elseif (isset($commentData['is_anonymous']) && ((bool) $commentData['is_anonymous'] === true)) {
                                            $anonymousPermission = $this->user['has_anonymous_permission'];
                                            if ($anonymousPermission === true && isset($permissions['anonymous_messaging'])) {
                                                    $anonymousPermission = $permissions['anonymous_messaging'];
                                            }
                                            if ($anonymousPermission === false) {
                                                    $error = true;
                                                    $errorMessage = __('You dont have permission to comment anonymously');
                                                    if ($post['posted_in_type'] === Post::POSTED_IN_TYPE_USERS) {
                                                            $errorMessage .= __(' on this profile!');
                                                    } else {
                                                            $errorMessage .= __('!');
                                                    }
                                                    $errorType = 'fatal';
                                            }
                                    }
                            }

                            if (!isset($error)) {
                                    if (!isset($commentData['is_anonymous'])) {
                                            $commentData['is_anonymous'] = false;
                                    }
                                    $data = array(
                                            'created_by' => $this->currentUserId,
                                            'ip' => $this->clientIp,
                                            'post_id' => $postId,
                                            'comment_text' => $commentData['comment_text'],
                                            'is_anonymous' => $commentData['is_anonymous']
                                    );

                                    if ((bool) $commentData['is_anonymous'] === false) {
                                            $latestHealthStatus = $this->HealthReading->getLatestHealthStatus($this->currentUserId);
                                            if (!empty($latestHealthStatus)) {
                                                    $data['health_status'] = $latestHealthStatus['health_status'];
                                            }
                                    }

                                    // save data
                                    if ($this->Comment->save($data, false)) {
                                            $commentId = $this->Comment->id;
                                            $comment = $this->Comment->findById($commentId);
                                            $this->__updatePostCommentJSONContent($comment);

                                            // add post comment notification task to queue
                                            ClassRegistry::init('Queue.QueuedTask')->createJob('PostCommentNotification', $comment);

                                            $result = array(
                                                    'success' => true,
                                                    'postId' => $postId,
                                                    'postInfo' => $this->__preparePostInfo($post),
                                                    'data' => $this->getCommentDisplayData($comment),
                                                    'commentCount' => $this->commentCount,
                                                    'commentedUserId' => $comment['Comment']['created_by'],
                                                    'postedUserId' => $post['post_by'],
                                                    'commentId' => $commentId
                                            );
                                    } else {
                                            $result = array(
                                                    'error' => true,
                                                    'message' => __('Failed to add comment')
                                            );
                                    }
                            } else {
                                    $result = array(
                                            'error' => $error,
                                            'message' => $errorMessage,
                                            'postId' => $postId,
                                            'errorType' => (isset($errorType) ? $errorType : 'normal')
                                    );
                            }
                    } else {
                            $result = array(
                                    'error' => true,
                                    'errorType' => 'postDeleted',
                                    'postId' => $postId,
                                    'message' => __(self::POST_DELETED_ERROR)
                            );
                    }
            } else {
                    $result = array(
                            'validationError' => true,
                            'message' => __('No comment entered')
                    );
            }

            return $result;
    }

    /**
     * Function to prepare an info array with only the post fields needed
     * for finding rooms for realtime update
     * 
     * @param array $post
     * @return array
     */
    private function __preparePostInfo($post) {
            $postInfo = array();
            $postInfoFields = array('posted_in_type', 'posted_in', 'post_by');
            foreach ($postInfoFields as $field) {
                    $postInfo[$field] = $post[$field];
            }
            return $postInfo;
    }

    /**
     * Function to update posts table with latest comments json
     *
     * @param array $commentData
     */
    private function __updatePostCommentJSONContent($commentData) {
            if (!empty($commentData)) {
                    $comment = $commentData['Comment'];

                    // get the post
                    $postId = $comment['post_id'];
                    $post = $this->__getModelById('Post', $postId);

                    if (!empty($post)) {
                            $postData = $post['Post'];
                            $this->commentCount = $postData['comment_count'];
                            $commentsArray = array();

                            // remove older comment, if limit has reached
                            if (!is_null($postData['comment_json_content']) && $postData['comment_json_content'] !== '') {
                                    $commentJSON = $postData['comment_json_content'];
                                    $commentsArray = json_decode($commentJSON);
                                    if (count($commentsArray) === self::LATEST_COMMENTS_COUNT) {
                                            array_pop($commentsArray);
                                    }
                            }

                            // modify the comment details in desired format
                            $commentDetails = $this->__getFormattedCommentData($commentData);

                            // add the new comment as the latest comment
                            array_unshift($commentsArray, $commentDetails);

                            // save the latest comments json on posts table
                            $this->Post->savePostCommentsJSON($postId, $commentsArray);
                    }
            }
    }

    /**
     * Function to get formatted comment data to save in posts table
     *
     * @param array $comment
     * @return array
     */
    private function __getFormattedCommentData($comment) {
            $commentData = $comment['Comment'];

            // filter out unwanted fields
            $fields = array('id', 'created_by', 'created', 'comment_text', 'is_anonymous', 'health_status');
            foreach ($commentData as $commentField => $commentFieldValue) {
                    if (!in_array($commentField, $fields)) {
                            unset($commentData[$commentField]);
                    }
            }

            // format commented user details
            $userDetail = $comment['User'];
            $commentData['created_user_name'] = Common::getUsername($userDetail['username'], $userDetail['first_name'], $userDetail['last_name']);
            $commentData['created_user_type'] = $userDetail['type'];

            return $commentData;
    }

    /**
     * Function to get comment display data from comment details
     *
     * @param array $comment
     * @return array
     */
    public function getCommentDisplayData($comment) {
            $timezone = $this->user['timezone'];
            $data = array();
            if (!empty($comment) && isset($comment['Comment']) && !empty($comment['Comment'])) {
                    $commentData = $comment['Comment'];
                    $commentedUserId = (int) $commentData['created_by'];
                    $commentedUser = $comment['User'];
                    $commentedUserHealthStatus = '';
                    $commentedUserSmileyClass = '';

                    if (isset($commentData['is_anonymous']) && ($commentData['is_anonymous'] === true)) {
                            $commentedUserLink = Common::getAnonymousUserLink();
                            $commentedUserThumb = Common::getAnonymousUserThumb('x_small', 'media-object');
                            $commentedUserProfileUrl = 'javascript:void(0)';
                            $commentedUserThumbCursorClass = 'cursor-default';
                    } else {
                            $commentedUserName = Common::getUsername($commentedUser['username'], $commentedUser['first_name'], $commentedUser['last_name']);
                            $showHoverCard = ($this->currentUserId > 0) ? true : false;
                            $commentedUserLink = Common::getUserProfileLink($commentedUserName, false, 'owner', $showHoverCard);
                            $commentedUserThumb = Common::getUserThumb($commentedUserId, $commentedUser['type'], 'x_small', 'media-object', 'img', $commentedUser['username']);
                            $commentedUserProfileUrl = Common::getUserProfileLink($commentedUserName, true);
                            $commentedUserThumbCursorClass = '';

                            // show commented user's health status at the time of commenting
                            App::uses('HealthStatus', 'Utility');
                            $healthStatus = (!empty($commentData['health_status'])) ? $commentData['health_status'] : HealthStatus::STATUS_VERY_GOOD;
                            $commentedUserHealthStatus = HealthStatus::getHealthStatusText($healthStatus);
                            $commentedUserSmileyClass = HealthStatus::getFeelingSmileyClass($healthStatus);
                    }

                    $data['commentedUserLink'] = $commentedUserLink;
                    $data['commentedUserAdminLink'] = Common::getUserAdminProfileLink($commentedUserId, $commentedUser['username']);
                    $data['commentedUserThumb'] = $commentedUserThumb;
                    $data['commentedUserOriginalThumb'] = Common::getUserThumb($commentedUserId, $commentedUser['type'], 'x_small', 'media-object');
                    $data['commentedUserProfileUrl'] = $commentedUserProfileUrl;
                    $data['commentedUserThumbCursorClass'] = $commentedUserThumbCursorClass;
                    $data['commentedTimeAgo'] = Date::timeAgoInWords($commentData['created'], $timezone);
                    $data['commentedTimeISO'] = Date::getISODate($commentData['created']);
                    $commentText = $commentData['comment_text'];
                    $data['commentText'] = $this->__getCommentDisplayText($commentText);
                    $data['truncatedCommentText'] = $this->__getTruncatedCommentDisplayText($commentText);
                    $data['commentId'] = $commentData['id'];
                    $this->postBy = $comment['Post']['post_by'];
                    $data['canDelete'] = $this->__canCurrentUserDeleteComment($commentData);
                    $data['canReportAbuse'] = ($this->currentUserId !== $commentedUserId);
                    $data['commentedUserHealthStatus'] = $commentedUserHealthStatus;
                    $data['commentedUserSmileyClass'] = $commentedUserSmileyClass;
            }

            return $data;
    }

    /**
     * Function to get the list of comments for a post
     *
     * @param int $postId
     * @return array
     */
    public function getPostCommentsList($postId) {
            $commentsList = array();
            $comments = $this->Comment->getPostComments($postId);
            if (!empty($comments)) {
                    foreach ($comments as $comment) {
                            $commentsList[] = $this->getCommentDisplayData($comment);
                    }
            }

            return $commentsList;
    }

    /**
     * Function to delete a comment
     *
     * @param int $commentId
     * @return array
     */
    public function deleteComment($commentId) {
            $comment = $this->Comment->findById($commentId);
            if (!empty($comment)) {
                    $this->Comment->delete($commentId);
                    $post = $comment['Post'];
                    if (!empty($post)) {
                            $postId = $comment['Comment']['post_id'];

                            // calculate the comment count
                            $commentCount = $post['comment_count'];
                            $commentCount--;

                            // update latest comments json, if latest comments are changed
                            $latestCommentsChanged = false;
                            if ($commentCount === 0) {
                                    $latestCommentsChanged = true;
                                    $latestComments = array();
                            } else {
                                    if (!is_null($post['comment_json_content']) && $post['comment_json_content'] !== '') {
                                            $commentJSON = $post['comment_json_content'];
                                            $commentsArray = json_decode($commentJSON, true);
                                            foreach ($commentsArray as $key => $commentRow) {
                                                    // check if deleted comment is in latest comments json
                                                    if ($commentRow['id'] == $commentId) {
                                                            if ($commentCount >= self::LATEST_COMMENTS_COUNT) {
                                                                    $latestCommentsChanged = true;

                                                                    // get latest post comments
                                                                    $latestComments = $this->__getLatestPostComments($postId);
                                                            } else {
                                                                    $latestCommentsChanged = true;

                                                                    // remove deleted comment from json list
                                                                    unset($commentsArray[$key]);
                                                                    $latestComments = array_values($commentsArray);
                                                            }
                                                            break;
                                                    }
                                            }
                                    }
                            }

                            if ($latestCommentsChanged === true) {
                                    // update the posts table with latest comments
                                    $this->Post->savePostCommentsJSON($postId, $latestComments);
                            }

                            // delete the site notifications related to the comment
                            $this->Notification->deleteCommentNotifications($commentId);
                            $result = array(
                                    'success' => true,
                                    'postId' => $postId,
                                    'postInfo' => $this->__preparePostInfo($post)
                            );
                            return $result;
                    }
            }
    }

    /**
     * Function to delete a post
     *
     * @param int $postId
     */
    public function deletePost($postId) {
            $post = $this->Post->findById($postId);
            if (!empty($post) && ($post['Post']['is_deleted'] == self::NOT_DELETED)) {
                    $this->Post->id = $postId;
                    $this->Post->set(
                                    array(
                                            'is_deleted' => self::IS_DELETED
                    ));
                    if ($this->Post->save()) {
                            // if the post was in a community,
                            // decrement the discussion count of the community
                            $postedInType = $post['Post']['posted_in_type'];
                            if ($postedInType == Post::POSTED_IN_TYPE_COMMUNITIES) {
                                    $communityId = $post['Post']['posted_in'];
                                    $this->Community->updateDiscussionCount($communityId, 'DEC');
                            }
                            $post_type = $post['Post']['post_type'];
                            $post_type_id = $post['Post']['post_type_id'];
                            if ($post_type == 'poll') {
                                    $this->Poll->id = $post_type_id;
                                    $this->Poll->set(array('is_deleted' => self::IS_DELETED));
                                    $this->Poll->save();
                            } elseif ($post_type == Post::POST_TYPE_VIDEO) {
                                    $this->Media->id = $post_type_id;
                                    $this->Media->set(array('is_deleted' => self::IS_DELETED));
                                    $this->Media->save();
                            }

                            // delete the site notifications related to the post
                            $this->Notification->deletePostNotifications($postId);

                            // return the deleted post details
                            $result = array(
                                    'postId' => $postId,
                                    'postInfo' => $this->__preparePostInfo($post['Post'])
                            );
                            return $result;
                    }
            }
    }

    /**
     * Function to get the latest comments (formatted) for a post
     *
     * @param int $postId
     * @return array
     */
    private function __getLatestPostComments($postId) {
            $commentsArray = array();
            $limit = self::LATEST_COMMENTS_COUNT;
            $latestComments = $this->Comment->getLatestPostComments($postId, $limit);
            if (!empty($latestComments)) {
                    foreach ($latestComments as $comment) {
                            $commentDetails = $this->__getFormattedCommentData($comment);
                            array_push($commentsArray, $commentDetails);
                    }
            }

            return $commentsArray;
    }

    /**
     * Function to get a model by id, without recursion
     *
     * @param string $model
     * @param int $id
     * @return array
     */
    protected function __getModelById($model, $id) {
            $this->$model->recursive = -1;
            return $this->$model->findById($id);
    }

    /**
     * Function to get the post filter options
     * 
     * @param string $type posted_in_type
     * @return array
     */
    public function getFilterOptions($type = null) {
            $options = array(
                    self::MOST_RECENT_FILTER => __('Most recent'),
                    self::MOST_ACTIVE_FILTER => __('Most active'),
                    self::MY_ACTIVITY_FILTER => __('My activity'),
                    self::POLL_FILTER => __('Poll')
            );

            if (!is_null($type) && $type === Post::POSTED_IN_TYPE_USERS) {
                    $options[self::HEALTH_STATUS_FILTER] = __('Health Status');
            }

            return $options;
    }

    /**
     * Function to check if currently logged in user can view the health of the 
     * specified user
     * 
     * @param int $userId
     * @return boolean
     */
    private function __canCurrentUserViewHealthOfUser($userId) {
            $userId = (int) $userId;
            $currentUserId = $this->currentUserId;
            if ($userId === $currentUserId) {
                    $viewHealth = true;
            } else {
                    $viewHealth = false;
                    $privacy = new UserPrivacySettings($userId);
                    $healthViewPermittedTo = (int) $privacy->__get('view_your_health');
                    if ($healthViewPermittedTo === $privacy::PRIVACY_PUBLIC) {
                            $viewHealth = true;
                    } elseif ($healthViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
                            $friendStatus = (int) $this->MyFriends->getFriendStatus($currentUserId, $userId);
                            if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
                                    $viewHealth = true;
                            }
                    }
            }
            return $viewHealth;
    }

    /**
     * Function to get post filter settings
     *
     * @param array $data
     * @return array
     */
    public function getFilterSettings($data) {
            $isNewsFeed = false;
            $isDiseaseNewsFeed = false;
            if (isset($data['isLibray']) && $data['isLibray'] == 1) {
                    $favoritePostIds = $this->User->getFavoritePostIds($this->currentUserId);
                    $conditions = array('Post.id' => $favoritePostIds);
            } else {
                    if (($data['postedInType'] === Post::POSTED_IN_TYPE_USERS) && ((int) $data['postedIn'] === $this->currentUserId)) {
                            $isNewsFeed = true;
                            $this->newsFeedId = $this->currentUserId;
                    } else if ($data['postedInType'] === Post::POSTED_IN_TYPE_DISEASES) {
                            $isDiseaseNewsFeed = true;
                    } else {
                            $conditions = array(
                                'Post.posted_in' => $data['postedIn'],
                                'Post.posted_in_type' => $data['postedInType'],
                                'Post.is_deleted' => self::NOT_DELETED,
                                'Post.status' => Post::STATUS_NORMAL
                            );
                    }
            }

            $filterValue = (int) $data['filterValue'];

            $order = array('Post.created' => 'DESC');
            $joins = array();
            $group = null;
            if ($isNewsFeed === true) {
                    $settings = $this->getNewsFeedQuerySettings();
                    $conditions = $settings['conditions'];
                    if ($filterValue === self::MOST_RECENT_FILTER) {
                            $order = $settings['order'];
                            $joins = $settings['joins'];
                            $group = $settings['group'];
                    }
            } elseif ($isDiseaseNewsFeed === true) {
                    $diseaseId = $data['postedIn'];
                    $settings = $this->getDiseaseNewsFeedQuerySettings($diseaseId);
                    $conditions = $settings['conditions'];
            } elseif ($data['postedInType'] === Post::POSTED_IN_TYPE_USERS) {
                    $profileUserId = $data['postedIn'];
                    $viewHealth = $this->__canCurrentUserViewHealthOfUser($profileUserId);
                    if ($viewHealth === false) {
                            $conditions['Post.post_type !='] = Post::POST_TYPE_HEALTH;
                    }
            }

            switch ($filterValue) {
                    case self::MOST_ACTIVE_FILTER:
                            $order = array_merge(array('Post.comment_count' => 'DESC'), $order);
                            break;
                    case self::MY_ACTIVITY_FILTER:
                            $filterConditions = array(
                                    'OR' => array(
                                            'Post.post_by' => $this->currentUserId,
                                            'Comment.id >' => 0,
                                            'Like.id >' => 0
                                    )
                            );
                            $joins = array(
                                    array(
                                            'table' => 'comments',
                                            'type' => 'LEFT',
                                            'alias' => 'Comment',
                                            'conditions' => array(
                                                    'Post.id = Comment.post_id',
                                                    'Comment.created_by' => $this->currentUserId
                                            )
                                    ),
                                    array(
                                            'table' => 'likes',
                                            'type' => 'LEFT',
                                            'alias' => 'Like',
                                            'conditions' => array(
                                                    'Post.id = Like.post_id',
                                                    'Like.created_by' => $this->currentUserId
                                            )
                                    )
                            );
                            $group = 'Post.id';
                            break;
                    case self::POLL_FILTER:
                            $filterConditions = array(
                                    'Post.post_type' => Post::POST_TYPE_POLL
                            );
                            break;
                    case self::TEXT_FILTER:
                            $filterConditions = array(
                                    'Post.post_type' => Post::POST_TYPE_TEXT
                            );
                            break;
                    case self::LINKS_FILTER:
                            $filterConditions = array(
                                    'Post.post_type' => Post::POST_TYPE_LINK
                            );
                            break;
                    case self::IMAGES_FILTER:
                            $filterConditions = array(
                                    'Post.post_type' => Post::POST_TYPE_IMAGE
                            );
                            break;
                    case self::VIDEOS_FILTER:
                            $filterConditions = array(
                                    'Post.post_type' => Post::POST_TYPE_VIDEO
                            );
                            break;
                    case self::HEALTH_STATUS_FILTER:
                            $filterConditions = array(
                                    'Post.post_type' => Post::POST_TYPE_HEALTH
                            );
                            break;
                    case self::NEW_UPDTES_FILTER:
                            $order = array('Post.created' => 'DESC');
                            break;
            }

            if (isset($filterConditions)) {
                    $conditions = array(
                            'AND' => array(
                                    $conditions
                            ),
                            $filterConditions
                    );
            }

            $settings = array(
                    'conditions' => $conditions,
                    'order' => $order,
                    'joins' => $joins,
                    'group' => $group
            );

            return $settings;
    }

    /**
     * Function to get the pagination settings array for the news feed query
     * 
     * @return array
     */
    public function getNewsFeedQuerySettings() {
            $userId = $this->currentUserId;

            $basicConditions = array(
                    'Post.is_deleted' => self::NOT_DELETED,
                    'Post.status' => Post::STATUS_NORMAL,
            );

            // current user profile posts
            $otherConditions[] = array(
                    'Post.posted_in' => $userId,
                    'Post.posted_in_type' => Post::POSTED_IN_TYPE_USERS
            );

            $followingPages = $this->FollowingPage->getUserFollowingPages($userId, true);

            // posts from the disease pages that the user is following
            if (isset($followingPages[FollowingPage::DISEASE_TYPE])) {
                    $followingDiseases = $followingPages[FollowingPage::DISEASE_TYPE];
                    $otherConditions[] = array(
                            'Post.posted_in' => $followingDiseases,
                            'Post.posted_in_type' => Post::POSTED_IN_TYPE_DISEASES,
                            'Post.post_type !=' => Post::POST_TYPE_QUESTION
                    );
            }

            // posts from the community pages that the user is following
            if (isset($followingPages[FollowingPage::COMMUNITY_TYPE])) {
                    $followingCommunities = $followingPages[FollowingPage::COMMUNITY_TYPE];
                    $otherConditions[] = array(
                            'Post.posted_in' => $followingCommunities,
                            'Post.posted_in_type' => Post::POSTED_IN_TYPE_COMMUNITIES,
                            'Post.post_type !=' => Post::POST_TYPE_COMMUNITY
                    );
            }

            // posts from the event pages that the user is following
            if (isset($followingPages[FollowingPage::EVENT_TYPE])) {
                    $followingEvents = $followingPages[FollowingPage::EVENT_TYPE];
                    $otherConditions[] = array(
                            'Post.posted_in' => $followingEvents,
                            'Post.posted_in_type' => Post::POSTED_IN_TYPE_EVENTS,
                            'Post.post_type !=' => Post::POST_TYPE_EVENT
                    );
            }

            // posts from the profile pages that the user is following (based on privacy)
            if (isset($followingPages[FollowingPage::USER_TYPE])) {
                    $followingUsers = $followingPages[FollowingPage::USER_TYPE];
                    $privacySettings = new UserPrivacySettings();
                    $feedAllowedUsers = $privacySettings->getFeedAllowedUsers($followingUsers);
                    if (!empty($feedAllowedUsers['activity_feed_allowed_users'])) {
                            $otherConditions[] = array(
                                    'Post.posted_in' => $feedAllowedUsers['activity_feed_allowed_users'],
                                    'Post.posted_in_type' => Post::POSTED_IN_TYPE_USERS,
                                    'Post.post_type !=' => Post::POST_TYPE_HEALTH
                            );
                    }
                    if (!empty($feedAllowedUsers['health_feed_allowed_users'])) {
                            $otherConditions[] = array(
                                    'Post.posted_in' => $feedAllowedUsers['health_feed_allowed_users'],
                                    'Post.posted_in_type' => Post::POSTED_IN_TYPE_USERS,
                                    'Post.post_type' => Post::POST_TYPE_HEALTH
                            );
                    }
            }

            $joins = array(
                    array(
                            'table' => 'comments',
                            'type' => 'LEFT',
                            'alias' => 'Comment',
                            'conditions' => array(
                                    'Post.id = Comment.post_id',
                                    'Comment.created_by' => $userId
                            )
                    ),
                    array(
                            'table' => 'likes',
                            'type' => 'LEFT',
                            'alias' => 'Like',
                            'conditions' => array(
                                    'Post.id = Like.post_id',
                                    'Like.created_by' => $userId
                            )
                    )
            );
            $conditions = array(
                    $basicConditions,
                    'OR' => $otherConditions
            );
            $group = 'Post.id';
            $order = 'CASE
                                    WHEN (Comment.id IS NOT NULL OR Like.id IS NOT NULL) THEN Post.modified DESC
                                    ELSE Post.created DESC
                            END';
            $settings = array(
                    'joins' => $joins,
                    'conditions' => $conditions,
                    'group' => $group,
                    'order' => $order,
                    'limit' => self::POSTS_PER_PAGE
            );
            return $settings;
    }

    /**
     * Function to get the pagination settings array for the disease page
     * news feed query
     * 
     * @return array
     */
    public function getDiseaseNewsFeedQuerySettings($diseaseId) {
            $this->CommunityDisease = ClassRegistry::init('CommunityDisease');
            $this->EventDisease = ClassRegistry::init('EventDisease');
            $basicConditions = array(
                    'Post.is_deleted' => self::NOT_DELETED,
                    'Post.status' => Post::STATUS_NORMAL,
                    'Post.post_type !=' => Post::POST_TYPE_QUESTION
            );

            // posts on the disease page
            $otherConditions[] = array(
                    'Post.posted_in' => $diseaseId,
                    'Post.posted_in_type' => Post::POSTED_IN_TYPE_DISEASES
            );

            // posts from the open/sitewide communities that the disease is tagged
            $diseaseCommunities = $this->CommunityDisease->getPublicCommunitiesWithDisease($diseaseId);
            if (!empty($diseaseCommunities)) {
                    $otherConditions[] = array(
                            'Post.posted_in' => $diseaseCommunities,
                            'Post.posted_in_type' => Post::POSTED_IN_TYPE_COMMUNITIES,
                            'Post.post_type !=' => Post::POST_TYPE_COMMUNITY
                    );
            }

            // posts from the public/sitewide events that the disease is tagged
            $diseaseEvents = $this->EventDisease->getPublicEventsWithDisease($diseaseId);
            if (!empty($diseaseEvents)) {
                    $otherConditions[] = array(
                            'Post.posted_in' => $diseaseEvents,
                            'Post.posted_in_type' => Post::POSTED_IN_TYPE_EVENTS,
                            'Post.post_type !=' => Post::POST_TYPE_EVENT
                    );
            }
            $conditions = array(
                    $basicConditions,
                    'OR' => $otherConditions
            );
            $order = array(
                    'Post.created' => 'DESC'
            );
            $settings = array(
                    'conditions' => $conditions,
                    'order' => $order,
                    'limit' => self::POSTS_PER_PAGE
            );
            return $settings;
    }

    /**
     * Function to get the pagination settings array for the disease page
     * questions query
     * 
     * @return array
     */
    public function getDiseaseQuestionsQuerySettings($diseaseId) {
            $conditions = array(
                    'Post.is_deleted' => self::NOT_DELETED,
                    'Post.status' => Post::STATUS_NORMAL,
                    'Post.post_type' => Post::POST_TYPE_QUESTION,
                    'Post.posted_in' => $diseaseId,
                    'Post.posted_in_type' => Post::POSTED_IN_TYPE_DISEASES
            );
            $order = array(
                    'Post.created' => 'DESC'
            );
            $settings = array(
                    'conditions' => $conditions,
                    'order' => $order
            );
            return $settings;
    }

    function createPoll($requestData) {
            $postData = $requestData;
            $data = array(
                    'created_by' => $this->currentUserId,
                    'posted_in' => $postData['posted_in'],
                    'posted_in_type' => $postData['posted_in_type'],
                    'title' => $postData['poll_title']
            );
            $this->Poll->create();
            if ($this->Poll->save($data, true)) {
                    $pollId = $this->Poll->id;
                    $pollChoices = array_filter($postData['poll_options']);
                    if (count($pollChoices) === count(array_unique($pollChoices))) {
                            // array has no duplicated values
                            foreach ($postData['poll_options'] as $options) {
                                    if ($options != NULL) {
                                            try {
                                                    // add poll options in PollChoices table
                                                    $this->PollChoices->create();
                                                    $data = array(
                                                            'poll_id' => $pollId,
                                                            'option' => $options
                                                    );
                                                    $this->PollChoices->save($data);
                                                    $error = false;
                                            } catch (Exception $e) {
                                                    $error = true;
                                            }
                                    }
                            }
                            $result['poll_id'] = $pollId;
                    } else {
                            // array has duplicated values.
                            $result['error'] = true;
                            $result['error_message'] = 'options has duplicated values';
                    }
            } else {
                    $result['error'] = true;
                    $result['error_message'] = 'Please enter poll tittle';
            }
            return $result;
    }

    function updatePollVote($pollId, $optionId) {
            if (isset($pollId) && isset($optionId)) {
                    $data = array(
                            'user_id' => $this->currentUserId,
                            'choice_id' => $optionId,
                            'poll_id' => $pollId,
                            'ip_address' => $this->clientIp
                    );
                    $pollDataDetails = $this->Poll->getPoll($pollId);

                    if (isset($pollDataDetails) && isset($pollDataDetails['Post'][0]['id'])) {
                        $postData = $pollDataDetails['Post'][0];
                        $postStatus = (int) $postData['status'];
                        if ($postData['is_deleted'] === true) {
                                $error = true;
                                $errorMessage = self::POST_DELETED_ERROR;
                                $errorType = 'postDeleted';
                        } elseif ($postStatus === Post::STATUS_ABUSE_REPORTED) {
                                $error = true;
                                $errorMessage = __('This post is reported as abuse.');
                                $errorType = 'postDeleted';
                        } elseif ($postStatus === Post::STATUS_BLOCKED) {
                                $error = true;
                                $errorMessage = __('This post is blocked by the admin.');
                                $errorType = 'postDeleted';
                        }
                        if (!isset($error) || $error != true) {
                            $isUserVoted = $this->PollVote->isUserVoted($pollId, $this->currentUserId);
                            if ($isUserVoted === false) {
                                $this->PollVote->create();
                                if ($this->PollVote->save($data, true)) {
                                        $result = $this->PollChoices->changeVoteCount($optionId);
                                        $isUserVoted = true;
                                } else {
                                        $isUserVoted = FALSE;
                                        $result = array(
                                                'error' => true,
                                                'message' => __('Failed to add your vote')
                                        );
                                }
                            }

                            if ($isUserVoted === true) {
                                    $result['poll_details'] = $pollDataDetails;
                                    $conditions = array(
                                            'Post.post_type_id' => $pollId,
                                            'Post.post_type' => Post::POST_TYPE_POLL,
                                    );
                                    $postData = $this->Post->find('first', array('conditions' => $conditions));

                                    if (isset($result['poll_details'])) {
                                            // add poll vote site notifications adding job to queue
                                            $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                                            $this->QueuedTask->createJob('PollVoteNotification', array(
                                                    'pollData' => $result['poll_details'],
                                                    'postData' => $postData,
                                                    'pollVoteData' => $data
                                            ));
                                    }

                                    $votedUsers = $this->PollVote->getPollAttendedUsers($pollId);
                                    $result = array(
                                            'success' => true,
                                            'data' => $this->getPostDisplayData($postData),
                                            'users' => $votedUsers
                                    );
                            }
                        } else {
                                $result = array(
                                        'error' => $error,
                                        'message' => $errorMessage,
                                        'errorType' => (isset($errorType) ? $errorType : 'normal')
                                );
                        }
                    } else {
                            $result = array(
                                    'error' => true,
                                    'errorType' => 'postDeleted',
                                    'message' => __(self::POST_DELETED_ERROR)
                            );
                    }
            } else {
                    $result = array(
                            'error' => true,
                            'errorType' => 'postDeleted',
                            'message' => __(self::POST_DELETED_ERROR)
                    );
            }
            return $result;
    }

    function getPollDetails($pollId) {
            $result['voted_user_details'] = $this->PollVote->getPollVoteDetails($pollId);
            $conditions = array(
                    'Post.post_type_id' => $pollId,
                    'Post.post_type' => Post::POST_TYPE_POLL,
            );
            $postData = $this->Post->find('first', array('conditions' => $conditions));
            $temData = $this->getPostDisplayData($postData);
            $temData['voted_user_details'] = $this->PollVote->getPollVoteDetails($pollId);
            $result = array(
                    'success' => true,
                    'data' => $temData
            );
            return $result;
    }

    function updateFavoritePostsList($postId, $status) {
            $result['success'] = false;
            $currentUserId = $this->currentUserId;
            $favoritePosts = array();
            $favoritePosts = $this->User->getFavoritePosts($currentUserId);
            if ($favoritePosts == NULL) {
                    $favoritePosts = array();
            }
            $tempArray = array(
                    'post_id' => $postId
            );
            $this->User->id = $currentUserId;
            switch ($status) {
                    case 1:
                            if (in_array($tempArray, $favoritePosts)) {
                                    $result['success'] = true;
                            } else {
                                    array_push($favoritePosts, $tempArray);
                                    $this->User->set(
                                                    array(
                                                            'favorite_posts' => json_encode($favoritePosts)
                                                    )
                                    );
                                    if ($this->User->save()) {
                                            $result['success'] = true;
                                    }
                            }
                            break;
                    case 0:
                            if (in_array($tempArray, $favoritePosts)) {
                                    if (($key = array_search($tempArray, $favoritePosts)) !== false) {
                                            unset($favoritePosts[$key]);
                                            $this->User->set(
                                                            array(
                                                                    'favorite_posts' => json_encode($favoritePosts)
                                                            )
                                            );
                                            if ($this->User->save()) {
                                                    $result['success'] = true;
                                            }
                                    }
                            } else {
                                    $result['success'] = false;
                            }
                            break;
            }
            return $result;
    }

    function getPostedInDetails($type = NULL, $typeId = NULL) {
            $result = array();
            $data = NULL;
            $result['posted_in_type'] = NULL;
            $result['posted_in_name'] = NULL;
            $result['posted_in_description'] = NULL;
            $result['posted_in_url'] = NULL;
            if ($type != NULL && $typeId != NULL) {
                    switch ($type) {
                            case POST::POSTED_IN_TYPE_COMMUNITIES :
                                    $result['posted_in_type'] = 'community';
                                    if ($this->Community->exists($typeId)) {
                                            $data = $this->Community->getCommunity($typeId);
                                            $result['posted_in_name'] = $data['name'];
                                            $result['posted_in_description'] = $data['description'];
                                            $result['posted_in_url'] = sprintf('/community/details/index/%d', $typeId);
                                    }
                                    break;
                            case POST::POSTED_IN_TYPE_DISEASES :
                                    $result['posted_in_type'] = 'disease';
                                    if ($this->Disease->exists($typeId)) {
                                            $data = $this->Disease->get_disease_details_by_id($typeId);
                                            $result['posted_in_name'] = $data['Disease']['name'];
                                            $result['posted_in_description'] = NULL;
                                            $result['posted_in_url'] = sprintf('/condition/index/%d', $typeId);
                                    }
                                    break;
                            case POST::POSTED_IN_TYPE_EVENTS :
                                    $result['posted_in_type'] = 'event';
                                    if ($this->Event->exists($typeId)) {
                                            $data = $this->Event->getEvent($typeId);
                                            $result['posted_in_name'] = $data['name'];
                                            $result['posted_in_description'] = $data['description'];
                                            $result['posted_in_url'] = sprintf('/event/details/index/%d', $typeId);
                                    }
                                    break;
                            case POST::POSTED_IN_TYPE_USERS :
                                    $result['posted_in_type'] = 'user profile';
                                    if ($this->User->exists($typeId)) {
                                            $data = $this->User->getUserDetails($typeId);
                                            $result['posted_in_name'] = $data['user_name'];
                                            $result['posted_in_description'] = NULL;
                                            $result['posted_in_url'] = Common::getUserProfileLink($data['user_name'], TRUE);
                                    }
                                    break;
                            case POST::POSTED_IN_TYPE_TEAM :
                                    $result['posted_in_type'] = 'team';
                                    if ($this->Team->exists($typeId)) {
                                            $data = $this->Team->findById($typeId, array('name'));
                                            $result['posted_in_name'] = $data['Team']['name'];
                                            $result['posted_in_url'] = "/myteam/{$typeId}";
                                    }
                                    break;
                    }
            }
            return $result;
    }

    /**
     * Function to report abuse a comment
     *
     * @param int $commentId
     * @param string $reason
     * @param string $action
     * @return array
     */
    public function reportAbuseComment($commentId, $reason, $action) {
            $comment = $this->Comment->findById($commentId);
            if (!empty($comment)) {
                    $commentStatus = (int) $comment['Comment']['status'];
                    if ($commentStatus === Comment::STATUS_ABUSE_REPORTED) {
                            $error = __('This comment was already reported as abuse.');
                    } elseif ($commentStatus === Comment::STATUS_BLOCKED) {
                            $error = __('This comment is already blocked by the admin.');
                    } else {
                            $this->Comment->id = $commentId;
                            $this->Comment->saveField('status', Comment::STATUS_ABUSE_REPORTED);
                            $this->AbuseReport->addCommentAbuseReport($comment['Comment'], $this->currentUserId, $reason);
                            $this->User->blockAnonymousPermission($comment['Comment']['created_by']);
                            $this->__handleReportAbuseFurtherActions($comment['Comment']['created_by'], $action);
                            $post = $comment['Post'];
                            if (!empty($post)) {
                                    $postId = $comment['Comment']['post_id'];

                                    //send abuse report reviewing mail to reported user
                                    $this->__sendAbuseReportReviewingMail();

                                    // calculate the comment count
                                    $commentCount = $post['comment_count'];
                                    $commentCount--;

                                    // update latest comments json, if latest comments are changed
                                    $latestCommentsChanged = false;
                                    if ($commentCount === 0) {
                                            $latestCommentsChanged = true;
                                            $latestComments = array();
                                    } else {
                                            if (!is_null($post['comment_json_content']) && $post['comment_json_content'] !== '') {
                                                    $commentJSON = $post['comment_json_content'];
                                                    $commentsArray = json_decode($commentJSON, true);
                                                    foreach ($commentsArray as $key => $commentRow) {
                                                            // check if abuse reported comment is in latest comments json
                                                            if ($commentRow['id'] == $commentId) {
                                                                    if ($commentCount >= self::LATEST_COMMENTS_COUNT) {
                                                                            $latestCommentsChanged = true;

                                                                            // get latest post comments
                                                                            $latestComments = $this->__getLatestPostComments($postId);
                                                                    } else {
                                                                            $latestCommentsChanged = true;

                                                                            // remove abuse reported comment from json list
                                                                            unset($commentsArray[$key]);
                                                                            $latestComments = array_values($commentsArray);
                                                                    }
                                                                    break;
                                                            }
                                                    }
                                            }
                                    }

                                    if ($latestCommentsChanged === true) {
                                            // update the posts table with latest comments
                                            $this->Post->savePostCommentsJSON($postId, $latestComments);
                                    }

                                    // delete the site notifications related to the comment
                                    $this->Notification->deleteCommentNotifications($commentId);
                            }
                    }
            } else {
                    $error = 'Oops... This comment seems to be deleted!';
            }

            if (isset($error)) {
                    $result = array(
                            'error' => true,
                            'message' => $error
                    );
            } else {
                    $result = array(
                            'success' => true
                    );
            }
            return $result;
    }

    /**
     * Function to report abuse a post
     *
     * @param int $postId
     * @param string $reason
     * @param string $action
     * @return array
     */
    public function reportAbusePost($postId, $reason, $action) {
            $post = $this->Post->findById($postId);
            if (!empty($post)) {
                    $postData = $post['Post'];
                    $postStatus = (int) $postData['status'];
                    if ($postData['is_deleted'] === true) {
                            $error = self::POST_DELETED_ERROR;
                    } elseif ($postStatus === Post::STATUS_ABUSE_REPORTED) {
                            $error = __('This post was already reported as abuse.');
                    } elseif ($postStatus === Post::STATUS_BLOCKED) {
                            $error = __('This post is already blocked by the admin.');
                    } else {
                            $this->Post->id = $postId;
                            if ($this->Post->saveField('status', Post::STATUS_ABUSE_REPORTED)) {

                                    $this->AbuseReport->addPostAbuseReport($post['Post'], $this->currentUserId, $reason);
                                    $this->User->blockAnonymousPermission($post['Post']['post_by']);

                                    // handle the further actions
                                    $this->__handleReportAbuseFurtherActions($post['Post']['post_by'], $action);

                                    //send abuse report reviewing mail to reported user
                                    $this->__sendAbuseReportReviewingMail();

                                    // if the post was in a community,
                                    // decrement the discussion count of the community
                                    $postedInType = $post['Post']['posted_in_type'];
                                    if ($postedInType == Post::POSTED_IN_TYPE_COMMUNITIES) {
                                            $communityId = $post['Post']['posted_in'];
                                            $this->Community->updateDiscussionCount($communityId, 'DEC');
                                    }

                                    // delete the site notifications related to the post
                                    $this->Notification->deletePostNotifications($postId);
                            }
                    }
            } else {
                    $error = self::POST_DELETED_ERROR;
            }

            if (isset($error)) {
                    $result = array(
                            'error' => true,
                            'message' => $error
                    );
            } else {
                    $result = array(
                            'success' => true
                    );
            }
            return $result;
    }

    /**
     * Function to handle further actions of report abuse
     * 
     * @param int $userId
     * @param string $action
     */
    private function __handleReportAbuseFurtherActions($userId, $action) {
            if (!is_null($action)) {
                    $blockedUsers = array();
                    if (isset($this->user['blocked_users'])) {
                            $userBlockedUsersJSON = $this->user['blocked_users'];
                            $blockedUsers = json_decode($userBlockedUsersJSON, true);
                    }
                    switch ($action) {
                            case 'block_anonymous_messaging':
                                    $anonymousMessagingBlockedUsers = array();
                                    if (!empty($blockedUsers['anonymous_messaging'])) {
                                            $anonymousMessagingBlockedUsers = $blockedUsers['anonymous_messaging'];
                                    }
                                    if (!in_array($userId, $anonymousMessagingBlockedUsers)) {
                                            array_push($anonymousMessagingBlockedUsers, $userId);
                                            $blockedUsers['anonymous_messaging'] = $anonymousMessagingBlockedUsers;
                                    }
                                    break;
                            case 'block_messaging':
                                    $messagingBlockedUsers = array();
                                    if (!empty($blockedUsers['messaging'])) {
                                            $messagingBlockedUsers = $blockedUsers['messaging'];
                                    }
                                    if (!in_array($userId, $messagingBlockedUsers)) {
                                            array_push($messagingBlockedUsers, $userId);
                                            $blockedUsers['messaging'] = $messagingBlockedUsers;
                                    }
                                    break;
                            case 'block_access':
                                    $accessBlockedUsers = array();
                                    if (!empty($blockedUsers['access'])) {
                                            $accessBlockedUsers = $blockedUsers['access'];
                                    }
                                    if (!in_array($userId, $accessBlockedUsers)) {
                                            array_push($accessBlockedUsers, $userId);
                                            $blockedUsers['access'] = $accessBlockedUsers;
                                    }
                                    break;
                    }

                    if (!empty($blockedUsers)) {
                            $blockedUsersJSON = json_encode($blockedUsers);
                            $this->User->id = $this->currentUserId;
                            if ($this->User->saveField('blocked_users', $blockedUsersJSON)) {
                                    $currentUser = $this->User->read(null, $this->currentUserId);
                                    $this->controller->Session->write('Auth', $currentUser);
                            }
                    }
            }
    }

    /**
     * Function to add abuse report reviewing email to the abuse reported user
     */
    private function __sendAbuseReportReviewingMail() {
            $reportedUser = $this->user;
            $toEmail = $reportedUser['email'];
            $templateData = array(
                    'username' => $reportedUser['username']
            );
            $templateId = EmailTemplateComponent::REPORT_ABUSE_REVIEW_EMAIL_TEMPLATE;
            $emailTemplateData = $this->EmailTemplate->getEmailTemplate($templateId, $templateData);
            $emailTemplate = $emailTemplateData['EmailTemplate'];
            $mailData = array(
                    'subject' => $emailTemplate['template_subject'],
                    'to_name' => $templateData['username'],
                    'to_email' => $toEmail,
                    'content' => json_encode($templateData),
                    'email_template_id' => $templateId,
                    'module_info' => 'Post',
                    'priority' => Email::DEFAULT_SEND_PRIORITY
            );
            return $this->EmailQueue->createEmailQueue($mailData);
    }

    /**
     * Function to reject comment abuse report
     * 
     * @param int $commentId 
     */
    public function rejectCommentAbuseReport($commentId) {
            $comment = $this->Comment->findById($commentId);
            if (!empty($comment)) {
                    // change the status of the comment
                    $this->Comment->id = $commentId;
                    $this->Comment->saveField('status', Comment::STATUS_NORMAL);

                    // update the comment post with latest comments
                    $postId = $comment['Comment']['post_id'];
                    $latestComments = $this->__getLatestPostComments($postId);
                    $this->Post->savePostCommentsJSON($postId, $latestComments);
            }
    }

    /**
     * Function to reject post abuse report
     * 
     * @param int $postId 
     */
    public function rejectPostAbuseReport($postId) {
            $post = $this->__getModelById('Post', $postId);
            if (!empty($post)) {
                    // change the status of the post
                    $this->Post->id = $postId;
                    $this->Post->saveField('status', Post::STATUS_NORMAL);

                    // if the post was in a community,
                    // increment the discussion count of the community
                    $postedInType = $post['Post']['posted_in_type'];
                    if ($postedInType == Post::POSTED_IN_TYPE_COMMUNITIES) {
                            $communityId = $post['Post']['posted_in'];
                            $this->Community->updateDiscussionCount($communityId, 'INC');
                    }
            }
    }

    /**
     * Function to add a question on a disease forum
     *
     * @return array
     */
    public function addQuestion() {
            $requestData = $this->controller->request->data;
            if (isset($requestData['Post'])) {
                    $postData = $requestData['Post'];
                    $contentData = array('question' => $postData['question']);
                    $latestHealthStatus = $this->HealthReading->getLatestHealthStatus($this->currentUserId);
                    if (!empty($latestHealthStatus)) {
                            $contentData['health_status'] = $latestHealthStatus['health_status'];
                    }
                    $data = array(
                            'post_by' => $this->currentUserId,
                            'ip' => $this->clientIp,
                            'posted_in' => $postData['posted_in'],
                            'posted_in_type' => Post::POSTED_IN_TYPE_DISEASES,
                            'post_type' => Post::POST_TYPE_QUESTION,
                            'content' => json_encode($contentData)
                    );

                    if ($this->Post->save($data, false)) {
                            $postId = $this->Post->id;
                            $post = $this->Post->findById($postId);

                            // add notification adding task to queue
                            ClassRegistry::init('Queue.QueuedTask')->createJob('QuestionNotification', $post['Post']);

                            $result = array(
                                    'success' => true,
                                    'postId' => $postId,
                                    'data' => $this->getPostDisplayData($post)
                            );
                    } else {
                            $result = array(
                                    'error' => true,
                                    'message' => __('Failed to add question')
                            );
                    }
            } else {
                    $result = array(
                            'error' => true,
                            'message' => __('No data posted')
                    );
            }

            return $result;
    }

    /**
     * Function to add an answer on a post
     *
     * @return array
     */
    public function addAnswer() {
            $requestData = $this->controller->request->data;
            if (!empty($requestData['Answer']['answer'])) {
                    $answerData = $requestData['Answer'];
                    $postId = $answerData['post_id'];
                    $post = $this->Post->findById($postId);
                    if (!empty($post) && $post['Post']['is_deleted'] === false) {
                            if (!isset($answerData['is_anonymous'])) {
                                    $answerData['is_anonymous'] = false;
                            }
                            $data = array(
                                    'created_by' => $this->currentUserId,
                                    'ip' => $this->clientIp,
                                    'post_id' => $postId,
                                    'answer' => $answerData['answer'],
                                    'is_anonymous' => $answerData['is_anonymous']
                            );

                            if ((bool) $answerData['is_anonymous'] === false) {
                                    $latestHealthStatus = $this->HealthReading->getLatestHealthStatus($this->currentUserId);
                                    if (!empty($latestHealthStatus)) {
                                            $data['health_status'] = $latestHealthStatus['health_status'];
                                    }
                            }

                            // save data
                            if ($this->Answer->save($data, false)) {
                                    $answerId = $this->Answer->id;
                                    $answerData = $this->Answer->findById($answerId);

                                    // add notification task to queue
                                    ClassRegistry::init('Queue.QueuedTask')->createJob('QuestionAnswerNotification', $answerData);

                                    $result = array(
                                            'success' => true,
                                            'postId' => $postId,
                                            'data' => $this->getAnswerDisplayData($answerData),
                                            'questionAskedUserId' => $answerData['Post']['post_by'],
                                            'answeredUserId' => $answerData['Answer']['created_by'],
                                            'answerId' => $answerId
                                    );
                            } else {
                                    $result = array(
                                            'error' => true,
                                            'message' => __('Failed to add answer')
                                    );
                            }
                    } else {
                            $result = array(
                                    'error' => true,
                                    'errorType' => 'postDeleted',
                                    'postId' => $postId,
                                    'message' => __(self::POST_DELETED_ERROR)
                            );
                    }
            } else {
                    $result = array(
                            'validationError' => true,
                            'message' => __('No answer entered')
                    );
            }

            return $result;
    }

    /**
     * Function to get answer display data from answer details
     *
     * @param array $answerData
     * @return array
     */
    public function getAnswerDisplayData($answerData) {
            $data = array();
            if (!empty($answerData)) {
                    $timezone = $this->user['timezone'];
                    $answerObj = $answerData['Answer'];
                    $answeredUserId = $answerObj['created_by'];
                    $answeredUser = $answerData['User'];
                    $answeredUserHealthStatus = '';
                    $answeredUserSmileyClass = '';

                    if (isset($answerObj['is_anonymous']) && ($answerObj['is_anonymous'] === true)) {
                            $answeredUserLink = Common::getAnonymousUserLink();
                            $answeredUserThumb = Common::getAnonymousUserThumb('x_small', 'media-object');
                            $answeredUserProfileUrl = 'javascript:void(0)';
                            $answeredUserThumbCursorClass = 'cursor-default';
                    } else {
                            $answeredUserName = $answeredUser['username'];
                            $answeredUserLink = Common::getUserProfileLink($answeredUserName, false, 'owner', true);
                            $answeredUserThumb = Common::getUserThumb($answeredUserId, $answeredUser['type'], 'x_small', 'media-object');
                            $answeredUserProfileUrl = Common::getUserProfileLink($answeredUserName, true);
                            $answeredUserThumbCursorClass = '';

                            // show answered user's health status at the time of answering
                            App::uses('HealthStatus', 'Utility');
                            $healthStatus = (!empty($answerObj['health_status'])) ? $answerObj['health_status'] : HealthStatus::STATUS_VERY_GOOD;
                            $answeredUserHealthStatus = HealthStatus::getHealthStatusText($healthStatus);
                            $answeredUserSmileyClass = HealthStatus::getFeelingSmileyClass($healthStatus);
                    }

                    $data['answeredUserLink'] = $answeredUserLink;
                    $data['answeredUserThumb'] = $answeredUserThumb;
                    $data['answeredUserProfileUrl'] = $answeredUserProfileUrl;
                    $data['answeredUserThumbCursorClass'] = $answeredUserThumbCursorClass;
                    $data['answeredTimeAgo'] = Date::timeAgoInWords($answerObj['created'], $timezone);
                    $data['answeredTimeISO'] = Date::getISODate($answerObj['created']);
                    $answerText = preg_replace("/[\r\n]+/", "\n", $answerObj['answer']);
                    $truncateOptions = array('exact' => false, 'html' => true, 'ellipsis' => false);
                    $truncatedAnswerText = String::truncate($answerText, 125, $truncateOptions);
                    $data['truncatedAnswerText'] = '';
                    if (strlen($truncatedAnswerText) < strlen($answerText)) {
                            $data['truncatedAnswerText'] = nl2br(h($truncatedAnswerText));
                    }
                    $data['answerText'] = nl2br(h($answerText));
                    $data['answerId'] = $answerObj['id'];
                    $data['canDeleteAnswer'] = $this->__canCurrentUserDeleteAnswer($answerData);
                    $data['answeredUserHealthStatus'] = $answeredUserHealthStatus;
                    $data['answeredUserSmileyClass'] = $answeredUserSmileyClass;
            }

            return $data;
    }

    /**
     * Function to check if the current user can delete an answer
     *
     * @return boolean
     */
    private function __canCurrentUserDeleteAnswer($answerData) {
            $currentUserId = $this->currentUserId;
            $postBy = (int) $answerData['Post']['post_by'];
            $answeredUserId = (int) $answerData['Answer']['created_by'];
            $isAnsweredUser = ($currentUserId === $answeredUserId);
            $isPostOwner = ($currentUserId === $postBy);
            $canDelete = ($isAnsweredUser || $isPostOwner) ? true : false;
            return $canDelete;
    }

    /**
     * Function to delete an answer
     *
     * @param int $answerId
     * @return array
     */
    public function deleteAnswer($answerId) {
            try {
                    if ($this->Answer->delete($answerId)) {
                            $this->Notification->deleteAnswerNotifications($answerId);
                            $success = true;
                    } else {
                            $success = false;
                    }
                    $success = true;
            } catch (Exception $e) {
                    $success = false;
            }
            $result = array(
                    'success' => $success,
            );
            return $result;
    }

    /**
     * Temp function to update the latest comments json in a post
     */
    public function tmpUpdatePostLatestComments($postId) {
            $latestComments = $this->__getLatestPostComments($postId);
            $this->Post->savePostCommentsJSON($postId, $latestComments);
    }

    /**
     * Function to get the rooms following current user's health update
     */
    public function getCurrentUserHealthFollowingRooms() {
            $userId = $this->currentUserId;
            $rooms = array(
                    "users/{$userId}",
                    "newsfeed/{$userId}"
            );

            $user = $this->User->findById($userId, array('privacy_settings'));
            $privacySettingStr = $user['User']['privacy_settings'];
            $privacySettings = unserialize($privacySettingStr);

            if (!empty($privacySettings['view_your_health'])) {
                    $healthViewPermittedTo = (int) $privacySettings['view_your_health'];
            } else {
                    $healthViewPermittedTo = UserPrivacySettings::PRIVACY_FRIENDS;
            }

            if ($healthViewPermittedTo !== UserPrivacySettings::PRIVACY_PRIVATE) {
                    $followingUsers = $this->FollowingPage->getProfileFollowingUsers($userId);
                    if (!empty($followingUsers)) {
                            foreach ($followingUsers as $followingUserId) {
                                    $rooms[] = "newsfeed/{$followingUserId}";
                            }
                    }
            }

            return $rooms;
    }

    /**
     * Temp function to update the latest comments json in a post
     */
    public function getEcardImages($name = "") {
        $eCardArray = array(
           "ecard_1" => "11.png",
           "ecard_2" =>  "12.png",
           "ecard_3" =>  "13.png",
           "ecard_4" =>  "14.png",
           "ecard_5" =>  "15.png",                
           "ecard_6" =>  "1.png",
           "ecard_7" =>  "2.png",
           "ecard_8" =>  "3.png",
           "ecard_9" =>  "4.png",
           "ecard_10" =>  "5.png",
           "ecard_11" =>  "6.png",
           "ecard_12" =>  "7.png",
           "ecard_13" =>  "8.png",
           "ecard_14" =>  "9.png",
           "ecard_15" =>  "10.png",
        );
        if((trim($name) != "") && isset($eCardArray[$name])) {
            return $eCardArray[$name];
        } else if($name == "") {
            return $eCardArray;
        }
    }        
}