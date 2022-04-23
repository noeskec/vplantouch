<?php
if (!$generate_head) {
    $json = null;
    $statusFile = "../vplan_updatedb/etc/status.json";
    $style = "position:fixed;left:0;bottom:0;opacity:.5;margin:8px;";
    if(!file_exists($statusFile)) {
        echo "<div style='".$style."'>VPlan UpdateDB status (or config) file missing.</div>";
    } else {
        $json=json_decode(file_get_contents($statusFile));
        if($json != null && $json->working==false){
            // A) from status file
            //echo "<div style='".$style."'>Stand: ".date("d.m.y H:i",($json->importTime))."</div>";
            
            // B) from database
            $sql = "SELECT * FROM metadata;";
            $result = $db->query($sql);
            $timestampArray = $result->fetch_array();
            echo "<div style='".$style."'>Stand: ".date("d.m.Y H:i",strtotime($timestampArray["lastFetchTimeStamp"]))."</div>";
        }else{
            echo "<div style='".$style."'>Stand: Wird gerade aktualisiert.</div>";
        }
    }
}
?>