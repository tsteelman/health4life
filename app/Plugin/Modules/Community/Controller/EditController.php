<?php

/**
 * EditController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('CommunityAppController', 'Community.Controller');

/**
 * EditController for communities.
 * 
 * EditController is used for editing communities.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Community
 * @category	Controllers 
 */
class EditController extends CommunityAppController {

    public $uses = array('Community', 'CommunityDisease', 'Country', 'State');
    public $components = array('CommunityForm');

    /**
     * Function to edit a community
     * 
     * @param int $id
     * @throws NotFoundException
     */
    public function index($id) {
        // throw error if id is not passed
        if (!$id) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect('/community');
        }

        // throw error if no such community
        $community = $this->Community->findById($id);
        if (!$community) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect('/community');
        }

        $communityData = $community['Community'];

        // redirect if not owner of the community
        $userId = $this->Auth->user('id');
        $communityOwnerId = $communityData['created_by'];
        if ($userId !== $communityOwnerId) {
            $this->Session->setFlash(__('You are not allowed to access that page'), 'error');
            $this->redirect('/community');
        }

        // community diseases
        $communityDiseases = $this->CommunityDisease->findAllByCommunityId($id);
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
            $data['communityDiseasesCount'] = $communityDiseasesCount;
        }

        // community image
        $data['communityImage'] = Common::getCommunityThumb($id);
        $states = $this->Country->getCountryStates($communityData['country']);
        $cities = $this->State->getStateCities($communityData['state']);

        // location
        $data['states'] = $states;
        $data['cities'] = $cities;
        $data['stateDisabled'] = false;
        $data['cityDisabled'] = false;

		if (intval($communityData['type']) === Community::COMMUNITY_TYPE_SITE) {
			$data['diagnosisMandatoryClass'] = 'hide';
			$data['step3SiteWideVisibilityClass'] = '';
			$data['step3CommonVisibilityClass'] = 'hide';
		}

		// community details for breadcrumb
        $data['communityId'] = $id;
        $data['communityName'] = $communityData['name'];

        // set data on form
        $this->CommunityForm->setFormData($id);
        $this->set($data);
        $this->set('backUrl', '/community/details/index/' . $id);

        if (!$this->request->data) {
            // set community data on form
            $this->request->data = array_merge($community, array('CommunityDisease' => $communityDiseasesData));
        } else {
            // save community
            $this->CommunityForm->saveCommunity();
        }
    }
}