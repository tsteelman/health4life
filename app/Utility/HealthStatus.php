<?php

/**
 * Health Status utility class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * HealthStatus class for utility functions to manipulate health status.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	App.Utility
 * @category	Utility
 */
class HealthStatus {
	
	/**
	 * Health status values
	 */
	const STATUS_VERY_GOOD = 5;
	const STATUS_GOOD = 4;
	const STATUS_NEUTRAL = 3;
	const STATUS_BAD = 2;
	const STATUS_VERY_BAD = 1;

	/**
	 * Function to get the list of health statuses
	 * 
	 * @return array
	 */
	public static function getHealthStatusList() {
		$healthStatusList = array(
			array
				(
				'text' => 'Very good',
				'value' => self::STATUS_VERY_GOOD,
				'image' => 'very_good_smiley.png',
				'class' => 'health_vg'
			),
			array(
				'text' => 'Good',
				'value' => self::STATUS_GOOD,
				'image' => 'good_smiley.png',
				'class' => 'health_g',
			),
			array(
				'text' => 'Neutral',
				'value' => self::STATUS_NEUTRAL,
				'image' => 'neutral_smiley.png',
				'class' => 'health_n'
			),
			array(
				'text' => 'Bad',
				'value' => self::STATUS_BAD,
				'image' => 'bad_smiley.png',
				'class' => 'health_b'
			),
			array(
				'text' => 'Very bad',
				'value' => self::STATUS_VERY_BAD,
				'image' => 'very_bad_smiley.png',
				'class' => 'health_vb'
			)
		);
		return $healthStatusList;
	}

	/**
	 * Returns the smiley class for different health statuses
	 * 
	 * @param int $feelingStatus
	 * @return string
	 */
	public static function getFeelingSmileyClass($healthStatus) {
		$feelingClass = null;
		switch ($healthStatus) {
			case self::STATUS_VERY_BAD:
				$feelingClass = 'feeling_very_bad';
				break;
			case self::STATUS_BAD:
				$feelingClass = 'feeling_bad';
				break;
			case self::STATUS_NEUTRAL:
				$feelingClass = 'feeling_neutral';
				break;
			case self::STATUS_GOOD:
				$feelingClass = 'feeling_good';
				break;
			case self::STATUS_VERY_GOOD:
				$feelingClass = 'feeling_very_good';
				break;
		}

		return $feelingClass;
	}

	/**
	 * Returns the health status text for different health statuses
	 * 
	 * @param int $healthStatus
	 * @return string
	 */
	public static function getHealthStatusText($healthStatus) {
		switch ($healthStatus) {
			case self::STATUS_VERY_BAD:
				$healthStatusText = 'very bad';
				break;
			case self::STATUS_BAD:
				$healthStatusText = 'bad';
				break;
			case self::STATUS_NEUTRAL:
				$healthStatusText = 'neutral';
				break;
			case self::STATUS_GOOD:
				$healthStatusText = 'good';
				break;
			case self::STATUS_VERY_GOOD:
				$healthStatusText = 'very good';
				break;
			default :
				$healthStatusText = '';
		}

		return $healthStatusText;
	}
}