<?php

class AppSchema extends CakeSchema {

    public function before($event = array()) {
        $db = ConnectionManager::getDataSource($this->connection);
        $db->cacheSources = false;
        return true;
    }

    public function after($event = array()) {
        if (isset($event['create'])) {
            $table = $event['create'];
            $data = null;
            switch ($table) {
                case 'users':
                    App::uses('AuthComponent', 'Controller/Component');
                    $data = array(
                        array(
                            'username' => 'admin',
                            'email' => 'p4l.qburst@gmail.com',
                            'password' => AuthComponent::password('qburst'),
                            'is_admin' => 1
                        ),
                        array(
                            'username' => 'sugunan',
                            'first_name' => 'Sugunan',
                            'last_name' => 'Asokan',
                            'email' => 'sugunan@qburst.com',
                            'password' => AuthComponent::password('qburst'),
                            'status' => 1
                        ),
                        array(
                            'username' => 'ajay',
                            'first_name' => 'Ajay',
                            'last_name' => 'Arjunan',
                            'email' => 'ajay@qburst.com',
                            'password' => AuthComponent::password('qburst'),
                            'status' => 1
                        ),
                        array(
                            'username' => 'greeshma',
                            'first_name' => 'Greeshma',
                            'last_name' => 'Radhakrishnan',
                            'email' => 'greeshma@qburst.com',
                            'password' => AuthComponent::password('qburst'),
                            'status' => 1
                        ),
                        array(
                            'username' => 'varun',
                            'first_name' => 'Varun',
                            'last_name' => 'Ashok',
                            'email' => 'varunashok@qburst.com',
                            'password' => AuthComponent::password('qburst'),
                        ),
                        array(
                            'username' => 'roshan',
                            'first_name' => 'Roshan',
                            'last_name' => 'Faizal',
                            'email' => 'roshan@qburst.com',
                            'password' => AuthComponent::password('qburst'),
                        ),
                    );
                    break;
                default:
            }
            if ($data) {
                ClassRegistry::init($table)->saveAll($data);
            }
        }
    }

    public $users = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'username' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'profile_picture' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'gender' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'date_of_birth' => array('type' => 'date', 'null' => true, 'default' => null),
        'country' => array('type' => 'integer', 'null' => true, 'default' => null),
        'zip' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'state' => array('type' => 'integer', 'null' => true, 'default' => null),
        'timezone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'last_login_datetime' => array('type' => 'datetime', 'null' => true, 'default' => null),
        'type' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1, 'comment' => 'user type - patient, family, caregiver)'),
        'is_admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
        'newsletter' => array('type' => 'boolean', 'null' => true, 'default' => null),
        'status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
        'remember_me_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'forgot_password_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'height' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3),
        'marital_status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'phone_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );
}