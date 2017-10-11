<?php
App::uses('AppModel', 'Model');
/**
 * Volunteer Model
 *
 */
class Volunteer extends AppModel {
    
    /*
     * Function to add user as a volunteer.
     */
    public function createVolunteer($data) {
        $this->create();
        if($this->save($data)) {
            return true;
        }
    }



}
