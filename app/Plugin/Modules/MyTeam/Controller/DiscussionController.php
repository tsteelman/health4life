<?php

/**
 * DiscussionController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');

/**
 * Discussion Controller for frontend My Team.
 * 
 * DiscussionController is used for discussions in My Team.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @author 	  Greeshma Radhakrishnan
 * @package   MyTeam
 * @category  Controllers 
 */
class DiscussionController extends MyTeamAppController {

	/**
	 * Models used by this controller
	 *
	 * @var array
	 */
	public $uses = array('Post');

	/**
	 * Components used by this controller
	 * 
	 * @var array 
	 */
	public $components = array('Paginator', 'Posting');

	/**
	 * View Team Discussions
	 */
	public function index() {
		$this->set('hasPostPermission', true);
		$this->set('hasFilterPermission', true);
		$this->Posting->hasLikePermission = true;
		$this->Posting->hasCommentPermission = true;
		$this->__loadPosts();
		if ($this->request->is('ajax')) {
			$this->layout = 'ajax';
			$this->view = 'discussion_ajax_index';
		} else {
			$options = array('team_id' => $this->_teamId);
			$this->Posting->setFormData($options);
			$filterOptions = $this->Posting->getFilterOptions();
			$this->set('filterOptions', $filterOptions);
		}
	}

	/**
	 * Loads the posts for a team and sets them on view
	 */
	private function __loadPosts() {
		$this->Paginator->settings = array(
			'conditions' => array(
				'Post.posted_in' => $this->_teamId,
				'Post.posted_in_type' => Post::POSTED_IN_TYPE_TEAM,
				'Post.is_deleted' => Post::NOT_DELETED,
				'Post.status' => Post::STATUS_NORMAL
			),
			'order' => array(
				'Post.created' => 'DESC'
			),
			'limit' => PostingComponent::POSTS_PER_PAGE
		);
		$posts = $this->Paginator->paginate('Post');
		$postsData = array();
		if (!empty($posts)) {
			$displayPage = Post::POSTED_IN_TYPE_TEAM;
			foreach ($posts as $post) {
				$postsData[] = $this->Posting->getPostDisplayData($post, $displayPage);
			}
		}
		$this->set('posts', $postsData);
	}
}