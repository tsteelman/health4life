<?php

/**
 * Common utility class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * Common class for frequently used functions.
 *
 * Manipulation of date data.
 *
 * @author 		Ajay Arjunan
 * @package 	App.Utility
 * @category	Utility
 */
class Common {

    /**
     * Function to get the profile image of the user
     *
     * @return string
     */
    public static function getUserThumb($user_id,  $user_type = 1, $size = "small", $class = "", $output ="img",  $user_name = "") {

        $filename = md5($user_id) . "_" . $size . ".jpg";
        $profile_path = Configure::read("App.PROFILE_IMG_PATH");
        $profile_image = $profile_path . "/" . $filename;

        if (is_null($user_type)) $user_type = 1;
        
        $border = self::getUserThumbClass($user_type);
        
        /*
         * Adding the class for the user thumb image
         */
        $border = $border. " user_".trim($size)."_thumb ";
        $modified = NULL;
        if (file_exists($profile_image)) {
            $thumb_image = Configure::read("App.UPLOAD_PATH_URL") . "/user_profile/" . $filename;
            $modified = filemtime($profile_image);
        } else {
        	$thumb_image ="img/user_default_".$user_type."_".$size.".png";
        	$thumb_image_fullpath =  App::themePath('App').DS."webroot".DS.$thumb_image;
        	if (!file_exists($thumb_image_fullpath)) {
                    $thumb_image_path = "/theme/App/img/user_default.png";
        	} else {
        		$thumb_image_path = "/theme/App/". $thumb_image;
        	}
                $thumb_image = Router::fullbaseUrl()  .$thumb_image_path;
        }
        $thumb_image = $thumb_image .'?' . $modified;

        $img_tag = '<img src="'. $thumb_image . '"';
		if($user_name) {  $img_tag .= 'data-hovercard="'.$user_name . '"'; }
		$img_tag .= 'class="'. $border . $class .'">';
		
        if($output === "img" ){
            return $img_tag;
        } else {
            return $thumb_image;
        }

    }

	/**
	 * Function to get the user thumb for an anonymous user
	 *
	 * @return string
	 */
	public static function getAnonymousUserThumb($size = 'small', $class = '', $output = 'img') {
		$imgName = sprintf('anonymous_%s.png', $size);
		$imgPath = '/theme/App/img/' . $imgName;
		if ($output === 'img') {
			$borderClass = 'border_anonymous';
			$sizeClass = sprintf('user_%s_thumb', trim($size));
			$imgClass = sprintf('%s %s %s', $borderClass, $sizeClass, $class);
			$imgTag = sprintf('<img src="%s" class="%s" />', $imgPath, $imgClass);
			return $imgTag;
		} else {
			return $imgPath;
		}
	}

	/**
     * Function to get the profile image class
     *
     * @return string
     */
    public static function getUserThumbClass($user_type) {
        switch ($user_type) {
            case '1':
                $border = 'border_patient ';
                break;
            case '2':
                $border = 'border_family ';
                break;
            case '3':
                $border = 'border_caregiver ';
                break;
            case '4':
                $border = 'border_other ';
                break;
            case '5':
            case '6':
                $border = 'border_admin ';
                break;
        }
        return $border;
    }
    
    /**
     * Function to get the profile image size of the user
     *
     * @return string
     */
    public static function getUserThumbSize() {
        $image_sizes = array(
            "large" => array("w" => 200, "h" => 200),
            "medium" => array("w" => 150, "h" => 150),
            "small" => array("w" => 60, "h" => 60),
            "x_small" => array("w" => 40, "h" => 40),
        );

        return $image_sizes;
    }

	/**
	 * Function to get the event image of specified size
	 *
	 * @param int $eventId
	 * @return string
	 */
    public static function getEventThumb($eventId, $size = "small") {
        $filename = self::getEventThumbName($eventId);
        $eventImgPath = Configure::read('App.EVENT_IMG_PATH');
        $eventImage = $eventImgPath . '/' . $filename;
        if (file_exists($eventImage)) {
            $thumbImage = Configure::read('App.UPLOAD_PATH_URL') . '/event_image/' . $filename;
        } else {
            /*
             * Default small image size
             */
            $defaultImage = '/theme/App/img/new_event_default_img.png';
            if($size != "small") {
                $defaultImage = '/theme/App/img/event_default_large.png';
            }
            $thumbImage = Configure::read('App.fullBaseUrl') . $defaultImage;
        }

        return $thumbImage;
    }
            
