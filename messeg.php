 <?php

require ('config.php');
define('BOT_TOKEN', '$tok');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $payload = json_encode($parameters);
  header('Content-Type: application/json');
  header('Content-Length: '.strlen($payload));
  echo $payload;

  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successful: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POST, true);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}








/* начинается метод*/

function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  $location= $message['location']['latitude'];
  $location1= $message['location']['longitude'];
  $user=   $message['from']['id'];
  $name_user=   $message['from']['username'];
  $name_user=   $message['from']['username'];
  $text = $message['text'];
  $staus = 0;
  $id_gtup = -1001296267077;
  
  

  
   
  
  
  if ($chat_id == $id_gtup) {
	  
	  

 if ($text == "/menu") {
	 
	 
	 $data =
[
  "chat_id" => "$id_gtup",
  "text" => "Ситуация",
  "reply_markup" => [
  "one_time_keyboard"=>true,
  "resize_keyboard"=>True,
    "keyboard" => [
      [
	  ["text"=>"ДТП"],
	  ["text"=>"ДПС"],
	  ["text"=>"Камера"]
    ], 
	[
	  ["text"=>"Пробка"],
	  ["text"=>"ДПС+Пристовы"],
	],
	[ ["text"=>"Тормозят всех подряд"]]
  ] ]];
    $data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
    $curl = curl_init('https://api.telegram.org/bot$tok/sendMessage?');
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    // Принимаем в виде массива. (false - в виде объекта)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Content-Length: ' . strlen($data_string))
    );
    $result = curl_exec($curl);
    curl_close($curl);
    echo '<pre>';
    print_r($result);
	 
	}

  
  if ($text == "ДПС") {$staus = 1;}
  if ($text == "ДТП") {$staus = 1;}  
  if ($text == "Камера") {$staus = 1;}
  if ($text == "Пробка") {$staus = 1;}
  if ($text == "ДПС+Пристовы") {$staus = 1;}
  if ($text == "Тормозят всех подряд") {$staus = 1;}  
       
  }
  
  
  
  
  if ($staus == 1) 
  {
	  $data =
[
  "chat_id" => "$user",
  "text" => "Поделиться Геолокацией $text",
  "reply_markup" => [
  "one_time_keyboard"=>true,
  "resize_keyboard"=>True,
    "keyboard" => [
      [
	  ["text"=>"Поделиться Геолокацией $text", "request_location" => True]
    ] 
  ] ]];
    $data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
    $curl = curl_init('https://api.telegram.org/bot$tok/sendMessage?');
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    // Принимаем в виде массива. (false - в виде объекта)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Content-Length: ' . strlen($data_string))
    );
    $result = curl_exec($curl);
    curl_close($curl);
    echo '<pre>';
    print_r($result);
    $dec = (Array)json_decode($result);
    $uesererror = $dec["error_code"];
	if ($uesererror == 403) {apiRequestJson("sendMessage", array('chat_id' => $id_gtup, "text" => "У вас не активирован наш бот! Пожалуйста запустите Бота @gdedps67_bot", 
	'reply_markup' => $keyboard = array(
"inline_keyboard" => array(array(array(
"text" => "Запустить",
"url" => "https://t.me/gdedps67_bot?start=start"
)))
)));}

$decc = (Array)json_decode($result);
    $uesergeo = $decc["ok"];
	if ($uesergeo == "ok") {apiRequestJson("sendMessage", array('chat_id' => $id_gtup, "text" => "Пожалуйста поделитесь геопозицией", 
	'reply_markup' => $keyboard = array(
"inline_keyboard" => array(array(array(
"text" => "Поделиться",
"url" => "https://t.me/gdedps67_bot"
)))
)));}
	
	
	$staus = 0;
	$fd = fopen("$user.txt", 'w') or die("не удалось создать файл");
