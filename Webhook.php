<?php

require ('config.php');
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.telegram.org/bot$tok/setwebhook?url=https://egorhay1.ddns.net/telegram_api/messeg.php",
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_CAINFO => "C:\Mega Parser\server\home\localhost\www\Telegram\dhparam.pem",
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
?>