    /**
    * Function to get the event image of specified size
    *
    * @param int $eventId
    * @return string
    */
    public static function getEventCoverThumb($eventId) {
        $filename = self::getEventCoverThumbName($eventId);
        $eventImgPath = Configure::read('App.EVENT_IMG_PATH');
        $eventImage = $eventImgPath . '/' . $filename;
        if (file_exists($eventImage)) {
            $thumbImage = Configure::read('App.UPLOAD_PATH_URL') . '/event_image/' . $filename;
        } else {
            $thumbImage = Configure::read('App.fullBaseUrl') . '/theme/App/img/event_cover.png';
        }

        return $thumbImage;
    }

	/**
	 * Function to get the event image dimension
	 *
	 * @return array
	 */
	public static function getEventThumbSize() {
		return array('w' => 320, 'h' => 140);
	}

	/**
	 * Function to get the image name for an event
	 *
	 * @param int $eventId
	 * @return string
	 */
    public static function getEventThumbName($eventId) {
        $name = md5($eventId) . '.jpg';
		return $name;
	}

    /**
     * Function to get the image name for an event cover
     *
     * @param int $eventId
     * @return string
     */
    public static function getEventCoverThumbName($eventId) {
        $name = md5($eventId). '_'. md5('thumb') . '.jpg';
        return $name;
    }
    
    /**
     * Function to get the disease image of specified size
     *
     * @param int $diseaseId
     * @return string
     */
    public static function getDiseaseThumb($diseaseId) {
        
        $filename = self::getDiseaseThumbName($diseaseId);
        $diseaseImgPath = Configure::read('App.DISEASE_IMG_PATH');
        $diseaseImage = $diseaseImgPath . '/' . $filename;
        if (file_exists($diseaseImage)) {
            $thumbImage = Configure::read('App.UPLOAD_PATH_URL') . '/disease_images/' . $filename;
        } else {
            $thumbImage = '/theme/App/img/disease_tile_img.png';
        }
		return $thumbImage;
    }
    /**
     * Function to get the disease logo of specified size
     *
     * @param int $logoId
     * @return string
     */
    public static function getDiseaseLogo($logoId) {
        
        APP::import('Model', 'Disease');
        $Disease = new Disease();
        $thumbImage = $Disease->getDiseaseLogo($logoId);
        return $thumbImage;
    }
    /**
     * Function to get the image name for an event
     *
     * @param int $eventId
     * @return string
     */
    public static function getDiseaseThumbName($diseaseId) {
        $name = md5($diseaseId) . '.jpg';
        return $name;
    }
    /**
     * Function to get the username
     *
     * @param string $username
     * @param string $first_name
     * @param string $last_name
     * @param string $type specifies what to return username or firstname lastname
     * @return string
     */
    public static function getUsername($username, $first_name, $last_name, $type = 'username') {
        if($type === 'username') {
            return $username;
        } else {
            return $first_name . ' ' . $last_name;
        }
    }

    /**
     * Function to get the community image of specified size
     *
     * @param int $communityId
     * @return string
     */
    public static function getCommunityThumb($communityId, $size = "small") {
        $filename = self::getCommunityThumbName($communityId);
        $communityImgPath = Configure::read('App.COMMUNITY_IMG_PATH');
        $communityImage = $communityImgPath . '/' . $filename;
        if (file_exists($communityImage)) {
            $thumbImage = Configure::read('App.UPLOAD_PATH_URL') . '/community_image/' . $filename;
        } else {
            /*
             * Default small image size
             */
            $defaultImage = '/theme/App/img/new_community_default_img.png';
            if($size != "small") {
                $defaultImage = '/theme/App/img/community_default_large.png';
            }
            
            $thumbImage = Configure::read('App.fullBaseUrl') . $defaultImage;
        }

        return $thumbImage;
    }

    /**
     * Function to get the community image dimension
     *
     * @return array
     */
    public static function getCommunityThumbSize() {
		return array('w' => 240, 'h' => 106);
	}

    /**
     * Function to get the image name for a community
     *
     * @param int $communityId
     * @return string
     */
    public static function getCommunityThumbName($communityId) {
        $name = md5($communityId) . '.jpg';
        return $name;
    }

