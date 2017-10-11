<?php

/**
 * CommunityController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('CommunityAppController', 'Community.Controller');

/**
 * CommunityController for frontend communities.
 * 
 * CommunityController is used for listing communities.
 *
 * @author 	Ajay Arjunan
 * @package 	Community
 * @category	Controllers 
 */
class CommunityController extends CommunityAppController {

    public $uses = array('Community', 'User', 'CommunityMember', 'PatientDisease', 'CommunityDisease');
    public $components = array('Paginator', 'RadiusSearch');

    /*
     * Function to list the communities.
     * 
     * @param int $community_type
     */

    public function index($community_type = '') {

        $user = $this->Auth->user();
        $communities = array();

        $userCommunityIds = array();
        $invitedCommunityIds = array();
        $diseases = $this->PatientDisease->findDiseases($user['id']); //Get the diseases associated with current user for identifing interested communities

        $userCommunities = $this->CommunityMember->find('list', array(
            'conditions' => array(
                'CommunityMember.user_id' => $user['id'],
                'CommunityMember.status' => CommunityMember::STATUS_APPROVED
            ),
            'fields' => array('CommunityMember.community_id')
                )
        );

        $invitedCommunities = $this->CommunityMember->find('list', array(
            'conditions' => array(
                'CommunityMember.user_id' => $user['id'],
                'CommunityMember.status' => CommunityMember::STATUS_INVITED
            ),
            'fields' => array('CommunityMember.community_id')
                )
        );

        $awaitingApprovalCommunities = $this->CommunityMember->find('list', array(
            'conditions' => array(
                'CommunityMember.user_id' => $user['id'],
                'CommunityMember.status' => CommunityMember::STATUS_NOT_APPROVED
            ),
            'fields' => array('CommunityMember.community_id')
                )
        );

        foreach ($invitedCommunities as $id) {
            $invitedCommunityIds[] = $id;
        }

        foreach ($userCommunities as $id) {
            $userCommunityIds[] = $id;
        }
		
        $nearByCities = $this->RadiusSearch->getNearByCities($user['id'], 250, 30);
		
        $user_all_community = array_merge($userCommunityIds, $invitedCommunityIds, $awaitingApprovalCommunities);
        if (isset($community_type) && $community_type != "") {
            switch ($community_type) {
                case 1:
					//Communities created by user
                    $this->getPaginatorMyCommunities($user['id']);
                    break;
                case 2:
					//Communities in which user belongs to
                    $this->getPaginatorBelongingCommunities($userCommunities, $user['id']);
                    break;
                case 3:
					//Communities from which user got an invitation to join
                    $this->getPaginatorInvitedCommunities($invitedCommunityIds);
                    break;
                case 4:
					//Communities in which user waiting for the approval
                    $this->getPaginatorAwaitingApproval($awaitingApprovalCommunities);
                    break;
                case 5:
					//Communities user might be interested in based upon diseases of user
                    $this->getPaginatorInterestedCommunities($user_all_community, $diseases, $nearByCities);
                    break;
            }

            $communities = $this->paginate('Community');
			$pageCount = $this->params['paging']['Community']['pageCount'];
            if (isset($this->request->params['named']['page'])) {
                $nextPage = $this->request->params['named']['page'] + 1;
            }

            $this->set(compact('community_type', 'users', 'communities', 'nextPage', 'pageCount'));
            $this->layout = "ajax";
            $View = new View($this, false);
            $response = $View->element('Community.community_row');
            echo $response;
            exit;
        } else {
			$this->getPaginatorMyCommunities($user['id']);
            $myCommunities = $this->paginate('Community');
            $pageCountArray[1] = $this->params['paging']['Community']['pageCount'];
            
            $this->getPaginatorBelongingCommunities($userCommunities, $user['id']);
            $belongingCommunities = $this->paginate('Community');
            $pageCountArray[2] = $this->params['paging']['Community']['pageCount'];
			
            $this->getPaginatorInvitedCommunities($invitedCommunityIds);
            $communityInvitations = $this->paginate('Community');
            $pageCountArray[3] = $this->params['paging']['Community']['pageCount'];
			
            $this->getPaginatorAwaitingApproval($awaitingApprovalCommunities);
            $awaitingCommunities = $this->paginate('Community');
            $pageCountArray[4] = $this->params['paging']['Community']['pageCount'];
			
            $this->getPaginatorInterestedCommunities($user_all_community, $diseases, $nearByCities);
            $interestedCommunities = $this->paginate('Community');
            $pageCountArray[5] = $this->params['paging']['Community']['pageCount'];
            $nextPage = '';
            
            $this->set(
                    compact(
                            'community_type','users', 'myCommunities','belongingCommunities', 
                            'communityInvitations', 'awaitingCommunities', 'interestedCommunities', 'nextPage',
                            'user', 'pageCountArray'
                            )
                    );
		}
    }
	
	function getPaginatorMyCommunities($userId) {
		$this->paginate = array(
                        'limit' => 3,
                        'conditions' => array('Community.created_by' => $userId),
                        'order' => array('Community.name' => 'asc')
                    );
	}
	
	function getPaginatorBelongingCommunities( $userCommunityIds , $userId) {
		$this->paginate = array(
                        'limit' => 3,
                        'conditions' => array(
                            'Community.id' => $userCommunityIds,
                            'Community.created_by !=' => $userId
                        ),
                        'order' => array('Community.name' => 'asc')
                    );
	}
	
	function getPaginatorInvitedCommunities($invitedCommunityIds) {
		$this->paginate = array(
                        'limit' => 3,
                        'conditions' => array(
                            'Community.id' => $invitedCommunityIds
                        ),
                        'order' => array('Community.name' => 'asc')
                    );
	}
	
	function getPaginatorAwaitingApproval($awaitingApprovalCommunities) {
		$this->paginate = array(
                        'limit' => 3,
                        'conditions' => array(
                            'Community.id' => $awaitingApprovalCommunities
                        ),
                        'order' => array('Community.name' => 'asc')
                    );
	}
	
	function getPaginatorInterestedCommunities($user_all_community, $diseases, $nearByCities) {
		$this->paginate = array(
                       'limit' => 3,
                        'conditions' => array(
                            'Community.id !=' => $user_all_community,
                            'OR' => array(
                                array('AND' => array(
                                        'CommunityDisease.disease_id' => $diseases,
                                        'Community.city' => $nearByCities
                                    )),
                                array('AND' => array(
                                        'CommunityDisease.disease_id' => $diseases
                                    )),
                                array('Community.type' => Community::COMMUNITY_TYPE_SITE)
                            )
                        ),
                        'joins' => array(
                            array(
                                'table' => 'community_diseases',
                                'type' => 'LEFT',
                                'alias' => 'CommunityDisease',
                                'conditions' => array(
                                    'Community.id = CommunityDisease.community_id'
                                )
                            )
                        ),
                        'order' => array('Community.name' => 'asc'),
                        'group' => array('Community.id')
                    );
	}
}

?>