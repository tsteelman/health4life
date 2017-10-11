<?php

/**
 * Cron manager file
 *
 * Script to run multiple cron jobs in parallel
 *
 * Cron job entry format : cron_job(cron expression, cron url, script lock file name)
 * Cron expression format: *    *    *      *       *
 * 						   min  hr   day    month   week day
 * @example http://en.wikipedia.org/wiki/Cron
 * @package Console.Command
 * @author  Ajay Arjunan <ajay@qburst.com>
 */
App::uses('CronHelperComponent', 'Controller/Component');

class CronManagerShell extends AppShell {

	public function main() {
		/*
		 * read base url from core.php
		 */ 

		$URL = Configure::read("App.fullBaseUrl");

		if ($URL === 'http://patients4life.qburst.com') {
			$auth = array('username' => 'HealthQuest4life', 'password' => 'H4L1219');
		} else if ($URL === 'http://qa.patients4life.qburst.com') {
			$auth = array('username' => 'talA49Ka', 'password' => 'Kava17obIs');
		} else if ($URL === 'http://patients4life.org') {
			$auth = array('username' => 'HealthQuest4life', 'password' => 'H4L1219');
		} else if ($URL === 'https://www.aids4life.com') {
			$auth = array('username' => 'olive', 'password' => 'olive123');
		} else if ($URL === 'http://patients-4life.com') {
			$auth = array();
		} else if ($URL === 'http://crohns4life.com') {
			$auth = array();
		} else if ($URL === 'http://scleroderma4life.com') {
			$auth = array();
		} else if ($URL === 'https://www.health4life.com') {
			$auth = array('username' => 'olive', 'password' => 'olive123');
		} else {
			$auth = array();
		}

		$SERVER = preg_replace('#^https?://#', '', $URL); // Remove the http:// string
		if (isset($_SERVER['SERVER_PORT'])) {
			$PORT = $_SERVER['SERVER_PORT'];
		} else {
			$PORT = 80;
		}

		/*
		 * Set the default timezone as the configured time
		 */
		$timezone_identifier = Configure::read("App.DEFAULT_TIMEZONE");
		date_default_timezone_set($timezone_identifier);

		$cronJobs = array();
		$cronJobs[] = CronHelperComponent::cronJob('0 23 * * *', 'dailyReportToEventCreator');
		$cronJobs[] = CronHelperComponent::cronJob('* * * * *', 'updateVideoStatus');
		$cronJobs[] = CronHelperComponent::cronJob('* * * * *', 'processEmailQueue');
		$cronJobs[] = CronHelperComponent::cronJob('* * * * *', 'processNewsletterQueue');
		$cronJobs[] = CronHelperComponent::cronJob('0 * * * *', 'sendHealthStatusUpdateReminders');
		$cronJobs[] = CronHelperComponent::cronJob('0 * * * *', 'sendHealthStatusRemindersNonPatients');
		$cronJobs[] = CronHelperComponent::cronJob('0 12 * * *', 'send_remainderMails');
		$cronJobs[] = CronHelperComponent::cronJob('0 12 * * *', 'send_teamRemainderMails');
		$cronJobs[] = CronHelperComponent::cronJob('*/5 * * * *', 'sendEventReminders');
		$cronJobs[] = CronHelperComponent::cronJob('0 * * * *', 'sendCareCalendarReminders');
		$cronJobs[] = CronHelperComponent::cronJob('0 * * * *', 'sendCareCalendarDailyDigest');
		$cronJobs[] = CronHelperComponent::cronJob('0 * * * *', 'sendAccountActivationReminders');
		$cronJobs[] = CronHelperComponent::cronJob('0 12 * * *', 'sendFriendRecommendationEmails');
		$cronJobs[] = CronHelperComponent::cronJob('*/30 * * * *', 'sendMedicationReminders');
		
		echo PHP_EOL . PHP_EOL . 'Cron job run at ' . date('d-m-y H:i:s') . PHP_EOL . PHP_EOL;
		echo "---------------------------------------------------------------" . PHP_EOL;

		CronHelperComponent::runCronJobs($cronJobs, $SERVER, $PORT, $auth);
	}
}