    public static function getUserProfileLink($userName = NULL, $link = FAlSE, $class = '', $hovercard = false){
    	$profile_link = '/profile/' . urlencode ( $userName );
        if(!$link) {
            $tag = '<a href="/profile/' . urlencode( $userName ) . '" ';
            if($hovercard) { $tag .= 'data-hovercard="'.$userName.'"'; }
            $tag .= 'class ="' . $class . '">'. __(h($userName)) . '</a>';

            return $tag;
        } else {
            return $profile_link;
        }

    }

	/**
	 * Function to get the profile link of a user in admin side
	 * 
	 * @param int $userId
	 * @param string $userName
	 * @return tystringpe 
	 */
	public static function getUserAdminProfileLink($userId, $userName) {
		$profileLink = "/admin/Users/view/{$userId}";
		$tag = sprintf('<a href="%s">%s</a>', $profileLink, h($userName));
		return $tag;
	}

	/**
	 * Function to get anonymous user link
	 * 
	 * @return string
	 */
	public static function getAnonymousUserLink() {
		$link = sprintf('<a class="owner"> %s </a>', self::getAnonymousUsername());
		return $link;
	}

	/**
	 * Function to get anonymous user name
	 * 
	 * @return string
	 */
	public static function getAnonymousUsername() {
		return __('Anonymous');
	}

	/**
	 * Function to get the location string from user details
	 *
	 * @param array $user
	 * @return string
	 */
    public static function getUserLocation($user) {
        $locationArray = array(
            $user['City']['description'],
            $user['State']['description'],
            $user['Country']['short_name']
        );
        return join(', ', $locationArray);
    }

    /**
     * Function to move a file from source to destination
     *
     * @param string $source source file full path
     * @param string $destination destination file full path
     */
    public static function moveFile($source, $destination) {
        try {
            // copy source to destination
            copy($source, $destination);

            // remove source
            unlink($source);

            return true;
        } catch (Exception $e) {
            CakeLog::write('debug', $e->getMessage());
            return false;
        }
    }

    /**
     * Fuction to get the link to import contacts from another site
     *
     * @param string $origin from where to import the contacts
     * @param string $token access token if any
     * @return string the link to import contacts
     */
    public static function getImportContactLink($origin, $token = null) {
        $link = '';
        switch ($origin) {
            case 'google':
                $googleAPIConfig = Configure::read('API.Google');
                $redirectUri = $googleAPIConfig['REDIRECT_URL'];
                if (($token === '') || ($token === null) || ($token === false)) {
                    $clientId = $googleAPIConfig['CLIENT_ID'];
                    $scope = 'https://www.google.com/m8/feeds/&response_type=code';
                    $authUrl = 'https://accounts.google.com/o/oauth2/auth';
                    $link = "{$authUrl}?client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}";
                } else {
                    $link = $redirectUri;
                }
                break;
             case 'facebook':
//                 $fbAPIConfig = Configure::read('API.Facebook');
//                $redirectUri = $fbAPIConfig['REDIRECT_URL'];
//                if (($token === '') || ($token === null) || ($token === false)) {
//                    $appId = $fbAPIConfig['APP_ID'];
//                    $scope = 'publish_stream,read_stream&state=TheDummyState#_=_';
//                    $authUrl = 'https://www.facebook.com/dialog/oauth';
//                    $link = "{$authUrl}?client_id={$appId}&redirect_uri={$redirectUri}&scope={$scope}";
//                } else {
//                    $link = $redirectUri;
//                }
                 break;
        }
        return $link;
    }

    /**
     * Fuction to get the details from a Youtube link
     *
     * @param string $url  - Youtbe link
     * @return array of youtube details
     */
    public static function getYoutubeDetails($url) {
        $media = array();
        if (preg_match("/(.*?)v=(.*?)($|&)/i", $url, $matching)) {
            $vid = $matching[2];
            $linkInfo = parse_url(trim($url));
            $isSecureLink = false;
            if (isset($linkInfo['scheme']) && ($linkInfo['scheme']) === 'https') {
    		$isSecureLink = true;
            }
            $scheme = 'http';
            if ($isSecureLink) {
                $scheme = 'https';
            }
            $embedUrl = sprintf('%s://www.youtube.com/embed/%s', $scheme, $vid);

            $media['image'] = "http://i2.ytimg.com/vi/$vid/hqdefault.jpg";
            $media['embedcode'] = '<iframe id="' . date("YmdHis") . $vid . '" width="100%" height="100%" src="' . $embedUrl . '" frameborder="0" &wmode="Opaque" allowfullscreen></iframe>';
        }
        return $media;
    }

