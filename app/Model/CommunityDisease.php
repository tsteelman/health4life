<?php

App::uses('AppModel', 'Model');
App::uses('Community', 'Model');

/**
 * CommunityDisease Model
 *
 * @property Community $Community
 * @property Disease $Disease
 */
class CommunityDisease extends AppModel {
//The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Community' => array(
            'className' => 'Community',
            'foreignKey' => 'community_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Disease' => array(
            'className' => 'Disease',
            'foreignKey' => 'disease_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    function findCommunitiesWithDisease($diseaseId) {
        $communities = $this->find('all', array(
            'conditions' => array('disease_id' => $diseaseId)
        ));

        if (isset($communities)) {
            return $communities;
        } else {
            return FALSE;
        }
    }

    function replaceDiseaseOfCommunities($currentDiseaseId, $newDiseaseId) {

        $communities = $this->find('all', array(
            'conditions' => array('disease_id' => $currentDiseaseId)
        ));
        foreach ($communities as $community) {
            if ($this->hasAny(array('disease_id' => $newDiseaseId, 'community_id' => $community['CommunityDisease']['community_id']))) {
                $this->delete($community['CommunityDisease']['id']);
            } else {
                $this->id = $community['CommunityDisease']['id'];
                $this->set('disease_id', $newDiseaseId);
                $this->save();
            }
        }
//        if ($this->updateAll(
//                        array('CommunityDisease.disease_id' => $newDiseaseId), array('CommunityDisease.disease_id' => $currentDiseaseId)
//                )) {
//            return TRUE;
//        }  else {
//            return FALSE; 
//        }
    }

	/**
	 * Function to get the public communities tagged with a disease
	 * 
	 * @param int $diseaseId
	 * @return array
	 */
	public function getPublicCommunitiesWithDisease($diseaseId) {
		$communityList = array();
		$query = array(
			'conditions' => array(
				'CommunityDisease.disease_id' => $diseaseId,
				'Community.type' => array(
					Community::COMMUNITY_TYPE_OPEN,
					Community::COMMUNITY_TYPE_SITE
				)
			),
			'fields' => array('Community.id')
		);
		$communities = $this->find('all', $query);
		if (!empty($communities)) {
			foreach ($communities as $community) {
				$communityList[] = $community['Community']['id'];
			}
		}
		return $communityList;
	}

	/**
	 * Function to get the diseases tagged with a public community
	 * 
	 * @param int $communityId
	 * @return array
	 */
	public function getDiseasesOfPublicCommunity($communityId) {
		$diseaseList = array();
		$query = array(
			'conditions' => array(
				'CommunityDisease.community_id' => $communityId,
				'Community.type' => array(
					Community::COMMUNITY_TYPE_OPEN,
					Community::COMMUNITY_TYPE_SITE
				)
			),
			'fields' => array('Disease.id')
		);
		$communityDiseases = $this->find('all', $query);
		if (!empty($communityDiseases)) {
			foreach ($communityDiseases as $communityDisease) {
				$diseaseList[] = $communityDisease['Disease']['id'];
			}
		}
		return $diseaseList;
	}
}