$str = "$text";
fwrite($fd, $str);
fclose($fd);
  }
  
  
  
  
 
  if ($chat_id != $id_gtup) {
  if (isset($message['text'])) {
    // если пришёл какой либо ответ
    // создаем переменную text
    apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "Пожалуйста просто поделитесь локацией, чтобы мы могли передать ее в группу"));	
  } 



  if (isset($message['location'])) {
	  $texts = file_get_contents("https://egorhay1.ddns.net/telegram_api/$chat_id.txt");
	  unlink("$chat_id.txt");
    // если пришёл какой либо ответ
    // создаем переменную text
    //apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "chat_id:$chat_id cord:$location $location1"));
	
	$stauss == 0;
	
  if ($texts == "ДПС") {$stauss = 1;}
  if ($texts == "ДТП") {$stauss = 1;}  
  if ($texts == "Камера") {$stauss = 1;}
  if ($texts == "Пробка") {$stauss = 1;}
  if ($texts == "ДПС+Пристовы") {$stauss = 1;}
  if ($texts == "Тормозят всех подряд") {$staus = 1;}  
	
	
	
	if ($stauss == 1) {

	apiRequestJson("sendMessage", array('chat_id' => $id_gtup, "text" => "$name_user сообщяет: $texts"));
	apiRequestJson("sendLocation", array('chat_id' => $id_gtup, "latitude" => "$location", "longitude" => "$location1"));
	apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "Спасибо, ваша локация размещена",
	 'reply_markup' => $keyboard = array(
"inline_keyboard" => array(array(array(
"text" => "Назад",
"url" => "https://t.me/gdeDPSsmolensk"
)))
))); 	} 




if ($stauss != 1) { 	 $data =
[
  "chat_id" => "$user",
  "text" => "Вы не выбрали ситуацию",
  "reply_markup" => [
  "one_time_keyboard"=>true,
  "resize_keyboard"=>True,
    "keyboard" => [
      [
	  ["text"=>"ДТП"],
	  ["text"=>"ДПС"],
	  ["text"=>"Камера"]
    ], 
	[
	  ["text"=>"Пробка"],
	  ["text"=>"ДПС+Пристовы"],
	],
	[ ["text"=>"Тормозят всех подряд"]]
  ] ]];
    $data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
    $curl = curl_init('https://api.telegram.org/bot$tok/sendMessage?');
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    // Принимаем в виде массива. (false - в виде объекта)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Content-Length: ' . strlen($data_string))
    );
    $result = curl_exec($curl);
    curl_close($curl);
    echo '<pre>';
    print_r($result);
}
  }	
	
  if ($text == "ДПС") {$stauss = 2;}
  if ($text == "ДТП") {$stauss = 2;}  
  if ($text == "Камера") {$stauss = 2;}
  if ($text == "Пробка") {$stauss = 2;}
  if ($text == "ДПС+Пристовы") {$stauss = 2;}
  if ($text == "Тормозят всех подряд") {$stauss = 2;} 
  
  if ($stauss == 2) 
  {
	  $data =
[
  "chat_id" => "$user",
  "text" => "Поделиться Геолокацией $text",
  "reply_markup" => [
  "one_time_keyboard"=>true,
  "resize_keyboard"=>True,
    "keyboard" => [
      [
	  ["text"=>"Поделиться Геолокацией $text", "request_location" => True]
    ] 
  ] ]];
    $data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
    $curl = curl_init('https://api.telegram.org/bot$tok/sendMessage?');
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    // Принимаем в виде массива. (false - в виде объекта)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Content-Length: ' . strlen($data_string))
    );
    $result = curl_exec($curl);
    curl_close($curl);
    echo '<pre>';
    print_r($result);

$fdd = fopen("$user.txt", 'w') or die("не удалось создать файл");
$strr = "$text";
fwrite($fdd, $strr);
fclose($fd);
  }
	

}
}


/*

	if (isset($text)) {
         apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "Result:$chat_id+$text", 'reply_markup' => array(
        'keyboard' => array(array('Hello', 'Hi')),
        'one_time_keyboard' => true,
        'resize_keyboard' => true)));
    } else if ($text === "Hello" || $text === "Hi") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
    } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'Cool'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  }
}
*/




$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"])) {
  processMessage($update["message"]);
}