    /**
     * Fuction to get a random quote
     *
     * @return string
     */
    public static function getQuotes() {
        $quotes = array(
            "He who stops being better stops being good.",
            "Challenges are what make life interesting and overcoming them is what makes life meaningful.",
            "To keep the body in good health is a duty... otherwise we shall not be able to keep our mind strong and clear.",
            "A smile is a curve that sets everything straight.",
            "Not only strike while the iron is hot, but make it hot by striking.",
            "Subtlety may deceive you; integrity never will.",
            "Necessity has no law.",
            "The wish for healing has always been half of health.",
            "No one rises so high as he who knows not whither he is going.",
            "A healthy attitude is contagious but don't wait to catch it from others. Be a carrier.",
            "Experience is simply the name we give our mistakes.",
            "The way you think, the way you behave, the way you eat, can influence your life by 30 to 50 years.",
            "The path towards your biggest dream passes through your deepest fears.",
            "Selfishness is not living as one wishes to live, it is asking others to live as one wishes to live.",
            "Tis healthy to be sick sometimes.",
            "Whatever the mind of man can conceive and believe, it can achieve. ",
            "You know, all that really matters is that the people you love are happy and healthy. Everything else is just sprinkles on the sundae.",
            "The most difficult thing is the decision to act, the rest is merely tenacity. ",
            "The most common way people give up their power is by thinking they don’t have any.",
            "You can never cross the ocean until you have the courage to lose sight of the shore.",
            "Patience is the companion of wisdom.",
            "Give a man health and a course to steer, and he'll never stop to trouble about whether he's happy or not.",
            "Believe you can and you’re halfway there.",
            "When one door of happiness closes, another opens, but often we look so long at the closed door that we do not see the one that has been opened for us.",
            "Treasure the love you receive above all. It will survive long after your good health has vanished.",
            "Healing is a matter of time, but it is sometimes also a matter of opportunity.",
            "A wise man should consider that health is the greatest of human blessings, and learn how by his own thought to derive benefit from his illnesses.",
            "Cheerfulness is the best promoter of health and is as friendly to the mind as to the body.",
            "Rest when you're weary. Refresh and renew yourself, your body, your mind, your spirit. Then get back to work."
        );

        /*
         * Shuffle the array first and get the random key
         */
        $random_key = mt_rand(0, count($quotes) - 1);
		return $quotes[$random_key];
	}

	/**
	 * Returns a unique filename by preserving the file extension
	 * 
	 * @param string $filename
	 * @param int $userId
	 */
	public static function getUniqueFileName($filename, $userId) {
		$pathinfo = pathinfo($filename);
		$ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
		$ext = $ext == '' ? $ext : '.' . $ext;
		$uniqueTime = microtime(true);
		$uniqueTime = str_replace('.', '_', $uniqueTime);
		$uniqueFileName = $userId . '_' . $uniqueTime . $ext;

		return $uniqueFileName;
	}

	/**
	 * Function to get the dimensions for team image thumbs
	 *
	 * @return array
	 */
	public static function getTeamThumbDimensions() {
		$dimensions = array(
			'medium' => array('w' => 240, 'h' => 106)
//			'small' => array('w' => 60, 'h' => 60),
//			'x_small' => array('w' => 40, 'h' => 40)
		);

		return $dimensions;
	}

	/**
	 * Function to get the image of a team of specified size
	 * 
	 * @param int $teamId
	 * @param int $patientId
	 * @param string $size
	 * @return string
	 */
	public static function getTeamThumb($teamId, $patientId, $size = 'small', $type = 'team') {
	$fileName = md5($teamId) . "_" . $size . ".jpg";
	$teamImgPath = Configure::read("App.TEAM_IMG_PATH");
	$teamImg = $teamImgPath . DS . $fileName;
	if (file_exists($teamImg)) {
	    $uploadPathUrl = Configure::read("App.UPLOAD_PATH_URL");
	    $teamImgUrl = $uploadPathUrl . DS . "team_image" . DS . $fileName;
	    $modified = filemtime($teamImg);
	    $teamImgUrl.= '?' . $modified;
	} else {
	    if ($type == 'team_invite') { 
		//default image for team invitation		
//		$defaultImagePath = "/theme/app/img/join_team_icon.png";		
		$defaultImagePath = "/theme/app/img/team_default.png";		
		$teamImgUrl = Router::fullbaseUrl() . $defaultImagePath;
	    } else if ($type == 'team_add') { 
		//default image for add team invitation		
//		$defaultImagePath = "/theme/app/img/add_new_team.png";		
		$defaultImagePath = "/theme/app/img/team_default.png";		
		$teamImgUrl = Router::fullbaseUrl() . $defaultImagePath;
	    } else {
		// if team image does not exist, return default team image
		$defaultImage = "img/team_default_" . "_" . $size . ".png";
		$webroot = App::themePath('App') . DS . "webroot";
		$defaultImageFullPath = $webroot . DS . $defaultImage;
		if (!file_exists($defaultImageFullPath)) {
		    $defaultImagePath = "/theme/App/img/team_default.png";
		} else {
		    $defaultImagePath = "/theme/App/" . $defaultImage;
		}
		$teamImgUrl = Router::fullbaseUrl() . $defaultImagePath;
	    }
	}

	return $teamImgUrl;
    }
    
