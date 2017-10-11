<?php

/**
 * EditController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');

/**
 * EditController for teams.
 * 
 * EditController is used for editing teams.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	MyTeam
 * @category	Controllers 
 */
class EditController extends MyTeamAppController {

	public $components = array('TeamForm');

	/**
	 * Function to edit a team
	 */
	public function index() {
		$teamData = $this->_teamObj['Team'];
                
                $teamPrivacyHintList =  array('Public - This will make the team visible to all the users.',
                                                'Private - This will make the team visible only for invited users and members');

		// redirect if logged in user does not have edit permission for the team
		if (!isset($this->_permissions['edit'])) {
			$this->Session->setFlash(__('You are not allowed to access that page'), 'error');
			$this->redirect($this->replaceUrl('list'));
		}

		$data['teamImage'] = Common::getTeamThumb($this->_teamId, $teamData['patient_id'], 'medium');
		$data['teamName'] = $teamData['name'];
		$data['teamUrl'] = $this->replaceUrl('home');
                $data['teamPrivacyHintList'] = $teamPrivacyHintList;
                
		// set data on form
		$this->TeamForm->setFormData($this->_teamId);
		$this->set($data);                

		if (!$this->request->data) {
			// set team data on form
			$this->request->data['Team'] = $teamData;
                        if ( $this->_memberRole == TeamMember::TEAM_ROLE_PATIENT ||
                               $this->_memberRole == TeamMember::TEAM_ROLE_PATIENT_ORGANIZER ) {
                            $this->set('isPatient', true);
                        }
		} else {   
                        if( $teamData['privacy'] != $this->request->data['Team']['privacy'] &&
                                $teamData['privacy'] == Team::TEAM_PRIVATE_TO_PUBLIC) {
                                $this->Session->setFlash('Team privacy has been changed while you were updating the team', 'error');
                                return $this->redirect("/myteam/{$teamData['id']}/edit");
                        } else {
                            $this->TeamForm->teamOldData = $teamData;
                            $this->TeamForm->memberRole = $this->_memberRole;
                            // save team
                            $this->TeamForm->saveTeam();
                        }
		}
	}
}