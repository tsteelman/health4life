<?php

/**
 * SettingsController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');

/**
 * SettingsController for teams.
 * 
 * SettingsController is used for team settings.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	MyTeam
 * @category	Controllers 
 */
class SettingsController extends MyTeamAppController {

	/**
	 * Function to edit and save the settings for a team
	 */
	public function index() {
		// redirect if logged in user does not have permission to view this page
		if (!isset($this->_permissions['view_all'])) {
			$this->Session->setFlash(__('You are not allowed to access that page'), 'error');
			$this->redirect($this->replaceUrl('list'));
		}

		if (!$this->request->data) {
			$teamMember = $this->TeamMember->getTeamMember($this->_currentUserId, $this->_teamId);

			$enableEmail = $teamMember['email_notification'];
			$enableSite = $teamMember['site_notification'];
			$enableNotification = ($enableEmail || $enableSite);

			$teamSettings = array(
				'enable_notification' => $enableNotification,
				'email_notification' => $enableEmail,
				'site_notification' => $enableSite
			);
			$this->request->data['TeamSetting'] = $teamSettings;
		} else {
			// save team
			$data = $this->request->data['TeamSetting'];
			$data['team_id'] = $this->_teamId;
			$data['user_id'] = $this->_currentUserId;
			$enableNotification = (isset($data['enable_notification'])) ? true : false;
			if ($enableNotification === false) {
				$data['email_notification'] = TeamMember::NOTIFICATION_OFF;
				$data['site_notification'] = TeamMember::NOTIFICATION_OFF;
			} else {
				if (!isset($data['email_notification'])) {
					$data['email_notification'] = TeamMember::NOTIFICATION_OFF;
				}
				if (!isset($data['site_notification'])) {
					$data['site_notification'] = TeamMember::NOTIFICATION_OFF;
				}
			}
			$this->request->data['TeamSetting'] = $data;
			if ($this->TeamMember->saveNotificationSettings($data)) {
				$this->Session->setFlash('Successfully saved the notification settings', 'success');
			} else {
				$this->Session->setFlash('Failes to save the notification settings', 'error');
			}
		}

		$inputDefaults = array(
			'label' => false,
			'div' => false,
			'class' => 'form-control'
		);
		$teamUrl = $this->replaceUrl('home');
		$emailSiteVisibilityClass = ($enableNotification === true) ? '' : 'hide';

		$this->set(compact('inputDefaults', 'teamUrl', 'emailSiteVisibilityClass'));
	}
}