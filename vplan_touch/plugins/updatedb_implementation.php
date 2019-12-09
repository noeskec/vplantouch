<?php
$json = null;
$statusFile = "../vplan_updatedb/etc/status.json";
if(!file_exists($statusFile)) {
	echo "<div style='position:fixed;left:0;bottom:0;opacity:.5;margin:8px;'>VPlan UpdateDB status file missing.</div>";
}else{
	$json = json_decode(file_get_contents($statusFile));
}

if($json != null && $json->working==false){
	$sql = "SELECT * FROM metadata;";
	$result = $db->query($sql);
	$timestampArray = $result->fetch_array();
	echo "<div style='position:fixed;left:0;bottom:0;opacity:.5;margin:8px;'>Stand: ".date("d.m.Y H:i",strtotime($timestampArray["lastFetchTimeStamp"]))."</div>";
}else{
	echo "<div style='position:fixed;left:0;bottom:0;opacity:.5;margin:8px;'>Wird aktualisiert.</div>";
}
