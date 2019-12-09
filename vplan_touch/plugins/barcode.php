<?php
/*

create barcodes:

http://online-barcode-generator.net/

1m00iiiii

m=mode ( 1=form,2=teacher,3=room,4=specialid,5=dynamic )

i=id ( id of object )


output:
9781500000762


v1.3

*/

$barcodes_dynamic_file="var/barcodes_dynamic.json";

if($_GET["q"]=="reset") {
	echo "<div style=position:fixed;top:80px;left:0;right:0;bottom:0;z-index:1;background:white;text-align:center;>
		<h1 style=font-size:100px;margin-top:100px id=mastercodemsg>Bitte warten...</h1>
		<form style=position:absolute;top:-200px;><input name=mastercode id=mastercodeinput onchange=\"$('#mastercodemsg').html('Bitte jetzt den Barcode scannen, der zurückgesetzt werden soll...');setTimeout(function() {\$('#resetinput').focus();},200);\"><input id=resetinput name=reset onchange=this.form.submit()></form>
	</div><script>setTimeout(function() {\$('#mastercodemsg').html('Bitte einen Mastercode scannen...');\$('#mastercodeinput').focus();},1200);</script>";
}

if(isset($_GET["reset"])) {
	if(in_array($_GET["mastercode"],$school_config["plugins"]["qrcode"]["mastercodes"])) {
		$bs=json_decode(file_get_contents($barcodes_dynamic_file),true);
		unset($bs["".(-1+(substr($_GET["reset"],7,5))+1)]);
		unset($_SESSION["bdynid"]);
		unset($_SESSION["barcode_url"]);
		file_put_contents($barcodes_dynamic_file,json_encode($bs));
		echo "<div style=position:fixed;top:80px;left:0;right:0;bottom:0;z-index:1;background:white;text-align:center;>
			<h1 style=font-size:100px;margin-top:100px id=mastercodemsg>Code wurde zurückgesetzt.</h1>";		
	} else {
		echo "<div style=position:fixed;top:80px;left:0;right:0;bottom:0;z-index:1;background:white;text-align:center;>
			<h1 style=font-size:100px;margin-top:100px id=mastercodemsg>Kein gültiger Mastercode! <br><br>Code wurde nicht zurückgesetzt.</h1>";
	}
	echo "<div style=height:1000px></div>";
}

if(isset($_SESSION["once"])) {
	echo "<style>body {background: ".$_SESSION["once"]["bg"]." !important;}</style>";
	if(strlen($_SESSION["once"]["theme"]))echo '<link rel="stylesheet" href="lib/themes/'.$_SESSION["once"]["theme"].'/'.$_SESSION["once"]["theme"].'.css">';
	unset($_SESSION["once"]);
}

if(isset($_SESSION["barcode_url"])) {
	echo "<meta http-equiv=refresh content=56,?barcode=unset>";
}

if(isset($_SESSION["bdynid"]) && $_GET["barcode"]!="set") {
	$_SESSION["barcode_url"]=$_SERVER["REQUEST_URI"];
	if(isset($_GET["id"])||isset($_GET["q"])||isset($_GET["cat"]))echo "<div onclick=\"window.location.href='?barcode=set'\" style='position:fixed;top:0px;right:0;z-index:99999999;background:#ffca28;padding:15px 50px;font-size:34px;color:black;text-shadow:1px 1px 1px hsla(0,0%,100%,.2);border-left:1px solid hsla(0,0%,0%,.15)' class='waves-effect waves-light'><span style=margin-right:20px;margin-left:-20px; class='glyphicon glyphicon-ok'></span> Schreiben</div>";
}

