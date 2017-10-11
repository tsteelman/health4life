<?php

/**
 * Script Locking Class
 *
 * Component for handling lock/unlock script files
 *
 * @package		App.Controller.Component	
 * @category	Component
 * @author		Greeshma
 */
App::uses('Component', 'Controller');

class ScriptLockComponent extends Component {

	/**
	 * Function to check if a job is running
	 * 
	 * @param string $jobName
	 * @return bool
	 */
	public static function isJobRunning($jobName) {
		$lockFile = self::__getLockFilePath($jobName);
		if (file_exists($lockFile)) {
			error_log($jobName . ' is locked.');
			$pid = file_get_contents($lockFile);
			if (self::__isProcessRunning($pid)) {
				error_log($jobName . ' is already running.');
				return true;
			} else {
				error_log($jobName . "== Previous job died abruptly...");
			}
		}
		return false;
	}

	/**
	 * Function to lock a job
	 * 
	 * @param string $jobName
	 * @return string process id
	 */
	public static function lock($jobName) {
		$lockFile = self::__getLockFilePath($jobName);
		$pid = getmypid();
		file_put_contents($lockFile, $pid);
		return $pid;
	}

	/**
	 * Function to unlock a job
	 * 
	 * @param string $jobName
	 * @return bool
	 */
	public static function unlock($jobName) {
		$lockFile = self::__getLockFilePath($jobName);

		if (file_exists($lockFile)) {
			unlink($lockFile);
		}
		return true;
	}

	/**
	 * Function to get the lock file path for a job
	 * 
	 * @param string $jobName job name
	 * @return string
	 */
	private static function __getLockFilePath($jobName) {
		$lockDir = WWW_ROOT . 'locks';
		$lockSuffix = '.lock';
		$lockFilePath = $lockDir . DS . $jobName . $lockSuffix;
		return $lockFilePath;
	}

	/**
	 * Function to check if a process is running
	 * 
	 * @param int $pid process id
	 * @return bool
	 */
	private static function __isProcessRunning($pid) {
		$pids = explode(PHP_EOL, `ps -e | awk '{print $1}'`);
		return (in_array($pid, $pids)) ? true : false;
	}
}