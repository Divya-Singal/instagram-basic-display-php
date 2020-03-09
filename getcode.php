<?php

/*ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
*/

/* 1. Gets code using $_GET
   2. Gives the code to get the temporary access_token
   3. Exchange temporary access_token for permanent_token
   4. Write new access_token to file
*/

// 1. Get code using $_GET

$code = $_GET['code'];
$authorization_code = strtok($code, '#');

//2. Give the code to get the temporary access_token

$ins_detail = json_decode(file_get_contents('instadetail.json'), true);

$client_id = $ins_detail['instadetail']['client_id'];
$client_secret = $ins_detail['instadetail']['client_secret'];
$redirect_uri = $ins_detail['instadetail']['redirect_uri'];

$post = [
  'client_id' => $client_id,
  'client_secret' => $client_secret,
  'grant_type' => 'authorization_code',
  'redirect_uri' => $redirect_uri,
  'code' => $authorization_code
];
$ch = curl_init('https://api.instagram.com/oauth/access_token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$response = curl_exec($ch);
curl_close($ch);


$insta_details = json_decode($response, true);
//important because we need user_id
$ins_detail['instadetail']['user_id'] =  $insta_details['user_id'];
file_put_contents('instadetail.json', json_encode($ins_detail));


// 3. Exchange temporary access_token for permanent_token

$long_live_url = "https://graph.instagram.com/access_token";
$dataArray = array(
  "grant_type" => 'ig_exchange_token',
  "client_secret" => $client_secret,
  "access_token" => $insta_details['access_token']
);

$ch = curl_init();
$data = http_build_query($dataArray);
$getUrl = $long_live_url . "?" . $data;
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_URL, $getUrl);
curl_setopt($ch, CURLOPT_TIMEOUT, 80);

$long_live_response = curl_exec($ch);

if (curl_error($ch)) {
  $str = 'Getcode.php Request Error:' . curl_error($ch) . PHP_EOL;
  file_put_contents('log.txt', $str, FILE_APPEND | LOCK_EX);
} else {
  //4. Write new access_token to file
  file_put_contents('access_token.json', $long_live_response);
}

curl_close($ch);

/* Demo response

{
  "access_token":"{long-lived-user-access-token}",
  "token_type": "bearer",
  "expires_in": 5183944 // Number of seconds until token expires
}

*/
