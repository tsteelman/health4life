<?php

/**
 * AddController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('EventAppController', 'Event.Controller');

/**
 * AddController for events.
 * 
 * AddController is used for adding events.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Event
 * @category	Controllers 
 */
class AddController extends EventAppController {

    public $components = array('Uploader', 'EventForm');
    public $uses = array('Timezone');

    public function index() {
		$this->set('title_for_layout',"Create Event");
        if ($this->request->isPost()) {

          $this->EventForm->saveEvent();
        } else {
            $timeZones = $this->Timezone->get_timezone_list();
            $defaultTimeZone = $this->Auth->user('timezone');
            date_default_timezone_set($defaultTimeZone);
            $startTime = 'false';
            $endTime = 'false';
            
            if(substr($this->request->referer(1), 0, 9) == '/calendar') {
                $refer = $this->request->referer(1);
                $this->Session->write('refer', $refer);
            }
			
			$eventDisease = '';
			if(substr($this->request->referer(1), 0, 10) == '/condition') {
                preg_match('~index/(.*?)/events~', $this->request->referer(1), $disease_id);
                $this->loadModel('Disease');
                $eventDisease = $this->Disease->findById($disease_id[1]);
                $refer = $this->request->referer(1);
                $this->Session->write('refer', $refer);
            }
            
            $this->EventForm->setFormData();
            $this->set('eventImage', 'new_event_default_img.png');
            $this->set('backUrl', '/event');
            $this->set('timeZones', $timeZones);
            $this->set('defaultTimeZone', $defaultTimeZone);
            $this->set('startTime', $startTime);
            $this->set('endTime', $endTime);
			$this->set('eventDisease', $eventDisease);
        }
    }

    /**
     * Function to get the diagnosis form
     */
    public function getDiagnosisForm() {
        $index = $this->request->data['index'];
        $options = array(
            'label' => false,
            'div' => false,
        );
        $view = new View($this, false);
        echo $view->element('Event.diagnosis_form', compact('index', 'options'));
        $this->autoRender = false;
    }

    /**
	 * Function to upload event photo
	 */
	public function photo() {
		$this->layout = null;
		$this->autoRender = false;
		$uploadPath = Configure::read('App.UPLOAD_PATH');
		$uploadUrl = Configure::read('App.UPLOAD_PATH_URL');

		/*
		 * Functionality to upload the image to a temporary folder
		 */
		$uploader = new $this->Uploader();

		$uploader->allowedExtensions = Configure::read('App.imageExtensions');
		// Specify max file size in bytes.
		$uploader->sizeLimit = 5 * 1024 * 1024; // default is 5 MiB
		// Specify the input name set in the javascript.
		$uploader->inputName = 'qqfile'; // matches Fine Uploader's default inputName value by default
		// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
		$uploader->chunksFolder = 'chunks';
		$minimumImageSize = Common::getEventThumbSize();
		$uploader->minImageSize = array($minimumImageSize['w'], $minimumImageSize['h']);

		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'POST') {
			header('Content-Type: text/plain');
			// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
			$result = $uploader->handleUpload($uploadPath);

			// To return a name used for uploaded file you can use the following line.
			$fileName = $uploader->getUploadName();
			$result['fileName'] = $fileName;
			$result['fileUrl'] = $uploadUrl . '/tmp/' . $fileName;
			$imgPath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;
			list($imageWidth, $imageHeight) = getimagesize($imgPath);
			$result['imageWidth'] = $imageWidth;
			$result['imageHeight'] = $imageHeight;

			echo json_encode($result);
		} else {
			header('HTTP/1.0 405 Method Not Allowed');
		}
		exit;
	}

	/**
	 * Function to crop event image
	 * 
	 * @throws Exception
	 */
	public function cropImage() {
		$this->autoRender = false;
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method === 'POST') {
			try {
				$uploadPath = Configure::read('App.UPLOAD_PATH');
				$uploadUrl = Configure::read('App.UPLOAD_PATH_URL');
				$data = $this->request->data;
				$minimumImageSize = Common::getEventThumbSize();

				$x1 = $data["x1"];
				$y1 = $data["y1"];
				$width = $data["w"];
				$height = $data["h"];

				if ($width <= 0) {
					$width = $minimumImageSize['w'];
					$x1 = 0;
				}

				if ($height <= 0) {
					$height = $minimumImageSize['h'];
					$y1 = 0;
				}

				if ($width > 0 && $height > 0) {
					App::import('Vendor', 'ImageTool');
					$fileName = $data['fileName'];
					$imgPath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;
					$cropOptions = array(
						'input' => $imgPath,
						'output' => $imgPath,
						'width' => $width,
						'height' => $height,
						'enlarge' => false,
						'keepRatio' => false,
						'paddings' => false,
						'output_width' => $minimumImageSize['w'],
						'output_height' => $minimumImageSize['h'],
						'top' => $y1,
						'left' => $x1,
					);
					ImageTool::crop($cropOptions);

					$result['success'] = true;
					$result['fileUrl'] = $uploadUrl . '/tmp/' . $fileName;
					$result['fileName'] = $fileName;
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
			header('HTTP/1.0 405 Method Not Allowed');
		}
		exit;
	}
}