<?php
	require_once __DIR__.'/vendor/autoload.php';
	date_default_timezone_set('Asia/Tokyo');

	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/keys');
	$dotenv->load();
	
	$key_path = __DIR__.'/keys/key.json';
	$client = new Google_Client();
	$client->setApplicationName('カレンダー取得テスト');
	$client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
	$client->setAuthConfig($key_path);
	$service = new Google_Service_Calendar($client);
	
	$timestamp = strtotime(date('Y/m/d'));
	$calendar_id = $_ENV['calendar_id'];
	$opt_params = [
	    'maxResults' => 10,
		'orderBy' => 'startTime',
		'singleEvents' => true,
		'timeMin' => date('c',strtotime(date('Y/m/d'))),
		'timeMax' => date('c',strtotime(date('Y/m/d',strtotime('+1 day')))),
	];
	$results = $service->events->listEvents($calendar_id, $opt_params);
	$events = $results->getItems();
	$message = date('Y/m/d')."の予定です．\n";
	if(empty($events) === true){
		$message = $message."今日のイベントはありません\n";
	}else{
		foreach($events as $event){
			$message = $message.$event->getSummary()." ";
			$start = $event->start->dateTime;
			if(empty($start)){
				$message = $message.date('Y/m/d',strtotime($event->start->date));	
			}else{
				$message = $message.date('H:i',strtotime($start));	
			}
			$end = $event->end->dateTime;
			if(empty($end)){
				$message = $message."～".date('Y/m/d',strtotime($event->end->date));	
			}else{
				$message = $message."～".date('H:i',strtotime($end));
			}
			$message = $message."\n";
		}
	}
	$client_slack = new sendSlacker\SendSlacker($_ENV['slack_incoming_webhooks']);
	$client_slack->sendText($message);
