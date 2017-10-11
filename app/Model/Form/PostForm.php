<?php

/**
 * PostForm class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppModel', 'Model');

/**
 * PostForm Model.
 * 
 * PostForm to validate posts.
 *
 * @author 		Greeshma Radhakrishnan
 * @category	Model 
 */
class PostForm extends AppModel {

    public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter the title.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 300),
                'message' => 'Title cannot be more than 300 characters long.'
            )
        ),
        'description' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter text in the posting area.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 10000),
                'message' => 'Posting text cannot be more than 10000 characters long.'
            )
        ),
        'link' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please post a valid URL.'
            ),
            'url' => array(
                'rule' => array('url'),
                'message' => 'Please post a valid URL.'
            )
        ),
        'poll_title' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter text in the poll title area'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 300),
                'message' => 'Poll title cannot be more than 300 characters long'
            )
        )
    );
}