<?php

/**
 * CalendarController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('CalendarAppController', 'Calendar.Controller');

/**
 * CalendarController for frontend calendar.
 * 
 * CalendarController is used for managing calendar entries.
 *
 * @author      Ajay Arjunan
 * @package 	Calendar
 * @category	Controllers 
 */
class CalendarController extends CalendarAppController {

    public $uses = array('Event', 'User', 'Country', 'State', 'City',
        'EventMember', 'Team', 'Timezone', 'AppoinmentForm', 'MedicationSchedule','CalendarForm');
    public $components = array('EventForm');

    /**
     * Message list index
     */
    public function index($showDate = null) {

        if (isset($this->request->date)) {
            $showDate = $this->request->date;
        }

        $calendarType = Event::CALENDAR_AND_ORDINARY_EVENTS; //3

        $timezone = $this->Auth->user('timezone');
        $time = new DateTime('now', new DateTimeZone($timezone));

        $timezoneOffset = $time->format('P');
        $timezoneOfindcator = $timezoneOffset;
        $hourMinuteArray = explode(":", $timezoneOffset);
        $hours = $hourMinuteArray[0] * 60;
        if ($hours < 0) {
            $hasNegSign = true;
            $hours = $hours * (-1);
            $timezoneOffsetInMinutes = (($hourMinuteArray[0]) * (-1) * 60) + $hourMinuteArray[1];
            $timezoneOffsetInMinutes = $timezoneOffsetInMinutes * (-1);
        } else {
            $timezoneOffsetInMinutes = (($hourMinuteArray[0]) * 60) + $hourMinuteArray[1];
        }
        $timezoneOffsetInMinutes = $timezoneOffsetInMinutes / (60);
        $timezoneOffset = $timezoneOffsetInMinutes;

        //data for adding new site event
        $timeZones = $this->Timezone->get_timezone_list();
        date_default_timezone_set($timezone);
        $startTime = 'false';
        $endTime = 'false';

//        if (substr($this->request->referer(1), 0, 9) == '/calendar') {
//            $refer = $this->request->referer(1);
//            $this->Session->write('refer', $refer);
//        }

        $this->EventForm->setFormData();
        $this->set('eventImage', 'event.png');
        $this->set('backUrl', '/event');
        $this->set('timeZones', $timeZones);
        $this->set('defaultTimeZone', $timezone);
        $this->set('startTime', $startTime);
        $this->set('endTime', $endTime);
        //data for adding new site event

        /*
         * Appoinment form validation 
         */
        $model = 'AppoinmentForm';
        $validations = $this->AppoinmentForm->validate;
        $reminderValidations = $this->CalendarForm->validate;
        $formId = 'AppoinmentForm';
        $this->JQValidator->addValidation($model, $validations, $formId);
        
        /*
         * Reminder form validation 
         */
        $this->JQValidator->addValidation('CalendarForm', $reminderValidations, 'CalendarForm');

        $this->set(compact('timezoneOffset', 'showDate', 'timezoneOfindcator', 'calendarType'));
    }

