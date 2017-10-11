<?php

App::uses('AppModel', 'Model');
App::import('Controller', 'Api');

/**
 * Disease Model
 *
 */
class Disease extends AppModel {

    const APPROVED_DISEASES = 0;
    const AWAITING_USER_CREATED_DISEASE = 1;
    const ADMIN_REJECTED_USER_CREATED_DISEASE = 2;

    public $disease_logos = array(
        'aids.png',
        'asthma.png',
        'crohns.png',
        'colitis.png',
        'copd.png',
        'depression.png',
        'diabetes.png',
        'hepatitis.png',
        'hypercholestrol.png',
        'ibd.png',
        'lupus.png',
        'ptsd.png',
        'rsd.png',
        'scleroderma.png',
        'tsd.png'
    );
    public $validate = array(
        'id' => array(
        ),
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter the disease name'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'message' => 'Cannot be more than 100 characters long.'
            ),
            'remote' => array(
                'rule' => array('remote', '/api/checkExistingDiseaseName', 'name'),
                'message' => 'This disease name already exists.'
            )
        )
    );

    /* Functin to check the disease name already exists before save */

    public function beforeSave($options = array()) {
        $Api = new ApiController;
        $name = $this->data['Disease']['name'];
        $id = $this->data['Disease']['id'];

        return $Api->checkExistingDiseaseName($name, $id);
    }

    /*
     * Function to get disease logos
     */
    public function getDiseaseLogo($id = NULL) {
        if($id == NULL) {
            return $this->disease_logos;
        } else {
            if(key_exists($id, $this->disease_logos)) {
                return $this->disease_logos[$id];
            }
        }
    }

        /**
     * Function to get diseases list in JSON format
     * This JSON can be used for disease autocomplete search
     *
     * @return string
     */
    public function getDiseaseJSON() {
        $items = array();
        $data = $this->find('list');
        if (!empty($data)) {
            $items = array();
            foreach ($data as $id => $name) {
                $items[] = array(
                    'label' => $name,
                    'value' => $name,
                    'id' => $id
                );
            }
        }

        $diseaseJSON = json_encode($items);
        return $diseaseJSON;
    }

    public function getDiseaseName($disease_id) {
        if (isset($disease_id) && $disease_id != NULL && $disease_id > 0) {
            $data = $this->find('first', array(
                'conditions' => array('id' => $disease_id)
            ));
        } else {
            return $data = '-';
        }
        if (!empty($data)) {
            return $data['Disease']['name'];
        }
    }

    public function is_parent($id) {
        $parent = $this->find('all', array(
            'conditions' => array('parent_id' => $id)
        ));

        return (!empty($parent));
    }

    public function replaceParentDiseaseName($delete_id, $replace_id) {
        if ($this->hasAny(array('Disease.parent_id' => $delete_id))) {
            if ($this->updateAll(
                            array('Disease.parent_id' => $replace_id), array('Disease.parent_id' => $delete_id)
                    )) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * Function returns disease details by id
     *
     * @param Integer $id
     * @return mixed
     */
    public function get_disease_details_by_id($id) {
        $result = array();
        $disease_details = $this->findById($id);
        if (!empty($disease_details)) {
            $library_videos = json_decode($disease_details['Disease']['library'], TRUE);
            if (isset($library_videos['profile'])) {
                $disease_details['profile_video'] = $library_videos['profile'];
            } else {
                $disease_details['profile_video'] = '';
            }
            if (isset($library_videos['advertisement'])) {
                $disease_details['advertisement_video'] = $library_videos['advertisement'];
            } else {
                $disease_details['advertisement_video'] = '';
            }
            if (isset($library_videos['library'])) {
                $disease_details['library'] = $library_videos['library'];
            } else {
                $disease_details['library'] = '';
            }
            if (isset($disease_details['Disease']['dashboard_data'])) {
                $disease_details['dashboard_data'] = $disease_details['Disease']['dashboard_data'];
            } else {
                $disease_details['dashboard_data'] = '';
            }
            return $disease_details;
        } else {
            return FALSE;
        }
    }

    /**
     * Function returns parent diseases and their children diseases
     *
     * @return array
     */
    public function getConditionsList() {
        $diseases = $this->find('all', array(
            'conditions' => array('parent_id IS NULL'),
            'order' => array('name')
        ));

        foreach ($diseases as $parent) {
            if ($this->hasAny(array('parent_id' => $parent['Disease']['id']))) {
                $temp['parent'] = $parent;
                $temp['child'] = $this->find('all', array(
                    'conditions' => array('parent_id' => $parent['Disease']['id']),
                ));
                $condition['parents'][] = $temp;
            } else {
                $condition['others'][] = $parent;
            }
        }
        return $condition;
    }
    
    public function getDiseaseAdVideo($ids = array()) {
        
        $advertisement_videos = array();
        
        $diseases = $this->find('all', array(
            'conditions' => array( 'id' => $ids ),
            'fields' => array('library', 'name'),
            'order' => array('name desc')
        ));
        
        if (!empty($diseases)) {
            foreach ($diseases as $disease) {
                $library_videos = json_decode($disease['Disease']['library'], TRUE);
                if (isset($library_videos['advertisement']) && $library_videos['advertisement']!== '') {
                    $advertisement_videos[] = $library_videos['advertisement'];
                }
            }
            
            return $advertisement_videos;
        } else {
            return FALSE;
        }
    }

	/**
	 * Function to get the list of parent diseases
	 * 
	 * @return array
	 */
	public function getParentDiseasesList() {
		$parentDiseases = $this->find('list', array(
			'conditions' => array('parent_id IS NULL')
		));
		return $parentDiseases;
	}
}