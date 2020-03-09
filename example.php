<?php

//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$access_token = json_decode(file_get_contents('access_token.json'), true);
$user_id = json_decode(file_get_contents('instadetail.json'), true);

$get_media_url = "https://graph.instagram.com/" . $user_id['instadetail']['user_id'] . "/media";
$dataArray = array(
  "access_token" => $access_token['access_token']
);

$ch = curl_init();
$data = http_build_query($dataArray);
$getUrl = $get_media_url . "?" . $data;
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_URL, $getUrl);
curl_setopt($ch, CURLOPT_TIMEOUT, 80);

$media_response = curl_exec($ch);

if (curl_error($ch)) {
  $str = 'Getcode.php Request Error:' . curl_error($ch) . PHP_EOL;
  file_put_contents('log.txt', $str, FILE_APPEND | LOCK_EX);
} else {
  //success
}

curl_close($ch);


$media_parent_array = json_decode($media_response, true);

foreach ($media_parent_array['data'] as $k => $v) {
  echo $v['id'];
  echo "<br>";
  $get_media_desc = "https://graph.instagram.com/" . $v['id'] . "";
  $dataArray = array(
    "fields" => "id,media_type,media_url,username,timestamp",
    "access_token" => $access_token['access_token']
  );


  $ch = curl_init();
  $data = http_build_query($dataArray);
  $getUrl = $get_media_desc . "?" . $data;
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_URL, $getUrl);
  curl_setopt($ch, CURLOPT_TIMEOUT, 80);

  $media_response = curl_exec($ch);
  echo $media_response;
  break;
}