    public function ajaxCalendar() {
        $this->autoRender = FALSE;
        $method = $_GET["method"];
        
        switch ($method) {
            case "add":
                $ret = $this->addCalendar($_POST);
                break;
            case "list":
                $ret = $this->listCalendar($_POST["showdate"], $_POST["viewtype"], $_POST["calendartype"], $_POST["teamid"], $_POST["filtertype"]);
                break;
            case "update":
                $ret = $this->updateCalendar($_POST["calendarId"], $_POST["CalendarStartTime"], $_POST["CalendarEndTime"]);
                break;
            case "remove":
                $ret = $this->removeCalendar($_POST["calendarId"]);
                break;
            case "adddetails":
                $id = $_GET["id"];
                $st = $_POST["stpartdate"] . " " . $_POST["stparttime"];
                $et = $_POST["etpartdate"] . " " . $_POST["etparttime"];
                if ($id) {
                    $ret = $this->updateDetailedCalendar($id, $st, $et, $_POST["Subject"], $_POST["IsAllDayEvent"] ? 1 : 0, $_POST["Description"], $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"]);
                } else {
                    $ret = $this->addDetailedCalendar($st, $et, $_POST["Subject"], $_POST["IsAllDayEvent"] ? 1 : 0, $_POST["Description"], $_POST["Location"], $_POST["colorvalue"], $_POST["timezone"]);
                }
                break;
        }
        echo json_encode($ret);
    }

    function addCalendar($postData) {
        
        $ret = array();
        $ret['IsSuccess'] = false;
        if ($postData['event_category'] == 2) {
            $ret['IsSuccess'] = $this->EventForm->saveEvent();
            if ($ret['IsSuccess']) {
                $ret['Msg'] = 'add success';
            }
        } else if ($postData['event_category'] == 3) {

            $ret['IsSuccess'] = $this->saveAppoinmentData();
            $ret['Msg'] = 'add success';
        } else {
            $postDataForm = $postData['data']['CalendarForm'];
            $currentUserId = $this->Auth->user('id');
            $currentUserTimezone = $this->Auth->user('timezone');

            $start_date = $this->js2PhpTime($postDataForm['stpartdate'] . ' ' . $postDataForm['stparttime']);
            $start_date = $this->php2MySqlTime($start_date);
            $start_date = CakeTime::toServer($start_date, $currentUserTimezone);
            
            $end_date_time = $this->js2PhpTime($postDataForm['stpartdate'] . ' ' . $postDataForm['etparttime']);
            $end_date_time = $this->php2MySqlTime($end_date_time);
            $end_date_time = CakeTime::toServer($end_date_time, $currentUserTimezone);
            
            if($postDataForm['repeat'] == 0 ){
               $end_date = $end_date_time;
               $repeat_end_type = 0;
               $repeat_interval = 0;
               $repeat_mode = 0;
            } else {
                $end_date = ($postDataForm['repeat_end_type'] == Event::REPEAT_END_DATE) ? $postDataForm['end_date'] : '';
                $repeat_end_type = $postDataForm['repeat_end_type'];
                $repeat_interval = $postDataForm['repeat_interval'];
                $repeat_mode = $postDataForm['repeat_mode'];
            }
            if(isset($postDataForm['event_id']) && !empty($postDataForm['event_id']) && $postDataForm['event_id'] != '') {
                $event_id = $postDataForm['event_id'];
            } else {
                $event_id = NULL;
            }
            $saveData = array(
                'id' => $event_id,
                'name' => $postDataForm['Subject'],
                'description' => $postDataForm['Description'],
                'event_type' => Event::EVENT_TYPE_CALENDAR_REMINDER,
                'community_id' => NULL,
                'guest_can_invite' => 0,
                'repeat' => $postDataForm['repeat'],
                'created_by' => $currentUserId,
                'start_date' => $start_date,
                'span_date' => $end_date_time,
                'end_date' => $end_date,
                'repeat_mode' => $repeat_mode,
                'repeat_interval' => $repeat_interval,
                'repeat_end_type' => $repeat_end_type,
                'virtual_event' => 0, //has to be desided. for the time being its not a virtual event.
                'location' => '',
                'country' => NULL,
                'zip' => NULL,
                'state' => NULL,
                'city' => NULL,
                'timezone' => $currentUserTimezone,
                'tags' => '',
                'attending_count' => 1,
                'is_full_day' => 1,
            );
            
            $ret['IsSuccess'] = $this->EventForm->saveCalendarReminderEvent($saveData);
            $ret['Msg'] = 'add success';
        }
        return $ret;
    }

    function addDetailedCalendar($st, $et, $sub, $ade, $dscr, $loc, $color, $tz) {
        $ret = array();
        $ret['IsSuccess'] = true;
        $ret['Msg'] = 'add success';
        $ret['Data'] = rand();
        return $ret;
    }

    function listCalendarByRange($sd, $ed, $cnt = 50, $calendarType = 3, $team_id = 0, $filterValues = null, $viewType) {

        App::uses('Date', 'Utility');
        $LoggedInUserId = $this->Auth->user('id');
        
        $timezone = $this->Auth->user('timezone');// user timezone
        $sdMysqlFormat = $this->php2MySqlTime($sd);
        $edMysqlFormat = $this->php2MySqlTime($ed);
        $allEvents = NULL;
        
        switch ($calendarType) {
            case 3:
                $allEvents = $this->Event->getAllUserRelatedEventsForCalendar($LoggedInUserId, $sdMysqlFormat, $edMysqlFormat, $team_id);
                break;
            case 4:
                $allEvents = $this->Event->getAllUserRelatedEventsForCareCalendar($LoggedInUserId, $sdMysqlFormat, $edMysqlFormat, $team_id, $filterValues);
                break;
        }

        $ret = array();
        $ret['events'] = array();
        $ret["issort"] = true; //default value true
        $ret["start"] = $this->php2JsTime($sd);
        $ret["end"] = $this->php2JsTime($ed);
        $ret['error'] = null;
        $moreThanOneDayEvent = 0;
        $careCalnedarEventLink = '/event';
//        echo '<pre>';
//        foreach ($allEvents as $event) {
//            if($event['Event']['repeat'] == 1) {
//                print_r($event['Event']['id']);
//                echo '<br />';
//            }
//        }
//        exit;
        foreach ($allEvents as $event) {

            $eventType = $event['Event']['event_type'];
            $team = NULL;
            $eventTeamId = NULL;
            if (isset($event['Event']['section']) && $event['Event']['section'] == 2) {
                $eventTeamId = $event['Event']['section_id'];
            }

            $teamName = NULL;
            $taskCreatedBy = NULL;
            $taskAsignedTo = NULL;
            $careCalnedarEventLink = NULL;
            $taskStatusText = NULL;

            if (($eventType == Event::EVENT_TYPE_CARE_CALENDAR_EVENT) || ($eventType == Event::EVENT_TYPE_PUBLIC) || ($eventType == Event::EVENT_TYPE_PRIVATE) ||
                    ($eventType == Event::EVENT_TYPE_CALENDAR_REMINDER && $event['Event']['created_by'] == $LoggedInUserId) || ($eventType == Event::EVENT_TYPE_APPOINMENT && $event['Event']['created_by'] == $LoggedInUserId)) {
                if ($event['Event']['virtual_event'] == Event::VIRTUAL_EVENT) {
                    $eventLocation = 'Online Event'; //$event['Event']['online_event_details'];
                } else {
                    //$cityStateCounty = $this->City->getCityLocation($event['Event']['city']);
                    $eventLocation = 'On-Site Event'; //$event['Event']['location'] . ', ' . $cityStateCounty['City']['description'] . ', ' . $cityStateCounty['States']['description'] . ', ' . $cityStateCounty['Country']['short_name'];
                }
                $eventAttendingCount = $event['Event']['attending_count'];
                $eventTumb = Common::getEventThumb($event['Event']['id']);
                $eventStartDate = CakeTime::nice($event['Event']['start_date'], $timezone, '%m/%d/%Y %H:%M');
                $eventStartDateOnly = CakeTime::nice($event['Event']['start_date'], $timezone, '%m/%d/%Y');
                $eventEndDate = CakeTime::nice($event['Event']['end_date'], $timezone, '%m/%d/%Y %H:%M');
                $eventEndDateOnly = CakeTime::nice($event['Event']['end_date'], $timezone, '%m/%d/%Y');
                if ($eventStartDateOnly == $eventEndDateOnly) {
                    $moreThanOneDayEvent = 0;
                } else {
                    $moreThanOneDayEvent = 1;
                }
                if ($event['Event']['event_type'] == Event::EVENT_TYPE_CALENDAR_REMINDER) {//reminder event type 4
                    $colorCode = 3;
                } elseif ($event['Event']['event_type'] == Event::EVENT_TYPE_CARE_CALENDAR_EVENT) {
                    if (isset($eventTeamId) && $eventTeamId > 0 && $eventTeamId != NULL) {
                        $team = $this->Team->getTeam($eventTeamId);
                    } else {
                        $team = $this->Team->getTeam($team_id);
                    }

                    if (isset($team['name'])) {
                        $teamName = h($team['name']);
                    }
                    $careCalnedarEventLink = '/myteam/' . $eventTeamId . '/task/' . $event['Event']['id']; //$team_id

                    if (!empty($event['CareCalendar']['assigned_to']) && $event['CareCalendar']['assigned_to'] != NULL && $event['CareCalendar']['assigned_to'] > 0) {
                        $taskAsignedTo = $this->User->getUsername($event['CareCalendar']['assigned_to']);
                    }

                    if ($event['Event']['event_type'] == 'Other') {
                        $careCalendarEventType = $event['Event']['additional_notes'];
                    } else {
                        $careCalendarEventType = $event['Event']['event_type'];
                    }

                    if (!empty($event['Event']['created_by']) && $event['Event']['created_by'] != NULL) {
                        $taskCreatedBy = $this->User->getUsername($event['Event']['created_by']);
                    }
                    $taskStatus = $event['CareCalendar']['status'];
                    switch ($taskStatus) {
                        case CareCalendarEvent::STATUS_OPEN:
                            $colorCode = 23; //custom color added to the plugin //light rose
                            $taskStatusText = 'Open';
                            break;
                        case CareCalendarEvent::STATUS_WAITING_FOR_APPROVAL:
                            $colorCode = 26; //custom color added to the plugin// light orange
                            $taskStatusText = 'Waiting For Approval';
                            break;
                        case CareCalendarEvent::STATUS_ASSIGNED:
                            $colorCode = 7; //greenish blue color theme
                            $taskStatusText = 'Assigned';
                            break;
                        case CareCalendarEvent::STATUS_COMPLETED:
                            $colorCode = 5; //grayish blue
                            $taskStatusText = 'Completed';
                            break;
                        default :
                            $colorCode = 23; //custom color added to the plugin//light rose
                            $taskStatusText = 'Open';
                            break;
                    }
                } elseif ($event['Event']['event_type'] == Event::EVENT_TYPE_APPOINMENT) {
                    $colorCode = 4;
                    $eventEndDate = $eventStartDate;
                    $moreThanOneDayEvent = 0;
                    $eventLocation = '';
                } else {
                    $attendanceStatus = $this->EventMember->getStatus($event['Event']['id'], $LoggedInUserId);
                    switch ($attendanceStatus) {
                        case EventMember::STATUS_ATTENDING:
                            $colorCode = 10; //green
                            break;
                        case EventMember::STATUS_MAYBE_ATTENDING:
                            $colorCode = 25; //light red
                            break;
                        case EventMember::STATUS_PENDING:
                            $colorCode = 6; //blue
                            break;
                        default :
                            $colorCode = 6; //blue
                            break;
                    }
                }
                
                /*
                 * Merge array with duplicates created to show them in calendar.
                 */
                if ($event['Event']['repeat'] == 1) {
                    $repeatEventsArray = $this->getRepaetEventDuplicates($event, $colorCode, $eventLocation, $eventAttendingCount, $viewType, $edMysqlFormat, $sdMysqlFormat);
                    if($repeatEventsArray['status'] == TRUE) {
                      $ret = array_merge_recursive($ret,$repeatEventsArray['result']);
                    }
                } else {
                    $ret['events'][] = array(
                        0 => $event['Event']['id'], //event id
                        1 => h($event['Event']['name']), //event name
                        2 => $eventStartDate,
                        3 => $eventEndDate, //event eventEndDate
                        4 => 0,
                        5 => $moreThanOneDayEvent, //more than one day event
                        6 => $event['Event']['repeat'], //Recurring event
                        7 => $colorCode, // color theme code
                        8 => 1, //editable
                        9 => $eventLocation, //eventLocation// online or on site
                        10 => $eventAttendingCount, //attending users count
                        11 => "{$eventTumb}", //event thumbnail
                        12 => h($event['Event']['description']), //event description
                        13 => $event['Event']['event_type'], //event type
                        14 => $eventStartDateOnly, //event start date in m/d/y format.
                        15 => $taskAsignedTo, //care calendar event asignd to.
                        16 => $event['CareCalendar']['status'], //care calendar event status.
                        17 => $event['CareCalendar']['type'], //care calendar event type.
                        18 => h($event['CareCalendar']['additional_notes']), //care calendar event additional notes.
                        19 => $careCalnedarEventLink, //care calendar event details page link..
                        20 => $taskCreatedBy, //care calendar event created by username.
                        21 => $taskStatusText, //care calendar event status text.
                        22 => h($teamName), //care calendar event team name
                        23 => $eventTeamId, //care calendar event team id
                        24 => ''//any additional data as json text
                    );
                }
            }
        }
        /*
         * Adding recurring medication details to the array with duplicated entries.
         */
        if ($calendarType == 3) {
            $result = $this->mergeMedicationSchedulerWithCalendar($ret, $LoggedInUserId, $edMysqlFormat, $sdMysqlFormat, $viewType);
        } else {
            $result = $ret;
        }
        return $result;
    }

    function listCalendar($day, $type, $calendarType = 3, $team_id = 0, $filtertype = null) {
        $filterValues = json_decode($filtertype, TRUE);
        $phpTime = $this->js2PhpTime($day);
        switch ($type) {
            case "month":
                $st = mktime(0, 0, 0, date("m", $phpTime), 1, date("Y", $phpTime));
                $et = mktime(0, 0, -1, date("m", $phpTime) + 1, 1, date("Y", $phpTime));
                $cnt = 50;
                break;
            case "week":
                //suppose first day of a week is monday 
                $monday = date("d", $phpTime) - date('N', $phpTime) + 1;
                $st = mktime(0, 0, 0, date("m", $phpTime), $monday, date("Y", $phpTime));
                $et = mktime(0, 0, -1, date("m", $phpTime), $monday + 7, date("Y", $phpTime));
                $cnt = 20;
                break;
            case "day":
                $st = mktime(0, 0, 0, date("m", $phpTime), date("d", $phpTime), date("Y", $phpTime));
                $et = mktime(0, 0, -1, date("m", $phpTime), date("d", $phpTime) + 1, date("Y", $phpTime));
                $cnt = 5;
                break;
        }

        return $this->listCalendarByRange($st, $et, $cnt, $calendarType, $team_id, $filterValues, $type);
    }

    function updateCalendar($id, $st, $et) {
        $ret = array();
        $ret['IsSuccess'] = true;
        $ret['Msg'] = 'Succefully';
        return $ret;
    }

    function updateDetailedCalendar($id, $st, $et, $sub, $ade, $dscr, $loc, $color, $tz) {
        $ret = array();
        $ret['IsSuccess'] = true;
        $ret['Msg'] = 'Succefully';
        return $ret;
    }

    function removeCalendar($id) {
            $LoggedInUserId = $this->Auth->user('id');
            $result = $this->Event->removeReminderEvent($LoggedInUserId, $id);
        $ret = array();
        $ret['IsSuccess'] = $result['success'];
        $ret['Msg'] = $result['message'];
        return $ret;
    }

    function js2PhpTime($jsdate) {
        if (preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)@', $jsdate, $matches) == 1) {
            $ret = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
            //echo $matches[4] ."-". $matches[5] ."-". 0  ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
        } else if (preg_match('@(\d+)/(\d+)/(\d+)@', $jsdate, $matches) == 1) {
            $ret = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
            //echo 0 ."-". 0 ."-". 0 ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
        }
        return $ret;
    }