if(isset($_GET["barcode"])) {

	if($_GET["barcode"]=="unset") {
		unset($_SESSION["bdynid"]);
		die("<script>window.location.href='?showDash';</script>");
	} elseif($_GET["barcode"]=="set") {	

		if(true) {

			$bs=json_decode(file_get_contents($barcodes_dynamic_file),true);
			$bs["".$_SESSION["bdynid"]]=array("uri"=>$_SESSION["barcode_url"],"bg"=>"#DCEBF0","theme"=>$_POST["theme"]);
			unset($_SESSION["bdynid"]);
			unset($_SESSION["barcode_url"]);
			file_put_contents($barcodes_dynamic_file,json_encode($bs));
			die('
					<link rel="stylesheet" href="lib/jquery-ui.css">
					<link rel="stylesheet" href="lib/bootstrap.css">
					<link rel="stylesheet" href="lib/bootstrap-theme.css">
					<link rel="stylesheet" href="lib/bootstrap-switch.min.css">
					<link rel="stylesheet" href="lib/touchvplan.css">
					<link rel="stylesheet" href="lib/domtis.css">
					<link rel="stylesheet" href="lib/domtis.css">
					<script src="lib/jquery.js"></script>
					<script src="lib/jquery-ui.js"></script>
					<script src="lib/bootstrap.js"></script>
					<meta http-equiv=refresh content=1,?barcode=unset>
					<div id=touchbody>
					<div id="bg"></div>
					<script src="lib/bootbox.js"></script>
					<script src="lib/bootstrap-switch.min.js"></script>'."
					<br><br><br><h1 style=font-size:60px>Kofiguration abgeschlossen.</h1>".'
				');


		}
	}
	
	$bcat=array("1"=>"forms","2"=>"teachers","3"=>"rooms");

	$b=$_GET["barcode"];
	$url="?nav=show&cat=".$bcat[$b[4]]."&id=".(-1+(substr($b,7,5))+1);


	
	if($b[4]=="5") { // mlx25 change later to index 5 tmp as 2
		$bs=json_decode(file_get_contents($barcodes_dynamic_file),true);
		if(strlen($bs["".(-1+(substr($b,7,5))+1)]["uri"])) {
			$obj=$bs["".(-1+(substr($b,7,5))+1)];
			$url=$obj["uri"];
			$_SESSION["once"]=array("bg"=>$obj["bg"],"theme"=>$obj["theme"]);
		} else {
			$_SESSION["bdynid"]="".(-1+(substr($_GET["barcode"],7,5))+1);
			die('
				<link rel="stylesheet" href="lib/jquery-ui.css">
				<link rel="stylesheet" href="lib/bootstrap.css">
				<link rel="stylesheet" href="lib/bootstrap-theme.css">
				<link rel="stylesheet" href="lib/bootstrap-switch.min.css">
				<link rel="stylesheet" href="lib/touchvplan.css">
				<link rel="stylesheet" href="lib/domtis.css">
				<link rel="stylesheet" href="lib/domtis.css">
				<script src="lib/jquery.js"></script>
				<script src="lib/jquery-ui.js"></script>
				<script src="lib/bootstrap.js"></script>
				<div id=touchbody>
				<div id="bg"></div>
				<script src="lib/bootbox.js"></script>
				<script src="lib/bootstrap-switch.min.js"></script>'."
				<br><br><br><h1 style=font-size:60px>Leeren Code erkannt.</h1>".'
				<p style=font-size:30px;margin:70px;width:900px;margin-top:80px;margin-bottom:40px;margin-left:auto;margin-right:auto>Um diesen Barcode mit einem Navigationsziel zu verknüpfen, klicken Sie bitte auf "Aktion setzen", navigieren Sie zur gewünschten Seite, und drücken Sie dann auf den orangenen Button "Schreiben" in der rechten oberen Ecke. </p>
				<a href=?nav=home&cat=forms><div style="margin:auto;display:block;background:orange;color:#000;border:none;box-shadow:1px 1px 3px gray" class="tile waves-effect waves-yellow tile-lg form-tile">>> Aktion setzen <<</div></a><br><br>
				<a href=?barcode=unset><div style="margin:auto;display:block;padding: 10px 20px;border:none;box-shadow:1px 1px 3px gray" class="tile tile-md form-tile">Zurück</div></a><meta http-equiv=refresh content=50,?barcode=unset>
			');
			$skipthatbarcode=true;
		}
	}

	if(!isset($skipthatbarcode)) {
		if(strstr($url,"searchonly")){
			$url.="&nav=search&frombarcode";
			$url=str_replace("autocomplete","oldautocomplete",$url);
		}
		echo "<script>window.location.href='$url';</script>";die();
	}
}


?>

<form style=position:fixed;top:-100px;><input style=font-size:0px;opacity:0; onchange=this.form.submit() name=barcode id=barcodeInputHidden autofocus=autofocus></form>
<?php if(!$_GET["q"]==" config") {?><script>
setTimeout(function() {$("#barcodeInputHidden").focus();},1000);
</script><style>.tile {
	border: none !important;
}</style><?php } ?>
<!--end of barcode plugin-->
