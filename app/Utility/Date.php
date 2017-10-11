<?php

/**
 * Date utility class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('CakeTime', 'Utility');

/**
 * Date Helper class for easy use of date data.
 *
 * Manipulation of date data.
 * 
 * @author 		Greeshma Radhakrishnan
 * @package 	App.Utility
 * @category	Utility 
 */
class Date {

	/**
	 * Function to get the months list
	 * 
	 * @return array
	 */
	public static function getMonths() {
		return array(
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December'
		);
	}

	/**
	 * Function to get the years list
	 * 
	 * @return array
	 */
	public static function getYears() {
		$years = array();
		$currentYear = self::getCurrentYear();
		for ($i = $currentYear; $i >= 1900; $i--) {
			$years[$i] = $i;
		}

		return $years;
	}

	/**
	 * Function to get the days list
	 * 
	 * @return array
	 */
	public static function getDays() {
		$days = array();
		for ($i = 1; $i <= 31; $i++) {
			$days[$i] = $i;
		}

		return $days;
	}

	/**
	 * Function to get the days list from month and year
	 *
	 * @return array
	 */
	public static function getDaysOfMonth($month, $year) {
		$days = array();
		$num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for ($i = 1; $i <= $num; $i++) {
			$days[$i] = $i;
		}

		return $days;
	}

	/**
	 * Function to get the current year
	 * 
	 * @return string
	 */
	public static function getCurrentYear() {
		$currentYear = date('Y', time());
		return $currentYear;
	}

	/**
	 * Converts JS date string to MySQL format date string
	 * 
	 * @param string $JSDate (format: d/m/Y)
	 * @return string (format: Y-m-d)
	 */
	public static function JSDateToMySQL($JSDate) {
		$JSDate = trim($JSDate);
		list($month, $day, $year) = explode('/', $JSDate);
		$MySQLDate = join('-', array(
			$year,
			$month,
			$day,
				));
		return $MySQLDate;
	}

	/**
	 * Convers Mysql date string to JS format string
	 * 
	 * @param string $mySQLdate
	 * @param string $timezone
	 * @return string
	 */
	public static function MySqlDateTimeToJSDate($mySQLdate, $timezone) {
		return CakeTime::nice($mySQLdate, $timezone, '%m/%d/%Y');
	}

	/**
	 * Convers Mysql date string to JS format string
	 * 
	 * @param string $mySQLdate
	 * @param string $timezone
	 * @return string
	 */
	public static function MySqlDateTimeoJSTime($mySQLdate, $timezone) {
		return CakeTime::nice($mySQLdate, $timezone, '%l:%M %P');
	}

	/**
	 * Converts JS time string to MySQL format time string
	 * 
	 * Converts from 12 Hr format to 24 Hr format
	 * 
	 * @param string $JSTime (format: hh:mm tt)
	 * @return string (format: HH:mm)
	 */
	public static function JSTimeToMySQL($JSTime) {
		$JSTime = trim($JSTime);
		list($hrMin, $ampm) = explode(' ', $JSTime);
		list($hr, $min) = explode(':', $hrMin);
		if (intval($hr) === 12) {
			$hrIncrement = 0;
			$hr = ($ampm === 'am' || $ampm === 'AM') ? 0 : 12;
		} else {
			$hrIncrement = ($ampm === 'am' || $ampm === 'AM') ? 0 : 12;
		}

		$hr = $hr + $hrIncrement;
		$MySQLTime = $hr . ':' . $min;
		return $MySQLTime;
	}

	/**
	 * Returns app date format text
	 * 
	 * @return string
	 */
	public static function getDateFormatText() {
		$format = 'MM/DD/YYYY';
		return $format;
	}

	/**
	 * Returns current datetime in mysql format
	 * 
	 * @return string
	 */
	public static function getCurrentDateTime() {
		$mysqlDateTime = date('Y-m-d H:i:s');
		return $mysqlDateTime;
	}

	/**
	 * Returns either a relative or a formatted absolute date depending
	 * on the difference between the current time and given datetime.
	 * 
	 * Relative dates look something like this:
	 *
	 * - 2 days, 1 hour ago
	 * - 15 seconds ago
	 *
	 * Default date formatting is M j, Y, g:i a 
	 * e.g: Dec 25, 2013, 8:30 am 
	 *
	 * @param integer|string|DateTime $dateTime Datetime UNIX timestamp, strtotime() valid string or DateTime object
	 * @param string $timezone The user timezone the timestamp should be formatted in.
	 * @return string Relative time string.
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/time.html#TimeHelper::timeAgoInWords
	 */
	public static function timeAgoInWords($dateTime, $timezone) {
		$options = array(
			'timezone' => $timezone,
			'end' => '+1 week',
			'format' => 'M j, Y, g:i a',
			'absoluteString' => '%s',
			'accuracy' => array('day' => 'day')
		);
		return CakeTime::timeAgoInWords($dateTime, $options);
	}

