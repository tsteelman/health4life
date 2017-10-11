<?php

/**
 * 		File: app/Controller/Component/CsvComponent.php
 * 		<p>Component for importing Csv files in cakephp.</p>
 *
 *     Copyright (c) 2012 Eyesore, Inc.
 *
 *     See the file license.txt for copying permission.
 *
 * 		@author Trey Jones <trey@eyesoreinc.com>
 * 		@author Nithin Alexander 
 *
 * */
class CsvComponent extends Component {

    /**
     * 	Array of fields in the proper order to be parsed.
     * 	By default the header of the csv is read.  If this value is not empty,
     * 	the header will be ignored.  The header is defined as the first line of the csv,
     * 	regardless of whether it contains anything or not.
     * */
    public $fields = array();
    public $wantedfields = array(
        'First Name',
        'Last Name',
        'First',
        'Last',
        'Name',
        'E-mail Address',
        'E-mail 1 - Value',
        'Email'
    );

    /**
     * 	CSV delimiter, defaults to comma
     * */
    public $delimiter = ',';
//        public $delimiter = "\t";

    /**
     * 	String enclosure
     * */
    public $enclosure = '"';

    /**
     * 	Escape character
     */
    public $escape = '\\';

    /**
     * 	Parse a csv file into an indexed array of "records", where keys
     * 	are values from the header or $this->fields, and values are the values for
     * 	each row.
     *
     * 	@param {string} $filename Path to the csv file being imported.
     * 	@param {bool} $parseFirstLine If true, will parse the first line as a record, unless $this->fields is not set. By default skips the first line if fields is already set.
     * 	@returns {array} Indexed array of records.  Each record is an array of key => value pairs.
     * */
    public function import($filename, $parseFirstLine = false) {        
        $count = 0;
        $records = array();        
         /*
         * array to hold the count of the delimiters
         * occuring inside the file uploaded
         */
        $countArray = array();
        /*
         * Hold the possible delimiters in an array
         */
        $seperator = array("comma" => ",",
            "semicoln" => ";",
            "tab" => "\t",
            "pipe" => "|");
        
        $handle = fopen($filename, 'r');
        $fileData = fread($handle, filesize($filename));
        

        /*
         * Search for the separator inside the file content
         */
        foreach ($seperator as $key => $value) {
            $countSeperator = substr_count($fileData, $value);
            $countArray[$key] = $countSeperator;
        }        
        
         /*
         * Using the maximum count, find the possible delimiter
         * used inside the file
         */
        if (!empty($countArray)) {
            $mxCnt = max($countArray);
            $probDelim = array_search($mxCnt, $countArray);
        }        
        /*
         * Get the delimiter string
         */
        $this->delimiter = $probDelim == "" ? $this->delimiter : $seperator[$probDelim];
        

        $handle = fopen($filename, 'r');
        while ($line = fgetcsv($handle, null, $this->delimiter, $this->enclosure, $this->escape)) {
            if ($count == 0 && empty($this->fields))
                $this->fields = $line;

            if (empty($this->fields))
                throw new Exception('You must provide fields for parsing, or a header for the csv.');

            if ($count == 0 and $parseFirstLine === false) {
                $count++;
                continue;
            }

            $currentRecord = array();


            foreach ($this->fields as $index => $field) {

                if (in_array(trim($field), $this->wantedfields)) {

                    if (!empty($line[$index])) {
                        $currentRecord[$field] = $line[$index];
                    }
                }
            }
            if (!empty($currentRecord)) {
                $records[] = $currentRecord;
            }
            $count++;
        }

        fclose($handle);
        return $records;
    }

}
