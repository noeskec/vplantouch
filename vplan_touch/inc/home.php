<?php
	// this script creates the categorizations, its written for each school individually, so dont mind if you dont get the code, sorry
	if($_GET["cat"]!="forms"&&$_GET["cat"]!=""&&$login!="1") {
		$nav="login";
	} else {
		echo "<div class=text-center>";
		if($cat=="forms") {
			$size="tile-md";
			if(isset($_GET["subnav"])) {
				foreach($forms as $class) {
					if(preg_match($school_config["home"]["tiles"]["forms"][1]["regex"],$class)) {
						$page1[]=$class;
					} elseif(preg_match($school_config["home"]["tiles"]["forms"][2]["regex"],$class)) {
						$page2[]=$class;
					} elseif(preg_match($school_config["home"]["tiles"]["forms"][3]["regex"],$class)) {
						$page3[]=$class;
					} else {
						$page4[]=$class;
					}	
				}

				if($_GET["subnav"]=="page1")
					$catr=$page1;
				elseif($_GET["subnav"]=="page2")
					$catr=$page2;
				elseif($_GET["subnav"]=="page3")
					$catr=$page3;
				elseif($_GET["subnav"]=="page4")
					$catr=$page4;

			} else {
				echo "
				<a href=?nav=home&cat=forms&subnav=page1><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["forms"][1]["html"]."</div></a>
				<a href=?nav=home&cat=forms&subnav=page2><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["forms"][2]["html"]."</div></a>
				<a href=?nav=home&cat=forms&subnav=page3><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["forms"][3]["html"]."</div></a>
				<a href=?nav=home&cat=forms&subnav=page4><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["forms"][4]["html"]."</div></a>
				";
				$skip=1;
			}
		} elseif($cat=="teachers") {
			$size="tile-md";
			if(isset($_GET["subnav"])) {
				foreach($teachers as $teacher) {
					if(preg_match($school_config["home"]["tiles"]["teachers"][1]["regex"],$teacher[0])) {
						$page1[]=$teacher;
					} elseif(preg_match($school_config["home"]["tiles"]["teachers"][2]["regex"],$teacher[0])) {
						$page2[]=$teacher;
					} elseif(preg_match($school_config["home"]["tiles"]["teachers"][3]["regex"],$teacher[0])) {
						$page3[]=$teacher;
					} else {
						$page4[]=$teacher;
					}	
				}




				if($_GET["subnav"]=="page1")
					$catr=$page1;
				elseif($_GET["subnav"]=="page2")
					$catr=$page2;
				elseif($_GET["subnav"]=="page3")
					$catr=$page3;
				elseif($_GET["subnav"]=="page4")
					$catr=$page4;




			} else {
				echo "<a href=?nav=home&cat=teachers&subnav=page1><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["teachers"][1]["html"]."</div></a>
				<a href=?nav=home&cat=teachers&subnav=page2><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["teachers"][2]["html"]."</div></a>
				<a href=?nav=home&cat=teachers&subnav=page3><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["teachers"][3]["html"]."</div></a>
				<a href=?nav=home&cat=teachers&subnav=page4><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["teachers"][4]["html"]."</div></a>
				";
				$skip=1;
			}
		}  elseif($cat=="rooms") {
			$size="tile-md";
			if(isset($_GET["subnav"])) {
				foreach($rooms as $room) {
					if(preg_match($school_config["home"]["tiles"]["rooms"][1]["regex"],$room[0])) {
						$page1[]=$room;
					} elseif(preg_match($school_config["home"]["tiles"]["rooms"][2]["regex"],$room[0])) {
						$page2[]=$room;
					} elseif(preg_match($school_config["home"]["tiles"]["rooms"][3]["regex"],$room[0])) {
						$page3[]=$room;
					} else {
						$page4[]=$room;
					}	
				}



				if($_GET["subnav"]=="page1")
					$catr=$page1;
				elseif($_GET["subnav"]=="page2")
					$catr=$page2;
				elseif($_GET["subnav"]=="page3")
					$catr=$page3;
				elseif($_GET["subnav"]=="page4")
					$catr=$page4;



			} else {
				echo "<a href=?nav=home&cat=rooms&subnav=page1><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["rooms"][1]["html"]."</div></a>
				<a href=?nav=home&cat=rooms&subnav=page2><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["rooms"][2]["html"]."</div></a>
				<a href=?nav=home&cat=rooms&subnav=page3><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["rooms"][3]["html"]."</div></a>
				<a href=?nav=home&cat=rooms&subnav=page4><div class='tile tile-lg waves-effect waves-yellow  form-tile tile-lg-font'>".$school_config["home"]["tiles"]["rooms"][4]["html"]."</div></a>
				";
				$skip=1;
			}
		} else {
			$size="tile-xs";
			$catr=$$cat;
		}

		$startTimeCurrently="0000";

		$result=$db->query("select DISTINCT startTime from timetable");
		if($result)while($row=$result->fetch_object()) {
			if($row->startTime > date("Hi")) break;
			$startTimeCurrently=$row->startTime;
		}

		if(!isset($skip)) {

			//some workaround for becoming faster
			$db->query("create table if not exists  view_timetables like timetables");
			$db->query("create table if not exists  view_timetables_day like timetables");
			$db->query("truncate view_timetables ");
			$db->query("truncate view_timetables_day ");
			
			$sql="insert into view_timetables select * from timetable where is_removed='0' and date = '".date("Ymd")."' and startTime = '".$startTimeCurrently."'";
			$res=$db->query($sql);
			$sql="insert into view_timetables_day select * from timetable where is_removed='0' and date = '".date("Ymd")."' and endTime > '".date("Hi")."'";
			$res=$db->query($sql);
			$i=0;
			if(is_array($catr))foreach($catr as $index=>$curr) {
			    $i++;$present="";$absent="";
			    $cc=substr($_GET["cat"],0,(strlen($_GET["cat"])-1));
			    $sql="select * from timetables join tt".$cc."s on tt".$cc."s.timetableId=timetables.id join ".$cc."s on ".$cc."s.id = tt".$cc."s.".$cc."Id where ".$cc."s.name = '".$curr."' and is_removed='0' and date = '".date("Ymd")."' and endTime > '".date("Hi",(time()+45))."'";
			    if($db->query($sql)->num_rows)
				    $present="present";

			    /*$sql="select * from timetables join tt".$cc."s on tt".$cc."s.timetableId=timetables.id join ".$cc."s on ".$cc."s.id = tt".$cc."s.".$cc."Id where ".$cc."s.name = '".$curr."' and is_removed='0' and date = '".date("Ymd")."' and endTime > '".date("Hi")."'";
			    if($db->query($sql)->num_rows < 1)
				    $absent="absent";
                */if($cat=="rooms"){$present=($present=="present")?"":"present";}
			    echo "<a href='?nav=show&cat=".$cat."&id=".(array_search($curr,$$cat))."'><div class='waves-effect waves-yellow f-tile tile form-tile $absent $present $size'>".ucfirst(utf8_encode($curr));
			    if($cat=="teachers") {
				    echo "<div style=font-size:10px;color:gray>(".utf8_encode($db->query("select fullname from teachers where id = '".array_search($curr,$$cat)."' limit 1")->fetch_object()->fullname);
				    echo")</div>";
			    }
			    echo "</div></a>";
			}
			while($i>0&&$i<58) {
				$i++;
				echo "<a><div class='f-tile disabled waves-effect waves-red tile form-tile $size'>&nbsp;";	if($cat=="teachers") {echo"<div style=font-size:10px;color:gray>&nbsp;</div>";}echo"</div></a>";
			}
			if($cat=="forms")$legende="<div class=present style=margin-top:5px;padding:5px;>".t("Klasse anwesend")."</div><div style=background:white;margin-top:5px;padding:5px;>".t("Klasse abwesend")."</div><br>";
			else if($cat=="teachers")$legende="<div class=present style=margin-top:5px;padding:5px;>".t("Lehrer anwesend")."</div><div style=background:white;margin-top:5px;padding:5px;>".t("Lehrer abwesend")."</div><br>";
			else if($cat=="rooms")$legende="<div class=present style=margin-top:5px;padding:5px;>".t("Raum unbelegt")."</div><div style=background:white;margin-top:5px;padding:5px;>".t("Raum belegt")."</div><br>";
			if(isset($_GET["subnav"]))echo "<a><div style='width:372px !important;text-align:left !important;opacity:.7;background:#E4EFF4;padding-top:0px !important;padding-left:20px !important' class='f-tile disabled waves-effect waves-red tile form-tile $size'><h3 style='text-align:left'>".t("Aktuelle Verfügbarkeit:")."<br style=marign-top:15px>".$legende."</h3>";	if($cat=="teachers") {echo"<div style=font-size:10px;color:gray>&nbsp;</div>";}echo"</div></a>";
		}
	}
