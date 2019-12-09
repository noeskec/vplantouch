<?php
echo "<h1><br><br><br><br><br><br>hi";
if(isset($_GET["pin"]) && $school_config["dash"]["password"]["teacher"]==hash("sha512",$_GET["pin"])) {
	$_SESSION["security_level"]="2";
	echo "<script>window.location.href='.';</script>";
	$login=$_COOKIE["login"];
} 
