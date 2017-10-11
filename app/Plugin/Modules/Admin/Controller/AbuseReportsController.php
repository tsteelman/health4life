<?php

/**
 * AbuseReportsController class file.
 *
 * @author    Greeshma Radhakrishnan <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AbuseReport', 'Model');
App::import('Controller', 'Api');

/**
 * AbuseReports Controller for the admin
 *
 * AbuseReportsController is used for the admin to manage abuse reports
 *
 * @author 	 Greeshma Radhakrishnan
 * @package  Admin
 * @category Controllers
 */
class AbuseReportsController extends AdminAppController {

	public $uses = array('AbuseReport', 'Post', 'Comment', 'User');
	public $components = array('Posting');
	const PAGE_LIMIT = 10;

	/**
	 * Admin Manage Abuse Reports
	 */
	public function index() {
		if (isset($this->request->data['delete_abuse_reports'])) {
			$this->__deleteAbuseReports();
		} elseif (isset($this->request->data['reject_abuse_reports'])) {
			$this->__rejectAbuseReports();
		}

		$conditions = array(
			'AbuseReport.status' => AbuseReport::STATUS_NEW
		);

		$filter = 0;
		if ($this->request->query('filter')) {
			$filter = $this->request->query('filter');
			if (!empty($filter)) {
				$conditions['AbuseReport.object_type'] = $filter;
			}
		}

		$this->paginate = array(
			'limit' => self::PAGE_LIMIT,
			'conditions' => $conditions
		);
		try {
			$abuseReports = $this->paginate('AbuseReport');
		} catch (NotFoundException $e) {
			$url = '/admin/abuseReports';
			if ($this->request->query('filter')) {
				$filter = $this->request->query('filter');
				if (!empty($filter)) {
					$url.="?filter={$filter}";
				}
			}
			$this->redirect($url);
		}
		
		$objectTypes = AbuseReport::getObjectTypes();

		if (count($abuseReports) > 0) {
			$timezone = $this->Auth->user('timezone');
			foreach ($abuseReports as &$abuseReport) {
				$abuseReport['AbuseReport']['created'] = Date::getUSFormatDateTime($abuseReport['AbuseReport']['created'], $timezone);
				$abuseReport['content'] = '';
				$objectType = $abuseReport['AbuseReport']['object_type'];
				if ($objectType === AbuseReport::OBJECT_TYPE_POST) {
					$JSONContent = $abuseReport['Post']['content'];
					$content = json_decode($JSONContent, true);
					if (!empty($content['title'])) {
						$abuseReport['content'] = $content['title'];
					} elseif (!empty($content['description'])) {
						$abuseReport['content'] = $content['description'];
					}
				} elseif ($objectType === AbuseReport::OBJECT_TYPE_COMMENT) {
					$abuseReport['content'] = $abuseReport['Comment']['comment_text'];
				}
			}
		} else {
			$this->Session->setFlash('No abuse reports found.', 'warning');
		}

		$this->set(compact('abuseReports', 'objectTypes'));
		$this->request->data['AbuseReport']['filter'] = $filter;
	}

	/**
	 * Function to delete abuse reports
	 */
	private function __deleteAbuseReports() {
		$abuseReports = $this->request->data['AbuseReports'];
		$data = array();
		$abuseReportedObjectOwners = array();
		foreach ($abuseReports as $abuseReportData) {
			if (!empty($abuseReportData['id'])) {
				$abuseReportRecord = $this->AbuseReport->findById($abuseReportData['id']);
				$abuseReport = $abuseReportRecord['AbuseReport'];
				$objectId = $abuseReport['object_id'];
				$objectType = $abuseReport['object_type'];
				$abuseReportedObjectOwners[] = $abuseReport['object_owner_id'];
				if ($objectType === AbuseReport::OBJECT_TYPE_POST) {
					$this->Post->id = $objectId;
					$this->Post->saveField('status', Post::STATUS_BLOCKED);
				} elseif ($objectType === AbuseReport::OBJECT_TYPE_COMMENT) {
					$this->Comment->id = $objectId;
					$this->Comment->saveField('status', Comment::STATUS_BLOCKED);
				}
				$abuseReportData['status'] = AbuseReport::STATUS_DELETED;
				$abuseReportData['action_taken_date'] = Date::getCurrentDateTime();
				$data[] = $abuseReportData;

				// if admin comment is present, notify both the users
				if (!empty($abuseReportData['admin_comment'])) {
					$abuseReportRecord['AbuseReport'] = array_merge($abuseReportRecord['AbuseReport'], $abuseReportData);
					$this->__sendAbuseDeletedMails($abuseReportRecord);
				}
			}
		}

		if (!empty($data)) {
			if ($this->AbuseReport->saveMany($data)) {
				$this->__enableAnonymousPermissions($abuseReportedObjectOwners);
				$this->Session->setFlash('Successfully deleted the selected abuse reports', 'success');
			} else {
				$this->Session->setFlash('Failed to delete the selected abuse reports', 'success');
			}
			$this->__refresh();
		}
	}

