<?php

date_default_timezone_set("Asia/Kolkata");

$json = file_get_contents('http://quarkinternational.com/_functions/latestNews');
$obj = json_decode($json);
$latestId = $obj->items[0]->id;

$conn = new mysqli( "localhost", "news-admin", "JayMehta123#", "news_storage" );

$sql = "SELECT
			`finance_news`.`id`, 
			`finance_news`.`title`, 
			`finance_news`.`url`,
			`finance_news`.`date`,
			`finance_news`.`img_url`
			FROM
			`finance_news`
			WHERE 
			`finance_news`.`id` > ".$latestId." 
			ORDER BY `finance_news`.`id`";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result)>0) {
	while($row = mysqli_fetch_assoc($result)) {

		$dt = new DateTime($row["date"]);
		$tosub = new DateInterval('PT5H30M');
		$dt->sub($tosub);

		// API URL
		$url = 'http://quarkinternational.com/_functions/newsStore';

		// Create a new cURL resource
		$ch = curl_init($url);

		// Setup request to send json via POST
		$payload = json_encode(
			array(
				"title"=>$row['title'],
				"url"=> $row['url'],
				"date"=> $dt->format('m/d/Y H:i'),
				"imageUrl"=>$row["img_url"],
				"id"=> (int)$row['id']
			)
		);

		// Attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		// Set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

		// Return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Execute the POST request
		$response = curl_exec($ch);

		// Close cURL resource
		curl_close($ch);

		// print_r($response);

	}
}