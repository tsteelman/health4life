<?php

App::uses('HashttagAppController', 'Hashtag.Controller');

/**
 * HashtagController for frontend communities.
 *
 * HashtagController is used for listing and managing hashtags.
 *
 * @package 	Hashtag
 * @category	Controllers
 */
class HashtagsController extends HashtagAppController {
    
    public $uses = array('Post', 'Hashtag');
    public $components = array('Posting', 'Paginator');
    
    /**
     * Returns posts for a hashtags
     */
    public function index() {
        $posts = array();
        if (isset($this->request->query['tag'])) {
            $hashTerm = $this->request->query['tag'];
            //getHashid for the term 
            $hashid = $this->Hashtag->getHashTermId($hashTerm);
            if (!empty($hashid)) {
               $hashRegex = "^$hashid,|,$hashid,|,$hashid|^$hashid$";
                if (!empty($hashTerm)) {
                    $this->Paginator->settings = array(
                        'conditions' => array(
                            'Post.is_deleted' => Post::NOT_DELETED,
                            'Post.hashtag_ids REGEXP' => $hashRegex
                        ),
                        'order' => array(
                            'Post.created' => 'DESC'
                        ),
                        'limit' => PostingComponent::POSTS_PER_PAGE
                    );
                    $postDetails = $this->Paginator->paginate('Post');

                    if (!empty($postDetails)) {
                        foreach ($postDetails as $post) {
                            $posts[] = $this->Posting->getPostDisplayData($post, 'hashtag');
                        }
                    }
                } 
            }
        }
        
        $trendingTags = $this->Hashtag->getTrendingHashTags();
        $this->set(compact('posts', 'trendingTags'));
    }
    
}