	/**
	 * Function to reject abuse reports
	 */
	private function __rejectAbuseReports() {
		$abuseReports = $this->request->data['AbuseReports'];
		$data = array();
		$abuseReportedObjectOwners = array();
		foreach ($abuseReports as $abuseReportData) {
			if (!empty($abuseReportData['id'])) {
				$abuseReportRecord = $this->AbuseReport->findById($abuseReportData['id']);
				$abuseReport = $abuseReportRecord['AbuseReport'];
				$objectId = $abuseReport['object_id'];
				$objectType = $abuseReport['object_type'];
				$abuseReportedObjectOwners[] = $abuseReport['object_owner_id'];

				// reject the abuse report on the object
				if ($objectType === AbuseReport::OBJECT_TYPE_POST) {
					$this->Posting->rejectPostAbuseReport($objectId, $abuseReportData);
				} elseif ($objectType === AbuseReport::OBJECT_TYPE_COMMENT) {
					$this->Posting->rejectCommentAbuseReport($objectId);
				}

				$abuseReportData['status'] = AbuseReport::STATUS_REJECTED;
				$abuseReportData['action_taken_date'] = Date::getCurrentDateTime();
				$data[] = $abuseReportData;

				// if admin comment is present, notify both the users
				if (!empty($abuseReportData['admin_comment'])) {
					$abuseReportRecord['AbuseReport'] = array_merge($abuseReportRecord['AbuseReport'], $abuseReportData);
					$this->__sendAbuseRejectedMails($abuseReportRecord);
				}
			}
		}

		if (!empty($data)) {
			if ($this->AbuseReport->saveMany($data)) {
				$this->__enableAnonymousPermissions($abuseReportedObjectOwners);
				$this->Session->setFlash('Successfully rejected the selected abuse reports', 'success');
			} else {
				$this->Session->setFlash('Failed to reject the selected abuse reports', 'success');
			}
			$this->__refresh();
		}
	}

	/**
	 * Function to enable the anonymous permission of the abuse reported object
	 * owners if there are no other abuse reports against them
	 * 
	 * @param array $abuseReportedObjectOwners 
	 */
	private function __enableAnonymousPermissions($abuseReportedObjectOwners) {
		$users = array_unique($abuseReportedObjectOwners);
		foreach ($users as $userId) {
			$abuseCount = $this->AbuseReport->getUserAbuseReportCount($userId);
			if ($abuseCount === 0) {
				$this->User->enableAnonymousPermission($userId);
			}
		}
	}

	/**
	 * Function to refresh the current page without losing filter query
	 */
	private function __refresh() {
		$url = $this->request->here;
		if ($this->request->query('filter')) {
			$filter = $this->request->query('filter');
			if (!empty($filter)) {
				$url.="?filter={$filter}";
			}
		}
		$this->redirect($url);
	}

	/**
	 * Function to send abuse report rejected mail to object owner and 
	 * reported user
	 * 
	 * @param array $abuseReportRecord 
	 */
	private function __sendAbuseRejectedMails($abuseReportRecord) {
		$this->__sendAbuseRejectedMailToObjectOwner($abuseReportRecord);
		$this->__sendAbuseRejectedMailToReportedUser($abuseReportRecord);
	}

	/**
	 * Function to send abuse report rejected mail to object owner
	 * 
	 * @param array $abuseReportRecord 
	 */
	private function __sendAbuseRejectedMailToObjectOwner($abuseReportRecord) {
		$Api = new ApiController();
		$Api->constructClasses();

		$abuseReport = $abuseReportRecord['AbuseReport'];
		$objectOwner = $abuseReportRecord['ObjectOwner'];
		$toEmail = $objectOwner['email'];
		$templateData = array(
			'username' => $objectOwner['username'],
			'admin_comment' => $abuseReport['admin_comment']
		);
		$objectType = $abuseReport['object_type'];
		if ($objectType === AbuseReport::OBJECT_TYPE_POST) {
			$postId = $abuseReport['object_id'];
			$templateId = EmailTemplateComponent::OWNER_POST_ABUSE_REPORT_REJECTED;
		} elseif ($objectType === AbuseReport::OBJECT_TYPE_COMMENT) {
			$comment = $abuseReportRecord['Comment'];
			$postId = $comment['post_id'];
			$templateId = EmailTemplateComponent::OWNER_COMMENT_ABUSE_REPORT_REJECTED;
			$templateData['comment'] = $comment['comment_text'];
		}
		$link = Router::Url('/', true) . "post/details/index/{$postId}";
		$templateData['link'] = $link;
		$Api->sendHTMLMail($templateId, $templateData, $toEmail);
	}

