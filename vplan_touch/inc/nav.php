<?php
//make sure you dont have to worry about casing
$_GET["q"]=strtolower($_GET["q"]);

//for easier and quicker acces 
$nav=@$_GET["nav"];
$cat=@$_GET["cat"];
$id=@$_GET["id"];

//well i know its slower doing it that way, but you know, first we had those weird html tables and we parsed a javascript array into php, and at the point we switched to database we were to lazy so we just recreate our arrays form the database
$teachers=array();
$result=$db->query("select id as utid,name from teachers");
while($row=$result->fetch_object()) {
	$teachers[$row->utid]=$row->name;
}
//and here almost the same code again
$rooms=array();
$result=$db->query("select id as utid,name from rooms");
while($row=$result->fetch_object()) {
	$rooms[$row->utid]=$row->name;
}
//yaaaay and a third time
$forms=array();
$result=$db->query("select id as utid,name from forms");
while($row=$result->fetch_object()) {
	$forms[$row->utid]=$row->name;
}

//but if i apologized there for it, i dunno what to do with the follwing code, well i know its kinda childish but yes i didnt knew abuot the case thingy as i wrote that so there is some elseifs 
if($nav=="home"||$nav=="") {
	require("home.php");
	if($nav=="") {
		if($_COOKIE["teachers"]=="1") {
			echo "<a class='tile-lg tile' href=?nav=home&cat=teachers><span class='glyphicon glyphicon-education'></span><br>".t("Lehrer")."</a>";
			echo "<a class='tile-lg tile' href=?nav=home&cat=rooms><span class='glyphicon glyphicon-home'></span><br>".t("Räume")."</a>";
		} else {
			echo "<div id=hometiles>
				<a href=?nav=home&cat=forms><div class='tile waves-effect waves-yellow tile-lg form-tile'><span class='glyphicon glyphicon-user'></span><br>".t("Klassen")."</div></a>
				<a href=?nav=home&cat=teachers><div class='tile waves-effect waves-yellow tile-lg form-tile'><span class='glyphicon glyphicon-education'></span><br>".t("Lehrer")."</div></a>
				<a href=?nav=home&cat=rooms><div class='tile waves-effect waves-yellow tile-lg form-tile'><span class='glyphicon glyphicon-home'></span><br>".t("Räume")."</div></a>
				";?><a onclick="$('#pinpad').hide({effect:'slide',direction:'down'});$('#keyboard').show({'effect':'slide',direction:'down'});$(this).val('');"><?php echo "<div class='tile waves-effect waves-yellow tile-lg form-tile'><span class='glyphicon glyphicon-search'></span><br>".t("Suche")."</div></a>
			</div>";
		}
	}
} elseif($nav=="setsession") {
	$_SESSION[$_GET["name"]]=$_GET["val"];
} elseif($_GET["nav"]=="v") {
	$sql = "SELECT * FROM metadata;";
	$result = $db->query($sql);
	$timestampArray = $result->fetch_array();
    $json=json_decode(file_get_contents("../vplan_updatedb/etc/status.json"));
	echo "<div class=container style=font-size:2em>
	<h1 class=text-center> ".t("Vertretungsplan Touch")."</h1>
	<div class=text-center><p><h2>-&emsp;v".$v."&emsp;-</h2>
	<br><br><img src=img/ghse-online.png><br><br>
	<br><br>".t("Systemupdate").": ".date("d.m.Y H:i",filemtime("etc/v.php"))." ".t("Uhr")."</p>
	<p>".t("Untisupdate").": ".date("d.m.Y H:i",strtotime($timestampArray["untisTimeStamp"]))." Uhr</p>
	<p>".t("Letzte Überprüfung").": ".date("d.m.Y H:i",strtotime($timestampArray["lastFetchTimeStamp"]))." Uhr</p>
	<p><br><p>".t("Schnittstelle Version").": vplan_updatedb v".$json->version."
	</p></div>
	";
} elseif($_GET["nav"]=="data_update") {
	echo "<iframe style=border:0;position:absolute;top:80px;right:0;bottom:0;left:0;width:100%;height:100% src='http://localhost/vplan_updatedb/bin/cron_untis.php?force&ignoreImport'></iframe>";
} elseif($_GET["nav"]=="touch_update") {
	echo "<iframe style=border:0;position:absolute;top:80px;right:0;bottom:0;left:0;width:100%;height:100% src='http://localhost/vplan_touch/bin/cron_check_update.php?force'></iframe>";
} elseif($_GET["nav"]=="cron_update") {
	echo "<iframe style=border:0;position:absolute;top:80px;right:0;bottom:0;left:0;width:100%;height:100% src='http://localhost/vplan_updatedb/bin/cron_update.php?force'></iframe>";
} elseif($nav=="search") {
	echo "<div class='text-center container'>";
	
	//array search instead of mysql, yaaaay
	$class_found=array_search(strtolower($_GET["q"]),array_map('strtolower',$forms));
	$teacher_found=array_search(strtolower($_GET["q"]),array_map('utf8_encode',array_map('strtolower',$teachers)));
	$room_found=array_search(strtolower($_GET["q"]),array_map('strtolower',$rooms));
	
	//something like num_rows, and then instead of showing it, we just redirect. - via javascript... well thats sooo saaaad :( but wait, theres more...
	if($class_found) {
		echo"<script>window.location.href='?nav=show&cat=forms&id=".($class_found)."';</script>";
	} elseif($teacher_found) {
		echo"<script>window.location.href='?nav=show&cat=teachers&id=".($teacher_found)."';</script>";
	} elseif($room_found) {
		echo"<script>window.location.href='?nav=show&cat=rooms&id=".($room_found)."';</script>";
	} elseif($_GET["q"]==" tellmeajoke") {
		$jokes=array("The box said ‘Requires Windows Vista or better’. So I installed LINUX.",
			"In a world without fences and walls, who needs Gates and Windows?",
			"UNIX is basically a simple operating system, but you have to be a genius to understand the simplicity.",
			"Bugs come in through open Windows.",
			"Unix is user friendly. It’s just selective about who its friends are.",
			"The more I C, the less I see.",
			"If you give someone a program, you will frustrate them for a day; if you teach them how to program, you will frustrate them for a lifetime",
			"Programmers are tools for converting caffeine into code.",
			"Black holes are where God divided by zero.",
			"Sind zwei Pointer aufm Stack, sagt der eine zum anderen: 'hey, hör auf auf mich zu zeigen!'.",
			"There is no place like 192.168.0.1'.",
			"Computers make very fast, very accurate mistakes.");
		echo "
			<h1 style=font-size:180px;>;-)</h1><br><br><br>
			<p style=font-size:50px;><i>\"".$jokes[rand(0,(count($jokes)-1))]."\"</i></p>
			";
	} elseif($_GET["q"]==" config") {

        //file_put_contents("config/pin_config.sha512",hash("sha512","1234"));

        if(hash("sha512",$_GET["mastercode"])==file_get_contents("config/pin_config.sha512")) {

            if($_GET["cpl_kanebit"]=="kanebit0") {
                if(file_get_contents("etc/kanebit0")=="1")file_put_contents("etc/kanebit0","0");else file_put_contents("etc/kanebit0","1");
            }
            if($_GET["cpl_kanebit"]=="kanebit1") {
                if(file_get_contents("etc/kanebit1")=="1")file_put_contents("etc/kanebit1","0");else file_put_contents("etc/kanebit1","1");
            }

		    ?>
		    <a href=?showdash><div class='tile tile-md waves-effect waves-yellow  form-tile' style="padding:50px 80px ;padding-top:70px;text-align:center;width:500px;height:200px;font-size:34px">Startscreen</div></a>
		    <a href=?nav=bake_config><div class='tile tile-md waves-effect waves-yellow  form-tile' style="padding:50px 80px ;text-align:center;width:500px;height:200px;font-size:34px">Bake Configuration to JSON File</div></a>
		    <a href="?nav=data_update"><div class='tile tile-md waves-effect waves-yellow  form-tile' style="padding:50px 80px ;text-align:center;width:500px;height:200px;font-size:34px">Force Data update</div></a>
		    <a href="?nav=v"><div class='tile tile-md waves-effect waves-yellow  form-tile' style="padding:50px 80px;padding-top:70px ;text-align:center;width:500px;height:200px;font-size:34px">Versioninfo</div></a>
		    <a href="?nav=touch_update"><div class='tile tile-md waves-effect waves-yellow  form-tile' style="padding:50px 80px;padding-top:70px ;text-align:center;width:500px;height:200px;font-size:34px">Force GUI update</div></a>
		    <a href="?nav=cron_update"><div class='tile tile-md waves-effect waves-yellow  form-tile' style="padding:50px 80px;padding-top:70px ;text-align:center;width:500px;height:200px;font-size:34px">Force Cron update</div></a>
		    <a href="?nav=search&mastercode=<?php echo $_GET["mastercode"];?>&q= config&cpl_kanebit=kanebit0"><div class='tile tile-md waves-effect waves-yellow  form-tile' style="padding:50px 80px;padding-top:70px ;text-align:center;width:500px;height:200px;font-size:34px"><?php 
            if(file_get_contents("etc/kanebit0")=="1") echo "CLR";
            else echo "SETB";
            ?> KANEBIT-0</div></a><a href="?nav=search&mastercode=<?php echo $_GET["mastercode"];?>&q= config&cpl_kanebit=kanebit1"><div class='tile tile-md waves-effect waves-yellow  form-tile' style="padding:50px 80px;padding-top:70px ;text-align:center;width:500px;height:200px;font-size:34px"><?php 
            if(file_get_contents("etc/kanebit1")=="1") echo "CLR";
            else echo "SETB";
            ?> KANEBIT-1</div></a>
		    <?php
        } else {
                
            include("inc/gui_config.php");

        }
	} elseif($_GET["q"]==" domi") {
		include("plugins/game/index.php");
	} else {
		echo "
			<h1 style=font-size:180px;>:(</h1>
			<h1 style=font-size:60px;>";echo t("Kein Ergebnis gefunden");echo"!</h1>
			<p style=font-size:30px;>";echo t("Leider konnten keine Suchergebnisse gefunden werden");echo".</p>
		</div>";
	}
} elseif($nav=="show") {
	include("inc/show.php");
}  elseif ($nav=="bake_config") {	
	echo("<iframe src='config/school_config_generate_file.php' style=border:none;width:100%;text-align:center></iframe>");
}


