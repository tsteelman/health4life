<?php

/**
 * CommunityPostForm class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppModel', 'Model');

/**
 * CommunityPostForm Model.
 * 
 * CommunityPostForm to validate community post.
 *
 * @author 		Greeshma Radhakrishnan
 * @category	Model 
 */
class CommunityPostForm extends AppModel {

    public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter the title'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 300),
                'message' => 'Cannot be more than 300 characters long'
            ),
        ),
        'description' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter text in the posting area'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 10000),
                'message' => 'Cannot be more than 10000 characters long'
            ),
        ),
         'poll_title' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter text in the poll title area'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 10000),
                'message' => 'Cannot be more than 10000 characters long'
            ),
        )
    );
}