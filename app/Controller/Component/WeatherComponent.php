<?php

/**
 * WeatherComponent class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');

/**
 * WeatherComponent to handle Weather data.
 * 
 * This class is used to get the weather data of a city.
 *
 * @author 		Ajay Arjunan
 * @package 	Controller.Component
 * @category	Component 
 */
class WeatherComponent extends Component {
    
    /**
    * Weather API url
    */
    public $weatherUrl = "http://api.worldweatheronline.com/free/v2/weather.ashx?format=json&num_of_days=4";
    
    /**
     * Constructor
     * 
     * Initialises the models
     */
    public function __construct() {
        $this->City = ClassRegistry::init('City');
    }
    
    /**
     * Function to fetch weather details of a city using API
     * 
     * @param type $cityId
     * @return array
     */
    public function fetchWeather($cityId, $timezone, $tempUnit) {
        $coordinates = $this->City->getCityLatLongPoints($cityId);
        if (!empty($coordinates)) {
            $latitude = $coordinates[0]['City']['latitude'];
            $longitude = $coordinates[0]['City']['longitude'];
            $modifiedDate = $coordinates[0]['City']['modified_date'];
        }
        
        if(!empty($longitude) && !empty($latitude)) {
            $currentTime = date('Y-m-d H:i:s');
            $timeInterval = date_diff( date_create($modifiedDate), date_create($currentTime));
            if($tempUnit == 1) {  // Celcius
                $currentT = 'temp_C';
                //$maxT = 'tempMaxC';
                //$minT = 'tempMinC';
            } else if($tempUnit == 2) {  //Fahrenheit
                $currentT = 'temp_F';
                //$maxT = 'tempMaxF';
                //$minT = 'tempMinF';
            }
            
            if(($timeInterval->h) > 0 || ($timeInterval->d > 0)) {
                    $apiKey = Configure::read('App.WEATHER_API_KEY');
                    $url = $this->weatherUrl . '&q=' .$latitude. '%2C'. $longitude. '&key=' .$apiKey;
                    $jsonresponse = $this->curlFileGetContents($url);
                    $weatherData = json_decode($jsonresponse, true);
                    if(!isset($weatherData['data']['error']) && isset($weatherData) && (!isset($weatherData['results']['error']))) {
                        $weatherInfo['content'] = $jsonresponse;
                        $weatherInfo['modifiedDate'] = date('Y-m-d H:i:s');
                        $timezoneDate = CakeTime::nice(date('Y-m-d H:i:s'), $timezone, '%Y-%m-%d');
                        $apiDate = $weatherData['data']['weather'][0]['date'];
                        if(strtotime($timezoneDate) < strtotime($apiDate)) {   
                            // get yesterday's weather
                            $jsonOldWeather = $this->City->getWeather($cityId);
                            $oldWeatherData = json_decode($jsonOldWeather, true);
                            $yesterdayData = $oldWeatherData['data']['weather'][0];
                            
                            $weatherData['data']['yesterdayData'] = $yesterdayData;
                            $jsonData = json_encode($weatherData);
                            $weatherInfo['content'] = $jsonData;
                        }
                        
                        $this->City->updateWeatherData($cityId, $weatherInfo);
                    }
                    
            } else  {
                    $jsonresponse = $this->City->getWeather($cityId);
                    $weatherData = json_decode($jsonresponse, true);
            }
            if(isset($weatherData['data'])) {
                $weather = array();
                $weather['currentTemperature'] = $weatherData['data']['current_condition'][0][$currentT];
                $weather['weatherDesc'] = $weatherData['data']['current_condition'][0]['weatherDesc'][0]['value'];
                $weather['weatherIcon'] = $this->getWeatherIcon($weatherData['data']['current_condition'][0]['weatherDesc'][0]['value']);
//                foreach ($weatherData['data']['weather'] as $data) {
//                        $temp['date'] = $data['date'];
//                        $temp['max'] = $data[$maxT];
//                        $temp['min'] = $data[$minT];
//                        $temp['weatherDesc'] = $data['hourly'][0]['weatherDesc'][0]['value'];
//						$temp['weatherDesc'] = $data['weatherDesc'][0]['value']; // old one
//                        $temp['weatherIcon'] = $this->getWeatherIcon($data['hourly'][0]['weatherDesc'][0]['value']);
//                        $weather['temperature'][] = $temp;
//                }
                
                // in case, yesterday data is not available in database, set it to today's data.
                //$weather['yesterdayData']['max'] = isset($weatherData['data']['yesterdayData'][$maxT]) ? ($weatherData['data']['yesterdayData'][$maxT]) : ($weatherData['data']['weather'][0][$maxT]);
                //$weather['yesterdayData']['min'] = isset($weatherData['data']['yesterdayData'][$minT]) ? ($weatherData['data']['yesterdayData'][$minT]) : ($weatherData['data']['weather'][0][$minT]);
                //$weather['yesterdayData']['weatherDesc'] = isset($weatherData['data']['yesterdayData']['weatherDesc'][0]['value']) ? ($weatherData['data']['yesterdayData']['weatherDesc'][0]['value']) : ($weatherData['data']['weather'][0]['weatherDesc'][0]['value']);
                //$weather['yesterdayData']['weatherIcon'] = isset($weatherData['data']['yesterdayData']['weatherDesc'][0]['value']) ? ($this->getWeatherIcon($weatherData['data']['yesterdayData']['weatherDesc'][0]['value'])) : ($this->getWeatherIcon($weatherData['data']['weather'][0]['weatherDesc'][0]['value']));
                
                $cityDetails = $this->City->getCityLocation($cityId);
                $weather['city'] = $cityDetails['City']['description'];
                $weather['state'] = $cityDetails['States']['description'];
                $weather['country'] = $cityDetails['Country']['short_name'];
                return $weather;
            }
        }
    }
    