    function php2JsTime($phpDate) {
        return date("m/d/Y H:i", $phpDate);
    }

    function php2MySqlTime($phpDate) {
        return date("Y-m-d H:i:s", $phpDate);
    }

    function mySql2PhpTime($sqlDate) {
        $arr = date_parse($sqlDate);
        return mktime($arr["hour"], $arr["minute"], $arr["second"], $arr["month"], $arr["day"], $arr["year"]);
    }

    /*
     * Function to get events to be displayed in dashboard calendar.
     */

    function initializeDashboardCalendar() {
        $this->autoRender = false;
        $userId = $this->Auth->user('id');
        $timezone = $this->Auth->user('timezone');        
        $startDate = mktime(0, 0, 0, $this->request->query('month'), 1, $this->request->query('year'));
        $startDate = $this->php2MySqlTime($startDate);
        $startDate = date('Y-m-d H:m:s', strtotime($startDate . ' -0 day'));        
        $endDate = mktime(0, 0, 0, $this->request->query('month'), 1, $this->request->query('year'));
        $endDate = $this->php2MySqlTime($endDate);
        $endDate = date('Y-m-t H:m:s', strtotime($endDate . ' +0 day'));
        
        $monthInQ = $this->request->query('month');
        $yearInQ = $this->request->query('year');
        
        $eventsList = $this->Event->getAllUserRelatedEvents($userId, $startDate, $endDate);
        $data = array();
        foreach ($eventsList as $event) {

            if ($event['Event']['event_type'] == 1 || $event['Event']['event_type'] == 2) {
                $link = '/event/details/index/' . $event['Event']['id'];
                if($event['Event']['repeat'] == 1) {
                    $duplicate_array_events = NULL;
                    $duplicate_array_events = $this->getRepaetEventDuplicatesForDashboard($event,$endDate, $startDate);
                    if($duplicate_array_events['status'] == TRUE && !empty($duplicate_array_events['result'])) {
                        $duplicate_events = $duplicate_array_events['result']['events'];
                        foreach ($duplicate_events as $dup_event){
                            array_push($data, array(
                                'date' => $dup_event['start_date'],
                                'name' => h($event['Event']['name']),
                                'time' => $dup_event['start_time'],
                                'link' => $link
                                )
                            );
                        }
                    }
                }
               
            } elseif ($event['Event']['event_type'] == 4) {
                $duplicate_array_reminders = NULL;
                if($event['Event']['repeat'] == 1) {
                    $duplicate_array_reminders = $this->getRepaetEventDuplicatesForDashboard($event,$endDate, $startDate);
                    if($duplicate_array_reminders['status'] == TRUE && !empty($duplicate_array_reminders['result'])) {
                        $duplicate_reminders = $duplicate_array_reminders['result']['events'];
                       foreach ($duplicate_reminders as $dup_reminder){
                            $date_rem = $dup_reminder['start_date'];
                            $link_rem = '/calendar/' . $date_rem;
                            array_push($data, array(
                                'date' => $dup_reminder['start_date'],
                                'name' => h($event['Event']['name']),
                                'time' => $dup_reminder['start_time'],
                                'link' => $link_rem
                                )
                            );
                        }
                    }
                } else {
                    $date = CakeTime::nice($event['Event']['start_date'], $timezone, '%Y-%m-%d');
                    $link = '/calendar/' . $date;
                }
            } elseif ($event['Event']['event_type'] == 6) {
                $duplicate_array_reminders = NULL;
                if($event['Event']['repeat'] == 1) {
                    $duplicate_array_reminders = $this->getRepaetEventDuplicatesForDashboard($event,$endDate, $startDate);
                    if($duplicate_array_reminders['status'] == TRUE && !empty($duplicate_array_reminders['result'])) {
                        $duplicate_reminders = $duplicate_array_reminders['result']['events'];
                        foreach ($duplicate_reminders as $dup_reminder){
                            $date_rem = $dup_reminder['start_date'];
                            $link_rem = '/calendar/' . $date_rem;
                            array_push($data, array(
                                'date' => $dup_reminder['start_date'],
                                'name' => h($event['Event']['name']),
                                'time' => $dup_reminder['start_time'],
                                'link' => $link_rem
                                )
                            );
                        }
                    }
                } else {
                    $date = CakeTime::nice($event['Event']['start_date'], $timezone, '%Y-%m-%d');
                    $link = '/calendar/' . $date;
                }
            } elseif ($event['Event']['event_type'] == 5) {
                $date = CakeTime::nice($event['Event']['start_date'], $timezone, '%Y-%m-%d');
                $link = 'myteam/' . $event['Event']['section_id'] . '/task/' . $event['Event']['id'];
            }
            if($event['Event']['repeat'] != 1) {
                $date_month = CakeTime::nice($event['Event']['start_date'], $timezone, '%m');
                $date_year = CakeTime::nice($event['Event']['start_date'], $timezone, '%Y');
                if($date_month == $monthInQ && $date_year == $yearInQ) {
                    array_push($data, array(
                                        'date' => CakeTime::nice($event['Event']['start_date'], $timezone, '%Y-%m-%d'),
                                        'name' => h($event['Event']['name']),
                                        'time' => CakeTime::nice($event['Event']['start_date'], $timezone, '%I:%M %p'),
                                        'link' => $link
                                        )
                    );
                }
            }
            
        }
        
        $medicationArray = $this->mergeMedicationSchedulerWithCalendarForDashboard($endDate, $startDate);
        if(isset($medicationArray) && !empty($medicationArray)) {
            $link = '/scheduler';
            foreach ($medicationArray as $med) {
                 array_push($data, array(
                            'date' => $med['start_date'],
                            'name' => $med['name'],
                            'time' => $med['start_time'],
                            'link' => $link
                            )
                        );
            }
        }
        echo json_encode($data);
    }

