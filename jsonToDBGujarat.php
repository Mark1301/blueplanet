<?php

date_default_timezone_set("Asia/Kolkata");

$conn = new mysqli("localhost", "news-admin", "JayMehta123#", "news_storage");

if ($conn->connect_error) {

	die("Connection failed: " . $conn->connect_error);
}

$sourceArr = [
	"www.akilanews.com" => "Akila News",
	"www.bbc.com" => "BBC Gujarati",
	"www.divyabhaskar.co.in" => "Divya Bhaskar",
	"gujaratmitra.in" => "Gujarat Mitra",
	"www.iamgujarat.com" => "I am Gujarat",
	"www.navgujaratsamay.com" => "Navgujarat Samay",
	"sandesh.com" => "Sandesh",
	"www.vtvgujarati.com" => "VTV Gujarati"
];

$sql = "SELECT `url` FROM `gujarat_news` WHERE `inserted_at` = (SELECT MAX(`inserted_at`) FROM `gujarat_news`)";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$row = $result->fetch_assoc();
if (isset($row["url"])) $lastURL = $row["url"];
else $lastURL = "";

$json = file_get_contents('https://www.inoreader.com/stream/user/1006597956/tag/Gujarat%20News/view/json');
$data = json_decode($json);

$i = 0;

foreach ($data->items as $item) {
	if ($item->url == $lastURL) {
		break;
	} else {
		$i++;
	}
}

echo ($i);

for ($j = $i - 1; $j >= 0; $j--) {

	$insertDate = date("Y-m-d H:i:s");
	$newsDate = DateTime::createFromFormat("Y-m-d\TH:i:sP", $data->items[$j]->date_published);
	$newsDate->add(new DateInterval('PT5H30M'));
	$newsDateValue = $newsDate->format('Y-m-d H:i:s');
	$source = $sourceArr[parse_url($data->items[$j]->url, PHP_URL_HOST)];

	$sql = "INSERT INTO `gujarat_news` VALUES ( null, ?, ?, ?, ?, ? )";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param(
		"sssss",
		$data->items[$j]->title,
		$data->items[$j]->url,
		$newsDateValue,
		$source,
		$insertDate
	);
	$stmt->execute();
	$stmt->close();
	sleep(2);
}

$conn->close();
