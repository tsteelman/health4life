<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * UserRegistrationForm Model
 *
 */
class UserRegistrationForm extends AppModel {

    public $useTable = false; // This model does not use a database table
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Username is required'
            ),
            'minLength' => array(
                'rule' => array('minLength', 5),
                'message' => 'Minimum 5 characters'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 30),
                'message' => 'Maximum 30 characters'
            ),
            'regex' => array(
//                'rule' => '/^(?=.{5,30}$)(?!.*[._]{2})[a-z][a-z0-9._]*[a-z0-9]$/i',
                'rule' => '/^[a-z][a-z0-9!@#$%^&*()?~_-]*$/i',
                'message' => 'Should start with an alphabet, can contain numbers and !@#$%^&*()?~-_'
            ),
            'remote' => array(
                'rule' => array('remote', '/api/checkExistingUsername', 'username'),
                'message' => 'This username exists in our system, please select another one.'
            ),
        ),
        'email' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Email is required'
            ),
            'email' => array(
                'rule' => '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/',
                'message' => 'Please enter a valid email id'
            ),
            'remote' => array(
                'rule' => array('remote', '/api/checkExistingEmail', 'email'),
                'message' => 'Possible errors: Invalid email address or Email address exists in our system. Please use a different email to create an account.'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password is required'
            ),
            'minLength' => array(
                'rule' => array('minLength', 6),
                'message' => 'Enter a password with a minimum of 6 characters.'
            )
        ),
        'confirm-password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please confirm the password.'
            ),
            'equalTo' => array(
                'rule' => array('equalTo', 'password'),
                'message' => 'The passwords do not match. Please try again.'
            )
        ),
        'agree' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please agree to terms and conditions in order to proceed.'
            )
        ),
        'firstname' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter your First Name.'
            ),
            'regex' => array(
                'rule' => '/^([a-zA-Z \']+[\-]*)+$/',
                'message' => 'Only alphabets, space, hyphen and “ ’ ” is allowed.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 30),
                'message' => 'Maximum 30 characters'
            )
        ),
        'lastname' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter your Last Name.'
            ),
            'regex' => array(
                'rule' => '/^([a-zA-Z \']+[\-]*)+$/',
                'message' => 'Only alphabets, space, hyphen and “ ’ ” is allowed.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 30),
                'message' => 'Maximum 30 characters'
            )
        ),
        'dob-year' => array(
            'age' => array(
                'rule' => array('dob'),
                'message' => 'Minimum age limit is 13 years',
                'allowEmpty' => true
            ),
        ),
        'dob-month' => array(
            'age' => array(
                'rule' => array('dob'),
                'message' => 'Minimum age limit is 13 years',
                'allowEmpty' => true
            ),
        ),
        'dob-day' => array(
            'age' => array(
                'rule' => array('dob'),
                'message' => 'Minimum age limit is 13 years',
                'allowEmpty' => true
            ),
        ),
        'gender' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a Gender.'
            )
        ),
        'country' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a country.'
            )
        ),
        'state' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a state/province.'
            )
        ),
        'city' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a city.'
            )
        ),
        'zip' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 15),
                'message' => 'Zip cannot exceed 15 characters.',
				'allowEmpty' => true
            )
        ),
        'patient-relationship' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select your relation.'
            )
        ),
        'patient-firstname' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter Patient First Name.'
            ),
            'regex' => array(
                'rule' => '/^([a-zA-Z \']+[\-]*)+$/',
                'message' => 'Only alphabets, space, hyphen and “ ’ ” is allowed.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 30),
                'message' => 'Maximum 30 characters'
            )
        ),
        'patient-lastname' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter Patient Last Name.'
            ),
            'regex' => array(
                'rule' => '/^([a-zA-Z \']+[\-]*)+$/',
                'message' => 'Only alphabets, space, hyphen and “ ’ ” is allowed.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 30),
                'message' => 'Maximum 30 characters'
            )
        ),
        'patient-dob-year' => array(
            'age' => array(
                'rule' => array('dob'),
                'message' => 'Minimum age limit is 13 years',
                'allowEmpty' => true
            ),
        ),
        'patient-dob-month' => array(
            'age' => array(
                'rule' => array('dob'),
                'message' => 'Minimum age limit is 13 years',
                'allowEmpty' => true
            ),
        ),
        'patient-dob-day' => array(
            'age' => array(
                'rule' => array('dob'),
                'message' => 'Minimum age limit is 13 years',
                'allowEmpty' => true
            ),
        ),
        'patient-gender' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select gender of Patient.'
            )
        ),
        'patient-country' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a country.'
            )
        ),
        'patient-state' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a state/province.'
            )
        ),
        'patient-city' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a city.'
            )
        ),
        'patient-zip' => array(
			'maxLength' => array(
                'rule' => array('maxLength', 15),
                'message' => 'Zip cannot exceed 15 characters.',
				'allowEmpty' => true
            )
        )
    );
}