    function saveAppoinmentData() {
        $postData = $this->request->data['AppoinmentForm'];
        $userId = $this->Auth->user('id');
        $currentUserTimezone = $this->Auth->user('timezone');

        $start_date = $this->js2PhpTime($postData['appoinment_date'] . ' ' . $postData['appoinment_time']);
        $start_date = $this->php2MySqlTime($start_date);
        $start_date = CakeTime::toServer($start_date, $currentUserTimezone);
            
        $end_date_time = $this->js2PhpTime($postData['appoinment_date'] . ' ' . $postData['appoinment_time']);
        $end_date_time = $this->php2MySqlTime($end_date_time);
        $end_date_time = CakeTime::toServer($end_date_time, $currentUserTimezone);
        
        if($postData['repeat'] == 0 ){
           $end_date = $end_date_time;
           $repeat_end_type = 0;
           $repeat_interval = 0;
           $repeat_mode = 0;
        } else {
            $end_date = ($postData['repeat_end_type'] == Event::REPEAT_END_DATE) ? $postData['end_date'] : '';
            $repeat_end_type = $postData['repeat_end_type'];
            $repeat_interval = $postData['repeat_interval'];
            $repeat_mode = $postData['repeat_mode'];
        }
        

        $saveData = array(
            'id' => $postData['id'],
            'name' => $postData['doctor_name'],
            'description' => $postData['appoinment_reason'],
            'event_type' => Event::EVENT_TYPE_APPOINMENT,
            'community_id' => NULL,
            'guest_can_invite' => 0,
            'repeat' => $postData['repeat'],
            'created_by' => $userId,
            'start_date' => $start_date,
            'span_date' => $end_date_time,
            'repeat_mode' => $repeat_mode,
            'repeat_interval' => $repeat_interval,
            'repeat_end_type' =>$repeat_end_type,
            'end_date' => $end_date,
            'virtual_event' => 0, //has to be desided. for the time being its not a virtual event.
            'location' => '',
            'country' => NULL,
            'zip' => NULL,
            'state' => NULL,
            'city' => NULL,
            'timezone' => $currentUserTimezone,
            'tags' => '',
            'attending_count' => 0,
            'is_full_day' => 1
        );

        return $this->EventForm->saveCalendarReminderEvent($saveData);
    }
    
    /*
     * Duplicating medication data for calendar tiles.
     */

