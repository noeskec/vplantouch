<?php
//connection paths to remote cdn if used
$cdnpath="cdn/vplan_touch/"; // root path for external files

//set weeknumber to display
$cweek=date("W");

//change weeknumber to skip a few weeks to see future plans
if(isset($_GET["skipweeks"])){
	$cweek+=$_GET["skipweeks"];
	if(strlen($cweek)<2)
		$cweek="0".$cweek;
}

//define plugins being loaded 
$plugins_activated=array("barcode","autocomplete","updatedb_implementation");

?>
