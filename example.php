<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require 'TogglClient.php';

$apiKey = 'YOUR-API-KEY-HERE';
$togglClient = new TogglClient($apiKey);

try {
	$profile = $togglClient->getProfile();
	echo date('d.m.Y - H:i:s', $togglClient->getResponseTimestamp());
	$workspace = $profile->workspaces[0];
	$workspaceId = $workspace->id;

	echo '<pre>';
	var_dump($profile);
	echo '</pre>';

	$extendProfile = $togglClient->getExtendedProfile();
	echo '<pre>';
	var_dump($extendProfile);
	echo '</pre>';

	$options = array(
		'user_agent' => 'PHP toggl API example',
		'workspace_id' => $workspaceId,
		'since' => '2014-09-01',
		'until' => '2014-09-23',
		//user_agent: string, required, the name of your application or your email address so we can get in touch in case you're doing something wrong.
		//workspace_id: integer, required. The workspace which data you want to access.
		//since: string, ISO 8601 date (YYYY-MM-DD), by default until - 6 days.
		//until: string, ISO 8601 date (YYYY-MM-DD), by default today
		//billable: possible values: yes/no/both, default both
		//client_ids: client ids separated by a comma, 0 if you want to filter out time entries without a client
		//project_ids: project ids separated by a comma, 0 if you want to filter out time entries without a project
		//user_ids: user ids separated by a comma
		//tag_ids: tag ids separated by a comma, 0 if you want to filter out time entries without a tag
		//task_ids: task ids separated by a comma, 0 if you want to filter out time entries without a task
		//time_entry_ids: time entry ids separated by a comma
		//description: string, time entry description
		//without_description: true/false, filters out the time entries which do not have a description ('(no description)')
		//order_field:
		//date/description/duration/user in detailed reports
		// title/duration/amount in summary reports
		// title/day1/day2/day3/day4/day5/day6/day7/week_total in weekly report
		// order_desc: on/off, on for descending and off for ascending order
		//distinct_rates: on/off, default off
		//rounding: on/off, default off, rounds time according to workspace settings
		//display_hours: decimal/minutes, display hours with minutes or as a decimal number, default minutes
	);
	$report = $togglClient->getSummaryReport($options);

echo '<pre>';
var_dump($report);
echo '</pre>';

} catch (Exception $e) {
	echo $e->getMessage();
}

echo 'Done';