    function mergeMedicationSchedulerWithCalendar($array, $LoggedInUserId, $edMysqlFormat, $sdMysqlFormat, $viewType) {

        App::uses('Date', 'Utility');
        $timezone = $this->Auth->user('timezone');
        $allMedScedules = $this->MedicationSchedule->getUserMedications($LoggedInUserId);
        $rruleArray = array();
        $newEventsArray = array();
        $rrule;

        $date1 = str_replace('-', '/', $sdMysqlFormat);
        $date2 = str_replace('-', '/', $edMysqlFormat);
        $sdMysqlFormat = date('Y-m-d', strtotime($date1 . "-7 days"));
        $edMysqlFormat = date('Y-m-d', strtotime($date2 . "+7 days"));

        foreach ($allMedScedules as $medication) {
            $rrule = Date::parseRRule($medication['MedicationSchedule']['rrule']);
            $medicationData = $medication['MedicationSchedule'];
            $treatmentData = $medication['Treatment'];

            $sqlStartDate = $medicationData['start_date'];
            $sqlEndDate = $medicationData['end_date'];
            if (empty($sqlStartDate) || is_null($sqlStartDate)) {
                $sqlStartDate = $medicationData['created'];
            }
            if (empty($sqlEndDate) || is_null($sqlEndDate)) {
                $sqlEndDate = $edMysqlFormat;
            }
            if ($sqlStartDate < $sdMysqlFormat) {
                $sqlStartDate = $sdMysqlFormat;
            }

            $eventStartDate = CakeTime::nice($sqlStartDate, $timezone, '%m/%d/%Y %H:%M');
            $eventStartDateOnly = CakeTime::nice($sqlStartDate, $timezone, '%m/%d/%Y');
            $eventEndDate = CakeTime::nice($sqlEndDate, $timezone, '%m/%d/%Y %H:%M');
            $eventEndDateOnly = CakeTime::nice($sqlEndDate, $timezone, '%m/%d/%Y');
            $colorCode = 24;
            $updatedDate = $eventStartDateOnly;
            $eventEndDateOnlyTimeStamp = strtotime($eventEndDateOnly);
            $updatedDateTimeStamp = strtotime($updatedDate);

            $isMedicationOnDate = FALSE;
            while ($eventEndDateOnlyTimeStamp >= $updatedDateTimeStamp) {

                $isMedicationOnDate = MedicationSchedule::isMedicationOnDate($updatedDate, $medicationData, $rrule);
                if (!$isMedicationOnDate) {
                    $updatedDate = date('m/d/Y', strtotime($updatedDate . ' +1 days'));
                    $updatedDateTimeStamp = strtotime($updatedDate);
                } else {
                    $dateFrequency = $rrule['INTERVAL_DAYS'];

                    foreach ($rrule['TIME_LIST'] as $rTime) {

                        $repeatingTime = date("H:i", strtotime($rTime));
                        $eventStartDateUpdated = $updatedDate . ' ' . $repeatingTime;
                        $additional_data = array(
                            'dosage_text' => $medicationData['dosage'] . ' ' . $medicationData['dosage_unit'] . ', ' . $medicationData['amount'] . ' Nos',
                            'timing_text' => $rrule['FREQUENCY_TEXT'] . ' ' . $rrule['TIME_TEXT']
                        );
                        $additional_data_json = json_encode($additional_data);
                        $array['events'][] = array(
                            0 => $medicationData['id'], //event id
                            1 => h($treatmentData['name']), //event name
                            2 => $eventStartDateUpdated,
                            3 => $eventStartDateUpdated, //event eventEndDate
                            4 => 0,
                            5 => 0, //$moreThanOneDayEvent
                            6 => 1, //Recurring event
                            7 => $colorCode,
                            8 => 1, //editable
                            9 => '', //eventLocation
                            10 => '', //attending users count
                            11 => '', //event thumbnail
                            12 => h($medicationData['additional_instructions']),//event description
                            13 => 7, //event type//medicaion scheduler
                            14 => $eventStartDateOnly, //event start date in m/d/y format.
                            15 => '', //care calendar event asignd to.//
                            16 => '', //care calendar event status.
                            17 => '', //care calendar event type.
                            18 => '', //care calendar event additional notes.
                            19 => '', //care calendar event details page link..
                            20 => '', //care calendar event created by username.
                            21 => '', //care calendar event status text.
                            22 => '', //care calendar event team name
                            23 => '', //care calendar event team id
                            24 => $additional_data_json, //additional notes for other types // here dosage details as a text
                        );
//                        if ($viewType == 'month') {
//                            break 1;
//                        }
                    }

                    $updatedDate = date('m/d/Y', strtotime($updatedDate . ' + ' . $dateFrequency . ' days'));
                    $updatedDateTimeStamp = strtotime($updatedDate);
                }
            }
        }
        return $array;
    }

