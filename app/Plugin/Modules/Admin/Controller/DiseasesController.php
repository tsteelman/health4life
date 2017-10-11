<?php

/**
 * DiseaseListManagementController class file.
 *
 * @author    Varun Ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
/**
 * Disease List Management for the admin
 *
 * Disease List Management Controller is used for admin to edit and create Diseases
 *
 * @author 	Varun Ashok
 * @package 	Admin
 * @category	Controllers
 */
App::uses('Common', 'Utility');
App::uses('CakeTime', 'Utility');

App::uses('AdminAppController', 'Admin.Controller');
App::uses('User', 'Model');
App::uses('Date', 'Utility');
App::import('Controller', 'Api');
App::uses('File', 'Utility');
App::import('Vendor', 'ImageTool');
App::uses('Crawler', 'Utility');

class DiseasesController extends AdminAppController {

    public $uses = array(
        'Disease',
        'PatientDisease',
        'EventDisease',
        'CommunityDisease',
        'User',
        'DiseaseSymptom',
        'Symptom',
        'Survey'
    );
    public $components = array('Session', 'Email', 'EmailTemplate', 'EmailQueue', 'Uploader', 'Analytics');
    public $minimumImageSize = array('320', '140');
    
    const DISEASE_IN_USER = 'profile';
    const DISEASE_IN_EVENT = 'event';
    const DISEASE_IN_COMMUNITY = 'community';
    const PAGE_LIMIT = 10;

    /**
     * Admin Disease List Management home
     */
//


    function index() {
        $this->JQValidator->addValidation('Disease', $this->Disease->validate, 'DiseaseIndexForm');
        $condition = array();
        $filter = 0;

        /* delete form submision */
        if ($this->request->is('post')) {
            if (isset($this->request->data['Disease']) && isset($this->request->data['Disease']['delete_disease_id'])) {
                $delete_disease_id = $this->request->data['Disease']['delete_disease_id'];
                $replace_disease_id = $this->request->data['Disease']['replace_disease_id'];
                $this->delete($delete_disease_id, $replace_disease_id);
                /* delete form submision */
            } elseif (isset($this->request->data ['Disease'])) {
                $this->JQValidator->addValidation('Disease', $this->Disease->validate, 'DiseaseIndexForm');
                $this->Disease->create();
                if ($this->Disease->save($this->request->data, array('validate' => false))) {
                    if ($this->request->data ['Disease'] ['id'] > 0) {
                        $this->Session->setFlash(__('Disease has been updated.'), 'success');
                    } else {
                        $this->Session->setFlash(__('Disease has been added.'), 'success');
                    }
                } else {
                    $this->Session->setFlash(__('Unable to add your disease.'), 'error');
                }
                /* add filter */
            } elseif (isset($this->request->data ['DiseaseFilter'])) {

                $filter = $this->request->data ['DiseaseFilter'] ['filter'];
                $admin = $this->Auth->user();

                switch ($filter) {
                    case 1 : $condition = array(
                            'user_id ' => $admin['id']);
                        break;
                    case 2: $condition = array(
                            'user_id !=' => $admin['id']);
                        break;
                        ;
                }
            }
        }




        $this->paginate = array(
            'limit' => DiseasesController::PAGE_LIMIT,
            'conditions' => $condition,
        );
        $disease_list = $this->paginate('Disease');
        $this->setOtherData($disease_list, $filter);
    }

    function setOtherData($disease_list, $filter) {
        $admin = $this->Auth->user();
        $timezone = new DateTimeZone($admin['timezone']);
        foreach ($disease_list as $key => $disease) {
            $disease_list[$key]['Disease']['parent_name'] = $this->Disease->getDiseaseName($disease_list[$key]['Disease']['parent_id']);
            $disease_list[$key]['Disease']['created'] = Date::getUSFormatDateTime($disease_list[$key]['Disease']['created'], $timezone);
            if ($this->Disease->is_parent($disease['Disease']['id'])) {
                $disease_list[$key]['Disease']['is_parent'] = 'true';
            } else {
                $disease_list[$key]['Disease']['is_parent'] = 'false';
            }
        } //debug($disease_list);exit();
        $all_disease_list = $this->Disease->find('list');

        $parent_list = $this->Disease->getParentDiseasesList();
        $this->set(compact('disease_list', 'parent_list', 'all_disease_list', 'filter'));
        //$this->render('index');
    }

