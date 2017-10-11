<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * ResetPasswordForm Model
 *
 */
class CsvImportForm extends AppModel {


    public $useTable = false; // This model does not use a database table
    public $validate = array(
        'csv_file' => array(
            'uploadError' => array(
                'rule' => 'uploadError',
                'message' => 'Please upload a file',
                'required' => FALSE,
                'allowEmpty' => TRUE,
            ),
            'extension' => array(
                'rule' => array('extension', array('csv','vcf')),
                'message' => 'Only csv & vcf files',
            ),
            'mimeType' => array(
                'rule' => array('mimeType', array(
                    'text/plain',
                    'text/csv',
                    'text/vcard',
                    'text/x-vcard',
                    'text/directory;profile=vCard',
                    'application/vcard'
                    )),
                'message' => 'Invalid file, only csv & Mac OS X Address Book - VCF format allowed',
                
            ),
        )
    );    
 

}