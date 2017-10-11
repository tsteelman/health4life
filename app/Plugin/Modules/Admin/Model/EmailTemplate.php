<?php

App::uses('AppModel', 'Model');
$username = $_SESSION['Auth']['User']['username'];
class EmailTemplate extends AdminAppModel {

    public $validate = array(
        'template_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            )
        ),
        'template_subject' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A Subject is required'
            )
        ),
        'template_body' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Some Text is required'
            )
        )
    );

}
