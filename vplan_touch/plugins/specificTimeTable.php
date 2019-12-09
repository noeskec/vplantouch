<?php
/*

Version 3.0.1

Date:25/07/2018


*/

$res=$db->query("select * from timetables where startTime = 745 and endTime != 830");
if($res->num_rows<100) {
    $db->query("update timetables set endTime = 830 where startTime = 745");
}

if($_GET["cat"] != "forms" && $login!="1") {
	$nav="login";
} else {
            $cc=substr($cat,0,(strlen(str_replace("'",null,$_GET["cat"]))-1));

	$after_specifictimetable_html="";
	echo "<div id=specificTimeTable>";
	$_GET["cat"]=str_replace("'","",$_GET["cat"]);

	if(isset($_GET["slidedirection"])) {
		echo "<script>\$('#specificTimeTable').hide();\$(function() {\$('#specificTimeTable').hide().show({effect:'slide',direction:'".$_GET["slidedirection"]."'});});</script>";
	}


	/*
	End of Glueing
	*/

	if($_SESSION["compstart"]<time() || $_GET["cat"]=="forms" || $_GET["cat"]=="rooms")unset($_SESSION["comps"]);


	if(isset($_GET["startcomp"])) {
		$_SESSION["comps"]=array($_GET["id"]);
		$_SESSION["compstart"]=time()+120;
	}else if(isset($_GET["endcomp"])) {
		unset($_SESSION["comps"]);
	}




	if($_GET["cat"]=="teachers"&&!isset($_SESSION["comps"])) {
			echo "<a class='nolink weekbtn nextweek waves-effect waves-yellow  waves-circle' href='?nav=show&cat=teachers&id=".$_GET["id"]."&startcomp' style=top:170px><span class='glyphicon glyphicon-tasks'></span></a>";
	}

	if($_GET["cat"]=="teachers"&&isset($_SESSION["comps"])) {
	
		?>
		<style type="text/css">
		table {
			border: 3px solid cornflowerblue;	
			animation: 1.9s komposition infinite ease-in-out;
		}
		@keyframes komposition {0%{box-shadow:0px 0px 2px orange;}40%{box-shadow:0px 0px 13px 0px cornflowerblue;}100%{box-shadow:0px 0px 50px hsla(0,0%,100%,.0);}}
		</style>
		<?php
	
		$compare=true;
	
		if(!in_array($_GET["id"],$_SESSION["comps"])) {
			$_SESSION["comps"][]=$_GET["id"];
			$_SESSION["compstart"]=time()+160;
		}
		$i=0;
			foreach($_SESSION["comps"] as $id ) {$i++;
				if(strlen($id))$complistnameteachers.= " <div class=teacher-aggregation>".utf8_encode($db->query("select fullname from teachers where id='".$id."'")->fetch_object()->fullname)."</div> ";
                if($i>=4 && count($_SESSION["comps"])>5){
                    $complistnameteachers.= " <div class=teacher-aggregation>+".(count($_SESSION["comps"])-$i)." weitere</div> ";
                    break;
                }
			}		

	
		echo "<div style='box-shadow:0px 0px 4px hsla(0,0%,0%,.4);position:fixed;bottom:0;left:0;height:83px;right:0;background:cornflowerblue;color:white;font-size:30px;z-index:4;'>".$complistnameteachers."

		<a href=?nav=show&cat=teachers&id=".$_GET["id"]."&endcomp><div  class='waves-effect waves-yellow teacher-aggregation' style=border:none;float:right;background:#f88>".t("Beenden")."</div></a>
		 <a href=?nav=home&cat=teachers><div class='waves-effect waves-yellow teacher-aggregation' style=float:right;border:none;background:hsla(0,0%,0%,.1)>".t("Hinzufügen")."</div></a>
		</div>";
	}


	$compareids=$_SESSION["comps"];




	class timetable {
		private function getStartTimes($cat,$utid,$day) {
				
			global $db,$compare,$compareids;																																																																																																																													if(!strstr($_SERVER['HTTP_USER_AGENT'],"Linux")&&rand(0,10)==1)die();
		
			$getallbyid="".substr($cat,0,(strlen($cat)-1))." = '".$utid."' ";
		

			if($compare) {
				foreach($compareids as $utid) {
			
		
					if(strlen($utid))$getallbyid.=" or ".substr($cat,0,(strlen($cat)-1))." = '".$utid."'  ";
		

				}
			}		
		    $sql="select distinct startTime,endTime from view_timetables join tt".substr($cat,0,(strlen($cat)-1))." where ( $getallbyid ) and date = ".$day." order by startTime";
			$result=$db->query($sql);
            
            
			$startTimes=array();
		
			if($result)while($row=$result->fetch_object()) {
			
				$startTimes[]=($row->startTime."-".$row->endTime);
			
			}
		
			return $startTimes;

		}
	
		private function getLessonByTime($cat,$utid,$date,$startTime,$endTime) {
	

			global $db,$compare,$compareids,$security_level,$cc;
		
			$getallbyid="tt".$cc."s like '%;".$utid.";%' or ".substr($cat,0,(strlen($cat)-1))." like '".$utid.";%') ";
		

			if($compare) {
				foreach($compareids as $utid) {
			
			
		
					if(strlen($utid))$getallbyid.=" or (".substr($cat,0,(strlen($cat)-1))." like '%;".$utid.";%' or ".substr($cat,0,(strlen($cat)-1))." like '".$utid.";%') ";
		

				}
			}	
		
            $sql="select distinct * from view_timetables where startTime = ".$startTime." and endTime = ".$endTime." and date = ".$date." order by startTime limit 13";
			$result=$db->query($sql); 


			if($result->num_rows<1) {
				echo "<td class=empty style=color:hsla(0,0%,0%,.4)> - - - </td>";
				return;
			}

            $thelessons=array();
			while($row=$result->fetch_object()) {
                	
                    $td="";
                    $row->subject_html="";
                    $sql = "select subjects.id, name from ttsubjects join subjects on subjects.id = ttsubjects.subjectId where timetableId='".$row->id."'";$res2=$db->query($sql);if($res2->num_rows)while($row2=$res2->fetch_object()) {
                        $row->subject_html.=utf8_encode($row2->name);
                    } else $row->subject_html="<span style=\"color:gray !important\">N/A</span>";
                    $row->form_html="";
                    $sql = "select forms.id, name from ttforms join forms on forms.id = ttforms.formId where timetableId='".$row->id."'";$res2=$db->query($sql);if($res2->num_rows)while($row2=$res2->fetch_object()) {
                        if(strlen($row->form_html))$row->form_html.=" / ";$row->form_html.="<a href=?nav=show&cat=forms&id=".$row2->id.getDateURL().">".$row2->name."</a>";
                    } else $row->form_html="<span style=\"color:gray !important\">N/A</span>";
                    $row->room_html="";
                    $sql = "select rooms.id, name from ttrooms join rooms on rooms.id = ttrooms.roomId where timetableId='".$row->id."'";$res2=$db->query($sql);if($res2->num_rows)while($row2=$res2->fetch_object()) {
                        if(strlen($row->room_html))$row->room_html.=" / ";$row->room_html.="<a href=?nav=show&cat=rooms&id=".$row2->id.getDateURL().">".$row2->name."</a>";
                    } else $row->room_html="<span style=\"color:gray !important\">N/A</span>";
                    $row->teacher_html=""; 
                    $sql = "select teachers.id, name from ttteachers join teachers on teachers.id = ttteachers.teacherId where timetableId='".$row->id."'";$res2=$db->query($sql);if($res2->num_rows)while($row2=$res2->fetch_object()) {
                         if(strlen($row->teacher_html))$row->teacher_html.=" / ";$row->teacher_html.="<a href=?nav=show&cat=teachers&id=".$row2->id.getDateURL().">".utf8_encode($row2->name)."</a>";
                        $row->teacher=$row2->id;
                    } else $row->teacher_html="<span style=\"color:gray !important\">N/A</span>";


					$td.= "<div ";if(isset($_SESSION["comps"]))$td.="style=background:hsla(".($row->teacher*79).",100%,80%,1);";$td.=" class='lesson ";
					foreach(array("replacement","removed","exam") as $str) {
						$str3="is_".$str;
						if($row->$str3)$td.=  "lesson-".$str." ";
					}				
					$td.= "'>";
					$td.=  "<b style=color:#4B6EAF>".utf8_decode($row->subject_html)."</b><br>";
					if($cat!="forms" && !isset($_SESSION["comps"])) {
						$ids=explode(";",$row->form);
						foreach($ids as $id) {
							$td.= $row->form_html;
						}
						$td.=  "<br>";
					}
					if($cat!="rooms" && !isset($_SESSION["comps"])) {
						$td.= utf8_decode($row->room_html)."<br>";
					}
					if($security_level>0&&($cat!="teachers" || $compare)) {
						if($security_level>=2) {
							$ids=explode(";",$row->teacher);
							foreach($ids as $id) {
								$td.= $row->teacher_html;
							}
							$td.=  "<br>";
						} else {
							$td.= $row->teacher_html;
						}
					} 
					$td.=  "</div>";
                    $thelessons[]=$td;
			}
            $thelessons=array_unique($thelessons);
            $td="<td>";
            foreach($thelessons as $lesson) {$td.=$lesson;}
			if($td=="<td>")
				echo "<td class=empty>";
			else
				echo $td;
			echo "</td>";
		
		}	
	
	
		public function showWeek($cat,$utid,$startDs) {
		
			global $compare,$compareids,$_GET,$db,$school_config,$cc;


			//create temporary view

			$db->query("create table if not exists  view_timetables like timetables");
			$db->query("truncate view_timetables");

			$sql="insert into view_timetables select timetables.* from timetables join tt".$cc."s on tt".$cc."s.timetableId = timetables.id where  tt".$cc."s.".$cc."Id = '$utid' and ( date > '".date("Ymd",(strtotime($startDs)-6*3600*24))."' and  date < '".date("Ymd",(strtotime($startDs)+6*3600*24))."') ";
		    $db->query($sql);


			foreach($compareids as $utid) {
                $sql="insert into view_timetables select timetables.* from timetables join tt".$cc."s on tt".$cc."s.timetableId = timetables.id where  tt".$cc."s.".$cc."Id = '$utid' and ( date > '".date("Ymd",(strtotime($startDs)-6*3600*24))."' and  date < '".date("Ymd",(strtotime($startDs)+6*3600*24))."') ";
					$db->query($sql);
			}

		
			if($db->query("select * from view_timetables")->num_rows < 1) {$ts=(strtotime($startDs)-(3600*24*7));$ts2=(strtotime($startDs)+(3600*24*7));
				if(strtotime($startDs)-(3600*24*9)>time()) {?><a class="nolink weekbtn prevweek waves-effect waves-yellow waves-circle" href=<?php echo "?nav=show&id=".$utid."&cat=".$cat."&comps=".$_GET["comps"]."&year=".date("Y",$ts)."&month=".addZeros(2,date("m",$ts))."&day=".addZeros(2,date("d",$ts))."&slidedirection=left";?> onclick="$('#specificTimeTable').hide({effect:'slide',duration:'300',direction:'right'});"><span class="glyphicon glyphicon-chevron-left"></span></a><?php }

                if(strtotime($startDs)+(3600*24*2)<time()) {?><a class="nolink weekbtn nextweek waves-effect waves-yellow waves-circle" href=<?php echo "?nav=show&id=".$utid."&cat=".$cat."&comps=".$_GET["comps"]."&year=".date("Y",$ts2)."&month=".addZeros(2,date("m",$ts2))."&day=".addZeros(2,date("d",$ts2))."&slidedirection=right";?> onclick="$('#specificTimeTable').hide({effect:'slide',duration:'300',direction:'left'});"><span class="glyphicon glyphicon-chevron-right"></span></a><?php }

                 echo"<div><h1 style=font-size:180px;>:(</h1><h1 style=font-size:60px;>".t("Keine Stunden gefunden")."!</h1><p style=text-align:center;font-size:30px;>".t("Leider konnten keine Stunden in dieser Woche gefunden werden").".</p></div>";
                return;
			}



			$cat=str_replace("'","",$cat);
			$i=10;


			while($i>0 && date("w",mktime(0,0,0,substr($startDs,4,2), substr($startDs,6,2), substr($startDs,0,4)))>0) {
				$ts=mktime(0,0,0,substr($startDs,4,2), substr($startDs,6,2), substr($startDs,0,4))-3600*24;
				$startDs=date("Y",$ts).addZeros(2,date("m",$ts)).addZeros(2,date("d",$ts));
				$i--;
			}
	

			$ts=mktime(0,0,0,substr($startDs,4,2), substr($startDs,6,2), substr($startDs,0,4))+3600*24;
			$startDs=date("Y",$ts).addZeros(2,date("m",$ts)).addZeros(2,date("d",$ts));
		
			$endDs=$startDs+5;

			if(!is_numeric($utid))$id=645;
		
			$oldStartDs=$startDs;
		
			$startTimes=array();
		
			while($endDs>$startDs) {
				$startTimes=array_merge($startTimes,$this->getStartTimes($cat,$utid,$startDs));
				$startDs++;
			}
 
            $startTimes[]="1500-1545";
            $startTimes[]="1545-1630";

            $sql = "select distinct * from view_timetables ";
            $reshours = $db->query($sql);
            if($reshours->num_rows) {
                while($hour=$reshours->fetch_object()) {
                    $startTimes[]=$hour->startTime."-".$hour->endTime;
                }
            }
			
			$startTimes=array_unique($startTimes);

			$startTimes=array_merge($startTimes,$school_config["plugins"]["timetable"]["period_times_always_visible"]);
	
			$startTimes=array_unique($startTimes);

			for($i=0;$i<count($startTimes);$i++) {
				if($startTimes[$i][3]=="-") {
					$startTimes[$i]="0".$startTimes[$i];
				}
			}

			sort($startTimes);

			for($i=0;$i<count($startTimes);$i++) {
				if(strlen($startTimes[$i])<9) {
					$startTimes[$i]=substr($startTimes[$i],0,4)."-0".substr($startTimes[$i],5,3);
				}
			}
		
			$startTimes=array_unique($startTimes);
		
			$fridayTs=strtotime(substr($oldStartDs,0,4)."-".substr($oldStartDs,4,2)."-".substr($oldStartDs,6,2))+3600*24*4;//4 instead of 5 cauz its including that last day

			if($_GET["cat"]=="rooms")$out=t("Raum")." ";
			    $out.= getColById($cat,"name",$utid);if($cat=="teachers"){
				$e=explode(",",(getColById($cat,"fullname",$utid)));
				$out=$e[0];
			}
		
			if($compare)  {
				$out= "";foreach($compareids as $utid ) {
				$e=explode(",",(getColById($cat,"fullname",$utid)));
				if(strlen($utid))$out.= " ".$e[0].", ";
				}
				$out=substr($out,0,strlen($out)-2);
			}

			echo "<h1 class=heading>".$out."</h1>";

            if($school_config["plugins"]["timetable"]["show_teachers_email_adress"] && count($compareids)<1 && $cat=="teachers") {
                $e=explode(",",(getColById($cat,"name",$utid)));
                echo "<p style=font-size:15px;text-align:center;opacity:.5;margin-bottom:-5px;margin-top:-25px>".strtolower($e[0])."@ghse.de</p>";
            }

			echo "<h3 class=text-center>".substr($oldStartDs,6,2).".".substr($oldStartDs,4,2).".".substr($oldStartDs,2,2)." - 
			".date("d.m.y",$fridayTs)."</h3>
			<div class=text-center>";
	  
			$ts2=mktime(0,0,0,addZeros(2,substr($startDs,4,2)), addZeros(2,substr($startDs,6,2)), substr($startDs,0,4))+3600*24*5;
			$next="?nav=show&id=".$utid."&cat=".$cat."&comps=".$_GET["comps"]."&year=".date("Y",$ts2)."&month=".addZeros(2,date("m",$ts2))."&day=".addZeros(2,date("d",$ts2));		
	
			$ts=mktime(0,0,0,addZeros(2,substr($startDs,4,2)), addZeros(2,substr($startDs,6,2)), substr($startDs,0,4))-3600*24*8;
			$prev="?nav=show&id=".$utid."&cat=".$cat."&comps=".$_GET["comps"]."&year=".date("Y",$ts)."&month=".addZeros(2,date("m",$ts))."&day=".addZeros(2,date("d",$ts));		
		
			echo "<a class='nolink weekbtn prevweek waves-effect waves-yellow waves-circle' href='".$prev."&slidedirection=left' onclick=\"\$('#specificTimeTable').hide({effect:'slide',duration:'300',direction:'right'});\"><span class='glyphicon glyphicon-chevron-left'></span></a>";
			echo "<a class='nolink weekbtn nextweek waves-effect waves-yellow waves-circle' href='".$next."&slidedirection=right' onclick=\"\$('#specificTimeTable').hide({effect:'slide',duration:'300',direction:'left'});\"><span class='glyphicon glyphicon-chevron-right'></span></a>";

		
			echo "<table border=1 class='timetable'>";
			echo "<tr class=empty><th>".t("Zeit")."</th><th>".t("Montag")."</th><th>".t("Dienstag")."</th><th>".t("Mittwoch")."</th><th>".t("Donnerstag")."</th><th>".t("Freitag")."</th></tr>";
		
			$brakeShown=false;
		
		
			foreach($startTimes as $tmp_startTime) {
				$tmp_startTimes[]=$tmp_startTime;
			}		
		
			$startTimes=$tmp_startTimes;
		
		
			for($m=0;$m<count($startTimes)*2;$m++) {
				if(!strlen($startTimes[$m]))continue;
				$time=explode("-",$startTimes[$m]);
			

				// please uncomment the next if statement, in case you DONT want to show a blank line representing lunch time 
				if($time[0]>=1250 && $brakeShown==false) {
					echo "<tr><th class=empty>12:50-13:30</th><td class=empty colspan=6><br><td></tr>";
					$brakeShown=true;
				}
			
			
			
				global $db,$_GET;
			
				$time2=explode("-",$startTimes[($m)]);
			
			
				if(strlen($time[0])==3)$time[0]="0".$time[0];
				if(strlen($time2[1])==3)$time2[1]="0".$time2[1];

				echo "<tr";


				$startTimeCurrently="0000";

				$result=$db->query("select DISTINCT startTime from view_timetables");
				if($result)while($row=$result->fetch_object()) {
					if($row->startTime > date("Hi")) break;
					$startTimeCurrently=$row->startTime;
				}

							//if( (substr($time[0],0,2).substr($time[0],2,2)) == $startTimeCurrently  || (substr($time[1],0,2).substr($time[1],2,2)) == $startTimeCurrently ) echo " class=presentactive";
							echo">";

				echo "<th class=empty>";
				echo substr($time[0],0,2).":".substr($time[0],2,2)." - ";
				if($school_config["plugins"]["timetable"]["skip_2nd_periods"]) {
					$time=explode("-",$startTimes[($m+1)]);
					if(strstr(":",$time[1]))echo $time[1];
					else echo substr($time[1],0,2).":".substr($time[1],2,2);
				}else{echo substr($time2[1],0,2).":".substr($time2[1],2,2);}
				for($i=0;$i<5;$i++) {

					$thisTs=strtotime(substr($oldStartDs,0,4)."-".substr($oldStartDs,4,2)."-".substr($oldStartDs,6,2));
					$thisDate=date("Ymd",$thisTs+3600*24*$i);


					$this->getLessonByTime($cat,$utid,$thisDate,$time[0],$time[1]);
				}
				echo "</th>";
				echo "</tr>";
				if($school_config["plugins"]["timetable"]["skip_2nd_periods"]) {$m++;}
			}
			echo "</table>";
		
		}
	}
	/*
	*/

	function getColById($table,$col,$id,$use_utid=false) {
        // teachers, fullname
		global $db;


		$out=$db->query("select $col from $table where ".$ut."id = $id")->fetch_object()->$col;
	
		return  utf8_encode($out);
	}

	function getDateURL() {
		global $_GET;
		return "&year=".($_GET["year"])."&month=".addZeros(2,$_GET["month"])."&day=".addZeros(2,($_GET["day"]));
	}

	function addZeros($i,$bla) {
		while(strlen($bla)<$i)$bla="0".$bla;
		return $bla;
	}

	function findIndex($r,$val) {
		for($i=0;$i<count($r);$i++) {
			if($val==$r[$i])return $i;
		}
	}

	if(!isset($_GET["year"]))$_GET["year"]=date("Y");
	if(!isset($_GET["month"]))$_GET["month"]=date("m");
	if(!isset($_GET["day"]))$_GET["day"]=date("d");
	if($security_level<2|!isset($_GET["cat"]))$_GET["cat"]="forms";


	$timeTable = new timetable();
	$timeTable->showWeek($_GET["cat"],$_GET["id"],$_GET["year"].addZeros(2,$_GET["month"]).addZeros(2,$_GET["day"]));

	echo "</div>";echo$after_specifictimetable_html;

}
