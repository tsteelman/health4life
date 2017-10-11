<?php

/**
 * Vimeo utility class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * Vimeo utility class.
 * 
 * Vimeo utility encloses static methods needed to work with Vimeo API.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	App.Utility
 * @category	Utility 
 */
class Vimeo {

    /**
     * Constructor
     * 
     * Initialises the vimeo vendor class with the vimeo credentials
     */
    public function __construct() {
        try {
            App::import('Vendor', 'phpVimeo');
            $vimeoConfig = Configure::read('API.Vimeo');
            $this->phpVimeo = new phpVimeo($vimeoConfig);
        } catch (Exception $e) {
            $this->phpVimeo = null;
        }
    }

    /**
     * Function to get the information about a vimeo video
     * 
     * @param string $videoId
     * @return object
     */
    public function getVideoInfo($videoId) {
        try {
            return $this->phpVimeo->getVideoInfo($videoId);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Delete a video from vimeo 
     * 
     * @param string $video_id
     * @return bool
     */
    public function deleteVideo($videoId) {
        try {
            return $this->phpVimeo->deleteVideo($videoId);
        } catch (Exception $e) {
            return false;
        }
    }
}