    function search() {
        $condition = array();
        $admin = $this->Auth->user();
        $filter = $this->request->query('filter');

        switch ($filter) {
            case 1 : $condition = array(
                    'user_id ' => $admin['id']);
                break;
            case 2: $condition = array(
                    'user_id !=' => $admin['id']);
                break;
                ;
        }

        if ($this->request->query('disease_name')) {
            $keyword = $this->request->query('disease_name');
            //debug($condition);
            $this->paginate = array(
                'conditions' => array('Disease.name LIKE' => '%' . $keyword . '%', $condition),
                'limit' => 10
            );
        } else {
            $this->paginate = array(
                'limit' => 10,
                'conditions' => $condition
            );
            $disease_list = $this->paginate('Disease');
        }
        $disease_list = $this->paginate('Disease');
        if (sizeof($disease_list) == 0) {
            $this->Session->setFlash('No Disease found.', 'warning');
        } else {
            foreach ($disease_list as $key => $disease) {
                $disease_list[$key]['Disease']['parent_name'] = $this->Disease->getDiseaseName($disease_list[$key]['Disease']['parent_id']);
            }
            $this->setOtherData($disease_list, $filter);
        }
        $this->request->data ['DiseaseFilter'] ['filter'] = $filter;
        $this->set(compact('keyword', 'filter'));
        $this->render('index');
    }

