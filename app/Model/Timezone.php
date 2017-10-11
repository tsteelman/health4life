<?php

App::uses('AppModel', 'Model');
App::uses('Date', 'Utility');

/**
 * Timezone Model
 *
 * @property Country $Country
 */
class Timezone extends AppModel {

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
	 * Function to list all the timezones along with GMT offset
	 * 
	 * format: country name (GMT+offset)
	 * eg: New Delhi (GMT+05:30)
	 * 
	 * @return array
	 */
	public function listTimeZones() {
		$timezones = array();
		$groupedZones = CakeTime::listTimezones(null, null, true);
		if (!empty($groupedZones)) {
			foreach ($groupedZones as $primaryZone => $zones) {
				foreach ($zones as $zoneName => $zoneShortName) {
					$timeZoneOffset = Date::getTimeZoneOffsetText($zoneName);
					if (!is_null($timeZoneOffset)) {
						$timeZoneName = "{$zoneName} (GMT {$timeZoneOffset}) ";
						$timezones[$primaryZone][$zoneName] = $timeZoneName;
					}
				}
			}

			// setting America as first timezone in the list
			$firstZone = 'America';
			if (isset($timezones[$firstZone])) {
				$firstZoneTimezones = $timezones[$firstZone];
				unset($timezones[$firstZone]);
				$firstZoneTimezonesArr = array($firstZone => $firstZoneTimezones);
				$timezones = array_merge($firstZoneTimezonesArr, $timezones);
			}
		}
		return $timezones;
	}

	/**
	 * Function to get a list of all timezones
	 * 
	 * @return array
	 */
	public function getTimezoneList() {
		return CakeTime::listTimezones(null, null, false);
	}

	/**
	 * Function to get the timezones where the hour is specified hour
	 * 
	 * @param int $hour
	 * @return array
	 */
	public function getTimezonesWhereHourIs($hour) {
		$timeZones = $this->getTimezoneList();
		foreach ($timeZones as $timezone) {
			$tzHour = $this->getHourInTimezone($timezone);
			if ($tzHour === $hour) {
				$selectedTimezones[] = $timezone;
			}
		}
		return $selectedTimezones;
	}

	/**
	 * Function to get the current hour in a timezone
	 * 
	 * @param string $timezone
	 * @return int
	 */
	public function getHourInTimezone($timezone) {
		$tzTime = CakeTime::convert(time(), new DateTimeZone($timezone));
		if (!empty($tzTime)) {
			$tzDate = getdate($tzTime);
			return $tzDate['hours'];
		}
	}
	

