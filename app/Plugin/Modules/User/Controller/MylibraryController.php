<?php

/**
 * MylibraryController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * MylibraryController for the user profile
 * 
 * MylibraryController is used to show the "My Library" in the profile page
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class MylibraryController extends ProfileController {

    /**
     * Profile -> My Library
     */
    public function index($username = null) {
        $this->set('title_for_layout',$this->Auth->user('username')."'s library");
        $profileUserId = $this->Auth->user('id');
        $hasFilterPermission = true;
        $this->set('hasFilterPermission', $hasFilterPermission);
        $this->_setUserProfileData();
		$this->__setActivityTabData($profileUserId);
		
		if ($this->request->is('ajax')) {
			$this->layout = 'ajax';
			$this->render('ajax_index');
		}
	}

    private function __setActivityTabData($profileUserId) {
        $options = array('user_id' => $profileUserId);
        $this->Posting->setFormData($options);
        $filterOptions = $this->getMyLibraryFilterOptions();
//        $filterOptions = $this->Posting->getFilterOptions();
        $this->set('filterOptions', $filterOptions);
        $this->__loadPosts($profileUserId);
    }

    /**
     * Loads the posts for a user and sets them on view
     *
     * @param int $userId
     */
    private function __loadPosts($profileUserId) {
        $favoritePostIds = $this->User->getFavoritePostIds($profileUserId);
        $this->Paginator->settings = array(
            'conditions' => array(
//                'Post.posted_in' => $profileUserId,
//                'Post.posted_in_type' => Post::POSTED_IN_TYPE_USERS,
                'Post.id' => $favoritePostIds
            ),
            'order' => array(
                'Post.created' => 'DESC'
            ),
            'limit' => PostingComponent::POSTS_PER_PAGE
        );
        $posts = $this->Paginator->paginate('Post');
        $postsData = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
//                $postsData[] = $this->Posting->getPostDisplayData($post);
                $postsData[] = $this->Posting->getLibraryPostDisplayData($post);
            }
        }
//        print_r($postsData);
//        exit;
        $hasPostPermission = true;
        $noPostsMessage = 'No items saved in My Library.';
        $isLibray = 1;
        $this->set('posts', $postsData);
        $this->set('hasPostPermission', $hasPostPermission);
        $this->set('isLibray', $isLibray);
        $this->set('noPostsMessage', $noPostsMessage);
    }


    public function getMyLibraryFilterOptions() {
        $options = array(
            PostingComponent::MOST_RECENT_FILTER => __('Most recent'),
            PostingComponent::TEXT_FILTER => __('Text'),
            PostingComponent::LINKS_FILTER => __('Links'),
            PostingComponent::IMAGES_FILTER => __('Images'),
            PostingComponent::VIDEOS_FILTER => __('Videos'),
			PostingComponent::POLL_FILTER => __('Poll'),
			PostingComponent::HEALTH_STATUS_FILTER => __('Health Status')
		);

		return $options;
    }
}