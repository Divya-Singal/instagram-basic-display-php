<?php
   /*
   1. get new access_token 
   2. Write it to file access_token
   */

  $ins_detail = json_decode(file_get_contents('instadetail.json'), true);
  $access_token = json_decode(file_get_contents('access_token.json'), true);

  $refresh_url = "https://graph.instagram.com/refresh_access_token";
  $dataArray = array("grant_type"=>'ig_refresh_token',
                      "access_token" => $access_token['access_token']
                  );
  
  $ch = curl_init();
  $data = http_build_query($dataArray);
  $getUrl = $refresh_url."?".$data;
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_URL, $getUrl);
  curl_setopt($ch, CURLOPT_TIMEOUT, 80);
   
  $refreshed_json = curl_exec($ch);
   
  $str = ' # '. date('Y-m-d h:i:s').' | ';
  if(curl_error($ch)){
      $str .= 'Request Error:' . curl_error($ch) .PHP_EOL;
  }
  else
  {
      $str .= $refreshed_json.PHP_EOL ;
      file_put_contents('access_token.json', $refreshed_json);
  }
   
  curl_close($ch);
  file_put_contents('log.txt', $str, FILE_APPEND | LOCK_EX);

?>