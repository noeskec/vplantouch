<?php
session_start();
error_reporting(0);

foreach($_GET as $index=>$val) {
    $_GET[$index]=str_replace("'",null,$val);
}
foreach($_POST as $index=>$val) {
    $_POST[$index]=str_replace("'",null,$val);
}

//get version
require("etc/v.php");

//worry about translations
//compress html output
function ob_html_compress($buf){
	return str_replace(array("\n","\r","\t"),'',$buf);
}
ob_start("ob_html_compress");

//inizialize cookies
if(!isset($_COOKIE["timeout"]))setcookie("timeout",70,time()+(3600*24*365*2));
if(!isset($_COOKIE["teachers"]))setcookie("teachers","0",time()+(3600*24*365*2));
if(@$_GET["nav"]=="setcookie")setcookie($_GET["name"],$_GET["val"],time()+(3600*24*365*2));

//send headers for clean html
echo '<!DOCTYPE html><head><title>VplanTouch</title><meta charset="UTF-8">';

//make sure screen gets reset properly
if(!isset($_GET["showdash"])){
    echo '<meta http-equiv="refresh" content="'.$_COOKIE["timeout"].'; URL=.?showdash">';
} else {
    unset($_SESSION["comps"]);echo '<meta http-equiv="refresh" content="3600; URL=.?showdash">';
}

//connect to database
if(!is_file("bin/dbcon.php"))echo("no db connection. please create object \$db as mysqli connection in file bin/dbcon.php");
require("bin/dbcon.php");
$db->set_charset("utf8mb4");

//load config files
require("etc/config.php");
require("etc/security.php");

require("bin/config_connect.php");
define("lang",$school_config["conf"]["lang"]);
require("lib/lang.php");

if($school_config["conf"]["pinlock_enabled"])$login=$_COOKIE["login"];else$login=true;

//handle ajax requests
include("bin/ajax.php");

//if a teacher confiremd a valid password
if(@$_SESSION["login"]==true) {
	setcookie("login",1,(time()+300)); //keeps you logged in 5 minutes
	unset($_SESSION["login"]);
	echo "<script>window.location.href=window.location.href;</script>";
}

//output header to include required libraries
//include("inc/header.php"); // -> done in next line also!

$generate_head = true;
for ($x = 0; $x < 2; $x++) {
    //load the individual page parts of vplantouch
    foreach(array("header","prep","navbar","nav","tail","keyboard") as $file) {
        include("inc/".$file.".php");
    }
    //include plugins
    foreach($plugins_activated as $plugin) {
        include("plugins/".$plugin.".php");
    }    if ($generate_head) echo "</head>";
    $generate_head = false;
}


//show dashboard with welcome message if the timeout redirected to ?showdash
if(isset($_GET["showdash"])) {
	include("inc/dash.php");
	include("bin/refresh_caches.php");
}

//output the compressed html
ob_end_flush();echo "</div></body></html>";
?>

