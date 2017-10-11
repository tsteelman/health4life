<?php

/**
 * Crawler utility class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * Crawler class for crawling urls.
 *
 * It crawls urls and grabs the content from them.
 * 
 * @author 		Greeshma Radhakrishnan
 * @package 	App.Utility
 * @category	Utility 
 */
class Crawler {

    public function grabUrlContents($link, $imageQuantity = 1) {
        header("Content-Type: text/html; charset=utf-8", true);
        error_reporting(false);

        $urlOpen = false;
        if (!ini_get('allow_url_fopen')) {
            $urlOpen = true;
            ini_set('allow_url_fopen', 1);
        }

        $link = " " . str_replace("\n", " ", $link);
        $urlRegex = "/(https?\:\/\/|\s)[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})(\/+[a-z0-9_.\:\;-]*)*(\?[\&\%\|\+a-z0-9_=,\.\:\;-]*)?([\&\%\|\+&a-z0-9_=,\:\;\.-]*)([\!\#\/\&\%\|\+a-z0-9_=,\:\;\.-]*)}*/i";

        if (preg_match($urlRegex, $link, $match)) {

            $linkInfo = parse_url(trim($link));
            $isSecureLink = false;
            if (isset($linkInfo['scheme']) && ($linkInfo['scheme']) === 'https') {
                $isSecureLink = true;
            }
            $this->isSecureLink = $isSecureLink;

            $raw = "";
            $title = "";
            $images = "";
            $description = "";
            $videoIframe = "";
            $videoIframeUrl = "";
            $finalUrl = "";
            $finalLink = "";
            $video = "no";

            if (strpos($match[0], " ") === 0)
                $match[0] = "http://" . substr($match[0], 1);

            $finalUrl = $match[0];
            $pageUrl = str_replace("https://", "http://", $finalUrl);

            $oEmbedMedia = $this->getOEmbedMedia(trim($link));
            if (!is_null($oEmbedMedia)) {
                if (!is_null($oEmbedMedia->title)) {
                    $title = $oEmbedMedia->title;
                }
                $description = $oEmbedMedia->description;
                $videoMediaTypes = array('video', 'rich');
                if (in_array($oEmbedMedia->type, $videoMediaTypes)) {
                    $video = "yes";
                    $videoIframe = $oEmbedMedia->html;
                    preg_match('/src="([^"]+)"/', $oEmbedMedia->html, $src);
                    $videoIframeUrl = $src[1];
                    $images = $oEmbedMedia->thumbnailUrl;
                } elseif ($oEmbedMedia->type == 'photo') {
                    $images = $oEmbedMedia->thumbnailUrl;
                }
            }

            $oEmbedDataPresent = false;
            if (($title !== '') || ($video === 'yes') || ($images !== '' && $images !== false && $images !== null)) {
                $oEmbedDataPresent = true;
                $pageUrl = $finalUrl = $link;
            }

            if ($oEmbedDataPresent === false) {
                $urlData = $this->getPage($pageUrl);
                if (!$urlData["content"] && strpos($pageUrl, "//www.") === false) {
                    if (strpos($pageUrl, "http://") !== false)
                        $pageUrl = str_replace("http://", "http://www.", $pageUrl);
                    elseif (strpos($pageUrl, "https://") !== false)
                        $pageUrl = str_replace("https://", "https://www.", $pageUrl);

                    $urlData = $this->getPage($pageUrl);
                }

                $pageUrl = $finalUrl = $urlData["url"];
                $raw = $urlData["content"];

                $metaTags = $this->getMetaTags($raw);

                $tempTitle = $this->extendedTrim($metaTags["title"]);
                if ($tempTitle != "")
                    $title = $tempTitle;

                if ($title == "") {
                    if (preg_match("/<title(.*?)>(.*?)<\/title>/i", str_replace("\n", " ", $raw), $matching))
                        $title = $matching[2];
                }

                $tempDescription = $this->extendedTrim($metaTags["description"]);
                if ($tempDescription != "")
                    $description = $tempDescription;
                else
                    $description = $this->crawlCode($raw);

                if ($description != "")
                    $descriptionUnderstood = true;

                if (($descriptionUnderstood == false && strlen($title) > strlen($description) && !preg_match($urlRegex, $description) && $description != "" && !preg_match('/[A-Z]/', $description)) || $title == $description) {
                    $title = $description;
                    $description = $this->crawlCode($raw);
                }

                $images = $this->extendedTrim($metaTags["image"]);
                if ($images !== "") {
                    if (!preg_match($urlRegex, $images)) {
                        $urlParts = parse_url($pageUrl);
                        $images = 'http://' . $urlParts['host'] . $images;
                    }
                }

                $media = array();

                if (strpos($pageUrl, "youtube.com") !== false) {
                    $media = $this->mediaYoutube($pageUrl);
                    $images = $media[0];
                    $videoIframe = $media[1];
                } else if (strpos($pageUrl, "vimeo.com") !== false) {
                    $media = $this->mediaVimeo($pageUrl);
                    $images = $media[0];
                    $videoIframe = $media[1];
                }

                if ($images == "") {
                    $images = $this->getImages($raw, $pageUrl, $imageQuantity);
                }
                if ($media != null && $media[0] != "" && $media[1] != "")
                    $video = "yes";

                $title = $this->extendedTrim($title);
                $pageUrl = $this->extendedTrim($pageUrl);
                $description = $this->extendedTrim($description);

                $description = preg_replace("/<script(.*?)>(.*?)<\/script>/i", "", $description);
            }

            $finalLink = explode("&", $finalUrl);
            $finalLink = $finalLink[0];

            if ($images === "" || $images === false || empty($images)) {
                if ($this->isImage($pageUrl)) {
                    $images = $pageUrl;
                }
            }
			
			// exclude css from the description
			if ($description !== '' && $description !== null && $description !== false) {
				$descFirstLetter = substr(trim($description), 0, 1);
				if ($descFirstLetter === '.' || $descFirstLetter == '#') {
					$description = '';
				}
			}

			$answer = array("title" => $title, "titleEsc" => $title, "url" => $finalLink, "pageUrl" => $finalUrl, "cannonicalUrl" => $this->cannonicalPage($pageUrl), "description" => strip_tags($description), "descriptionEsc" => strip_tags($description), "images" => $images, "video" => $video, "videoIframe" => $videoIframe, "videoIframeUrl" => $videoIframeUrl);
            echo json_encode($answer);
            exit;
        }

        if ($urlOpen == true) {
            ini_set('allow_url_fopen', 0);
        }
    }

