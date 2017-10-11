<?php

App::uses('AppModel', 'Model');

/**
 * City Model
 *
 * @property State $State
 */
class Configuration extends AppModel {

    public function getValue($name) {

        $values = $this->find('first', array(
            'conditions' => array('name' => $name),
            'fields' => array('value')
        ));

        return $values['Configuration']['value'];
    }
    
    public function updateConfig($name, $value) {
        if (is_string($value)) 
        {
            $value = "'".$value."'";
        }
        $status = $this->updateAll(
                    array( 'value' => $value ),   
                    array( 'name' => $name )  
                );
        return $status;
    }

}