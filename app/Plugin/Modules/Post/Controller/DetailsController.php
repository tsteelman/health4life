<?php

/**
 * DetailsController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('PostAppController', 'Post.Controller');

/**
 * DetailsController for frontend posts.
 * 
 * DetailsController is used for post detail page.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Post
 * @category	Controllers 
 */
class DetailsController extends PostAppController {

	public $uses = array('Post');
	public $components = array('Posting');

	/**
	 * Displays the post detail page
	 * 
	 * @param int $postId
	 */
	public function index($postId) {
		$post = $this->Post->findById($postId);
		if (!empty($post) && intval($post['Post']['is_deleted']) === PostingComponent::NOT_DELETED) {
			if ($this->__canCurrentUserAccess($post['Post'])) {
				// mark the post notifications as read by the logged in user
				$this->Notification = ClassRegistry::init('Notification');
				$currentUserId = $this->Auth->user('id');
				$this->Notification->markPostNotificationsReadByUser($postId, $currentUserId);

				// set post display data on view
				$this->Posting->hasLikePermission = true;
				$this->Posting->hasCommentPermission = true;
				$postData = $this->Posting->getPostDisplayData($post);
				$postType = $post['Post']['post_type'];
				$isQuestionPost = ($postType === Post::POST_TYPE_QUESTION);
				$containerClass = ($isQuestionPost === true) ? '' : 'notification_view';
				$this->set('containerClass', $containerClass);
				$this->__setPostBreadCrumbData($post['Post']);
				$this->set($postData);
			} else {
				// show error on trying to access a post that the user is not allowed to access
				$this->Session->setFlash(__('You are not allowed to access the post'), 'error');
				$this->redirect('/');
			}
		} else {
			// show error on trying to access non-existing post
			$this->Session->setFlash(__($this->invalidMessage), 'error');
			$this->redirect('/');
		}
	}

	/**
	 * Function to check if the currently logged in user can access the post
	 * 
	 * @param array $post
	 * @return boolean
	 */
	private function __canCurrentUserAccess($post) {
		$currentUserId = $this->Auth->user('id');
		$canAccess = false;
		if ($post['post_by'] === $currentUserId) {
			$canAccess = true;
		} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_COMMUNITIES) {
			// if post is in a community, only the community members can access
			$communityId = $post['posted_in'];
			$this->CommunityMember = ClassRegistry::init('CommunityMember');
			if ($this->CommunityMember->isUserApprovedCommunityMember($currentUserId, $communityId)) {
				$canAccess = true;
			}
		} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_EVENTS) {
			$eventId = $post['posted_in'];
			$this->Event = ClassRegistry::init('Event');
			$this->Event->recursive = -1;
			$event = $this->Event->getEvent($eventId);
			if ($event['community_id'] > 0) {
				// if post is in a community event, the community members can access
				$this->CommunityMember = ClassRegistry::init('CommunityMember');
				$communityId = $event['community_id'];
				if ($this->CommunityMember->isUserApprovedCommunityMember($currentUserId, $communityId)) {
					$canAccess = true;
				}
			} else {
				$eventType = $event['event_type'];
				$this->EventMember = ClassRegistry::init('EventMember');
				$eventMemberStatus = $this->EventMember->getStatus($eventId, $currentUserId);
				if ($eventType == Event::EVENT_TYPE_PUBLIC) {
					// if post is in a public event, rsvped members can access
					$rsvp = array(
						EventMember::STATUS_ATTENDING,
						EventMember::STATUS_MAYBE_ATTENDING,
						EventMember::STATUS_NOT_ATTENDING
					);
					if (in_array($eventMemberStatus, $rsvp)) {
						$canAccess = true;
					}
				} elseif ($eventType == Event::EVENT_TYPE_PRIVATE) {
					// if post is in a private event, invited members can access
					if (!is_null($eventMemberStatus)) {
						$canAccess = true;
					}
				}
			}
		} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_TEAM) {
			// if post is in a team, only the approved team members can access
			$teamId = $post['posted_in'];
			$this->TeamMember = ClassRegistry::init('TeamMember');
			if ($this->TeamMember->isUserApprovedTeamMember($currentUserId, $teamId)) {
				$canAccess = true;
			}
		} else {
			// if post is not in an event or community or team, anyone can access
			$canAccess = true;
		}

		return $canAccess;
	}

	/**
	 * Function to set the breadcrumb data for the post on the view
	 * 
	 * @param array $post 
	 */
	private function __setPostBreadCrumbData($post) {
		$postedInType = $post['posted_in_type'];
		switch ($postedInType) {
			case Post::POSTED_IN_TYPE_COMMUNITIES:
				$communityId = $post['posted_in'];
				$this->Community = ClassRegistry::init('Community');
				$this->Community->recursive = -1;
				$community = $this->Community->getCommunity($communityId);
				$breadCrumbList = array(
					array(
						'text' => 'Community',
						'href' => '/community'
					),
					array(
						'text' => h($community['name']),
						'href' => "/community/details/index/{$communityId}"
					)
				);
				break;
			case Post::POSTED_IN_TYPE_EVENTS:
				$eventId = $post['posted_in'];
				$this->Event = ClassRegistry::init('Event');
				$this->Event->recursive = -1;
				$event = $this->Event->getEvent($eventId);
				$breadCrumbList = array(
					array(
						'text' => 'Events',
						'href' => '/event'
					),
					array(
						'text' => h($event['name']),
						'href' => "/event/details/index/{$eventId}"
					)
				);
				break;
			case Post::POSTED_IN_TYPE_USERS:
				$profileId = $post['posted_in'];
				$currentUserId = $this->Auth->user('id');
				if ($profileId === $currentUserId) {
					$profileText = 'My Profile';
					$profileUserName = $this->Auth->user('username');
				} else {
					$this->User = ClassRegistry::init('User');
					$this->User->recursive = -1;
					$profileUser = $this->User->getUserDetails($profileId);
					$profileUserName = $profileUser['user_name'];
					$profileText = __("%s's Profile", $profileUserName);
				}
				$breadCrumbList = array(
					array(
						'text' => $profileText,
						'href' => Common::getUserProfileLink($profileUserName, true)
					)
				);
				break;
			case Post::POSTED_IN_TYPE_DISEASES:
				$diseaseId = $post['posted_in'];
				$this->Disease = ClassRegistry::init('Disease');
				$diseaseName = $this->Disease->getDiseaseName($diseaseId);
				$breadCrumbList = array(
					array(
						'text' => $diseaseName,
						'href' => "/condition/index/{$diseaseId}"
					)
				);
				break;
			case Post::POSTED_IN_TYPE_TEAM:
				$teamId = $post['posted_in'];
				$this->Team = ClassRegistry::init('Team');
				$team = $this->Team->getTeam($teamId);
				$breadCrumbList = array(
					array(
						'text' => $team['name'],
						'href' => "/myteam/{$teamId}"
					)
				);
				break;
		}

		$this->set(compact('breadCrumbList'));
	}
}