    /**
     * Function to get the contents of a file using curl
     * 
     * @param string $url
     * @return string
     */
    public function curlFileGetContents($url) {
        $curl = curl_init();
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

        curl_setopt($curl, CURLOPT_URL, $url); //The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); //The number of seconds to wait while trying to connect.	

        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //To stop cURL from verifying the peer's certificate.
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
    }
    
    /**
     * Function to get the weather icon from weather
     * 
     * @param string $url
     * @return string
     */
    public function getWeatherIcon($desc) {
        switch ($desc) {
            case 'Clear':
                    return 'clear_sky';
                    break;
            case 'Cloudy':
                    return 'cloud';
                    break;
            case 'Fog' :
                    return 'smog';
                    break;
            case 'Freezing drizzle' :
                    return 'cloudy_with_heavy_sleet';
                    break;
            case 'Freezing fog' :
                    return 'smog';
                    break;    
            case 'Light drizzle' :
                    return 'cloudy_with_light_rain';
                    break;
            case 'Light rain' :
                    return 'cloudy_with_light_rain';
                    break;    
            case 'Light rain shower' :
                    return 'light_rain_showers';
                    break; 
            case 'Light snow' :
                    return 'light_snow_showers';
                    break;
            case 'Mist' :
                    return 'mist';
                    break;
            case 'Moderate or heavy rain shower' :
                    return 'cloudy_with_heavy_rain';
                    break;
            case 'Moderate rain' :
                    return 'cloudy_with_heavy_rain';
                    break;
            case 'Moderate rain at times' :
                    return 'cloudy_with_heavy_rain';
                    break;
            case 'Moderate snow' :
                    return 'cloudy_with_heavy_snow';
                    break;    
            case 'Overcast': 
                    return 'black_low_cloud';
                    break;
            case 'Partly Cloudy' :
                    return 'sunny_inervals';
                    break;
            case 'Patchy light drizzle' :
                    return 'light_rain_showers';
                    break;
            case 'Patchy light rain' :
                    return 'cloudy_with_light_rain';
                    break;
            case 'Patchy rain nearby' :
                    return 'light_rain_showers';
                    break;
            case 'Patchy light snow' :
                    return 'light_snow_showers';
                    break;
            case 'Sunny' :
                    return 'sunny';
                    break;
            default : 
                    return 'sunny';
                    break;
        }
    }
    
}