	/**
	 * Function to send abuse report rejected mail to reported user
	 * 
	 * @param array $abuseReportRecord 
	 */
	private function __sendAbuseRejectedMailToReportedUser($abuseReportRecord) {
		$Api = new ApiController();
		$Api->constructClasses();

		$abuseReport = $abuseReportRecord['AbuseReport'];
		$reportedUser = $abuseReportRecord['ReportedUser'];
		$toEmail = $reportedUser['email'];
		$templateData = array(
			'username' => $reportedUser['username'],
			'admin_comment' => $abuseReport['admin_comment']
		);
		$objectType = $abuseReport['object_type'];
		if ($objectType === AbuseReport::OBJECT_TYPE_POST) {
			$postId = $abuseReport['object_id'];
			$templateId = EmailTemplateComponent::REPORTER_POST_ABUSE_REPORT_REJECTED;
		} elseif ($objectType === AbuseReport::OBJECT_TYPE_COMMENT) {
			$comment = $abuseReportRecord['Comment'];
			$postId = $comment['post_id'];
			$templateId = EmailTemplateComponent::REPORTER_COMMENT_ABUSE_REPORT_REJECTED;
			$templateData['comment'] = $comment['comment_text'];
		}
		$link = Router::Url('/', true) . "post/details/index/{$postId}";
		$templateData['link'] = $link;
		$Api->sendHTMLMail($templateId, $templateData, $toEmail);
	}

	/**
	 * Function to send abuse report deleted mail to object owner and 
	 * reported user
	 * 
	 * @param array $abuseReportRecord 
	 */
	private function __sendAbuseDeletedMails($abuseReportRecord) {
		$this->__sendAbuseDeletedMailToObjectOwner($abuseReportRecord);
		$this->__sendAbuseDeletedMailToReportedUser($abuseReportRecord);
	}

	/**
	 * Function to send abuse report deleted mail to object owner
	 * 
	 * @param array $abuseReportRecord 
	 */
	private function __sendAbuseDeletedMailToObjectOwner($abuseReportRecord) {
		$Api = new ApiController();
		$Api->constructClasses();

		$abuseReport = $abuseReportRecord['AbuseReport'];
		$objectOwner = $abuseReportRecord['ObjectOwner'];
		$toEmail = $objectOwner['email'];
		$templateData = array(
			'username' => $objectOwner['username'],
			'admin_comment' => $abuseReport['admin_comment']
		);
		$objectType = $abuseReport['object_type'];
		if ($objectType === AbuseReport::OBJECT_TYPE_POST) {
			$templateId = EmailTemplateComponent::OWNER_ABUSE_REPORT_POST_DELETED;
		} elseif ($objectType === AbuseReport::OBJECT_TYPE_COMMENT) {
			$comment = $abuseReportRecord['Comment'];
			$templateId = EmailTemplateComponent::OWNER_ABUSE_REPORT_COMMENT_DELETED;
			$templateData['comment'] = $comment['comment_text'];
		}
		$Api->sendHTMLMail($templateId, $templateData, $toEmail);
	}

	/**
	 * Function to send abuse report deleted mail to reported user
	 * 
	 * @param array $abuseReportRecord 
	 */
	private function __sendAbuseDeletedMailToReportedUser($abuseReportRecord) {
		$Api = new ApiController();
		$Api->constructClasses();

		$abuseReport = $abuseReportRecord['AbuseReport'];
		$reportedUser = $abuseReportRecord['ReportedUser'];
		$toEmail = $reportedUser['email'];
		$templateData = array(
			'username' => $reportedUser['username'],
			'admin_comment' => $abuseReport['admin_comment']
		);
		$objectType = $abuseReport['object_type'];
		if ($objectType === AbuseReport::OBJECT_TYPE_POST) {
			$templateId = EmailTemplateComponent::REPORTER_ABUSE_REPORT_POST_DELETED;
		} elseif ($objectType === AbuseReport::OBJECT_TYPE_COMMENT) {
			$comment = $abuseReportRecord['Comment'];
			$templateId = EmailTemplateComponent::REPORTER_ABUSE_REPORT_COMMENT_DELETED;
			$templateData['comment'] = $comment['comment_text'];
		}
		$Api->sendHTMLMail($templateId, $templateData, $toEmail);
	}

	/**
	 * Function to view the details of an abuse reported post
	 * 
	 * @param int $postId 
	 */
	public function viewPost($postId) {
		$post = $this->Post->findById($postId);
		$postData = $this->Posting->getAbusePostDisplayData($post);
		$this->set($postData);
	}

	/**
	 * Function to view the details of an abuse reported comment
	 * 
	 * @param int $commentId 
	 */
	public function viewComment($commentId) {
		$comment = $this->Comment->findById($commentId);
		$postId = $comment['Comment']['post_id'];
		$post = $this->Post->findById($postId);
		$postData = $this->Posting->getAbusePostDisplayData($post, true);
		$this->set($postData);
		$this->set('selectedCommentId', $commentId);
		$this->view = 'view_post';
	}
}