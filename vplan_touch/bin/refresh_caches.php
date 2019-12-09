<?php

$school_config=json_decode(file_get_contents("../config/school_config.json"),true);

if(isset($_GET["force"])) {
	// create curl resource
	$ch = curl_init();

	// set url
	curl_setopt($ch, CURLOPT_URL, $school_config["conf"]["conf_server_path"]);

	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// $output contains the output string
	$json = curl_exec($ch);

	// close curl resource to free up system resources
	curl_close($ch);     

	// write local cache
    if(strlen($json)<7)return "<h1>Cannot write config! cdn-response: ".$json."</h1>";

	if(is_file("../config/school_config_generate_file.php"))$dir="../config/";
	chmod($dir."cached_school_config.json",0755);
	$json=json_decode($json,true);
	$school_config["meta"]["ts"]=time();
	if(file_put_contents($dir."cached_school_config.json",json_encode($json)))echo "configuration cache was refreshed. ";else echo "error while trying to refresh configuration cache. please check writing permissions for ".$dir."cached_school_config.json.";
}

