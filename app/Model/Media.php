<?php

App::uses('AppModel', 'Model');

/**
 * Media Model
 *
 */
class Media extends AppModel {
    
    /**
     * Media types
     */
    const TYPE_PHOTO = 'photo';
    const TYPE_VIDEO = 'video';

    /**
     * Media Status
     */
    const STATUS_PROCESSING = 0;
    const STATUS_READY = 1;

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'created_by',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * Function to add a video media
     * 
     * @param array $videoInfo
     * @param int $userId
     * @return mixed int or boolean
     */
    public function addVideo($videoInfo, $userId) {
        $this->create();
        $data = array(
            'type' => self::TYPE_VIDEO,
            'content' => json_encode($videoInfo),
            'created_by' => $userId
        );
        if ($this->save($data)) {
            return $this->id;
        } else {
            return false;
        }
    }

    /**
     * Function to get the videos which are under processing status
     * 
     * @return array
     */
    public function getProcessingVideos() {
        $this->recursive = -1;
        $params = array(
            'conditions' => array(
                'Media.status' => self::STATUS_PROCESSING,
                'Media.type' => self::TYPE_VIDEO
            ),
            'fields' => array('id', 'content')
        );
        return $this->find('list', $params);
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
    public function afterDelete($cascade = true) {
        $media = $this->data['Media'];
        $content = $media['content'];
        $contentArray = json_decode($content, true);
        $type = $media['type'];
        if ($type === self::TYPE_VIDEO) {
            $fileName = $contentArray['file_name'];
            $videoDir = Configure::read('App.POST_VIDEO_PATH');
            $filePath = $videoDir . DIRECTORY_SEPARATOR . $fileName;
            $file = new File($filePath);
            if ($file->delete()) {
                $videoId = $contentArray['video_id'];
                App::uses('Vimeo', 'Utility');
                $vimeo = new Vimeo();
                $vimeo->deleteVideo($videoId);
            }
        }
        parent::beforeDelete($cascade);
    }
    
    /**
     * Function to get the videos which are added by a user
     * 
     * @return array
     */
    public function getUserVideos($userId, $limit = 5) {
        $this->recursive = -1;
        $params = array(
            'conditions' => array(
                'Media.created_by' => $userId,
                'Media.status' => self::STATUS_READY,
                'Media.type' => self::TYPE_VIDEO
            ),
            'fields' => array('id', 'content'),
            'limit' => $limit
        );
        
        if($limit == "") {
            unset($params['limit']);
        }
        
        return $this->find('list', $params);
    }    
}