    /**
     * Function to create array containing all the occurances fo the selected recurring event in the time range.
     */
    function getRepaetEventDuplicates($event, $colorCode, $eventLocation, $eventAttendingCount, $viewType, $edMysqlFormat, $sdMysqlFormat) {
        $eventEndTypeArray = array(Event::REPEAT_END_NEVER,Event::REPEAT_END_DATE);
        $eventRepeatModeArray = array(Event::REPEAT_MODE_DAILY, Event::REPEAT_MODE_MONTHLY,Event::REPEAT_MODE_WEEKLY,Event::REPEAT_MODE_YEARLY);
        $LoggedInUserId = $this->Auth->user('id');
        $timezone = $this->Auth->user('timezone');
        $eventTumb = Common::getEventThumb($event['Event']['id']);
        
        $date1 = str_replace('-', '/', $sdMysqlFormat);
        $date2 = str_replace('-', '/', $edMysqlFormat);
        $sdMysqlFormat = date('Y-m-d', strtotime($date1 . "-7 days"));
        $edMysqlFormat = date('Y-m-d', strtotime($date2 . "+7 days"));
        
        $event_start_date_db = CakeTime::nice($event['Event']['start_date'], $timezone, '%Y-%m-%d %H:%M:%S');
        $eventSatrtTimeOnlySql = CakeTime::format($event_start_date_db, '%H:%M:%S');
        
        $sdMysqlFormat = $sdMysqlFormat . ' ' . $eventSatrtTimeOnlySql;
        $edMysqlFormat = $edMysqlFormat . ' ' . '23:59:00';

        $sqlStartDate = $event_start_date_db;
        $sqlEndDate = $event['Event']['end_date'];
        $eventSingleEndDate = $event['Event']['span_date']; 
        
        $event_end_dateonly_db = NULL; //to show in edit popup
        $event_start_dateonly_db =CakeTime::nice($event['Event']['start_date'], $timezone, '%m/%d/%Y'); //to show in edit popup

        $allDayEvent = FALSE;

        if (empty($sqlEndDate) || is_null($sqlEndDate) || $sqlEndDate == '0000-00-00 00:00:00') {
            $sqlEndDate = $edMysqlFormat;
        } else {
            $event_end_date_db = CakeTime::nice($event['Event']['end_date'], $timezone, '%Y-%m-%d %H:%M:%S');
            $event_end_dateonly_db = CakeTime::nice($event['Event']['end_date'], $timezone, '%m/%d/%Y');
//            $sqlEndDate = CakeTime::nice($event['Event']['end_date'], $timezone, '%Y-%m-%d %H:%M:%S');//not needed
        }
        if ($sqlStartDate <= $sdMysqlFormat) {
            $sqlStartDate = $sdMysqlFormat;
        }
        if ($sqlEndDate >= $edMysqlFormat) {
            $sqlEndDate = $edMysqlFormat;
        }
        if (empty($eventSingleEndDate) || is_null($eventSingleEndDate) || $eventSingleEndDate == '0000-00-00 00:00:00') {
            $eventSingleEndDate = $sqlStartDate;
            $allDayEvent = TRUE;
        } else {
             $event_span_date_db = CakeTime::nice($event['Event']['span_date'], $timezone, '%Y-%m-%d %H:%M:%S');
             $eventSingleEndDate = $event_span_date_db;
        }
        
        /**
         * Geting Repeat interval values i text type.
         */
        $getRepeatIntervalTexts = $this->Event->getRepeatIntervalText();
        
        /*
         *  Details for calendar pop-up, editing.
         */
        $repeatDetailsArray = array(
            'repeat_mode' => $event['Event']['repeat_mode'],
            'repeat_interval' => $event['Event']['repeat_interval'],
            'repeats_on' => $event['Event']['repeats_on'],
            'repeats_by' => $event['Event']['repeats_by'],
            'repeat_end_type' => $event['Event']['repeat_end_type'],
            'start_date' => $event_start_dateonly_db,
            'end_date' => $event_end_dateonly_db,
            'interval_text' => $getRepeatIntervalTexts[$event['Event']['repeat_mode']]
        );
        $repeatDetailsJson = json_encode($repeatDetailsArray);
        
        $moreThanOneDayEvent = 0;
        
        
        /**
         * Calculating the first occurance of the given event in the given date range.
         */
        $timeStringStartDb = $event_start_date_db;
        $gotFirstDay = FALSE;
        $timeStringEnd = $sqlEndDate;
        $timeStringCalStart = $sdMysqlFormat;
        $firstStartDateFound = $sdMysqlFormat;
        $dateNoToAdd = $event['Event']['repeat_interval'];
        $repeatMode = $event['Event']['repeat_mode'];

        if(!in_array($event['Event']['repeat_end_type'], $eventEndTypeArray)){
            $gotFirstDay = FALSE; 
        } elseif(!in_array($repeatMode, $eventRepeatModeArray)){
            $gotFirstDay = FALSE;  
        } elseif (($event['Event']['start_date'] > $event['Event']['end_date']) && ($event['Event']['end_date'] != '0000-00-00 00:00:00')) {
           $gotFirstDay = FALSE;  
        } else {
            if ($timeStringStartDb == $sdMysqlFormat) {
                $firstStartDateFound = $timeStringStartDb;
                $gotFirstDay = TRUE;
            } else {
                while (strtotime($timeStringStartDb) <= strtotime($sqlEndDate)) {
                    if ((strtotime($timeStringStartDb) >= strtotime($sdMysqlFormat)) && (strtotime($timeStringStartDb) <= strtotime($edMysqlFormat))
                            || strtotime($timeStringStartDb) == strtotime($sdMysqlFormat)) {
                        $firstStartDateFound = $timeStringStartDb;
                        $gotFirstDay = TRUE;
                        break 1;
                    } else {
                        $timeStringStartDb = date('Y-m-d H:i:s', strtotime($timeStringStartDb . ' + ' . $dateNoToAdd . ' ' . $getRepeatIntervalTexts[$repeatMode]));
                    }
                }
            }
        }

        $returnArray = array();
        $resultArray = array();
        if ($gotFirstDay == TRUE) {
            $sqlStartDate = $firstStartDateFound;
            if ($allDayEvent == TRUE) {
                $eventSingleEndDate = $sqlStartDate;
            } else {
                $moreThanOneDayEvent = 1;
                $diff = strtotime($sqlStartDate) - strtotime($event_start_date_db);
                $diffDays = floor($diff / (3600 * 24));
                if($diffDays > 0) {
                    $eventSingleEndDate = date('Y-m-d H:i:s', strtotime($eventSingleEndDate . ' + ' . $diffDays . ' days'));
                }
            }
            
            $updatedStartDate = $sqlStartDate;
            $updatedEndDate = $eventSingleEndDate;

            $eventEndDateOnlyTimeStamp = strtotime($sqlEndDate);
            $updatedStartDateTimeStamp = strtotime($updatedStartDate);
            $updatedEndDateTimeStamp = strtotime($updatedEndDate);

            while ($eventEndDateOnlyTimeStamp >= $updatedStartDateTimeStamp) {
                
                $dateFrequency = $event['Event']['repeat_interval'];
                $repeatMode = $event['Event']['repeat_mode'];
                $updatedStartDateFormated = CakeTime::format($updatedStartDate, '%m/%d/%Y %H:%M');
                $eventStartDateOnly = CakeTime::format($updatedStartDate, '%m/%d/%Y');
                $updatedEndDateFormated = CakeTime::format($updatedEndDate, '%m/%d/%Y %H:%M');

                $resultArray['events'][] = array(
                    0 => $event['Event']['id'], //event id
                    1 => h($event['Event']['name']),//event name
                    2 => $updatedStartDateFormated, 
                    3 => $updatedEndDateFormated, //event eventEndDate
                    4 => 0,
                    5 => $moreThanOneDayEvent, //more than one day event
                    6 => $event['Event']['repeat'], //Recurring event
                    7 => $colorCode,
                    8 => 1, //editable
                    9 => $eventLocation, //eventLocation
                    10 => $eventAttendingCount, //attending users count
                    11 => "{$eventTumb}", //event thumbnail
                    12 => $event['Event']['description'],  //event description
                    13 => $event['Event']['event_type'], //event type
                    14 => $eventStartDateOnly, //event start date in m/d/y format.
                    15 => '', //care calendar event asignd to.
                    16 => '', //care calendar event status.
                    17 => '', //care calendar event type.
                    18 => '', //care calendar event additional notes.
                    19 => '', //care calendar event details page link..
                    20 => '', //care calendar event created by username.
                    21 => '', //care calendar event status text.
                    22 => '', //care calendar event team name
                    23 => '', //care calendar event team id
                    24 => $repeatDetailsJson//any additional data as json text
                );

                $updatedStartDate = date('Y-m-d H:i:s', strtotime($updatedStartDate . ' + ' . $dateFrequency . ' ' . $getRepeatIntervalTexts[$repeatMode]));
                if ($allDayEvent == FALSE) {
                    $updatedEndDate = date('Y-m-d H:i:s', strtotime($updatedEndDate . ' + ' . $dateFrequency . ' ' . $getRepeatIntervalTexts[$repeatMode]));
                }
                $updatedStartDateTimeStamp = strtotime($updatedStartDate);
            }
        }

        $returnArray = array(
            'result' => $resultArray,
            'status' => $gotFirstDay
        );
        return $returnArray;
    }
    
    
    /**
     * Function to create array all the occuring dates of recurring events
     */    
    function getRepaetEventDuplicatesForDashboard($event, $edMysqlFormat, $sdMysqlFormat) {//%Y-%m-%d //%I:%M %p 
         $LoggedInUserId = $this->Auth->user('id');
         $timezone = $this->Auth->user('timezone');
         $eventTumb = Common::getEventThumb($event['Event']['id']);

         $date1 = str_replace('-', '/', $sdMysqlFormat);
         $date2 = str_replace('-', '/', $edMysqlFormat);
         $sdMysqlFormat = date('Y-m-d', strtotime($date1 . "+0 days"));
         $edMysqlFormat = date('Y-m-d', strtotime($date2 . "-0 days"));

         $event_start_date_db = CakeTime::nice($event['Event']['start_date'], $timezone, '%Y-%m-%d %H:%M:%S');
         $eventSatrtTimeOnlySql = '00:00:00';//CakeTime::format($event_start_date_db, '%H:%M:%S');
         
         $sdMysqlFormat = $sdMysqlFormat . ' ' . $eventSatrtTimeOnlySql;
         $edMysqlFormat = $edMysqlFormat . ' ' . '23:59:00';

         $sqlStartDate = $event_start_date_db;
         $sqlEndDate = $event['Event']['end_date'];
         $eventSingleEndDate = $event['Event']['span_date']; 

         $event_end_dateonly_db = NULL; //to show in edit popup
         $event_start_dateonly_db =CakeTime::nice($event['Event']['start_date'], $timezone, '%m/%d-%Y'); //to show in edit popup

         $allDayEvent = FALSE;

         if (empty($sqlEndDate) || is_null($sqlEndDate) || $sqlEndDate == '0000-00-00 00:00:00') {
             $sqlEndDate = $edMysqlFormat;
         } else {
             $event_end_date_db = CakeTime::nice($event['Event']['end_date'], $timezone, '%Y-%m-%d %H:%M:%S');
             $event_end_dateonly_db = CakeTime::nice($event['Event']['end_date'], $timezone, '%m/%d/%Y');
 //            $sqlEndDate = CakeTime::nice($event['Event']['end_date'], $timezone, '%Y-%m-%d %H:%M:%S');//not needed
         }
         if ($sqlStartDate <= $sdMysqlFormat) {
             $sqlStartDate = $sdMysqlFormat;
         }
         if ($sqlEndDate >= $edMysqlFormat) {
             $sqlEndDate = $edMysqlFormat;
         }
         if (empty($eventSingleEndDate) || is_null($eventSingleEndDate) || $eventSingleEndDate == '0000-00-00 00:00:00') {
             $eventSingleEndDate = $sqlStartDate;
             $allDayEvent = TRUE;
         } else {
              $event_span_date_db = CakeTime::nice($event['Event']['span_date'], $timezone, '%Y-%m-%d %H:%M:%S');
              $eventSingleEndDate = $event_span_date_db;
         }

         /**
          * Geting Repeat interval values i text type.
          */
         $getRepeatIntervalTexts = $this->Event->getRepeatIntervalText();

         /*
          *  Details for calendar pop-up, editing.
          */
         $repeatDetailsArray = array();
         $repeatDetailsJson = json_encode($repeatDetailsArray);

         $moreThanOneDayEvent = 0;


        /**
         * Calculating the first occurance of the given event in the given date range.
         */
        $timeStringStartDb = $event_start_date_db;
        $gotFirstDay = FALSE;
        $timeStringEnd = $sqlEndDate;
        $timeStringCalStart = $sdMysqlFormat;
        $firstStartDateFound = $sdMysqlFormat;
        $dateNoToAdd = $event['Event']['repeat_interval'];
        $repeatMode = $event['Event']['repeat_mode'];
        
        $eventEndTypeArray = array(Event::REPEAT_END_NEVER,Event::REPEAT_END_DATE);
        $eventRepeatModeArray = array(Event::REPEAT_MODE_DAILY, Event::REPEAT_MODE_MONTHLY,Event::REPEAT_MODE_WEEKLY,Event::REPEAT_MODE_YEARLY);
        
        if(!in_array($event['Event']['repeat_end_type'], $eventEndTypeArray)){
            $gotFirstDay = FALSE; 
        } elseif(!in_array($repeatMode, $eventRepeatModeArray)){ 
            $gotFirstDay = FALSE; 
        } elseif (($event['Event']['start_date'] > $event['Event']['end_date']) && ($event['Event']['end_date'] != '0000-00-00 00:00:00')){
            $gotFirstDay = FALSE; 
        } else {
             if ($timeStringStartDb == $sdMysqlFormat) {
                 $firstStartDateFound = $timeStringStartDb;
                 $gotFirstDay = TRUE;
             } else {
                 while (strtotime($timeStringStartDb) <= strtotime($sqlEndDate)) {
                     if ((strtotime($timeStringStartDb) >= strtotime($sdMysqlFormat)) && (strtotime($timeStringStartDb) <= strtotime($edMysqlFormat))
                             || strtotime($timeStringStartDb) == strtotime($sdMysqlFormat)) {
                         $firstStartDateFound = $timeStringStartDb;
                         $gotFirstDay = TRUE;
                         break 1;
                     } else {
                         $timeStringStartDb = date('Y-m-d H:i:s', strtotime($timeStringStartDb . ' + ' . $dateNoToAdd . ' ' . $getRepeatIntervalTexts[$repeatMode]));
                     }
                 }
             }
         }

         $returnArray = array();
         $resultArray = array();
         if ($gotFirstDay == TRUE) {
             $sqlStartDate = $firstStartDateFound;
             if ($allDayEvent == TRUE) {
                 $eventSingleEndDate = $sqlStartDate;
             } else {
                 $moreThanOneDayEvent = 1;
                 $diff = strtotime($sqlStartDate) - strtotime($event_start_date_db);
                 $diffDays = floor($diff / (3600 * 24));
                 if($diffDays > 0) {
                     $eventSingleEndDate = date('Y-m-d H:i:s', strtotime($eventSingleEndDate . ' + ' . $diffDays . ' days'));
                 }
             }

             $updatedStartDate = $sqlStartDate;
             $updatedEndDate = $eventSingleEndDate;

             $eventEndDateOnlyTimeStamp = strtotime($sqlEndDate);
             $updatedStartDateTimeStamp = strtotime($updatedStartDate);
             $updatedEndDateTimeStamp = strtotime($updatedEndDate);

             while ($eventEndDateOnlyTimeStamp >= $updatedStartDateTimeStamp) {

                 $dateFrequency = $event['Event']['repeat_interval'];
                 $repeatMode = $event['Event']['repeat_mode'];
                 $updatedStartDateFormated = CakeTime::format($updatedStartDate, '%Y-%m-%d');
                 $updatedStartTimeFormated = CakeTime::format($updatedStartDate, '%I:%M %p');
                 $eventStartDateOnly = CakeTime::format($updatedStartDate, '%m/%d/%Y');
                 $updatedEndDateFormated = CakeTime::format($updatedEndDate, '%Y-%m-%d');

                 $resultArray['events'][] = array(
                     'id' => $event['Event']['id'], //event id
                     'name' => h($event['Event']['name']),//event name
                     'start_date' => $updatedStartDateFormated, 
                     'start_time' => $updatedStartTimeFormated, 
                     'end_date' => $updatedEndDateFormated, //event eventEndDate
                 );
//                 echo '<pre>';
//                 print_r($event);
//                 exit;
                 if($allDayEvent == FALSE) {
                    $moreThanOneDayEvent = 1;
                    $diff = strtotime($event_span_date_db) - strtotime($event_start_date_db);
                    $diffDays = floor($diff / (3600 * 24));
                    if($diffDays > 0) {
//                        $eventSingleEndDate = date('Y-m-d H:i:s', strtotime($eventSingleEndDate . ' + ' . 1 . ' days'));
                        $updatedStartDateTemp = date('Y-m-d H:i:s', strtotime($updatedStartDate . ' + 1 days'));
                        $updatedStartDateTempTimeStamp = strtotime($updatedStartDateTemp);
                        for($dayCount = 1; $dayCount <=$diffDays; $dayCount++ ) {
                            if($eventEndDateOnlyTimeStamp >= $updatedStartDateTempTimeStamp){
                                $updatedStartDateFormatedTemp = CakeTime::format($updatedStartDateTemp, '%Y-%m-%d');
                                $updatedStartTimeFormatedTemp = '12.00 AM';
                                $resultArray['events'][] = array(
                                    'id' => $event['Event']['id'], //event id
                                    'name' => h($event['Event']['name']),//event name
                                    'start_date' => $updatedStartDateFormatedTemp, 
                                    'start_time' => $updatedStartTimeFormatedTemp, 
                                    'end_date' => $updatedEndDateFormated, //event eventEndDate
                                );
                                $updatedStartDateTemp = date('Y-m-d H:i:s', strtotime($updatedStartDateTemp . ' + 1 days'));
                                $updatedStartDateTempTimeStamp = strtotime($updatedStartDateTemp);
                            }
                        }
                    }
                 }
                 $updatedStartDate = date('Y-m-d H:i:s', strtotime($updatedStartDate . ' + ' . $dateFrequency . ' ' . $getRepeatIntervalTexts[$repeatMode]));
//                 if ($allDayEvent == FALSE) {
//                     $updatedEndDate = date('Y-m-d H:i:s', strtotime($updatedEndDate . ' + ' . $dateFrequency . ' ' . $getRepeatIntervalTexts[$repeatMode]));
//                 }
                 $updatedStartDateTimeStamp = strtotime($updatedStartDate);
             }
         }

         $returnArray = array(
             'result' => $resultArray,
             'status' => $gotFirstDay
         );
         return $returnArray;
    }
    
