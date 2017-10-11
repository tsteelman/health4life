<?php

/**
 * ApiController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('PostAppController', 'Post.Controller');

/**
 * ApiController for frontend posts.
 * 
 * ApiController is used for API operations related to posting.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Post
 * @category	Controllers 
 */
class ApiController extends PostAppController {

    public $components = array('Posting', 'Uploader');

    /**
     * Array to store API output data
     * 
     * @var array 
     */
    public $data = array();

    /**
     * Disable auto render
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->autoRender = false;

        App::import('Vendor', 'ImageTool');
    }

    /**
     * Output JSON data
     */
    public function afterFilter() {
        parent::afterFilter();
        echo json_encode($this->data);
    }

    /**
     * Function to create a post
     */
    public function createPost() {
        $this->Posting->hasLikePermission = true;
        $this->Posting->hasCommentPermission = true;
        $result = $this->Posting->createPost();

        if (isset($result['data'])) {
            $view = new View($this, false);
            $data = $result['data'];
            $element = $data['element'];
            unset($data['element']);
			$data['loggedIn'] = $this->Auth->loggedIn();
			$data['loggedin_userid'] = $this->Auth->user('id');
			$data['loggedin_user_type'] = $this->Auth->user('type');
            $view->set($data);
            $viewContent = $view->element($element);
            unset($result['data']);
            $result['content'] = $viewContent;
        }
        
        try{
            //$posted_in = "users";
            //$posted_in_id = "60";
            //$posted_in_room = $posted_in.'/'.$posted_in_id;
//            App::import('Vendor', 'elephantio/client');
//            $elephant = new ElephantIO\Client(Configure::read('SOCKET.URL'), 'socket.io', 1, false, true, true);
//            $elephant->init();
//            $elephant->emit('new_post' , 
//            array(
//                "room" => $posted_in_room, 
//                "post_id" => $data['postId'],
//                "name" => $data['postedUserName'],
//                "message" => $data['description']));
//            
//            $elephant->close();   
        } 
        catch(Exception $e)
        {
            
        }
        $this->data = $result;
    }

    /**
     * Function to like a post
     */
    public function likePost() {
        $postId = $this->request->data['postId'];
        $data = $this->Posting->likePost($postId);
        if (isset($data['success'])) {
            $view = new View($this, false);
            $viewContent = $view->element('Post.last_liked_users_list', $data);
            $result = array(
                'content' => $viewContent,
                'likeCount' => $data['likeCount'],
                'success' => $data['success'],    
                'lastLikedUsers' => $data['lastLikedUsers'],
				'postInfo' => $data['postInfo']
			);
        } else {
            $result = $data;
        }
        $this->data = $result;
    }

    /**
     * Function to unlike a post
     */
    public function unlikePost() {
        $postId = $this->request->data['postId'];
        $data = $this->Posting->unlikePost($postId);
        if (isset($data['success'])) {
            $view = new View($this, false);
            $viewContent = $view->element('Post.last_liked_users_list', $data);
            $result = array(
                'content' => $viewContent,
                'likeCount' => $data['likeCount'],
                'success' => $data['success'],
                'lastLikedUsers' => $data['lastLikedUsers'],
				'postInfo' => $data['postInfo']
			);
        } else {
            $result = $data;
        }
        $this->data = $result;
    }

    /**
     * Function to list the likes for a post
     */
    public function listLikes() {
        $postId = $this->request->data['postId'];
        if ($postId > 0) {
            $likedUsers = $this->Posting->getLikedUsersList($postId);
            if (!empty($likedUsers)) {
                $current_user_id = $this->Auth->user('id');
                foreach ($likedUsers as $key => $user) {
                    if($user['userId'] == $current_user_id) {
                        $current_user = $user;
                        unset($likedUsers[$key]);
                        array_unshift($likedUsers, $current_user);
                    }
                }
                
                $view = new View($this, false);
                $element = 'Post.liked_users_list';
                $viewData = array('likedUsers' => $likedUsers);
                $result = array(
                    'success' => true,
                    'message' => $view->element($element, $viewData),
                );
            } else {
                $result = array(
                    'error' => true,
                    'message' => __('No likes for the post')
                );
            }
        } else {
            $result = array(
                'error' => true,
                'message' => __('Invalid post')
            );
        }
        $this->data = $result;
    }

    /**
     * Function to add a comment on a post
     */
    public function addComment() {
        $result = $this->Posting->addComment();
        if (isset($result['data'])) {
            $view = new View($this, false);
            $data = $result['data'];
            unset($result['data']);
			$data['loggedIn'] = $this->Auth->loggedIn();
			$result['content'] = $view->element('Post.comment_row', $data);
        }

        $this->data = $result;
    }

