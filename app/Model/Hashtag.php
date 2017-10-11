<?php

App::uses('AppModel', 'Model');

/**
 * Hashtag Model
 *
 */
class Hashtag extends AppModel {

    var $inserted_ids = array();
   
    function afterSave($created, $options = array()) {
        if($created) {
            $this->inserted_ids[] = $this->getInsertID();
        }
        return true;
    }
    
    /**
     * Returns trending Hashtags
     * 
     * @param Integer $limit
     * @return Array
     */
    public function getTrendingHashTags($limit = 10) {
        $trendingTags = $this->find('all', array(
            'order' => array('Hashtag.total_posts DESC'),
            'limit' => $limit));
        
        $this->virtualFields['total'] = 'SUM(Hashtag.total_posts)';
        $totalTags = $this->find('all');
        $totalRecords = array_shift($totalTags);
        $totalTagCount = $totalRecords['Hashtag']['total'];
        
        $trends = array();
        foreach ($trendingTags as $tags) {
            $trends[$tags['Hashtag']['tag_name']] = ($tags['Hashtag']['total_posts']/$totalTagCount)*100;
        };
        
        return $trends;
    }
    
    public function getHashTermId($term) {
        $result = $this->find('first', array(
            'conditions' => array('Hashtag.tag_name' => $term)));
        return empty($result)? 0 : $result['Hashtag']['id'];
    }
    
    /**
     * Returns trending Hashtags
     * 
     * @param Integer $limit
     * @return Array
     */
    public function getDashboardHashTags($userId, $limit = 10) {
        
        App::import('Model','FollowingPage');
        App::import('Model','Post');
        App::import('Model','MyFriends');
        
        $followingModel = new FollowingPage();
        $postModel = new Post();
        $friendModel = new MyFriends();
        
        $trends = array();        
        $diseaseFollowList = array();
        
       
        $diseaseFollowList = $followingModel->getFollowingDiseaseListId($userId);
        $friendsList = $friendModel->getUserConfirmedFriendsIdList($userId);
        
        if(!empty($diseaseFollowList)) {
            
            
            
            $conditions = array(
                    'Post.posted_in' => $diseaseFollowList,
                    'Post.is_deleted' => Post::NOT_DELETED,
                    'Post.hashtag_ids !=' => '',
                    'Post.post_by' => $friendsList,
            );
            $postData = $postModel->find('all', 
                array('conditions' => $conditions,
                'order' => array('Post.hashtag_ids DESC', 'Post.created DESC'),
                'limit' => $limit+1)
            );         
            
            if(!empty($postData)) {
                $diseaseTags = '';

                
                foreach($postData as $posts) {
                    $diseaseTags .= trim($posts['Post']['hashtag_ids']).",";
                    $trends[] = array("type" => "posts", "content" => $posts);
                }
                
                $tempArray = explode(",", trim($diseaseTags, ','));
                $diseaseTags = array_unique ($tempArray, SORT_STRING);

                $trendingTags = $this->find('all', array(
                    'conditions' => array('Hashtag.id' => $diseaseTags),
                    'order' => array('Hashtag.total_posts DESC'),
                    'limit' => $limit));

                $this->virtualFields['total'] = 'SUM(Hashtag.total_posts)';
                $totalTags = $this->find('all');
                $totalRecords = array_shift($totalTags);
                $totalTagCount = $totalRecords['Hashtag']['total'];


                foreach ($trendingTags as $tags) {
                    $trends[] = array("type" => "hashtag", "content" => 
                        array("tagname" => $tags['Hashtag']['tag_name'], 
                              "tagrank" => ($tags['Hashtag']['total_posts']/$totalTagCount)*100)    
                        );
                }                
            }          
                    
        }
        
        //print_r($trends);
        
        return $trends;
    }    
    
	
}