    /**
     * Function to get media information from url using OEmbed
     * 
     * @param string $pageUrl
     * @return object
     */
    protected function getOEmbedMedia($pageUrl) {
        App::import('Vendor', 'essence/lib/bootstrap');
        $Essence = new fg\Essence\Essence();
        $oEmbedMedia = $Essence->embed($pageUrl);
        return $oEmbedMedia;
    }

    protected function getPage($url, $referer, $timeout, $header = "") {
        // php5-curl must be installed and enabled
        $res = array();
        $useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';
        $options = array(CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // do not return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_USERAGENT => $useragent, // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $res['content'] = $content;
        $res['url'] = $header['url'];

        return $res;
    }

    protected function getTagContent($tag, $string) {
        $pattern = "/<$tag(.*?)>(.*?)<\/$tag>/i";

        preg_match_all($pattern, $string, $matches);
        $content = "";
        for ($i = 0; $i < count($matches[0]); $i++) {
            $currentMatch = strip_tags($matches[0][$i]);
            if (strlen($currentMatch) >= 120) {
                $content = $currentMatch;
                break;
            }
        }
        if ($content == "") {
            preg_match($pattern, $string, $matches);
            $content = $matches[0];
        }
        return str_replace("&nbsp;", "", $content);
    }

    protected function mediaYoutube($url) {
        $media = array();
        if (preg_match("/(.*?)v=(.*?)($|&)/i", $url, $matching)) {
            $vid = $matching[2];

            $scheme = 'http';
            if ($this->isSecureLink) {
                $scheme = 'https';
            }
            $embedUrl = sprintf('%s://www.youtube.com/embed/%s', $scheme, $vid);

            array_push($media, "http://i2.ytimg.com/vi/$vid/hqdefault.jpg");
            array_push($media, '<iframe id="' . date("YmdHis") . $vid . '" style="display: none; margin-bottom: 5px;" width="499" height="368" src="' . $embedUrl . '" frameborder="0" &wmode="Opaque" allowfullscreen></iframe>');
        } else {
            array_push($media, "", "");
        }
        return $media;
    }

    protected function mediaVimeo($url) {
        $url = str_replace("https://", "", $url);
        $url = str_replace("http://", "", $url);
        $breakUrl = explode("/", $url);
        $media = array();
        if ($breakUrl[1] != "") {
            $imgId = $breakUrl[1];
            $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$imgId.php"));
            array_push($media, $hash[0]['thumbnail_large']);
            array_push($media, '<iframe id="' . date("YmdHis") . $imgId . '" style="display: none; margin-bottom: 5px;" width="500" height="281" src="http://player.vimeo.com/video/' . $imgId . '" width="654" height="368" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen ></iframe>');
        } else {
            array_push($media, "", "");
        }
        return $media;
    }

    protected function cannonicalLink($imgSrc, $referer) {
        if (strpos($imgSrc, "//") === 0)
            $imgSrc = "http:" . $imgSrc;
        else if (strpos($imgSrc, "/") === 0)
            $imgSrc = "http://" . $this->cannonicalPage($referer) . $imgSrc;
        else
            $imgSrc = "http://" . $this->cannonicalPage($referer) . '/' . $imgSrc;
        return $imgSrc;
    }

    protected function cannonicalImgSrc($imgSrc) {
        $imgSrc = str_replace("../", "", $imgSrc);
        $imgSrc = str_replace("./", "", $imgSrc);
        $imgSrc = str_replace(" ", "%20", $imgSrc);
        return $imgSrc;
    }

    protected function cannonicalRefererPage($url) {
        $cannonical = "";
        $barCounter = 0;
        for ($i = 0; $i < strlen($url); $i++) {
            if ($url[$i] != "/") {
                $cannonical .= $url[$i];
            } else {
                $cannonical .= $url[$i];
                $barCounter++;
            }
            if ($barCounter == 3) {
                break;
            }
        }
        return $cannonical;
    }

    protected function cannonicalPage($url) {
        $cannonical = "";

        if (substr_count($url, 'http://') > 1 || substr_count($url, 'https://') > 1 || (strpos($url, 'http://') !== false && strpos($url, 'https://') !== false))
            return $url;

        if (strpos($url, "http://") !== false)
            $url = substr($url, 7);
        else if (strpos($url, "https://") !== false)
            $url = substr($url, 8);

        for ($i = 0; $i < strlen($url); $i++) {
            if ($url[$i] != "/")
                $cannonical .= $url[$i];
            else
                break;
        }

        return $cannonical;
    }

    protected function getImageUrl($pathCounter, $url) {
        $src = "";
        if ($pathCounter > 0) {
            $urlBreaker = explode('/', $url);
            for ($j = 0; $j < $pathCounter + 1; $j++) {
                $src .= $urlBreaker[$j] . '/';
            }
        } else {
            $src = $url;
        }
        return $src;
    }

    protected function joinAll($matching, $number, $url, $content) {
        for ($i = 0; $i < count($matching[$number]); $i++) {
            $imgSrc = $matching[$number][$i] . $matching[$number + 1][$i];
            $src = "";
            $pathCounter = substr_count($imgSrc, "../");
            if (!preg_match("/https?\:\/\//i", $imgSrc)) {
                $src = $this->getImageUrl($pathCounter, $this->cannonicalLink($imgSrc, $url));
            }
            if ($src . $imgSrc != $url) {
                if ($src == "")
                    array_push($content, $src . $imgSrc);
                else
                    array_push($content, $src);
            }
        }
        return $content;
    }

    protected function getImages($text, $url, $imageQuantity) {
        $content = array();
        if (preg_match_all("/<img(.*?)src=(\"|\')(.+?)(gif|jpg|png|bmp)(\"|\')(.*?)(\/)?>(<\/img>)?/", $text, $matching)) {

            for ($i = 0; $i < count($matching[0]); $i++) {
				$src = "";
				$pathCounter = substr_count($matching[0][$i], "../");
				preg_match('/src=(\"|\')(.+?)(\"|\')/i', $matching[0][$i], $imgSrc);
				if (strpos($imgSrc[2], 'data-src') === false) {
					$rawSrc = $imgSrc[2];
					$imgSrc = $this->cannonicalImgSrc($imgSrc[2]);
					if (!preg_match("/https?\:\/\//i", $imgSrc)) {
						if (substr($imgSrc, 0, 1) === '/') {
							$src = $this->getImageUrl($pathCounter, $this->cannonicalLink($imgSrc, $url));
						} else if (substr($rawSrc, 0, 1) === '.') {
							$src = $this->cannonicalLink($imgSrc, $url);
						} else {
							$src = $url . $imgSrc;
						}
					}
                    if ($src . $imgSrc != $url) {
                        if ($src == "")
                            array_push($content, $src . $imgSrc);
                        else
                            array_push($content, $src);
                    }
                }
            }
        }
        $content = array_unique($content);
        $content = array_values($content);

        $maxImages = $imageQuantity != -1 && $imageQuantity < count($content) ? $imageQuantity : count($content);

        $images = "";
        for ($i = 0; $i < count($content); $i++) {
            $images .= $content[$i] . "|";
            $maxImages--;
            if ($maxImages == 0)
                break;
        }
        return substr($images, 0, -1);
    }

    protected function crawlCode($text) {
        $content = "";
        $contentSpan = "";
        $contentParagraph = "";
        $contentSpan = $this->getTagContent("span", $text);
        $contentParagraph = $this->getTagContent("p", $text);
        $contentDiv = $this->getTagContent("div", $text);
        $content = $contentSpan;
        if (strlen($contentParagraph) > strlen($contentSpan) && strlen($contentParagraph) >= strlen($contentDiv))
            $content = $contentParagraph;
        else if (strlen($contentParagraph) > strlen($contentSpan) && strlen($contentParagraph) < strlen($contentDiv))
            $content = $contentDiv;
        else
            $content = $contentParagraph;
        return $content;
    }

    protected function separeMetaTagsContent($raw) {
        preg_match('/content="(.*?)"/i', $raw, $match);
        return $match[1];
        // htmlentities($match[1]);
    }

    protected function getMetaTags($contents) {
        $result = false;
        $metaTags = array("url" => "", "title" => "", "description" => "", "image" => "");

        if (isset($contents)) {

            preg_match_all('/<meta(.*?)>/i', $contents, $match);

            foreach ($match[1] as $value) {

                if ((strpos($value, 'property="og:url"') !== false || strpos($value, "property='og:url'") !== false) || (strpos($value, 'name="url"') !== false || strpos($value, "name='url'") !== false))
                    $metaTags["url"] = $this->separeMetaTagsContent($value);
                else if ((strpos($value, 'property="og:title"') !== false || strpos($value, "property='og:title'") !== false) || (strpos($value, 'name="title"') !== false || strpos($value, "name='title'") !== false))
                    $metaTags["title"] = $this->separeMetaTagsContent($value);
                else if ((strpos($value, 'property="og:description"') !== false || strpos($value, "property='og:description'") !== false) || (strpos($value, 'name="description"') !== false || strpos($value, "name='description'") !== false))
                    $metaTags["description"] = $this->separeMetaTagsContent($value);
                else if ((strpos($value, 'property="og:image"') !== false || strpos($value, "property='og:image'") !== false) || (strpos($value, 'name="image"') !== false || strpos($value, "name='image'") !== false) || (strpos($value, 'itemprop="image"') !== false))
                    $metaTags["image"] = $this->separeMetaTagsContent($value);
            }

            $result = $metaTags;
        }
        return $result;
    }

    protected function isImage($url) {
        if (preg_match("/\.(jpg|png|gif|bmp)$/i", $url))
            return true;
        else
            return false;
    }

    protected function extendedTrim($content) {
        return trim(str_replace("\n", " ", str_replace("\t", " ", preg_replace("/\s+/", " ", $content))));
    }
    
    /**
     * Function to get embed code for media urls
     *
     * @param string $pageUrl
     * @return string
     */
    public function getEmbedPlayer($pageUrl, $width = '100%', $height = '100%', $autoplay = false) {   	
    	
    	$videoIframe = false;
    	$linkInfo = parse_url(trim($pageUrl));
    	$isSecureLink = false;
    	
    	if (isset($linkInfo['scheme']) && ($linkInfo['scheme']) === 'https') {
    		$isSecureLink = true;
    	}
    	$this->isSecureLink = $isSecureLink;
    	
    	//if it is a Youtube link
    	if (strpos($pageUrl, "youtube.com") !== false) {
    		
    		// get Youtube iframe 		
    		$videoIframe = $this->getYoutubeEmbedPlayer($pageUrl, $width, $height, $autoplay);

    		//if it is a vimeo link
    	} else if (strpos($pageUrl, "vimeo.com") !== false) {
    		
    		// get Vimeo iframe
    		$videoIframe = $this->getVimeoEmbedPlayer($pageUrl, $width, $height, $autoplay);
    		
    		//if it is a ustream link
    	} else if (strpos($pageUrl, "ustream.tv") !== false) {
    		
    		// get Ustream iframe
    		$videoIframe = $this->getUstreamEmbedPlayer($pageUrl, $width, $height, $autoplay);
    	}
    	
    	//if no iframes selected, use cake essence to get the iframe embed code
    	if(!$videoIframe) {
    		$oEmbedMedia = $this->getOEmbedMedia(trim($pageUrl));
    		if (!is_null($oEmbedMedia)) {
    			$videoMediaTypes = array('video', 'rich');
    			if (in_array($oEmbedMedia->type, $videoMediaTypes)) {
    				$videoIframe = $oEmbedMedia->html;
    				$videoIframe = $this->resizeVideoIframe($videoIframe, $width, $height);
    			}
    		}    		
    	}

    	return $videoIframe;
    	  	
    }   
    
    /**
     * Function to get Vimeo iframe of a url
     * @param string $pageUrl
     * @param string|int $width
     * @param string|int $height
     * @param boolean $autoplay
     * @return string|boolean
     */
    public function getVimeoEmbedPlayer($pageUrl, $width = '100%', $height = '100%', $autoplay = false){
    	
    	$url = str_replace("https://", "", $pageUrl);
    	$url = str_replace("http://", "", $pageUrl);
    	$breakUrl = explode("/", $url);
    	
    	if ($breakUrl[1] != "") {
    		$imgId = $breakUrl[1];
    		
    		//check for autoplay
    		if ($autoplay) {
    			$imgId .= '?autoplay=1';
    		}else{
    			$imgId .= '?autoplay=0';
    		}
    		
    		//create iframe for vimeo
    		$videoIframe = '<iframe id="' . date("YmdHis") . $imgId . '"
	            		width="'.$width.'" 
	            		height="'.$height.'"
	            		src="http://player.vimeo.com/video/' . $imgId . '"
	            		frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen ></iframe>';
    		
    		return $videoIframe;
    	}
    	
    	return false;
    }

    
    /**
     * Function to get Youtube iframe of a url
     * @param string $pageUrl
     * @param string|int $width
     * @param string|int $height
     * @param boolean $autoplay
     * @return string|boolean
     */
    public function getYoutubeEmbedPlayer($pageUrl, $width = '100%', $height = '100%', $autoplay = false){
    	
    	if (preg_match("/(.*?)v=(.*?)($|&)/i", $pageUrl, $matching)) {
    		$vid = $matching[2];
    	
    		$scheme = 'http';
    		if ($this->isSecureLink) {
    			$scheme = 'https';
    		}
    		$embedUrl = sprintf('%s://www.youtube.com/embed/%s', $scheme, $vid);
    		
    		//check for autoplay
    		if ($autoplay) {
    			$embedUrl .= '?autoplay=1';
    		}else{
    			$embedUrl .= '?autoplay=0';
    		}
    		 
    		//create iframe for youtube
    		$videoIframe = '<iframe id="' . date ( "YmdHis" ) . $vid . '"
    					width="'. $width . '" 
    					height="' . $height . '"
    					src="' . $embedUrl . '"
    					frameborder="0" &wmode="Opaque" allowfullscreen></iframe>';
    		
    		return $videoIframe;
    	}
    	
    	return false;
    }
    
    
    /**
     * Function to get Ustream iframe of a url
     * @param string $pageUrl
     * @param string|int $width
     * @param string|int $height
     * @param boolean $autoplay
     * @return string|boolean
     */
    public function getUstreamEmbedPlayer($pageUrl, $width = '100%', $height = '100%', $autoplay = false){
    	
    	$videoIframe = false;
    	
		//developer key for Ustream Api    	    	
    	$USTREAM_DEV_KEY = '2047E1EA8F19CC438FD068629D56A143';
    	
    	$url = str_replace("https://", "", $pageUrl);
    	$url = str_replace("http://", "", $pageUrl);
    	$breakUrl = explode("/", $url);
    	
    	//check for recorded/channel video url
    	$recorded = array_search('recorded',$breakUrl);
    	$channel = array_search('channel',$breakUrl);
    	
    	// if it is a recorded video
    	if($recorded){
    		$imgId = $breakUrl[$recorded+1];
    	
    		//check for autoplay
    		if ($autoplay) {
    			$imgId .= '?autoplay=1';
    		}else{
    			$imgId .= '?autoplay=0';
    		}
    		
    		//create iframe for Ustream
    		$videoIframe = '<iframe
		            		width="'.$width.'" height="'.$height.'"
		            		src="http://www.ustream.tv/embed/recorded/' . $imgId . '"
		            		frameborder="0"  style="border: 0px none transparent;" ></iframe>';
    	
    	// if it is a channel video
    	}else if($channel){
    		 
    		$request =  'http://api.ustream.tv';
    		$format = 'php';   // this can be xml, json, html, or php
    		$args = 'channel/';
    		$args .= $breakUrl[$channel+1]; ;
    		$args .= '/getCustomEmbedTag';
    		$args .= '?key=' . $USTREAM_DEV_KEY;
    		$args .= '&params=autoplay:'.$autoplay.';mute:false;height:'.$width.';width:'.$height.'';
    		
    		 
    		// Get and config the curl session object
    		$session = curl_init($request.'/'.$format.'/'.$args);
    		curl_setopt($session, CURLOPT_HEADER, false);
    		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    		 
    		//execute the request and close
    		$response = curl_exec($session);
    		curl_close($session);
    		 
    		// this line works because we requested $format='php' and not some other output format
    		$resultsArray = unserialize($response);
    	
    		// this is the data returned;
    		if($resultsArray['results']){
    			$videoIframe = $resultsArray['results'];
    		}
    	}
    	
    	return $videoIframe;
    }
    
    
    /**
     * Function to replace the width and height with given width and height
     * @param string $videoIframe
     * @param int $width
     * @param int $height
     * @return string
     */
    function resizeVideoIframe($videoIframe, $width, $height){
    	
    	//replace width using regular expression
    	$pattern = '/width="(\d+)"/i';
    	$replacement = 'width="'. $width .'"';    	
    	$videoIframe =( preg_replace($pattern, $replacement, $videoIframe));
    	
    	//replace height using regular expression
    	$pattern = '/height="(\d+)"/i';
    	$replacement = 'height="' .$height. '"';
    	$videoIframe =( preg_replace($pattern, $replacement, $videoIframe));
    	
    	$pattern = '/width=\s*(\d+)/i';
    	$replacement = 'width=816' ;
    	$videoIframe =( preg_replace($pattern, $replacement, $videoIframe));
    	
    	//replace height using regular expression
    	$pattern = '/height=\s*(\d+)/i';
    	$replacement = 'height=478';// .$height;
    	$videoIframe =( preg_replace($pattern, $replacement, $videoIframe));
    
    	return $videoIframe;
    }
}