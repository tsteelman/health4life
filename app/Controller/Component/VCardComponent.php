<?php

App::import('Vendor', 'vCard');

/**
 * 		<p>Component for importing vCard files in cakephp.</p>
 *
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 *
 * */
class VCardComponent extends Component {

    /**
     * 	Function to read name and email from vCard
     *
     * 	@param {string} $filename Path to the csv file being imported.
     * 	@returns {array} Indexed array of records.  Each record is an array of key => value pairs.
     * */
    public function importNameEmail($filename) {
        $vCard = new vCard(
                $filename, // Path to vCard file
                false, // Raw vCard text, can be used instead of a file
                array(// Option array
            // This lets you get single values for elements that could contain multiple values but have only one value.
            //	This defaults to false so every value that could have multiple values is returned as array.
            'Collapse' => false
                )
        );
        $records = array();
        if (count($vCard) == 0) {
            $this->set('error', true);
            $this->Session->setFlash("Empty vCard", 'error');
        } elseif (count($vCard) == 1) {
            $records[] = $this->extractNameEmail($vCard);
        }
        // if the file contains multiple vCards, they are accessible as elements of an array
        else {
            foreach ($vCard as $Index => $vCardPart) {
                $records[] = $this->extractNameEmail($vCardPart);
            }
        }
        return $records;
    }

    /**
     * 	Function to extract name and email from each vCard array
     *
     * 	@param {obj} $vCard single vCard record object.
     * 	@returns {array} Indexed array of records.  Each record is an array of key => value pairs.
     * */
    public function extractNameEmail(vCard $vCard) {
        $data = array();
        foreach ($vCard->N as $Name) {

            $data['name'] = $Name['FirstName'] . ' ' . $Name['LastName'];
        }
        if ($vCard->EMAIL) {

            foreach ($vCard->EMAIL as $Email) {

                if (is_scalar($Email)) {
                    $data['email'] = $Email;
                } else {
                    $data['email'] = $Email['Value'];
                }
            }
        }

        return $data;
    }

}