	/**
	 * Get the timezone list
	 *
	 * @return array
	 */
	public function get_timezone_list() {
		
		// timezones customized 
		$timeZones =  array (
				'Pacific/Midway' => 'Midway Island, Samoa',				
				'Pacific/Honolulu' => 'Hawaii',
				'US/Alaska' => 'Alaska',
				'America/Los_Angeles' => 'Pacific Time (US & Canada)',
				'America/Tijuana' => 'Tijuana, Baja California',				
				'America/Chihuahua' => 'Chihuahua, La Paz, Mazatlan',	
		 		'US/Arizona' => 'Arizona',
				'US/Mountain' => 'Mountain Time (US & Canada)',
				'America/Managua' => 'Central America',				
				'America/Mexico_City' => 'Guadalajara, Mexico City, Monterrey',	
				'US/Central' => 'Central Time (US & Canada)',
				'Canada/Saskatchewan' => 'Saskatchewan',
				'America/Bogota' => 'Bogota, Lima, Quito, Rio Branco',
				'US/Eastern' => 'Eastern Time (US & Canada)',
				'US/East-Indiana' => 'Indiana (East)',				
				'Canada/Atlantic' => 'Atlantic Time (Canada)',
				'America/Caracas' => 'Caracas',
				'America/La_Paz' => 'La Paz',
				'America/Santiago' => 'Manaus, Santiago',
				'Canada/Newfoundland' => 'Newfoundland',
				'America/Sao_Paulo' => 'Brasilia',
				'America/Argentina/Buenos_Aires' => 'Buenos Aires, Georgetown',				
				'America/Godthab' => 'Greenland, Montevideo',
				'America/Noronha' => 'Mid-Atlantic',
		 		'Atlantic/Cape_Verde' => 'Cape Verde Is.',
				'Atlantic/Azores' => 'Azores',				
				'Africa/Casablanca' => 'Casablanca, Monrovia, Reykjavik',				
				'Etc/Greenwich' => 'Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London',			
				'Europe/Amsterdam' => 'Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
				'Europe/Belgrade' => 'Belgrade, Bratislava, Budapest, Ljubljana, Prague',		
				'Europe/Brussels' => 'Brussels, Copenhagen, Madrid, Paris',
				'Europe/Sarajevo' => 'Sarajevo, Skopje, Warsaw, Zagreb',		
				'Africa/Lagos' => 'West Central Africa',
				'Asia/Amman' => ' Amman',
				'Europe/Athens' => 'Athens, Bucharest, Istanbul',
				'Asia/Beirut' => 'Beirut',
				'Africa/Cairo' => 'Cairo',
				'Africa/Harare' => 'Harare, Pretoria',
				'Europe/Helsinki' => 'Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius',				
				'Asia/Jerusalem' => 'Jerusalem',
		 		'Africa/Windhoek' => 'Windhoek',
		 		'Europe/Minsk' => 'Minsk',		
				'Asia/Kuwait' => 'Kuwait, Riyadh, Baghdad',				
				'Africa/Nairobi' => 'Nairobi',					
		 		'Europe/Moscow' => 'Moscow, St. Petersburg, Volgograd',
		 		'Asia/Tehran' => 'Tehran',
				'Asia/Muscat' => 'Abu Dhabi, Muscat',
				'Asia/Baku' => 'Baku',					
				'Asia/Tbilisi' => 'Tbilisi',
				'Asia/Yerevan' => 'Yerevan',
				'Asia/Kabul' => 'Kabul',
		 		'Asia/Yekaterinburg' => 'Yekaterinburg',	
				'Asia/Karachi' => 'Islamabad, Karachi, Tashkent',			
				'Asia/Kolkata' => 'Chennai, Kolkata, Mumbai, New Delhi',				
				'Asia/Calcutta' => 'Sri Jayawardenepura',
				'Asia/Katmandu' => 'Kathmandu',
				'Asia/Almaty' => 'Almaty',
				'Asia/Dhaka' => 'Astana, Dhaka',			
				'Asia/Rangoon' => 'Yangon (Rangoon)',
				'Asia/Bangkok' => 'Bangkok, Hanoi, Jakarta',			
				'Asia/Novosibirsk' => 'Novosibirsk',
		 		'Asia/Hong_Kong' => 'Beijing, Chongqing, Hong Kong, Urumqi',				
				'Asia/Krasnoyarsk' => 'Krasnoyarsk',
				'Asia/Kuala_Lumpur' => 'Kuala Lumpur, Singapore',
				'Australia/Perth' => 'Perth',	
				'Asia/Taipei' => 'Taipei',				
				'Asia/Irkutsk' => 'Irkutsk, Ulaan Bataar',
				'Asia/Tokyo' => 'Osaka, Sapporo, Tokyo',				
				'Asia/Seoul' => 'Seoul',				
				'Australia/Adelaide' => 'Adelaide',
				'Australia/Darwin' => 'Darwin',
				'Australia/Brisbane' => 'Brisbane',
				'Australia/Canberra' => 'Canberra, Melbourne, Sydney',
				'Pacific/Guam' => 'Guam, Port Moresby',
				'Australia/Hobart' => 'Hobart',				
				'Asia/Yakutsk' => 'Yakutsk',
				'Asia/Vladivostok' => 'Vladivostok',
		 		'Asia/Magadan' => 'Magadan, Solomon Is., New Caledonia',
				'Pacific/Auckland' => 'Auckland, Wellington',
				'Pacific/Fiji' => 'Fiji, Kamchatka, Marshall Is.',
				'Pacific/Kwajalein' => 'International Date Line West',								
				'Pacific/Tongatapu' => 'Nuku\'alofa'
		);
		
		$timezones_list = array();
		
		/*
		 * adding GMT offset to tizone names 
		 */
		foreach ($timeZones as $zoneName => $zoneShortName) {
			$timeZoneOffset = Date::getTimeZoneOffsetText($zoneName);
			if (!is_null($timeZoneOffset)) {
				$timeZoneName = "(GMT{$timeZoneOffset}) {$zoneShortName}";
				$timezones_list[] = array('value' => $zoneName,'name'=>$timeZoneName, 'offset' => $timeZoneOffset);			
			}
		}
		
		/*
		 * sort timezone list based on current GMT offset
		 */
		uasort($timezones_list, array('Timezone','usortTimezone'));
		return $timezones_list;
	}

