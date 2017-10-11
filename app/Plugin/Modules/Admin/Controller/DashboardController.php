<?php

/**
 * DashboardController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AdminAppController', 'Admin.Controller');

/**
 * DashboardController for the admin
 * 
 * DashboardController is used for admin dashboard
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Admin
 * @category	Controllers 
 */
class DashboardController extends AdminAppController {

    /**
     * Admin Dashboard home
     */
    public $uses = array('Analytics', 'AbuseReport');

    public function index() {
        $this->set('title_for_layout', 'Dashboard');
        $usersCount = $this->Analytics->getUsersCount(FALSE);
        $eventsCount = $this->Analytics->getEventsCount(FALSE);
        $communityCount = $this->Analytics->getcommunityCount(FALSE);
        $topDisease = $this->Analytics->getTopDiseases(1);
        $topTreatment = $this->Analytics->getTopTreatments(1);
        $abuseReportsCount = $this->AbuseReport->getNewAbuseReportsCount();

        $this->set(compact('usersCount', 'eventsCount', 'communityCount', 'topDisease', 'topTreatment', 'abuseReportsCount'));
    }

}

?>