	/**
	 * Function to get a ISO format representation of a date string
	 * 
	 * @param string $dateStr
	 * @return string
	 */
	public static function getISODate($dateStr) {
		$ISODate = date('c', strtotime($dateStr));
		return $ISODate;
	}

	/**
	 * Function to get the current date
	 * 
	 * @param string $timezone timezone
	 * @return string
	 */
	public static function getCurrentDate($timezone = null) {
		$format = 'Y-m-d';
		if (is_null($timezone)) {
			$date = date($format, time());
		} else {
			$date = CakeTime::format($format, date('Y-m-d H:i:s'), false, $timezone);
		}

		return $date;
	}

	/**
	 * Function to get the GMT timezone offset text of a timezone
	 * 
	 * eg:+5:30
	 * 
	 * @param string $zoneName
	 * @return string
	 */
	public static function getTimeZoneOffsetText($zoneName) {
		try {
			$dateTimeZone = new DateTimeZone($zoneName);
			$dateTime = new DateTime(null, $dateTimeZone);
			$offset = $dateTime->getOffset();
			$seconds = abs($offset);
			$minutes = $seconds / 60;
			$hr = intval($minutes / 60);
			$min = $minutes % 60;
			$offsetSign = ($offset < 0) ? '-' : '+';
			$offsetTime = $offsetSign . $hr . ':' . $min;
		} catch (Exception $e) {
			$offsetTime = null;
		}

		return $offsetTime;
	}

	public static function getCurrentUserTimezoneOffset($timezone) {
		//Setting user timezone offset
		$time = new DateTime('now', new DateTimeZone($timezone));

		$timezoneOffset = $time->format('P');
		$hourMinuteArray = explode(":", $timezoneOffset);
		$hours = $hourMinuteArray[0] * 60;
		if ($hourMinuteArray[0] < 0) {
			$timezoneOffsetInMinutes = (($hourMinuteArray[0]) * (-1) * 60) + $hourMinuteArray[1];
			$timezoneOffsetInMinutes = $timezoneOffsetInMinutes * (-1);
		} else {
			$timezoneOffsetInMinutes = (($hourMinuteArray[0]) * 60) + $hourMinuteArray[1];
		}
		$timezoneOffsetInMinutes = $timezoneOffsetInMinutes / (60);
		$timezoneOffset = $timezoneOffsetInMinutes;
		return $timezoneOffset;
	}

	/**
	 * Function to get age from date of birth
	 *  
	 * @param string $dob date of birth in 'Y-m-d' format
	 * @return int age
	 */
	public static function getAgeFromDOB($dob) {
		$then = DateTime::createFromFormat('Y-m-d', $dob);
		$diff = $then->diff(new DateTime());
		$age = $diff->format("%y");
		return $age;
	}

	/**
	 * Function to format a datetime string to US format date time string
	 * 
	 * @param string $dateTimeStr
	 * @param string $timezone
	 * @return string
	 */
	public static function getUSFormatDateTime($dateTimeStr, $timezone) {
		return CakeTime::format('M d, Y h:i a', $dateTimeStr, false, $timezone);
	}

	/**
	 * Function to format a datetime string to US format date string
	 * 
	 * @param string $dateTimeStr
	 * @param string $timezone
	 * @return string
	 */
	public static function getUSFormatDate($dateTimeStr, $timezone = null) {
		return CakeTime::format('M d, Y', $dateTimeStr, false, $timezone);
	}

	/**
	 * Function to get the years list for dob starting from 1994
	 * 
	 * @return array
	 */
	public static function getBirthYears() {
		$years = array();
		$currentYear = self::getCurrentYear();
		$defaultStartingYear = $currentYear;
		for ($i = $defaultStartingYear; $i >= 1900; $i--) {
			$years[$i] = $i;
		}

		return $years;
	}
	/*
	 * Function to get default starting year of DOB
	 */

	public static function getDOB_DefaultStartingYear() {
		$currentYear = self::getCurrentYear();
		return $currentYear - 20;
	}