if($nav=="login") {
	if(isset($_GET["pin"]) && $school_config["dash"]["password"]["teacher"]==hash("sha512","0MjM5dTg5F03101998dTJuFog5VSg9KVovJlRSejk4cjM0cj187QzMnI0".$_GET["pin"]."VCYvUsKnCWi8oIlpSPVUoKSGHSELCp1UoKVJ1N42DM5dXI")) {
		echo "<script>
		$(function() {
			window.location.href='?cat=".$_SESSION["cat"]."&nav=".$_SESSION["nav"]."&id=".$_SESSION["id"]."';
		});
		</script>";
		$_SESSION["login"]=true;
		$_SESSION["security_level"]="2";
		unset($_SESSION["cat"]);
		unset($_SESSION["nav"]);
		unset($_SESSION["id"]);
	} elseif(isset($_GET["pin"])) {
		echo "<div class='text-center container'>
			<h1>".t("Falsche PIN").".</h1>
			<input type=password id=pinfield placeholder=____ onclick=\"showpinpad();\">
		</div>
		<script>
		$(function() {
			$('#pinpad').show();
		});
		</script>";
	} else {
		$_SESSION["cat"]=$_GET["cat"];
		$_SESSION["nav"]=$_GET["nav"];
		$_SESSION["id"]=$_GET["id"];
		echo "<div class='text-center container'>
			<h1>".t("Bitte geben Sie die PIN ein").":</h1>
			<input type=password id=pinfield placeholder=____ onclick=\"showpinpad();\">
		</div>
		<script>
		$(function() {
			$('#pinpad').show({effect:'slide',direction:'down'});
		});
		</script>";
	}
	echo "<script>
	function showpinpad() {
		$('#keyboard').hide({effect:'slide',direction:'down'});	
		$('#pinpad').show({effect:'slide',direction:'down'});	
	}
	</script>";
}
?>
