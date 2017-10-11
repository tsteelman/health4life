<?php

App::uses('AppModel', 'Model');

/**
 * State Model
 *
 * @property Country $Country
 * @property City $City
 */
class State extends AppModel {

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'state_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    /**
     * Get all cities in a state
     */
    public function getStateCities($stateId) {
        $data = $this->City->find('list', array(
            'fields' => array('id', 'description'),
            'conditions' => array('state_id' => $stateId),
        ));
        return $data;
    }
}