	/**
	 * Function to convert a date in a timezone to server datetime
	 * 
	 * @param string $date
	 * @param string $timezone
	 * @return string 
	 */
	public static function convertDateToServerDateTime($date, $timezone, $format = 'Y-m-d H:i:s') {
		$serverCurrentDateTime = date('Y-m-d H:i:s');
		$timezoneCurrentTime = CakeTime::format('H:i:s', $serverCurrentDateTime, false, $timezone);
		$timezoneDateTime = $date . ' ' . $timezoneCurrentTime;
		$serverDateTime = CakeTime::toServer($timezoneDateTime, $timezone, $format);
		return $serverDateTime;
	}

	/**
	 * Converts JS time string to RRULE format time string
	 * 
	 * Converts from 12 Hr format to 24 Hr format
	 * 
	 * @param string $JSTime (format: hh:mm tt)
	 * @return string (format reference: http://www.ietf.org/rfc/rfc2445: 4.3.12)
	 */
	public static function JSTimeToRRuleTime($JSTime) {
		list($hr, $min, $ampm) = preg_split('/[: ]/', $JSTime);
		if (intval($hr) === 12) {
			$hrIncrement = 0;
			$hr = ($ampm === 'am' || $ampm === 'AM') ? 0 : 12;
		} else {
			$hrIncrement = ($ampm === 'am' || $ampm === 'AM') ? 0 : 12;
		}
		$hr = $hr + $hrIncrement;
		$hr = str_pad($hr, 2, 0, STR_PAD_LEFT);
		$sec = '00';
		$rruleTime = sprintf('%s%s%s', $hr, $min, $sec);
		return $rruleTime;
	}

	/**
	 * Parses an rrule string and converts it to an array
	 * 
	 * @param type $rrule
	 * @return array
	 */
	public function parseRRule($rrule) {
		$rrule = trim($rrule, ';');
		$parts = explode(';', $rrule);
		foreach ($parts as $part) {
			list($rule, $value) = explode('=', $part);
			$rule = strtoupper($rule);
			$value = strtoupper($value);
			$rruleArray[$rule] = $value;
		}
		if (isset($rruleArray['TIME'])) {
			$timeList = self::getTimeListFromRRuleValue($rruleArray['TIME']);
			$rruleArray['TIME_LIST'] = $timeList;
			$rruleArray['TIME_TEXT'] = self::getTimeListText($timeList);
		}
		if (isset($rruleArray['FREQ']) && isset($rruleArray['INTERVAL'])) {
			$frequency = $rruleArray['FREQ'];
			$interval = (int) $rruleArray['INTERVAL'];
			$frequencyValue = "{$frequency}:{$interval}";
			$frequencyText = 'Every ';
			if ($frequency === 'DAILY') {
				$intervalDays = $interval;
				if ($interval === 1) {
					$frequencyText.='day';
				} elseif ($interval === 2) {
					$frequencyText.='other day';
				}
			} elseif ($frequency === 'WEEKLY') {
				$intervalDays = $interval * 7;
				if ($interval === 1) {
					$frequencyText.='week';
				} elseif ($interval === 2) {
					$frequencyText.='2 weeks';
				}
			}
			$rruleArray['FREQUENCY_TEXT'] = $frequencyText;
			$rruleArray['FREQUENCY_VALUE'] = $frequencyValue;
			$rruleArray['INTERVAL_DAYS'] = $intervalDays;
		}
		return $rruleArray;
	}

	/**
	 * Function to get time list from RRULE TIME value
	 * 
	 * @param string $timeRuleValue
	 * @return array
	 */
	public static function getTimeListFromRRuleValue($timeRuleValue) {
		$times = explode(',', $timeRuleValue);
		foreach ($times as $time) {
			list($hr, $min, $sec) = str_split($time, 2);
			$date = sprintf('%s:%s:%s', $hr, $min, $sec);
			$timeStr = date('h:i a', strtotime($date));
			$timeList[] = $timeStr;
		}
		return $timeList;
	}

	/**
	 * Function to get time list text from time list
	 * 
	 * @param array $timeList
	 * @return string
	 */
	public static function getTimeListText($timeList) {
		$timeCount = count($timeList);
		$text = $timeList[0];
		if ($timeCount > 1) {
			$lastTimeIndex = $timeCount - 1;
			$lastTime = $timeList[$lastTimeIndex];
			unset($timeList[0]);
			unset($timeList[$lastTimeIndex]);
			if (!empty($timeList)) {
				$middleTimes = join(', ', $timeList);
				$text.=', ' . $middleTimes;
			}
			$text.=', and ' . $lastTime;
		}
		return $text;
	}
}