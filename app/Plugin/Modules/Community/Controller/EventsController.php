<?php

/**
 * EventsController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('CommunityAppController', 'Community.Controller');

/**
 * EventsController for communities.
 * 
 * EventsController is used for managing community events.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Community
 * @category	Controllers 
 */
class EventsController extends CommunityAppController {

    public $uses = array('Community', 'CommunityDisease', 'CommunityMember', 'Timezone');
    public $components = array('EventForm');

    /**
     * Function to add community event
     */
    public function add() {
        $communityId = $this->request->params['communityId'];

        // throw error if no such community
        $community = $this->Community->findById($communityId);
        if (!$community) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect('/community');
        }
        
        // allow only approved community members and admin to add events to community
        $userId = $this->Auth->user('id');
        $isCommunityMember = $this->CommunityMember->isUserApprovedCommunityMember($userId, $communityId);
        $isCommunityAdmin = ($community['Community']['created_by'] == $userId) ? true : false;
//        $hasManagePermission = $this->CommunityMember->hasManagePermission($userId, $communityId);
        if ( !$isCommunityAdmin ) {
            $this->Session->setFlash(__('You are not allowed to access that page'), 'error');
            $this->redirect('/community');
        }

        // set community id on EventForm component
        $this->EventForm->communityId = $communityId;

        // display form, if form is not posted
        if (!$this->request->isPost()) {
            // load the community diseases by default
            $communityDiseases = $this->CommunityDisease->findAllByCommunityId($communityId);
            $communityDiseasesData = array();
            if (!empty($communityDiseases)) {
                $communityDiseasesData = array();
                foreach ($communityDiseases as $communityDisease) {
                    $communityDiseasesData[] = array(
                        'id' => $communityDisease['CommunityDisease']['id'],
                        'disease_id' => $communityDisease['Disease']['id'],
                        'disease_name' => $communityDisease['Disease']['name']
                    );
                }
                $communityDiseasesCount = count($communityDiseasesData);
                $data['eventDiseasesCount'] = $communityDiseasesCount;
            }

            // event form data
            $data['communityId'] = $communityId;
            $data['communityName'] = $community['Community']['name'];
            $data['eventImage'] = 'event.png';
            $data['backUrl'] = "/community/details/index/{$communityId}";

            $timeZones = $this->Timezone->get_timezone_list();
            $data['timeZones'] = $timeZones;
            $defaultTimeZone = $this->Auth->user('timezone');
            $data['defaultTimeZone'] = $defaultTimeZone;
            $data['startTime'] = 'false';
            $data['endTime'] = 'false';
            
            // set event form data on view
            $this->set($data);
            $this->request->data = array('EventDisease' => $communityDiseasesData);
            $this->EventForm->setFormData();
        } else {
            // save event
            $this->EventForm->saveEvent();
        }
    }
}