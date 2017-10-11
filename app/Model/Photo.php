<?php

App::uses('AppModel', 'Model');

/**
 * Photo Model
 *
 * @property User $User
 */
class Photo extends AppModel {

	/**
	 * Photo types
	 */
	const TYPE_POST = 1;
	const TYPE_DASHBOARD = 2;
	const TYPE_PROFILE_COVER = 3;
	const TYPE_EVENT_COVER = 4;
	const TYPE_COMMUNITY_COVER = 5;
	const TYPE_PROFILE_BG = 6;
	
	var $inserted_ids = array();

	/**
	 * Photo created user id
	 * 
	 * @var int 
	 */
	public $createdUserId = null;

	function afterSave($created, $options = array()) {
		if ($created) {
			$this->inserted_ids[] = $this->getInsertID();
		}
		return true;
	}

	/**
	 * Function to add dashboard photos of a user
	 * 
	 * @param array $images
	 * @param int $userId
	 * @param string $defaultImgName
	 */
	public function addDashboardPhotos($images, $userId, $defaultImgName = '') {
		$photos = array();
		if (!empty($images)) {
			App::import('Vendor', 'ImageTool');
			$data = array();
			$isDefaultImgSelected = false;
			foreach ($images as $imageFileName) {
				$imageName = $this->__saveDashboardImageFile($imageFileName, $userId);
				if ($imageName !== false) {
					$isDefault = 0;
					if ($defaultImgName !== '') {
						$isDefault = ($defaultImgName === $imageFileName) ? 1 : 0;
						$isDefaultImgSelected = true;
					}
					$data[] = array(
						'created_by' => $userId,
						'file_name' => $imageName,
						'type' => self::TYPE_DASHBOARD,
						'is_default' => $isDefault
					);
				}
			}

			if (!empty($data)) {
				if ($isDefaultImgSelected === true) {
					$this->unsetUserDashboardDefaultPhoto($userId);
				}
				if ($this->saveMany($data)) {
					$limit = count($data);
					$lastInsertedPhotos = $this->getLastInsertPhotos($userId, $limit);
					$photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/dashboard_image/';
					foreach ($lastInsertedPhotos as $photo) {
						$photos[] = array(
							'id' => $photo['Photo']['id'],
							'src' => $photoPath . $photo['Photo']['file_name']
						);
					}
				}
			}
		}

		return $photos;
	}

	/**
	 * Function to get the last inserted photos of a user
	 * 
	 * @param int $userId
	 * @param int $limit
	 * @return array
	 */
	public function getLastInsertPhotos($userId, $limit) {
		$query = array(
			'conditions' => array(
				'created_by' => $userId
			),
			'limit' => $limit,
			'order' => array('created DESC')
		);
		return $this->find('all', $query);
	}

	/**
	 * Function to get the last inserted photos 
	 * 
	 * @param int $imageIds
	 * @param int $limit
	 * @return array
	 */
	public function getLastInsertedCoverPhotos($imageIds = array()) {
		$query = array(
			'conditions' => array(
				'id' => $imageIds
			),
			'order' => array('created DESC')
		);
		return $this->find('all', $query);
	}

	/**
	 * Make the current default dashboard photo of the user as not default
	 * 
	 * @param int $userId
	 */
	public function unsetUserDashboardDefaultPhoto($userId) {
		$fields = array(
			'is_default' => 0
		);
		$conditions = array(
			'created_by' => $userId,
			'type' => self::TYPE_DASHBOARD,
			'is_default' => 1
		);
		$this->updateAll($fields, $conditions);
	}

	/**
	 * Function to make the current default cover photo as not default
	 * 
	 * @param int $typeId
	 * @param int $type
	 */
	public function unsetDefaultCoverPhoto($typeId, $type) {
		$fields = array(
			'is_default' => 0
		);
		$conditions = array(
			'posted_in' => $typeId,
			'type' => $type,
			'is_default' => 1
		);
		$this->updateAll($fields, $conditions);
	}

	/**
	 * Set a photo as default photo
	 * 
	 * @param int $photoId
	 */
	public function makePhotoDefault($photoId) {
		$this->id = $photoId;
		$this->saveField('is_default', 1);
	}

