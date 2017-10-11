<?php

/**
 * HashTag utility class file.
 *
 * @author    Ajay Arjunan<greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * HashTag class for utility functions to manipulate Hash Tag.
 *
 * @author 	Ajay Arjunan
 * @package 	App.Utility
 * @category	Utility
 */
class HashTagUtil {
    
    /**
    * Hashtag page url
    */
   const _hashtagUrl = "/hashtag";
    
    /**
     * Function to convert a text having hashtag to link
     * 
     * @param type $text
     * @return string
     */    
    public static function convertHashTags($text) {
        $regex = "/#+([a-zA-Z0-9_]+)/";
        $linkHtml = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', 
               '\1<a class="hashtaglink" '
                . 'href="'.self::getHashtagUrl().'/?tag=\2">#\2</a>' 
               , $text);
	return($linkHtml);
    }
    
    public static function getHashtagUrl() {
        return self::_hashtagUrl;
    }
}