    /**
     * Function to list the comments for a post
     */
    public function listComments() {
        $postId = $this->request->data['postId'];
		if ($postId > 0) {
			if (isset($this->request->data['postedInType'])) {
				$postedInType = $this->request->data['postedInType'];
				if ($postedInType === Post::POSTED_IN_TYPE_DISEASES) {
					$this->Posting->layout = '2_column';
				}
			}
			$comments = $this->Posting->getPostCommentsList($postId);
            if (!empty($comments)) {
                $view = new View($this, false);
                $element = 'Post.comments_list';
                $viewData = array('comments' => $comments);
				$viewData['loggedIn'] = $this->Auth->loggedIn();
                $result = array(
                    'success' => true,
                    'message' => $view->element($element, $viewData),
                );
            } else {
                $result = array(
                    'error' => true,
                    'message' => __('No comments for the post')
                );
            }
        } else {
            $result = array(
                'error' => true,
                'message' => __('Invalid post')
            );
        }
        $this->data = $result;
    }

    /**
     * Function to delete a comment
     */
    public function deleteComment() {
        $commentId = $this->request->data['commentId'];
        $result = $this->Posting->deleteComment($commentId);
		$this->data = $result;
    }

    /**
     * Function to delete a post
     */
    public function deletePost() {
		$postId = $this->request->data['postId'];
		$result = $this->Posting->deletePost($postId);
		$this->data = $result;
	}

    public function textCrawler() {
        App::uses('Crawler', 'Utility');
        $Crawler = new Crawler();
        $link = $this->request->data['link'];
        $imageQuantity = -1;
        $Crawler->grabUrlContents($link, $imageQuantity);
    }

    /**
     * Function to show photos preview in a post
     */
    public function previewPhoto() {
        $this->layout = null;
        $uploadPath = Configure::read('App.UPLOAD_PATH');
        $previewPath = $uploadPath . DIRECTORY_SEPARATOR.'preview';
        $tempPreviewUrl = FULL_BASE_URL . '/uploads/tmp/preview';
        $tempUrl = FULL_BASE_URL . '/uploads/tmp';

        /*
         * Functionality to upload the image to a temporary folder
         */
        $uploader = new $this->Uploader();


        $uploader->allowedExtensions = array("jpg", "jpeg", "png", "bmp", "gif"); // all files types allowed by default
        // Specify max file size in bytes.
        $uploader->sizeLimit = 5 * 1024 * 1024; // default is 5 MiB

        $uploader->minImageSize = array('5', '5');

        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = "chunks";

        $method = $_SERVER["REQUEST_METHOD"];
        if ($method == "POST") {
           
            header("Content-Type: text/plain");

            // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
            $result = $uploader->handleUpload($uploadPath);
 
            if (isset($result['success'])) {
                $result['file_name'] = $uploader->getUploadName();

                $photoPath = $uploadPath . DIRECTORY_SEPARATOR . $result['file_name'];
                $previewPhotoPath = $previewPath . DIRECTORY_SEPARATOR . $result['file_name'];
                ImageTool::resize(array(
					'quality' => 100,
					'enlarge' => false,
					'keepRatio' => true,
					'paddings' => false,
					'crop' => false,
					'input' => $photoPath,
					'output' => $previewPhotoPath,
					'width' => 100,
					'height' => 100
				));

				$result['fileName'] = $result['file_name'];
                $result['fileurl'] = $tempPreviewUrl . '/' . $result['file_name'];
                $result['orig_fileurl'] = $tempUrl . '/' . $result['file_name'];

                $view = new View($this, false);
                $viewContent = $view->element("Post.photo_upload_preview", $result);
                $result['filehtml'] = $viewContent;
            }


            echo json_encode($result);
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        exit;
    }

    public function updatePollVote() {
        $this->Posting->hasCommentPermission = true;
        $this->Posting->hasLikePermission = true;
        $pollId = $this->request->params ['named'] ['poll_id'];
        $optionId = $this->request->params ['named'] ['option_id'];
        $result = $this->Posting->updatePollVote($pollId, $optionId);

        if (isset($result['data'])) {
            $view = new View($this, false);
            $data = $result['data'];
            $element = $data['element'];
            unset($data['element']);
            $data['loggedin_userid'] = $this->Auth->user('id');
            $data['loggedin_user_type'] = $this->Auth->user('type');
            $view->set($data);
            $viewContent = $view->element($element);
            unset($result['data']);
            $result['content'] = $viewContent;
			$result['attendedUsers'] = $result['users'];
        }

        $this->data = $result;
    }

    public function getPollDetails() {
        $pollId = $this->request->params ['named'] ['poll_id'];
        $result = $this->Posting->getPollDetails($pollId);
        if (isset($result['data'])) {
            $view = new View($this, false);
            $data = $result['data'];
            unset($data['iconClass']);
            unset($data['postedUserThumb']);
            unset($data['canDeletePost']);
            unset($data['postedUserName']);
            unset($data['title']);
//            unset($data['description']);
            $element = 'Post.poll_result_popup';
            unset($data['element']);
            $data['loggedin_userid'] = $this->Auth->user('id');
            $data['loggedin_user_type'] = $this->Auth->user('type');
            $view->set($data);
            $viewContent = $view->element($element);
            unset($result['data']);
            $result['content'] = $viewContent;
        }
        $this->data = $result;
    }

    /**
     * Function to upload videos to temporary location on server
     */
    public function uploadVideos() {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            header('Content-Type: text/plain');
            $uploader = new $this->Uploader();
            $uploader->allowedExtensions = array('avi', 'mp4', '3gp', 'mpeg', 'mov', 'flv', 'wmv', 'mpg');
            $uploader->sizeLimit = 100 * 1024 * 1024; // 100 MB
            $uploader->inputName = 'qqfile';
            $uploader->chunksFolder = 'chunks';

            $uploadPath = Configure::read('App.UPLOAD_PATH');
            $result = $uploader->handleUpload($uploadPath);

            if (isset($result['success'])) {
                $result['fileName'] = $uploader->getUploadName();
            }
            $this->data = $result;
        } else {
            header('HTTP/1.0 405 Method Not Allowed');
        }
    }
    public function addToFavorite(){
        $postId = $this->request->data['post_id'];
        $status = $this->request->data['status'];
        $current_user = $this->Auth->user('id');
//        $status_add = 1;
//        $status_remove = 0;
        $result = $this->Posting->updateFavoritePostsList($postId, $status);
        $this->data = $result;
    }