    function mergeMedicationSchedulerWithCalendarForDashboard($edMysqlFormat, $sdMysqlFormat) {
        $viewType = 'week';
        $LoggedInUserId = $this->Auth->user('id');
        $resultArray = array();
        App::uses('Date', 'Utility');
        $timezone = $this->Auth->user('timezone');
        $allMedScedules = $this->MedicationSchedule->getUserMedications($LoggedInUserId);
        $rruleArray = array();
        $newEventsArray = array();
        $rrule;

        $date1 = str_replace('-', '/', $sdMysqlFormat);
        $date2 = str_replace('-', '/', $edMysqlFormat);
        $sdMysqlFormat = date('Y-m-d', strtotime($date1 . "-0 days"));
        $edMysqlFormat = date('Y-m-d', strtotime($date2 . "+0 days"));

        foreach ($allMedScedules as $medication) {
            $rrule = Date::parseRRule($medication['MedicationSchedule']['rrule']);
            $medicationData = $medication['MedicationSchedule'];
            $treatmentData = $medication['Treatment'];

            $sqlStartDate = $medicationData['start_date'];
            $sqlEndDate = $medicationData['end_date'];
            if (empty($sqlStartDate) || is_null($sqlStartDate)) {
                $sqlStartDate = $medicationData['created'];
            }
            if (empty($sqlEndDate) || is_null($sqlEndDate)) {
                $sqlEndDate = $edMysqlFormat;
            }
            if ($sqlStartDate < $sdMysqlFormat) {
                $sqlStartDate = $sdMysqlFormat;
            }

            $eventStartDate = CakeTime::nice($sqlStartDate, $timezone, '%Y-%m-%d %H:%M');
            $eventStartDateOnly = CakeTime::nice($sqlStartDate, $timezone, '%Y-%m-%d');
            $eventEndDate = CakeTime::nice($sqlEndDate, $timezone, '%Y-%m-%d %H:%M');
            $eventEndDateOnly = CakeTime::nice($sqlEndDate, $timezone, '%Y-%m-%d');
            $colorCode = 24;
            $updatedDate = $eventStartDateOnly;
            $eventEndDateOnlyTimeStamp = strtotime($eventEndDateOnly);
            $updatedDateTimeStamp = strtotime($updatedDate);

            $isMedicationOnDate = FALSE;
            while ($eventEndDateOnlyTimeStamp >= $updatedDateTimeStamp) {

                $isMedicationOnDate = MedicationSchedule::isMedicationOnDate($updatedDate, $medicationData, $rrule);
                if (!$isMedicationOnDate) {
                    $updatedDate = date('Y-m-d', strtotime($updatedDate . ' +1 days'));
                    $updatedDateTimeStamp = strtotime($updatedDate);
                } else {
                    $dateFrequency = $rrule['INTERVAL_DAYS'];

                    foreach ($rrule['TIME_LIST'] as $rTime) {

                        $repeatingTime = date("h:i A", strtotime($rTime));
                        $eventStartDateUpdated = $updatedDate . ' ' . $repeatingTime;
//                        $additional_data = array(
//                            'dosage_text' => $medicationData['dosage'] . ' ' . $medicationData['dosage_unit'] . ', ' . $medicationData['amount'] . ' Nos',
//                            'timing_text' => $rrule['FREQUENCY_TEXT'] . ' ' . $rrule['TIME_TEXT']
//                        );
//                        $additional_data_json = json_encode($additional_data);
                        $resultArray[] = array(
                                    'id' => $medicationData['id'], //event id
                                    'name' => h($treatmentData['name']),//event name
                                    'start_date' => $updatedDate, 
                                    'start_time' => $repeatingTime, 
                                    'end_date' => $eventEndDateOnly, //event eventEndDate
                                );
                        
//                        if ($viewType == 'month') {
//                            break 1;
//                        }
                    }

                    $updatedDate = date('Y-m-d', strtotime($updatedDate . ' + ' . $dateFrequency . ' days'));
                    $updatedDateTimeStamp = strtotime($updatedDate);
                }
            }
        }
        return $resultArray;
    }

}