    function delete($delete_disease_id = null, $replace_disease_id = null) {
        $this->loadModel('Disease');
        if (!$delete_disease_id || !$replace_disease_id) {
            $this->Session->setFlash('Invalid id for Disease management', 'error');
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Disease->hasAny(array('Disease.id' => $delete_disease_id)) && $this->Disease->hasAny(array('Disease.id' => $replace_disease_id))) {
            if ($delete_disease_id != $replace_disease_id) {
                $can_delete_disaese = true;
                $delete_disease_name = $this->Disease->getDiseaseName($delete_disease_id);
                $replace_disease_name = $this->Disease->getDiseaseName($replace_disease_id);
                $usersWithDisease = $this->PatientDisease->findUsersWithDisease($delete_disease_id);
                $eventsWithDisease = $this->EventDisease->findEventsWithDisease($delete_disease_id);
                $communitiesWithDisease = $this->CommunityDisease->findCommunitiesWithDisease($delete_disease_id);
                $this->Disease->replaceParentDiseaseName($delete_disease_id, $replace_disease_id);
                $mail_users_list = array();
                $user_details = null;
                $currentDiseaseId = $delete_disease_id;
                $newDiseaseId = $replace_disease_id;
                if (isset($usersWithDisease) && $usersWithDisease != NULL) {
                    $user_details = NULL;
                    foreach ($usersWithDisease as $userWithDisease) {
                        $user_details = $this->User->getUserDetails($userWithDisease);
                        if (isset($user_details) && $user_details != NULL) {
                            $mail_users_list[] = array(
                                'type' => self::DISEASE_IN_USER,
                                'type_id' => $user_details['user_name'],
                                'type_name' => 'profile',
                                'user_mail_id' => $user_details['email'],
                                'username' => Common::getUsername($user_details['user_name'], $user_details['first_name'], $user_details['last_name']),
                                'user_id' => $userWithDisease
                            );
                        }
                    }
                    $this->PatientDisease->replaceDiseaseOfUsers($currentDiseaseId, $newDiseaseId);
                }
                if (isset($eventsWithDisease) && $eventsWithDisease != NULL) {
                    $user_details = NULL;
                    foreach ($eventsWithDisease as $eventWithDisease) {
                        $user_details = $this->User->getUserDetails($eventWithDisease['Event']['created_by']);
                        if (isset($user_details) && $user_details != NULL) {
                            $mail_users_list[] = array(
                                'type' => self::DISEASE_IN_EVENT,
                                'type_id' => $eventWithDisease['Event']['id'],
                                'type_name' => $eventWithDisease['Event']['name'],
                                'user_mail_id' => $user_details['email'],
                                'username' => Common::getUsername($user_details['user_name'], $user_details['first_name'], $user_details['last_name']),
                                'user_id' => $eventWithDisease['Event']['created_by']
                            );
                        }
                    }
                    $this->EventDisease->replaceDiseaseOfEvents($currentDiseaseId, $newDiseaseId);
                }
                if (isset($communitiesWithDisease) && $communitiesWithDisease != NULL) {
                    foreach ($communitiesWithDisease as $communityWithDisease) {
                        $user_details = $this->User->getUserDetails($communityWithDisease['Community']['created_by']);
                        if (isset($user_details) && $user_details != NULL) {
                            $mail_users_list[] = array(
                                'type' => self::DISEASE_IN_COMMUNITY,
                                'type_id' => $communityWithDisease['Community']['id'],
                                'type_name' => $communityWithDisease['Community']['name'],
                                'user_mail_id' => $user_details['email'],
                                'username' => Common::getUsername($user_details['user_name'], $user_details['first_name'], $user_details['last_name']),
                                'user_id' => $communityWithDisease['Community']['created_by']
                            );
                        }
                    }
                    $this->CommunityDisease->replaceDiseaseOfCommunities($currentDiseaseId, $newDiseaseId);
                }
                if ($this->Disease->deleteAll(array('Disease.id' => $delete_disease_id))) {
                    $this->sendMailDiseaseDeletion($mail_users_list, $delete_disease_name, $replace_disease_name);
                    $this->Session->setFlash(__('Disease deleted'), 'success');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('Disease was not deleted'), 'warning'); //either one of them of the two desease are not
                    $this->redirect(array('action' => 'index'));
                }
            } else {
                $this->Session->setFlash(__('please select a disease'), 'error'); //ids are not given.
                $this->redirect(array('action' => 'index'));
            }
        } else {
            $this->Session->setFlash(__('please select disease'), 'warning'); //ids are not given.
            $this->redirect(array('action' => 'index'));
        }
    }

    function sendMailDiseaseDeletion($mailContentDetails = null, $delete_disease_name = null, $replace_disease_name = null) {
        if (isset($mailContentDetails) && $mailContentDetails != null) {
            foreach ($mailContentDetails as $content) {
//                $email = $content['user_mail_id'];
                $link = Router::Url('/', TRUE) . $content['type'] . '/details/index/' . $content['type_id'];
                if ($content['type'] == self::DISEASE_IN_USER) {
                    $link = Router::Url('/', TRUE) . 'profile/' . $content['type_id'];
                }
                $emailData = array(
                    'disease_name' => $delete_disease_name,
                    'replace_disease_name' => $replace_disease_name,
                    'page_type' => $content['type'],
                    'page_name' => $content['type_name'],
                    'username' => $content['username'],
                    'link' => $link
                );

                $emailManagement = $this->EmailTemplate->getEmailTemplate(EmailTemplateComponent::DISEASE_DELETED_TEMPLATE, $emailData);
//                $Email = new CakeEmail();
//                try {
//                    $Email->config('mailServerSettings')
//                            ->template('default')
//                            ->emailFormat('html')
//                            ->to($email)
//                            ->subject(__($emailManagement['EmailTemplate']['template_subject']))
//                            ->send($emailManagement['EmailTemplate']['template_body']);
//                } catch (Exception $e) {
//
//                }

                /* start send mail using mail queue */

                $mailData = array(
                    'subject' => $emailManagement['EmailTemplate']['template_subject'],
                    'to_name' => $emailData['username'],
                    'to_email' => $content['user_mail_id'],
                    'content' => json_encode($emailData),
                    'module_info' => 'disease deleted',
                    'email_template_id' => EmailTemplateComponent::DISEASE_DELETED_TEMPLATE
                );

                $this->EmailQueue->createEmailQueue($mailData);

                /* end send mail using mail queue */
            }
        }
    }

    /**
     * Function to disease image from tmp to correct directory
     *
     * @param type $image_name
     */
    private function move_image_file($image_name, $diseaseId = NULL) {
        if (!$diseaseId) {
            $diseaseId = $this->Disease->id;
        }

        $image_tmp_path = Configure::read('App.UPLOAD_PATH');
        $image_path = Configure::read('App.DISEASE_IMG_PATH');
        if (!is_dir($image_path)) {
            // create directory if it does not exist
            @mkdir($image_path);
        }

        $name = explode('.', $image_name);
//        print_r($name);
//        exit;
//        $new_name = md5($this->Disease->id);
        $new_name = md5($diseaseId) . '.jpg';

        rename($image_tmp_path . '/' . $image_name, $image_path . '/' . $new_name);
    }

    /**
     * Function to edit a particular disease
     *
     */
    function edit($id = null) {

        /*
         * Use to display saved symptoms in view
         */
        $symptoms = array();
        $defalutSymptoms = "";
        $isParent = false;

        $parent_list = $this->Disease->getParentDiseasesList();
        
        $disease_details = $this->Disease->get_disease_details_by_id($id);
        if ($this->Disease->is_parent($id)) {
            $isParent = true;
        }
        $dashboard_data = json_decode($disease_details['dashboard_data'], TRUE);
        if(isset($dashboard_data['profile_image']) && $dashboard_data['profile_image'] != '') {
            $profileImgUrl = $thumbImage = Configure::read('App.UPLOAD_PATH_URL') . '/disease_logos/' . Common::getDiseaselogo($dashboard_data['profile_image']);
        } else {
            $profileImgUrl = Configure::read('App.fullBaseUrl') . '/theme/App/img/logo.png';
        }
        $profileImg = '<img src="' . $profileImgUrl . '">';
        
        
        $diseaseImgUrl = Common::getDiseaseThumb($disease_details['Disease']['id']);
        if(isset($diseaseImgUrl) && $diseaseImgUrl != ''){
            $diseaseImg = '<img src="' . $diseaseImgUrl . '">';
        } else {
            $diseaseImg = '<img src="' . $diseaseImgUrl . '" class="hidden">';
        }
        
        if (isset($disease_details['library']['url'])) {
            $url_list = $disease_details['library']['url'];
        } else {
            $url_list = '';
        }

        if (isset($disease_details['profile_video'])) {
            $profile_video = $disease_details['profile_video'];
        } else {
            $profile_video = "";
        }
        if (isset($disease_details['advertisement_video'])) {
            $advertisement_video = $disease_details['advertisement_video'];
        } else {
            $advertisement_video = "";
        }

        // get disease symptoms
        $diseaseSymptoms = $this->DiseaseSymptom->find('first', array(
            'conditions' => array(
                'DiseaseSymptom.disease_id' => $disease_details ['Disease'] ['id']
            )
        ));

        if (!empty($diseaseSymptoms ['DiseaseSymptom'] ['symptom_ids'])) {
            $defalutSymptoms = $diseaseSymptoms ['DiseaseSymptom'] ['symptom_ids'] . ',';
            $diseaseSymptomIds = explode(',', $diseaseSymptoms ['DiseaseSymptom'] ['symptom_ids']);

            /*
             * Find each symptom name and save it to an array 
             */
            $symptoms = $this->Symptom->find('all', array(
                'conditions' => array('id' => $diseaseSymptomIds)
            ));
        }
        $surveyList = $this->Survey->find('list', array(
                )
        );
        $disease_logos = $this->Disease->getDiseaseLogo();
        
        if (($key = array_search($disease_details['Disease']['name'], $parent_list)) !== false) {
            unset($parent_list[$key]);
        }

        $this->set(compact('diseaseImg', 'disease_logos', 'parent_list', 'url_list', 'disease_details', 'profile_video', 'symptoms', 'defalutSymptoms', 'surveyList', 'isParent', 'profileImg', 'advertisement_video', 'dashboard_data'));

        if (!empty($this->request->data)) {
            $this->Disease->id = $id;

//            $image_name = $this->request->data['Disease']['disease_image'];
            $image_name = $this->request->data['cropfileName'];

            $data = $this->request->data['Disease'];

            if ($image_name) {
                $this->move_image_file($image_name, $id);
            }

            $libray = array();
            if (isset($data['url']) && !empty($data['url'][1])) {
                foreach ($data['url'] as $url) {
                    if (!empty($url) && isset($url['src']) && isset($url['image'])) {
                        $link_details = array(
                            'src' => $url['src'],
                            'image' => $url['image']
                        );
                        $libray['library']['url'][] = json_encode($link_details);
                    }
                }
            }

            $libray['profile'] = $data['ProfileVideo'];
            
            $libray['advertisement'] = $this->request->data['Disease']['advertisement_video'];

            $this->request->data['Disease']['library'] = json_encode($libray);
            $encodedString = htmlspecialchars($this->request->data['Disease']['description']);
            $this->request->data['Disease']['description'] = $encodedString;

            $dashboard_data_save = array();
            $dashboard_data_save['name'] = $this->request->data['Disease']['frontend_name'];
            $dashboard_data_save['profile_image'] = $this->request->data['Disease']['profile_image'];
            $dashboard_data_save['connect_text'] = $this->request->data['Disease']['connect_text'];
            $dashboard_data_save['manage_health_text'] = $this->request->data['Disease']['manage_health_text'];
            $dashboard_data_save['learn_text'] = $this->request->data['Disease']['learn_text'];
            $dashboard_data_save['medical_mecords_text'] = $this->request->data['Disease']['medical_mecords_text'];
            $this->request->data['Disease']['dashboard_data'] = json_encode($dashboard_data_save);

            // trim the last ','
            $symptomIds = trim($this->request->data['Disease']['DiseaseSymptoms_id'], ',');
            $this->__addDiseaseSymptoms($this->request->data['Disease']['id'], $symptomIds);

            if ($this->Disease->save($this->request->data, array('validate' => false))) {
                $this->Session->setFlash(__("Disease details updated."));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__("Disease with the same name already exists."));
        }
        if (!$this->request->data) {
            $this->request->data = $disease_details;
        }
        $this->render('edit_disease');
    }

    /**
     * Function to add new disease
     *
     * @return type
     */
    function add() {
		$parent_list = $this->Disease->getParentDiseasesList();
        $surveyList = $this->Survey->find('list', array(
                )
        );
        $diseaseImgUrl = Common::getDiseaseThumb(0);
        if(isset($diseaseImgUrl) && $diseaseImgUrl != ''){
            $diseaseImg = '<img src="' . $diseaseImgUrl . '">';
        } else {
            $diseaseImg = '<img src="' . $diseaseImgUrl . '" class="hidden">';
        }
        $profileImgUrl = Configure::read('App.fullBaseUrl') . "/theme/App/img/logo.png";
        $profileImg = '<img src="' . $profileImgUrl . '">';
        $disease_logos = $this->Disease->getDiseaseLogo();
        
        $this->set(compact('parent_list', 'post', 'surveyList', 'profileImg', 'disease_logos', 'diseaseImg'));
        
        if ($this->request->is("post")) {
            
            $image_name = $this->request->data['cropfileName'];
            $libray = array();
            $data = $this->request->data['Disease'];

            $libray = array();
            if (isset($data['url']) && !empty($data['url'][1])) {
                foreach ($data['url'] as $url) {
                    if (!empty($url) && isset($url['src']) && isset($url['image'])) {
                        $link_details = array(
                            'src' => $url['src'],
                            'image' => $url['image']
                        );
                        $libray['library']['url'][] = json_encode($link_details);
                    }
                }
            }
            
            if(isset($this->request->data['Disease']['profile_video'])){
                $libray['profile'] = $this->request->data['Disease']['profile_video'];
            } else {
                $libray['profile'] = '';
            }
            if (isset($this->request->data['Disease']['advertisement_video'])) {
                $libray['advertisement'] = $this->request->data['Disease']['advertisement_video'];
            } else {
                $libray['advertisement'] = '';
            }
            $this->request->data['Disease']['library'] = json_encode($libray);
            $encodedString = htmlspecialchars($this->request->data['Disease']['description']);
            $this->request->data['Disease']['description'] = $encodedString;
            
            $dashboard_data = array();
            $dashboard_data['name'] = $this->request->data['Disease']['frontend_name'];
            $dashboard_data['profile_image'] = $this->request->data['Disease']['profile_image'];
            $dashboard_data['connect_text'] = $this->request->data['Disease']['connect_text'];
            $dashboard_data['manage_health_text'] = $this->request->data['Disease']['manage_health_text'];
            $dashboard_data['learn_text'] = $this->request->data['Disease']['learn_text'];
            $dashboard_data['medical_mecords_text'] = $this->request->data['Disease']['medical_mecords_text'];
            $this->request->data['Disease']['dashboard_data'] = json_encode($dashboard_data);

            $this->Disease->create();

            if ($this->Disease->save($this->request->data, array('validate' => false))) {

                $diseaseId = $this->Disease->id;
                if ($image_name) {
                    $this->move_image_file($image_name, $diseaseId);
                }
                //save disease symptoms
                if (!empty($this->request->data['Disease']['DiseaseSymptoms_id'])) {

                    // trim the last ','
                    $symptomIds = trim($this->request->data['Disease']['DiseaseSymptoms_id'], ',');
                    $this->__addDiseaseSymptoms($diseaseId, $symptomIds);
                }

                // set success message
                $this->Session->setFlash(__("New disease has been saved."));
                return $this->redirect(array("action" => "index"));
            }
            $this->Session->setFlash(__("Disease with the same name already exists."));
        }
        $this->render('add_disease');
    }

    /**
     * Function to upload disease image
     */

    /**
     * Function to upload user profile photo and give response in 
     * Ajax request
     */
    public function photo() {
        $this->layout = null;
        $uploadPath = Configure::read("App.UPLOAD_PATH");
        $thumbnailPath = Configure::read("App.DISEASE_IMG_PATH");
        $uploadUrl = Configure::read("App.UPLOAD_PATH_URL");
        $webFolder = $thumbnailPath;
        $tempUrl = FULL_BASE_URL . "/uploads/tmp/";
        $webUrl = FULL_BASE_URL . "/uploads/disease_images";
        /*
         * Do the image Cropping
         */
        if (isset($this->request->data['crop_image'])) {


            $uploadedImage = $this->request->data['cropfileName'];

            try {

                $options = array('thumbnail' => array(
                        "max_width" => $this->minimumImageSize[0],
                        "max_height" => $this->minimumImageSize[1],
                        "path" => $thumbnailPath
                    ),
                    'max_width' => 700
                );
                $x1 = $_POST["x1"];
                $y1 = $_POST["y1"];
                $width = $_POST["w"];
                $height = $_POST["h"];
                $fileName = $_POST['cropfileName'];
                $imageUrl = $tempUrl . $_POST['cropfileName'];

                $photoPath = $uploadPath . DIRECTORY_SEPARATOR . $uploadedImage;

                if ($width <= 0) {
                    $width = $this->minimumImageSize[0];
                    $x1 = 0;
                }

                if ($height <= 0) {
                    $height = $this->minimumImageSize[1];
                    $y1 = 0;
                }

                if ($width > 0 && $height > 0) {
                    $status = ImageTool::crop(array(
                                'input' => $photoPath,
                                'output' => $photoPath,
                                'width' => $width,
                                'height' => $height,
                                'enlarge' => false,
                                'keepRatio' => false,
                                'paddings' => false,
                                'output_width' => $this->minimumImageSize[0],
                                'output_height' => $this->minimumImageSize[1],
                                'top' => $y1,
                                'left' => $x1,
                    ));

                    $result['success'] = true;
                    $result['fileUrl'] = $tempUrl . $uploadedImage;
                    $result['fileName'] = $uploadedImage;
                } else {
                    throw new Exception("Image Not cropped");
                }
            } catch (Exception $e) {
                $result['success'] = false;
                $result['message'] = $e->getMessage();
            }

            $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
            echo $result;
        } else {
            /*
             * Functionality to upload the image to a temporary folder
             */
            $uploader = new $this->Uploader();
            //$webFolder

            $uploader->allowedExtensions = array("jpg", "jpeg", "png", "gif"); // all files types allowed by default
            // Specify max file size in bytes.
            $uploader->sizeLimit = 5 * 1024 * 1024; // default is 5 MiB

            $uploader->minImageSize = array('100', '100');

            // Specify the input name set in the javascript.
            $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
            // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
            $uploader->chunksFolder = "chunks";

            $method = $_SERVER["REQUEST_METHOD"];
            if ($method == "POST") {
                header("Content-Type: text/plain");

                // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
                $result = $uploader->handleUpload($uploadPath);

                if (isset($result['success'])) {
                    $result['file_name'] = $uploader->getUploadName();

                    $photoPath = $uploadPath . DIRECTORY_SEPARATOR . $result['file_name'];
                    $status = ImageTool::resize(array(
                                'quality' => 90,
                                'enlarge' => false,
                                'keepRatio' => true,
                                'paddings' => false,
                                'crop' => false,
                                'input' => $photoPath,
                                'output' => $photoPath,
//                                'width' => '570',
//                                'height' => '220'
                    ));

                    // image dimension
                    list($imageWidth, $imageHeight) = getimagesize($photoPath);

                    $result['imageWidth'] = $imageWidth;
                    $result['imageHeight'] = $imageHeight;
                    $result['fileName'] = $result['file_name'];
                    $result['fileurl'] = $tempUrl . DIRECTORY_SEPARATOR . $result['file_name'];
                }

                echo json_encode($result);
            } else {
                header("HTTP/1.0 405 Method Not Allowed");
            }
        }

        exit;
    }