	/**
	 * Function to save dashboard image file in permanent location
	 * 
	 * @param string $fileName
	 * @return string
	 */
	private function __saveDashboardImageFile($fileName, $userId) {
		try {
			$tmpPath = Configure::read("App.UPLOAD_PATH");
			$tmpImage = $tmpPath . DIRECTORY_SEPARATOR . $fileName;

			if (file_exists($tmpImage)) {

				// create permanent upload folder if it does not exist
				$uploadPath = Configure::read("App.DASHBOARD_IMG_PATH");
				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777);
				}

				// move the image to permanent folder
				$targetImageName = Common::getUniqueFileName($fileName, $userId);
				$targetImage = $uploadPath . DIRECTORY_SEPARATOR . $targetImageName;
				Common::moveFile($tmpImage, $targetImage);
				ImageTool::gaussianBlurBottom($targetImage);
				$result = $targetImageName;
			} else {
				$result = null;
			}
		} catch (Exception $e) {
			$result = null;
		}

		return $result;
	}

	/**
	 * Function to get the dashboard photos of a user
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getUserDashboardPhotos($userId) {
		$query = array(
			'conditions' => array(
				'created_by' => $userId,
				'type' => self::TYPE_DASHBOARD
			),
			'order' => array('created ASC')
		);
		return $this->find('all', $query);
	}

	/**
	 * Function to get the default dashboard photo of a user
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getUserDashboardDefaultPhoto($userId) {
		$query = array(
			'conditions' => array(
				'created_by' => $userId,
				'type' => self::TYPE_DASHBOARD,
				'is_default' => 1
			),
		);
		return $this->find('first', $query);
	}

	/**
	 * Function to delete a list of photos
	 * 
	 * @param array $photoList array of photo ids
	 */
	public function deletePhotos($photoList) {
		return $this->deleteAll(array('Photo.id' => $photoList), false, true);
	}

	/**
	 * Before deleting, set the record data on model, for afterDelete processing
	 * 
	 * @param bool $cascade
	 * @return bool
	 */
	public function beforeDelete($cascade = true) {
		$this->recursive = -1;
		$this->data = $this->findById($this->id);
		return parent::beforeDelete($cascade);
	}

	/**
	 * After deleting a record from the database, delete the associated file
	 * 
	 * @param bool $cascade
	 */
	public function afterDelete() {
		$photo = $this->data['Photo'];
		$type = intval($photo['type']);
		if ($type === self::TYPE_DASHBOARD) {
			$fileName = $photo['file_name'];
			$fileDir = Configure::read('App.DASHBOARD_IMG_PATH');
			$filePath = $fileDir . DIRECTORY_SEPARATOR . $fileName;
			$file = new File($filePath);
			$file->delete();
		}

		parent::afterDelete();
	}

	/**
	 * Function to save photos of a post
	 * 
	 * @param array $images
	 * @param int $userId
	 */
	public function savePostPhotos($images, $userId, $posted_in_type, $posted_in) {
		$photos = array();
		if (!empty($images)) {
			$data = array();
			$isDefaultImgSelected = false;
			App::import('Vendor', 'ImageTool');			
			foreach ($images as $imageFileName) {
				$imageName = $this->__savePostImageFile($imageFileName, $userId);
				if ($imageName !== false) {
					$data[] = array(
						'created_by' => $userId,
						'file_name' => $imageName,
						'type' => self::TYPE_POST,
                                                'posted_in_type' => $posted_in_type,
                                                'posted_in' => $posted_in					);
				}
			}

			if (!empty($data)) {
				if ($this->saveMany($data)) {
					$limit = count($data);
					$lastInsertedPhotos = $this->getLastInsertPhotos($userId, $limit);
					foreach ($lastInsertedPhotos as $photo) {
						$photos[] = $photo['Photo']['id'];
					}
				}
			}
		}

		return $photos;
	}

	/**
	 * Function to save post image file in permanent location
	 * 
	 * @param string $fileName
	 * @return string
	 */
	private function __savePostImageFile($fileName, $userId) {
		try {
			$tmpPath = Configure::read("App.UPLOAD_PATH");
			$tmpImage = $tmpPath . DIRECTORY_SEPARATOR . $fileName;

			if (file_exists($tmpImage)) {

				// create permanent upload folder if it does not exist
				$uploadPath = Configure::read("App.POST_IMG_PATH");
				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777);
				}
				
				$targetImageName = Common::getUniqueFileName($fileName, $userId);
				
				// copy resized preview image to permanent folder
				$previewImageName = 'preview_' . $targetImageName;
				$previewImage = $uploadPath . DIRECTORY_SEPARATOR . $previewImageName;
				ImageTool::resize(array(
					'quality' => 100,
					'enlarge' => false,
					'keepRatio' => true,
					'paddings' => false,
					'crop' => false,
					'input' => $tmpImage,
					'output' => $previewImage,
					'width' => 400,
					'height' => 300
				));

				// move the image to permanent folder
				$targetImage = $uploadPath . DIRECTORY_SEPARATOR . $targetImageName;
				Common::moveFile($tmpImage, $targetImage);
				$result = $targetImageName;
			} else {
				$result = null;
			}
		} catch (Exception $e) {
			$result = null;
		}

		return $result;
	}

	/**
	 * Function to add cover photos of a profile | community | event
	 * 
	 * @param array $images
	 * @param int $typeId
	 * @param string $defaultImgName
	 */
	public function addCoverPhotos($images, $typeId = 0, $defaultImgName = '', $type = self::TYPE_PROFILE_COVER) {

		$photos = array();
		if (!empty($images)) {
			App::import('Vendor', 'ImageTool');
			$data = array();
			$isDefaultImgSelected = false;
			foreach ($images as $imageFileName) {
				$imageName = $this->__saveCoverImageFile($imageFileName, $typeId, $type);
				if ($imageName !== false) {
					$isDefault = 0;
					if ($defaultImgName !== '') {
						$isDefault = ($defaultImgName === $imageFileName) ? 1 : 0;
						$isDefaultImgSelected = true;
					}
					$data[] = array(
						'created_by' => $this->createdUserId,
						'posted_in' => $typeId,
						'file_name' => $imageName,
						'type' => $type,
						'is_default' => $isDefault
					);
				}
			}

			if (!empty($data)) {

				if ($isDefaultImgSelected === true) {
					$this->unsetDefaultCoverPhoto($typeId, $type);
				}

				if ($this->saveMany($data)) {
					$limit = count($data);
					$insertedIds = $this->inserted_ids;
					$lastInsertedPhotos = $this->getLastInsertedCoverPhotos($insertedIds, $limit);
					$photoPath = $this->getPhotoPath($type);
					foreach ($lastInsertedPhotos as $photo) {
						$photos[] = array(
							'id' => $photo['Photo']['id'],
							'src' => $photoPath . $photo['Photo']['file_name']
						);
					}
				}
			}
		}

		return $photos;
	}

	/**
	 * Functiont to get photo saved path
	 * 
	 * @param string $type
	 * @return string
	 */
	public function getPhotoPath($type = self::TYPE_PROFILE_COVER) {

		switch ($type) {
			case self::TYPE_EVENT_COVER:
				return $photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/event_image/';
			case self::TYPE_COMMUNITY_COVER:
				return $photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/community_image/';
			default :
				return $photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/user_profile/';
		}
	}

	/**
	 * Functiont to get photo upload path
	 * 
	 * @param string $type
	 * @return string
	 */
	public function getUploadPath($type = self::TYPE_PROFILE_COVER) {

		switch ($type) {
			case self::TYPE_EVENT_COVER:
				return $photoPath = Configure::read('App.EVENT_IMG_PATH');
			case self::TYPE_COMMUNITY_COVER:
				return $photoPath = Configure::read('App.COMMUNITY_IMG_PATH');
			default :
				return $photoPath = Configure::read('App.PROFILE_IMG_PATH');
		}
	}

	/**
	 * Function to save event image file in permanent location
	 * 
	 * @param string $fileName
	 * @return string
	 */
	private function __saveCoverImageFile($fileName, $typeId, $type = self::TYPE_PROFILE_COVER) {
		try {
			$tmpPath = Configure::read("App.UPLOAD_PATH");
			$tmpImage = $tmpPath . DIRECTORY_SEPARATOR . $fileName;

			if (file_exists($tmpImage)) {

				// create permanent upload folder if it does not exist
				$uploadPath = $this->getUploadPath($type);
				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777);
				}

				// move the image to permanent folder
				$targetImageName = Common::getUniqueFileName($fileName, $typeId);
				$targetImage = $uploadPath . DIRECTORY_SEPARATOR . $targetImageName;
				Common::moveFile($tmpImage, $targetImage);
				ImageTool::gaussianBlurBottom($targetImage);
				$result = $targetImageName;
			} else {
				$result = null;
			}
		} catch (Exception $e) {
			$result = null;
		}

		return $result;
	}

	/**
	 * Function to get the default Event cover photo of a user
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getCoverDefaultPhoto($typeId, $type = self::TYPE_PROFILE_COVER) {
		$query = array(
			'conditions' => array(
				'type' => $type,
				'posted_in' => $typeId,
				'is_default' => 1
			)
		);
		return $this->find('first', $query);
	}

	/**
	 * Function to get photos from photo ids
	 * 
	 * @param array $coverPhotoIds
	 * @return array
	 */
	public function getPhotos($coverPhotoIds) {
		$query = array(
			'conditions' => array(
				'id' => $coverPhotoIds
			)
		);

		return $this->find('all', $query);
	}

	public function getUserProfileBg($userId) {
		$query = array(
			'conditions' => array(
				'created_by' => $userId,
				'type' => self::TYPE_PROFILE_BG
			)
		);
		return $this->find('first', $query);
	}

	public function updateUserProfileBg($id, $userId, $imageName) {

		$fileName = $this->__saveProfileCoverBgFile($imageName, $userId);
		$this->id = $id;
		$data = array(
			'created_by' => $userId,
			'file_name' => $fileName,
			'type' => self::TYPE_PROFILE_BG,
			'is_default' => true
		);
		if ($this->save($data)) {
			return $fileName;
		}
		return null;
	}

	public function createUserProfileBg($userId, $imageName) {
		$fileName = $this->__saveProfileCoverBgFile($imageName, $userId);
		$data = array(
			'created_by' => $userId,
			'file_name' => $fileName,
			'type' => self::TYPE_PROFILE_BG,
			'is_default' => true
		);
		$this->create();
		if ($this->save($data)) {
			return $fileName;
		}
		return null;
	}

	public function deleteUserProfileBg($id) {
		return $this->delete($id);
	}

	/**
	 * Function to save profile cover bg
	 * 
	 * @param string $fileName
	 * @return string
	 */
	private function __saveProfileCoverBgFile($fileName, $userId) {
		try {
			$tmpPath = Configure::read("App.UPLOAD_PATH");
			$tmpImage = $tmpPath . DIRECTORY_SEPARATOR . $fileName;

			if (file_exists($tmpImage)) {

				// create permanent upload folder if it does not exist
				$uploadPath = Configure::read('App.PROFILE_IMG_PATH');
				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777);
				}

				// move the image to permanent folder
				$targetImageName = Common::getUniqueFileName($fileName, $userId);
				$targetImage = $uploadPath . DIRECTORY_SEPARATOR . $targetImageName;
				Common::moveFile($tmpImage, $targetImage);
				$result = $targetImageName;
			} else {
				$result = null;
			}
		} catch (Exception $e) {
			$result = null;
		}

		return $result;
	}

	/**
	 * Function to get the cover photos of a user profile
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getProfileCoverPhotos($userId) {
		$type = self::TYPE_PROFILE_COVER;
		return $this->_getCoverPhotosOfType($userId, $type);
	}

	/**
	 * Function to get the cover photos of an event
	 * 
	 * @param int $eventId
	 * @return array
	 */
	public function getEventCoverPhotos($eventId) {
		$type = self::TYPE_EVENT_COVER;
		return $this->_getCoverPhotosOfType($eventId, $type);
	}

	/**
	 * Function to get the cover photos of a community
	 * 
	 * @param int $communityId
	 * @return array
	 */
	public function getCommunityCoverPhotos($communityId) {
		$type = self::TYPE_COMMUNITY_COVER;
		return $this->_getCoverPhotosOfType($communityId, $type);
	}

	/**
	 * Function to get the cover photos of a type
	 * 
	 * @param int $typeId
	 * @param int $type
	 * @return array
	 */
	protected function _getCoverPhotosOfType($typeId, $type) {
		$query = array(
			'conditions' => array(
				'posted_in' => $typeId,
				'type' => $type
			)
		);
		return $this->find('all', $query);
	}
        
	/**
	 * Function to get the post photos of a user
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getRecentPhotos($profileUserId, $limit = '5') {
            $type = array(
                self::TYPE_POST
            );
            $query = array(
                'conditions' => array(
                    'created_by' => $profileUserId,
                    'posted_in' => $profileUserId,
                    'type' => $type
                ),
                'limit' => $limit,
                'order' => array('created' => 'DESC')
            );
            if($limit = "") {
                unset($query['limit']);
            }
            return $this->find('all', $query);
	}        
        
	/**
	 * Function to save photos of a post
	 * 
	 * @param array $images
	 * @param int $userId
	 */
	public function saveBlogPhotos($images, $userId, $posted_in_type, $posted_in) {
		$photos = array();
		if (!empty($images)) {
                    $data = array();

                    foreach ($images as $imageFileName) {
                        if ($imageFileName !== false) {
                            $data[] = array(
                                    'created_by' => $userId,
                                    'file_name' => $imageFileName,
                                    'type' => self::TYPE_POST,
                                    'posted_in_type' => $posted_in_type,
                                    'posted_in' => $posted_in					
                                    );
                        }
                    }

                    if (!empty($data)) {
                        if ($this->saveMany($data)) {
                            $limit = count($data);
                            $lastInsertedPhotos = $this->getLastInsertPhotos($userId, $limit);
                            foreach ($lastInsertedPhotos as $photo) {
                                    $photos[] = $photo['Photo']['id'];
                            }
                        }
                    }
		}

		return $photos;
	}        
}