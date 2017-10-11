<?php

/**
 * WeatherComponent class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('Hashtag', 'Model');

/**
 * HashTagComponent to handle Weather data.
 * 
 * This class is used to get the weather data of a city.
 *
 * @author 	Ajay Arjunan
 * @package 	Controller.Component
 * @category	Component 
 */
class HashTagComponent extends Component {
      
    /**
     * Constructor
     * 
     * Initialises the models
     */
    public function __construct() {
        $this->Hashtag = ClassRegistry::init('Hashtag');
    }
    
    /**
     * Function to parse a string to check the hashtag existense
     * 
     * @param type $text
     * @return array
     */
    public function parseText($text) {
        
        if(trim($text) != "") {
            $hashtag = $this->getHashTags($text);
            $hashtag_ids = array();
            
            if(!empty($hashtag)) {
                $userHashtag = $hashtag[0];
                $hashtagData = $hashtag[1];
                $hashtagToSave = array();
                
                $existingData = $this->Hashtag->find('all', array(
                    'conditions' => array('Hashtag.tag_name' => $hashtagData)
                ));
                
                
                /*
                 * If added tag is already present in DB, no need to add them again
                 */
                $hashtagToSave =  $hashtagData;
                if(!empty($existingData)) {
                    foreach ($existingData as $existingHashtag) {
                        if ((($key = array_search($existingHashtag['Hashtag']['tag_name'],
                                $hashtagData)) !== false)) {
                           unset($hashtagToSave[$key]);
                           $hashtag_ids[] = $existingHashtag['Hashtag']['id'];
                        }
                    }
                }
                
                /*
                 * Add all the tags to the DB since they are not present now.
                 */
                foreach($hashtagToSave as $saveData) {
                    $hashtagToSaveNew[]['tag_name'] = $saveData;
                }

                if(!empty($hashtagToSaveNew)) {
                    try {
                        if($this->Hashtag->saveAll($hashtagToSaveNew)) {
                            $hashtag_ids = $this->Hashtag->inserted_ids; 
                        }
                    } catch (Exception $e) {
                        $error = 'The item you are trying to delete is associated with other records';
                        // The exact error message is $e->getMessage();
                        echo $error;
                    }
                }
                
                /*
                 * Update the count of the hashtags
                 * Bad coding
                 */
                if(!empty($hashtag_ids)) {
                    foreach($hashtag_ids as $id) {
                        $this->Hashtag->updateAll(array('Hashtag.total_posts'
                            =>'Hashtag.total_posts+1'), array('Hashtag.id'=>$id));
                    }
                }
                
                return $hashtag_ids;
            }
        }

    }
    
    /**
     * Function to parse a string to check the hashtag existense
     * 
     * @param type $text
     * @return array
     */    
    public function getHashTags($text) {
        //Match the hashtags
        preg_match_all('/(^|[^a-z0-9_])#([a-z0-9_]+)/i', $text, $matchedHashtags);
        $hashtag = array();
        $hashtagData = array();
        // For each hashtag, strip all characters but alpha numeric
        if(!empty($matchedHashtags[0])) {
            foreach($matchedHashtags[0] as $match) {
                $tags = preg_replace("/[^a-z0-9]+/i", "", $match);
                $hashtag[]['tag_name'] = $tags;
                if(!in_array($tags, $hashtagData)) {
                    $hashtagData[] = $tags;
                }
            }
        }
        //to remove last comma in a string
        return array($hashtag, $hashtagData);
    }
}