    /**
	 * Function to get the task type icon image.
	 *
	 * @return array
	 */
	public static function getTaskTypeIcon($type = 'other', $classNameOnly = false) {

            $task_type_lower = strtolower($type);
            $task_type_class = 'task_'.str_replace(" ","_",$task_type_lower);
            if($classNameOnly == true){
                $result = $task_type_class;
            }  else {
                $result = "<span class='task_type ".$task_type_class."'></span>";
            }
            return $result;
            
	}
        
        /**
	 * Function to check if the user has thumb image
	 *
	 * @return array
	 */
	public static function userHasThumb($user_id, $size = "small", $return = "url") {

            $filename = md5($user_id) . "_" . $size . ".jpg";
            $profile_path = Configure::read("App.PROFILE_IMG_PATH");
            $profile_image = $profile_path . "/" . $filename;
        
            if (file_exists($profile_image)) {
                $thumb_image = Configure::read("App.UPLOAD_PATH_URL") . "/user_profile/" . $filename;
                if($return == "url") {
                    return $thumb_image;
                } else {
                    return $profile_image;
                }
                
            }
            
            return false;            
	}
        
        /*
	 * Function  to return truncated name based on name length
	 * 
	 * @param string $name
	 * @param int $count
	 * 
	 * @return array
	 */

	public static function truncate($name, $count) {

		$data = array();
		if (strlen($name) > $count) {
			$data = array(
				'name' => String::truncate($name, $count, array(
					'ellipsis' => '...',
					'exact' => true,
					'html' => false
				)),
				'title' => $name
			);
		} else {
			$data = array(
				'name' => $name,
				'title' => ''
			);
		}
		return $data;
	}
	/**
	 * Function to get role name of a user.
	 * 
	 * @param int $role
	 * @return string
	 */
	public static function getUserRoleName($role) {
		App::uses('User', 'Model');
		
		switch ($role) {
			case User::ROLE_PATIENT:
				$roleName = __('Patient');
				break;
			case User::ROLE_FAMILY:
				$roleName = __('Family');
				break;
			case User::ROLE_CAREGIVER:
				$roleName = __('Caregiver');
				break;
			case User::ROLE_OTHER:
				$roleName = __('Friend');
				break;
			case User::ROLE_ADMIN:
			case User::ROLE_SUPER_ADMIN:
				$roleName = __('Admin');
				break;
		}
		return $roleName;
	}
        
        /**
         * Return class name for trending tags according to their percentage 
         * occurence
         * 
         * @param Integer $percentage
         * @return String
         */
        public static function getColorCode($percentage) {
            $hashColorTrends = array(
            "80" => "rating_1",
            "60" => "rating_2",
            "40" => "rating_3",
            "20" => "rating_4");
            
            $class = "rating_4";
            
            foreach ($hashColorTrends as $limit => $color) {
                if ($percentage >= $limit) {
                    $class = $color;
                    break;
                }
            }
            return $class;
        }
        
        public static function getHashtagUrl($hashtag) {
            return "/hashtag?tag=".$hashtag;
        }
        
        
        /**
         * Function to show photos preview in a post
         */
        public static function uploadSavePhoto($photoData) {

            $uploadPath = Configure::read("App.POST_IMG_PATH");

            /*
             * Functionality to upload the image to a temporary folder
             */            
            list($type, $photoData) = explode(';', $photoData);
            list(, $photoData)      = explode(',', $photoData);
            $photoData = base64_decode($photoData);
            $fileExtension = str_replace('data:image/', '', $type);
            $filename = uniqid('blog_').".".$fileExtension;
            $filePath = $uploadPath."/".$filename;

            file_put_contents($filePath, $photoData);
            
            return $filename;
        }        
        
}