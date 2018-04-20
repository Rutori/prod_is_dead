<?php
$url = ""; //your prod url
$multiplier = 1;
while (1) {
	$time = microtime(true);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, true);
	$status = curl_exec($ch);
	$timeres = microtime(true) - $time;
	$code = curl_getinfo($ch)['http_code'];
	echo "Code ".$code." for {$timeres} sec \r\n";
	if ($code != 200){ 
		telegram_send("{$url} seems dead from here, code {$code}");
		sleep(5*$multiplier);
		$multiplier = $multiplier*5;
	} else{
		$multiplier = 1;
	}
}

function telegram_send($message){
	$users = array(
		""//your chat ids
	);
	$message = urlencode($message);
	$api_key = ""; // your bot api key
	foreach ($users as $user) {
	$url = "https://api.telegram.org/bot".$api_key."/sendMessage?chat_id={$user}&text={$message}&parse_mode=Markdown";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5 ); //proxy via tor
	//curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1:9050");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'GET');
	curl_exec($ch);
	echo curl_error($ch);
	}

}