<?php

/**
 * BlogController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');
App::import('Controller', 'Api');
App::uses('UserPrivacySettings', 'Lib');


/**
 * BlogController for the frontend
 *
 * BlogController is used for to implementing the blog functionality
 *
 * @author      Ajay Arjunan
 * @package 	User
 * @category	Controllers
 */
class BlogController extends ProfileController {

    /**$userId
     * Models needed in the Controller
     *
     * @var array
     */
    protected $_mergeParent = 'ProfileController';
    
    public $uses = array(
        'User',
        'MyFriends',
        'NotificationSetting',
        'Notification',
        'Media'
    );
    public $components = array(
        'Session',
        'EmailTemplate',
        'ImportContacts',
        'EmailInvite',
        'RecommendedFriend',
        'Csv',
        'VCard'
    );

    /**
     * Profile -> Blog & View All Blog
     */
    public function index($username = null) {
        
        /*
         * Set the Profile data for the logged in user
         */
        $this->_setUserProfileData();
            
        /*
         * Check if the visiting user has the privillege to view Privacy tab
         */
        if ($this->_requestedUser['id'] != $this->_currentUser['id']) {
            $privacy = new UserPrivacySettings($this->_requestedUser['id']);
            $isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'],
                    $this->_currentUser['id']);
            $viewSettings = array($privacy::PRIVACY_PUBLIC);
            if ($isFriend == MyFriends::STATUS_CONFIRMED) {
                array_push($viewSettings, $privacy::PRIVACY_FRIENDS);
            }
            if (!in_array($privacy->__get('view_your_blog'), $viewSettings)) {
                //redirect to profile page
                $this->redirect(Common::getUserProfileLink( $this->_requestedUser['username'], true));
            }
        }
        
        /*
         * Checking if the user is viewing his own profile
         */
        $profileUserId = $this->_requestedUser['id'];
        $currentUserId = (int) $this->Auth->user('id');
        $isOwnProfile = ((int) $profileUserId === $currentUserId) ? true : false;
        $this->set('isOwnProfile', $isOwnProfile);
        
            
        /*
         * Set the title for the page
         */    
        $userForTitle = isset($this->_requestedUser['id']) ? 
                $this->_requestedUser['username'] : $this->Auth->user('username');

        $this->set('title_for_layout',$userForTitle."'s blog");

        /*
         * Handle the view based on the request coming
         */
        
        if(isset($this->request->query['view'])) {
            if($this->request->query['view'] == "blog") {
                $this->__loadBlog();
            }
            else if ($this->request->query['view'] == "ecard") {
                $this->__loadEcard();
            }
        } else {
            
             $this->__setBlogTabData();
        }
 

    }
    
    
    private function __setBlogTabData() {
        
        $profileUserId = $this->_requestedUser['id'];
        
        $options = array('user_id' => $profileUserId);
        $this->Posting->setFormData($options);


        $hasFilterPermission = false;
        $filterOptions = $this->Posting->getFilterOptions(Post::POSTED_IN_TYPE_USERS);
        $this->set('filterOptions', $filterOptions);
        $this->set('hasFilterPermission', $hasFilterPermission);
        $this->__loadLatestPost($profileUserId, Post::POST_TYPE_BLOG);        
        $this->__loadLatestPost($profileUserId, Post::POST_TYPE_ECARD);        
        $this->__loadRecentPhotos($profileUserId);        
        $this->__loadRecentVideo($profileUserId);        
    }

    /**
     * Loads the blogs for a user and sets them on view
     *
     * @param int $userId
     */
    private function __loadLatestPost($profileUserId, $postType) {
        
        $conditions = array(
                'Post.posted_in' => $profileUserId,
                'Post.posted_in_type' => Post::POSTED_IN_TYPE_USERS,
                'Post.is_deleted' => Post::NOT_DELETED,
                'Post.status' => Post::STATUS_NORMAL,
                'Post.post_type' => $postType
        );
        $this->Paginator->settings = array(
                'conditions' =>$conditions,
                'order' => array(
                        'Post.created' => 'DESC'
                ),
                'limit' => 1
        );
        
        $posts = $this->Paginator->paginate('Post');

        
        $postsData = array();
        if (!empty($posts)) {
            $displayPage = Post::POSTED_IN_TYPE_USERS;
            foreach ($posts as $post) {
                $postsData = $this->Posting->getPostDisplayData($post, $displayPage);
            }
        }
                
        $this->set('latestpost_'.$postType, $postsData);
    }
    
    /**
     * Loads the blogs for a user and sets them on view
     *
     * @param int $userId
     */
    private function __loadBlog() {
        
        $profileUserId = $this->_requestedUser['id'];
        
        $conditions = array(
                'Post.posted_in' => $profileUserId,
                'Post.posted_in_type' => Post::POSTED_IN_TYPE_USERS,
                'Post.is_deleted' => Post::NOT_DELETED,
                'Post.status' => Post::STATUS_NORMAL,
                'Post.post_type' => array(Post::POST_TYPE_BLOG)
        );
        $this->Paginator->settings = array(
                'conditions' =>$conditions,
                'order' => array(
                        'Post.created' => 'DESC'
                ),
                'limit' => PostingComponent::POSTS_PER_PAGE
        );
        
        $posts = $this->Paginator->paginate('Post');

        
        $postsData = array();
        if (!empty($posts)) {
            $displayPage = Post::POSTED_IN_TYPE_USERS;
            foreach ($posts as $post) {
                $postsData[] = $this->Posting->getPostDisplayData($post, $displayPage);
            }
        }
        
        $this->set('blogData', $postsData);         
        $this->render('Blog/viewall_blog');
    }
    
    /**
     * Loads the photos of a user and sets them on view
     *
     * @param int $userId
     */
    private function __loadRecentPhotos($profileUserId) {
        
        $photos = $this->Photo->getRecentPhotos($profileUserId);
        
        $this->set('recentPhotos', $photos);
    }    
    
    /**
     * Loads the photos of a user and sets them on view
     *
     * @param int $userId
     */
    private function __loadRecentVideo($profileUserId) {
        
        $photos = $this->Media->getUserVideos($profileUserId, 5);
        
        $this->set('recentVideos', $photos);
    }  
    
   /**
     * Loads the ecards for a user and sets them on view
     *
     * @param int $userId
     */
    private function __loadEcard() {
        
        $profileUserId = $this->_requestedUser['id'];
        
        $conditions = array(
                'Post.posted_in' => $profileUserId,
                'Post.posted_in_type' => Post::POSTED_IN_TYPE_USERS,
                'Post.is_deleted' => Post::NOT_DELETED,
                'Post.status' => Post::STATUS_NORMAL,
                'Post.post_type' => array(Post::POST_TYPE_ECARD)
        );
        $this->Paginator->settings = array(
                'conditions' =>$conditions,
                'order' => array(
                        'Post.created' => 'DESC'
                ),
                'limit' => PostingComponent::POSTS_PER_PAGE
        );
        
        $posts = $this->Paginator->paginate('Post');

        
        $postsData = array();
        if (!empty($posts)) {
            $displayPage = Post::POSTED_IN_TYPE_USERS;
            foreach ($posts as $post) {
                $postsData[] = $this->Posting->getPostDisplayData($post, $displayPage);
            }
        }
        
        $this->set('eCardData', $postsData);         
        $this->render('Blog/viewall_ecard');
    }
    
}