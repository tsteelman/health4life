<?php

/**
 * Cron Manager Helper file
 *
 * Helper file to run multiple cron jobs in parallel
 *
 * @link     http://phplens.com/phpeverywhere/?q=node/view/254 - parallel processing
 * @link     http://www.binarytides.com/php-manage-multiple-cronjobs-with-a-single-crontab-entry/
 * @link     http://www.binarytides.com/php-check-if-a-timestamp-matches-a-given-cron-schedule/
 * @link     http://www.electrictoolbox.com/check-php-script-already-running/
 * @package  App.Controller.Component
 * @category Component
 * @author   Ajay Arjunan <ajay@qburst.com>
 */
App::uses('Component', 'Controller');
App::uses('ScriptLockComponent', 'Controller/Component');

class CronHelperComponent extends Component {

	/**
	 * Function to test if a timestamp matches a cron format or not
	 *
	 * @param timestamp $time
	 * @param string $cron cron format string
	 * @return boolean
	 */
	function isTimeCron($time, $cron) {

		$cron_parts = explode(' ', $cron);
		if (count($cron_parts) != 5) {
			return false;
		}

		list($min, $hour, $day, $mon, $week) = explode(' ', $cron);

		$to_check = array('min' => 'i', 'hour' => 'G', 'day' => 'j', 'mon' => 'n', 'week' => 'w');

		$ranges = array(
			'min' => '0-59',
			'hour' => '0-23',
			'day' => '1-31',
			'mon' => '1-12',
			'week' => '0-6',
		);

		foreach ($to_check as $part => $c) {

			$val = $$part;
			$values = array();

			/*
			  For patters like 0-23/2
			 */
			if (strpos($val, '/') !== false) {

				//Get the range and step
				list($range, $steps) = explode('/', $val);

				//Now get the start and stop
				if ($range == '*') {
					$range = $ranges[$part];
				}
				list($start, $stop) = explode('-', $range);

				for ($i = $start; $i <= $stop; $i = $i + $steps) {

					$values[] = $i;
				}
			}
			/*
			  For patters like :
			  2
			  2,5,8
			  2-23
			 */
			else {

				$k = explode(',', $val);

				foreach ($k as $v) {

					if (strpos($v, '-') !== false) {

						list($start, $stop) = explode('-', $v);

						for ($i = $start; $i <= $stop; $i++) {
							$values[] = $i;
						}
					} else {
						$values[] = $v;
					}
				}
			}

			if (!in_array(date($c, $time), $values) and (strval($val) != '*')) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Function to open a socket connection to the cron job url
	 *
	 * @param string $server
	 * @param string $url
	 * @param int $port
	 * @param int $conn_timeout
	 * @param int $rw_timeout
	 * @return mixed
	 */
	function jobStartAsync($server, $url, $port = 80, $auth = array(), $conn_timeout = 30, $rw_timeout = 86400) {
		$errno = '';
		$errstr = '';

		set_time_limit(0);

		$fp = fsockopen($server, $port, $errno, $errstr, $conn_timeout);
		if (!$fp) {
			echo "$errstr ($errno)<br />\n";
			return false;
		}
		$out = "GET $url HTTP/1.1\r\n";
		$out .= "Host: $server\r\n";
		$out .= "Connection: Close\r\n";
		if (!empty($auth)) {
			$out .= "Authorization: Basic " . base64_encode($auth['username'] . ":" . $auth['password']) . "\r\n";
		}
		$out .= "\r\n";

		stream_set_blocking($fp, false);
		stream_set_timeout($fp, $rw_timeout);
		fwrite($fp, $out);

		return $fp;
	}

	/**
	 * Function to read chunks from a file pointer
	 *
	 * returns false if HTTP disconnect (EOF),
	 * or a string (could be empty string) if still connected
	 *
	 * @param boolean $fp file pointer
	 * @return mixed
	 */
	function jobPollasync(&$fp) {

		if ($fp === false)
			return false;

		if (feof($fp)) {
			fclose($fp);
			$fp = false;
			return false;
		}

		return fread($fp, 10000);
	}

	/**
	 * Function to create a cron job entry array
	 *
	 * @param string $cronExpr cron expression
	 * @param string $jobName  cron job name
	 * @return array
	 */
	function cronJob($cronExpr, $jobName) {

		$cronJob['expr'] = $cronExpr;
		$cronJob['name'] = $jobName;
		$cronJob['url'] = "/api/{$jobName}";
		return $cronJob;
	}

	/**
	 * Function to run multiple cron jobs in parallel
	 *
	 * @param array $cronjobs list of cron jobs
	 * @param string $server  server name where the scripts exist
	 */
	function runCronJobs($cronjobs, $server, $port, $auth) {

		$time = time();
		foreach ($cronjobs as $key => $cronjob) {
			if (self::isTimeCron($time, $cronjob['expr'])) {
				if (!ScriptLockComponent::isJobRunning($cronjob['name'])) {
					echo "Starting job: {$cronjob['name']}" . PHP_EOL;
					$cronjobs[$key]['fp'] = self::jobStartAsync($server, $cronjob['url'], $port, $auth);
				}
			}
		}

		while (true) {

			sleep(1);

			$running_process_count = 0;
			foreach ($cronjobs as $key => $cronjob) {
				if (isset($cronjob['fp'])) {
					$chunk = self::jobPollasync($cronjob['fp']);
					if ($chunk !== false) {
						echo self::extractContent($chunk);
						$running_process_count++;
					} else {
						ScriptLockComponent::unlock($cronjob['name']);
						unset($cronjobs[$key]['fp']);
					}
				}
			}

			if ($running_process_count === 0) {
				break;
			}

			flush();
			@ob_flush();
		}

		echo PHP_EOL . "---------------------------------------------------------------" . PHP_EOL;
		echo PHP_EOL . 'Jobs Complete' . PHP_EOL;
		echo PHP_EOL . "---------------------------------------------------------------" . PHP_EOL;
	}

	/**
	 * Function to extract content from a chunk of data excluding header information
	 *
	 * @param string $chunk
	 * @return string
	 */
	function extractContent($chunk) {
		$chunk = str_replace('HTTP/1.1 200 OK', '', $chunk);
		$chunk_arr = explode("\n", $chunk);
		$header_params[] = 'Date';
		$header_params[] = 'Server';
		$header_params[] = 'X-Powered-By';
		$header_params[] = 'Set-Cookie';
		$header_params[] = 'Vary';
		$header_params[] = 'Connection';
		$header_params[] = 'Transfer-Encoding';
		$header_params[] = 'Content-Type';
		$header_params[] = 'Last-Modified';
		$header_params[] = 'Expires';
		$header_params[] = 'Cache-Control';
		$header_params[] = 'Pragma';
		$header_params[] = 'Content-Length';

		foreach ($chunk_arr as $chunk_row) {
			foreach ($header_params as $header_param) {
				$pattern = '/^' . $header_param . ': ([^\r\n]*)[\r\n]*$/';
				$chunk_row = preg_replace($pattern, '', $chunk_row);
			}
			if ($chunk_row !== '') {
				$content_arr[] = $chunk_row;
			}
		}

		if (!empty($content_arr)) {
			$content = join("\n", $content_arr);
		} else {
			$content = '';
		}

		return $content;
	}
}