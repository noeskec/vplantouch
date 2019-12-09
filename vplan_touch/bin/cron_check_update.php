<?php
if(date("i")[1]!="9" && !isset($_GET["force"])) die("not the time");
echo "update started";
//check for lock
if(is_file("../tmp/update_kane.php.lock"))die("please delete file '/var/www/html/vplan_touch/tmp/update_kane.php.lock'");

chmod("../*",777);
chmod("../../*",777);

include("../etc/v.php");

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://soft-atomos.com/kane/cdn/service/get_update.php?v=".$v);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
$res = curl_exec($ch);
curl_close($ch);      

echo $res;
$update=json_decode($res);
if($update->update=="true") {
	echo "new update found."."updating ".$v." to ".$update->version_new." at ".time().".";

	//set lock
	file_put_contents("../tmp/update_kane.php.lock","updating ".$v." to ".$update->version_new." at ".time());
	if(!is_file("../tmp/update_kane.php.lock"))die("please check writing permissions for user www-data for directory '/var/www/html/vplan_touch/tmp/'. Aborting could't write lock file.");
	
	//write the upload script	
	echo $update->source;
	file_put_contents("../tmp/updater.php",base64_decode($update->source));

	//call upload script
	include("../tmp/updater.php");
} elseif($update->update=="false") {
	echo "already newest version installed.";
} elseif(!isset($update->update)) {
	echo "error contacting update servers.";
}
?>
