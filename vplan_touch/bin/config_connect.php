<?php
$school_config=json_decode(file_get_contents("config/school_config.json"),true);
if(strlen($school_config["conf"]["conf_server_path"])>7) {
    $school_config=json_decode(file_get_contents("config/cached_school_config.json"),true);
}
/*
$json=file_get_contents("config/school_config.json");
if($school_config["conf"]["conf_server_path"]!=false) {
	$json=file_get_contents("config/cached_school_config.json");
	if(!strlen($json)) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $school_config["conf"]["conf_server_path"]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$json = curl_exec($ch);
		curl_close($ch);     
	}
}
$school_config=json_decode($json,true);
*/
