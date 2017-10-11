<?php

/**
 * VideoProcessingComponent class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('Analytics', 'Model');

/**
 * VideoProcessingComponent to handle videos.
 * 
 * This class is used to handle the processing of vimeo videos.
 * This class is used by the cron shell via API controller to update the 
 * video status and thumbnail of processed videos.
 *
 * @author 	Ajay Arjunan
 * @package 	Admin.Controller.Component
 * @category	Component 
 */
class AnalyticsComponent extends Component {
    
    public function __construct() {
        $this->Analytics = ClassRegistry::init('Analytics');
    }
    

    public function getDiseaseUserGenderCount($diseaseId = NULL) {
        $this->autoRender = FALSE;
        if (!empty($this->request->query)) {
            $diseaseId = $this->request->query('diseaseId');
        }

        $data = $this->Analytics->getDiseaseGenderAnalytics($diseaseId);

        return json_encode($data);
    }

    public function getDiseaseUserAgeCount($diseaseId = NULL) {
        $this->autoRender = FALSE;
        if (!empty($this->request->query)) {
            $diseaseId = $this->request->query('diseaseId');
        }
        $data = $this->Analytics->getDiseaseAgeAnalytics($diseaseId);

        $result = array();
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'kids':
                    $result[] = array(
                        0 => 'under 18',
                        1 => intval($value)
                    );
                    break;
                case 'junior':
                    $result[] = array(
                        0 => '[19-25]',
                        1 => intval($value)
                    );
                    break;
                case 'adult':
                    $result[] = array(
                        0 => '[25-35]',
                        1 => intval($value)
                    );
                    break;
                case 'middle':
                    $result[] = array(
                        0 => '[35-60]',
                        1 => intval($value)
                    );
                    break;
                case 'senior':
                    $result[] = array(
                        0 => 'above 60',
                        1 => intval($value)
                    );
                    break;
            }
        }
        return json_encode($result);
    }

    public function getTopDiseaseCountry($diseaseId = NULL) {
        $this->autoRender = FALSE;
        if (!empty($this->request->query)) {
            $diseaseId = $this->request->query('diseaseId');
        }

        $data = $this->Analytics->getTopDiseaseCountry($diseaseId);

        foreach ($data as $value) {
            $categories[] = $value['COUNTRY']['name'];
            $users[] = intval($value[0]['users']);
        }

        $response = array(
            'categories' => $categories,
            'users' => $users
        );
        return json_encode($response);
    }

    public function getDiseaseUserLocations($diseaseId = NULL) {
        $this->autoRender = FALSE;
        if (!empty($this->request->query)) {
            $diseaseId = $this->request->query('diseaseId');
        }
        $data = $this->Analytics->getDiseaseUserLocation($diseaseId);
        return json_encode($data);
    }

    public function getDiseaseAnalytics($diseaseId) {
        $this->autoRender = false;
        $data = array();
        $data['age'] = $this->getDiseaseUserAgeCount($diseaseId);
        $data['gender'] = $this->getDiseaseUserGenderCount($diseaseId);
        $data['location'] = $this->getDiseaseUserLocations($diseaseId);
        $data['top_country'] = $this->getTopDiseaseCountry($diseaseId);
        
        $result['data'] = json_encode($data);
        $result['users'] = $this->Analytics->getDiseaseUserCount($diseaseId);
        $result['events'] = $this->Analytics->getDiseaseEventsCount($diseaseId);
        $result['communities'] = $this->Analytics->getDiseaseCommunitiesCount($diseaseId);
        
        return $result;
    }

}

?>