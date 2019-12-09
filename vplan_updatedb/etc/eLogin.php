<?php 

	$loginUsername = json_decode(file_get_contents("../etc/logins.json"),true)["Data-Server"]["Username"];
	$loginPassword = json_decode(file_get_contents("../etc/logins.json"),true)["Data-Server"]["Password"];
	$dataUrl = json_decode(file_get_contents("../etc/logins.json"),true)["Data-Server"]["Url"];
	if($dataUrl == ""){
		$opts = array('http' =>
		  array(
		    'method'  => 'POST',
		    'header'  => "Content-Type: text/xml\r\n".
		      "Authorization: Basic ".base64_encode($loginUsername.":".$loginPassword)."\r\n",
		    'content' => "",
		    'timeout' => 60
		  )
		);
				       
		$context  = stream_context_create($opts);
		$url="https://www.ghse.de/vpapp/ghse.json"; 
		$result = file_get_contents($url, false, $context, -1, 40000);
		$array = json_decode($result,true);
		$vplan = $array["StplanLogin"];
		$username = $vplan["username"];
		$password = $vplan["passwd"];
		//Da user gesperrt ist (vpapp)
		$username = $loginUsername;
		$url = $vplan["server"]."/jsonrpc.do?school=".$vplan["school"];
	}else{
		$url = $dataUrl;
		$username = $loginUsername;
		$password = $loginPassword;

	}
?>
