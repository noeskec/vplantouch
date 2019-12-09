<?php
if(!file_exists("../vplan_updatedb/etc/status.json")) {
	echo "<div style='position:fixed;left:0;bottom:0;opacity:.5;margin:8px;'>VPlan UpdateDB status file missing.</div>";
} elseif($json->working==false){
	$sql = "SELECT * FROM metadata;";
	$result = $db->query($sql);
	$timestampArray = $result->fetch_array();
	echo "<div style='position:fixed;left:0;bottom:0;opacity:.5;margin:8px;'>Stand: ".date("d.m.Y H:i",strtotime($timestampArray["lastFetchTimeStamp"]))."</div>";
}else{
	echo "<div style='position:fixed;left:0;bottom:0;opacity:.5;margin:8px;'>Wird aktualisiert.</div>";
}