	/**
	 * comparator function for sort timezones
	 *
	 */
	function usortTimezone($a, $b) {

		// select only time offset value
		$offset_a = $a['offset'];
		$offset_b = $b['offset'];
		
		//return 0 if time offset are equal
		if($offset_a == $offset_b){
			return 0;
		}
		
		/*
		 * If the sign of the offset values are ezual
		 * ie, `+` == `+` or `-` == `-`
		 */
		if($offset_a[0]==$offset_b[0]){
			
			/*
			 * Extract the intiger offset value 
			 * eg: +10:0 => 10:0
			 */
			$offset_value_a =substr($offset_a, 1, strlen($offset_a));
			$offset_value_b =substr($offset_b, 1, strlen($offset_b));

			/*
			 * If it is a `-` value, arrange in asc order
			 * if it is a `+` value, arrange in in desc order
			 */
			if(strtotime($offset_value_a)>strtotime($offset_value_b)){
				if($offset_a[0]=='-'){
					return -1;
				}else{
					return 1;
				}
			}else{
				if($offset_a[0]=='-'){
					return 1;
				}else{
					return -1;
				}
			}

		}else{		
			/*
			 *  - has higher priority than +
			 */	
			if($offset_a[0]=='+'){
				return 1;
			}else{
				return -1;
			}
			
		}
	}
	
	
	/**
	 * Detect the timezone id(s) from an offset and dst
	 *
	 * @param   int     $offset
	 * @param   int     $dst
	 * @param   bool    $multiple
	 * @param   string  $default
	 * @return  string|array
	 */
	public function detect_timezone_id($offset, $dst, $multiple = FALSE, $default = 'US/Central'){
	
		$detected_timezone_ids = array();
	
		// Get the timezone list
		$timezones = self::get_timezone_list();
	
		// Try to find a timezone for which both the offset and dst match
		foreach ($timezones as $timzone){

			$timezone_data = self::get_timezone_data($timzone['value']);
			if ($timezone_data['offset'] == $offset && $dst == $timezone_data['dst']){
					
				array_push($detected_timezone_ids, $timzone['value']);
				if ( ! $multiple)
					break;
			}
		}
	
		if (empty($detected_timezone_ids)){
	
			$detected_timezone_ids = array($default);
		}
	
		return $multiple ? $detected_timezone_ids : $detected_timezone_ids[0];
	}
	
	/**
	 * Get the current offset and dst for the given timezone id
	 *
	 * @param   string  $timezone_id
	 * @return  int
	 */
	public function get_timezone_data($timezone_id){
	
		$date = new DateTime("now");
		$date->setTimezone(timezone_open($timezone_id));
	
		return array(
				'offset' => $date->getOffset() / 3600,
				'dst' => intval(date_format($date, "I"))
		);
	}
		
}