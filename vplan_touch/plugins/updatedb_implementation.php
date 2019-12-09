<?php
if(!file_exists("../vplan_updatedb/etc/status.json")) {
	echo "<div style='position:fixed;left:0;bottom:0;opacity:.5;margin:8px;'>VPlan UpdateDB config file missing.</div>";
} elseif($json->working==false){
    $json=json_decode(file_get_contents("../vplan_updatedb/etc/status.json"));
	echo "<div style='position:fixed;left:0;bottom:0;opacity:.5;margin:8px;'>Stand: ".date("d.m.y H:i",($json->importTime))."</div>";
}else{
	echo "<div style='position:fixed;left:0;bottom:0;opacity:.5;margin:8px;'>Stand: Wird aktualisiert.</div>";
}
