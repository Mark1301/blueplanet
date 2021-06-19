<?php

include "./library/simple_html_dom.php";

date_default_timezone_set("Asia/Kolkata");

$conn = new mysqli("localhost", "blueplanet", "Bluepl@net2021", "BLUE");

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `url` FROM `blueplanet_News` WHERE `inserted_at` = (SELECT MAX(`inserted_at`) FROM `blueplanet_News`)";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$row = $result->fetch_assoc();
if (isset($row["url"])) $lastURL = $row["url"];
else $lastURL = "";

$json = file_get_contents('https://www.inoreader.com/stream/user/1006597956/tag/Finance%20India/view/json');
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

    $html = str_get_html($data->items[$j]->content_html);
    $ret = $html->find('img', 0);
    $src = ltrim($ret->src, '\"');
    $src = rtrim($src, '\"');
    $insertDate = date("Y-m-d H:i:s");
    $newsDate = DateTime::createFromFormat("Y-m-d\TH:i:sP", $data->items[$j]->date_published);
    $newsDate->add(new DateInterval('PT5H30M'));
    $newsDateValue = $newsDate->format('Y-m-d H:i:s');

    $sql = "INSERT INTO `blueplanet_News` VALUES ( null, ?, ?, ?, ?, ? )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssss",
        $data->items[$j]->title,
        $data->items[$j]->url,
        $newsDateValue,
        $insertDate,
        $src
    );
    $stmt->execute();
    $stmt->close();
    sleep(2);
}

$conn->close();