	/**
	 * Function to report abuse a comment
	 */
	public function reportAbuseComment() {
		$commentId = $this->request->data['comment_id'];
		$reason = $this->request->data['reason'];
		$action = null;
		if (!empty($this->request->data['action'])) {
			$action = $this->request->data['action'];
		}
		$this->data = $this->Posting->reportAbuseComment($commentId, $reason, $action);
	}

	/**
	 * Function to report abuse a post
	 */
	public function reportAbusePost() {
		$postId = $this->request->data['post_id'];
		$reason = $this->request->data['reason'];
		$action = null;
		if (!empty($this->request->data['action'])) {
			$action = $this->request->data['action'];
		}
		$this->data = $this->Posting->reportAbusePost($postId, $reason, $action);
	}

	/**
	 * Function to add a question on a disease forum
	 */
	public function addQuestion() {
		$result = $this->Posting->addQuestion();
		if (isset($result['data'])) {
			$view = new View($this, false);
			$data = $result['data'];
			$element = $data['element'];
			unset($data['element']);
			$currentUser=$this->Auth->user();
			$data['loggedin_userid'] = $currentUser['id'];
			$data['loggedin_user_type'] = $currentUser['type'];
			$data['username'] = $currentUser['username'];
			$view->set($data);
			$viewContent = $view->element($element);
			unset($result['data']);
			$result['content'] = $viewContent;
		}
		$this->data = $result;
	}

	/**
	 * Function to add an answer on a post
	 */
	public function addAnswer() {
		$result = $this->Posting->addAnswer();
		if (isset($result['data'])) {
			$view = new View($this, false);
			$data = $result['data'];
			unset($result['data']);
			$result['content'] = $view->element('Post.answer_row', $data);
		}
		$this->data = $result;
	}

	/**
	 * Function to delete an answer
	 */
	public function deleteAnswer() {
		$answerId = $this->request->data['answerId'];
		$result = $this->Posting->deleteAnswer($answerId);
		$this->data = $result;
	}

	/**
	 * Function to get the list of rooms following a post
	 */
	public function getPostFollowingRooms() {
		$post = $this->request->data['post'];
		$this->data = $this->Posting->getPostPageFollowingRooms($post);
	}

	/**
	 * Function to get the list of rooms following current user's health update
	 */
	public function getCurrentUserHealthFollowingRooms() {
		$this->data = $this->Posting->getCurrentUserHealthFollowingRooms();
	}
}