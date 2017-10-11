<?php

/**
 * DashboardController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AdminAppController', 'Admin.Controller');

/**
 * DashboardController for the admin
 * 
 * DashboardController is used for admin dashboard
 *
 * @author 	Ajay Arjunan
 * @package 	Admin
 * @category	Controllers 
 */
class AnalyticsController extends AdminAppController {

    /**
     * Admin Dashboard home
     */
    public $uses = array('Analytics');

    public function index() {
        $totalUsers = $this->Analytics->getUsersCount(FALSE);
        $totalEvents = $this->Analytics->getEventsCount(FALSE);
        $totalCommunities = $this->Analytics->getcommunityCount(FALSE);
        $topCountries = $this->getTopCountries();
        $topDiseases = $this->getTopDiseases();
        $topTreatments = $this->getTopTreatments();
        $userDetails = $this->userRegistration();
        $eventDetails = $this->getEvents();
        $communityDetails = $this->getCommunities();

        $this->set(compact('totalUsers', 'totalEvents', 'totalCommunities', 'topCountries', 'topDiseases', 'userDetails', 'eventDetails', 'communityDetails', 'topTreatments'));
    }

    public function userRegistration() {

        $data = array();
        $data['Total'] = $this->Analytics->getUsersCount(TRUE, 'Total');
        $data['Male'] = $this->Analytics->getUsersCount(TRUE, 'Male');
        $data['Female'] = $this->Analytics->getUsersCount(TRUE, 'Female');
        $result = array();

        foreach ($data as $key => $values) {
            foreach ($values as $value) {
                $dateTime = strtotime($value[0]['created_date']) * 1000;
                $result[$key][] = array(
                    0 => $dateTime,
                    1 => intval($value[0]['users'])
                );
            }
        }

        return json_encode($result);
    }

    public function getEvents() {

        $data['Total'] = $this->Analytics->getEventsCount(TRUE, 'Total');
        $data['Public'] = $this->Analytics->getEventsCount(TRUE, 'Public');
        $data['Private'] = $this->Analytics->getEventsCount(TRUE, 'Private');
        $results = array();

        foreach ($data as $key => $values) {
            foreach ($values as $value) {
                $dateTime = strtotime($value[0]['date']) * 1000;
                $result[$key][] = array(
                    0 => $dateTime,
                    1 => intval($value[0]['events'])
                );
            }
        }

        return json_encode($result);
    }

    public function getCommunities() {

        $data['Total'] = $this->Analytics->getCommunityCount(TRUE, 'Total');
        $data['Open'] = $this->Analytics->getCommunityCount(TRUE, 'Open');
        $data['Closed'] = $this->Analytics->getCommunityCount(TRUE, 'Closed');
        $results = array();

        foreach ($data as $key => $values) {
            foreach ($values as $value) {
                $dateTime = strtotime($value[0]['date']) * 1000;
                $result[$key][] = array(
                    0 => $dateTime,
                    1 => intval($value[0]['communities'])
                );
            }
        }

        return json_encode($result);
    }

    public function getTopDiseases() {
        $limit = 10;
        $topDiseases = $this->Analytics->getTopDiseases($limit);

        return $topDiseases;
    }

    public function getTopMedications() {
        $limit = 10;
        $this->autoRender = FALSE;
        $topMedications = $this->Analytics->getTopMedications($limit);
    }

    public function getTopCountries() {
        $limit = 10;
        $topCountries = $this->Analytics->getTopCountries($limit);

        return $topCountries;
    }
    
    public function getTopTreatments() {
        $limit = 10;
        $topTreatments = $this->Analytics->getTopTreatments($limit);
        return $topTreatments;
    }

    public function test() {
        $this->autoRender = false;
        $data = $this->getTopTreatments(10);
        debug(json_decode($data));
//        echo json_encode($data);
    }

}

?>