<?php

/**
 * ListController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('PostAppController', 'Post.Controller');

/**
 * ListController for frontend posts.
 * 
 * ListController is used for listing posts.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Post
 * @category	Controllers 
 */
class ListController extends PostAppController {

    public $uses = array('Post');
    public $components = array('Posting', 'Paginator');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
                'filterPosts'
        );
    }

    /**
     * Function to filter posts
     */
    public function filterPosts() {
        $this->autoRender = false;

        // filter condition
        if ($this->request->data) {
			$data = $this->request->data;
			$settings = $this->Posting->getFilterSettings($data);
			$this->Session->write('settings', $settings);
			$this->Session->write('filterData', $data);
		} elseif ($this->Session->check('settings')) {
			$settings = $this->Session->read('settings');
			$data = $this->Session->read('filterData');
		}
		
        if (isset($settings)) {
            // paginate
            $this->Paginator->settings = array(
                'conditions' => $settings['conditions'],
                'order' => $settings['order'],
                'joins' => $settings['joins'],
                'group' => $settings['group'],
                'limit' => PostingComponent::POSTS_PER_PAGE
            );
            $posts = $this->Paginator->paginate('Post');

            if (!empty($posts)) {
                // get posts display data
                $this->Posting->hasLikePermission = true;
                $this->Posting->hasCommentPermission = true;
                $postsData = array();
                if (isset($data['isLibray']) && $data['isLibray'] == 1) {
                    foreach ($posts as $post) {
                        $postsData[] = $this->Posting->getLibraryPostDisplayData($post);
                    }
                    $this->Posting->hasLikePermission = false;
                    $this->Posting->hasCommentPermission = false;
                } else {
					if ($data['postedInType'] === Post::POSTED_IN_TYPE_DISEASES) {
						$this->Posting->layout = '2_column';
					}
					$displayPage = $data['postedInType'];
                    foreach ($posts as $post) {
                        $this->Posting->hasLikePermission = true;
                        $this->Posting->hasCommentPermission = true;
                        $postsData[] = $this->Posting->getPostDisplayData($post, $displayPage);
                    }
                }


                // get view content
                $view = new View($this, false);
                $viewData = array(
                    'posts' => $postsData,
                    'loggedin_userid' => $this->Auth->user('id'),
                    'loggedin_user_type' => $this->Auth->user('type'),
                    'loggedIn' => $this->Auth->loggedIn()
                );
                $view->set($viewData);
                $viewContent = $view->element('Post.post_list');

                // render the result
                if (isset($this->request->params['named']['page'])) {
                    echo $viewContent;
                } else {
                    $result = array(
                        'success' => true,
                        'content' => $viewContent
                    );
                    echo json_encode($result);
                }
            } else {
                $view = new View($this, false);
                $viewData = array('message' => __('No results'));
                $viewContent = $view->element('warning', $viewData);
                $result = array(
                    'error' => true,
                    'content' => $viewContent
                );
                echo json_encode($result);
            }
        }
    }
	
/*
    public function loadNewPosts() {
        $postIds = $this->request->data['postIds'];
        if (isset($postIds) && $postIds != NULL) {
//            if ($this->Session->check('settings')) {
//                $settings = $this->Session->read('settings');
//                print_r($settings);
//                exit;
//            }
            foreach ($postIds as $postId) {
                $post = $this->Post->findById($postId);
                if ($this->Auth->loggedIn()) {
                    $this->Posting->hasLikePermission = true;
                    $this->Posting->hasCommentPermission = true;
                }
                $postsData = $this->Posting->getPostDisplayData($post);

                if (isset($postsData) && $postsData != NULL) {
                    $view = new View($this, false);
                    $data = $postsData;
                    $element = $data['element'];
                    unset($data['element']);
                    $data['loggedin_userid'] = $this->Auth->user('id');
                    $data['loggedin_user_type'] = $this->Auth->user('type');
                    $data['loggedIn'] = $this->Auth->loggedIn();
                    $view->set($data);
                    $viewContent = $view->element($element);
//                unset($result['data']);
                    $resultPostElements['content'][] = $viewContent;
                }
                $post = null;
                $postsData = NULL;
            }
        }

        echo json_encode($resultPostElements);
        exit;
    }
*/


}