//
//    public function upload_image() {
//        $this->layout = null;
//        $this->autoRender = false;
//        $uploadPath = Configure::read('App.UPLOAD_PATH');
//        $uploadUrl = Configure::read('App.UPLOAD_PATH_URL');
//
//        /*
//         * Functionality to upload the image to a temporary folder
//         */
//        $uploader = new $this->Uploader();
//
//        $uploader->allowedExtensions = array('jpg', 'jpeg', 'png', 'bmp', 'gif'); // all files types allowed by default
//        // Specify max file size in bytes.
//        $uploader->sizeLimit = 5 * 1024 * 1024; // default is 5 MiB
//        // Specify the input name set in the javascript.
//        $uploader->inputName = 'qqfile'; // matches Fine Uploader's default inputName value by default
//        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
//        $uploader->chunksFolder = 'chunks';
//        $uploader->minImageSize = array(350, 200);
//
//        $method = $_SERVER['REQUEST_METHOD'];
//        if ($method == 'POST') {
//            header('Content-Type: text/plain');
//            // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
//            $result = $uploader->handleUpload($uploadPath);
////            print_r($result);
////            exit;
//            // To return a name used for uploaded file you can use the following line.
//            $result['fileName'] = $uploader->getUploadName();
//            $result['fileurl'] = $uploadUrl . '/tmp/' . $uploader->getUploadName();
//
//            echo json_encode($result);
//        } else {
//            header('HTTP/1.0 405 Method Not Allowed');
//        }
//
//        exit;
//    }

    private function __addDiseaseSymptoms($diseaseId, $symptomIds) {

        /*
         * Check the disease id exists
         */
        $id = $this->DiseaseSymptom->findByDiseaseId($diseaseId);
        if (!$id) {
            $this->DiseaseSymptom->create();
        }

        $data = array(
            'DiseaseSymptom' => array(
                'id' => $id['DiseaseSymptom']['id'],
                'disease_id' => $diseaseId,
                'symptom_ids' => $symptomIds
            )
        );
        return $this->DiseaseSymptom->save($data);
    }

    /*
     * Function to display the details of a disease
     * 
     * @param int $id disease_id
     */

    public function view($id = NULL) {
        if (isset($id) && $id != NULL) {
            if ($this->Disease->exists($id)) {
                $disease_details = $this->Disease->get_disease_details_by_id($id);
                $analytics_data = $this->Analytics->getDiseaseAnalytics($id);
                $treatment_analytics = $this->Analytics->getDiseaseTreatmentUsersAgeCount($id);
                $users_count = $analytics_data['users'];
                $events_count = $analytics_data['events'];
                $communities_count = $analytics_data['communities'];
                $graph_data = $analytics_data['data'];
                $diseaseImage = Common::getDiseaseThumb($id);
                $this->set(compact('url_list', 'disease_details', 'profile_video', 'symptoms', 'defalutSymptoms', 'surveyList', 'users_count', 'events_count', 'communities_count', 'graph_data', 'treatment_analytics', 'diseaseImage'
                ));
            } else {
                $this->Session->setFlash('No disease found.', 'warning');
                $this->redirect('/admin/Diseases');
            }
        } else {
            $this->Session->setFlash('No disease found.', 'warning');
            $this->redirect('/admin/Diseases');
        }
    }

    public function textCrawler() {
        $this->autoRender = false;
        $Crawler = new Crawler();
        $link = $this->request->data['link'];
        $imageQuantity = -1;
        $Crawler->grabUrlContents($link, $imageQuantity);
        echo json_encode($this->data);
    }

}

?> 