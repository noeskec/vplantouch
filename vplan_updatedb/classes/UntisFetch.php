<?php
include("../lib/UntisAccess.php");
class Untis extends UntisAccess {

	/*
	*  0001 = Fetch started;
	*  0002 = Fetch finished;
	*  0003 = New timestamp is same as old timestamp;
	*  0004 = Error truncate main databse;
	*  0005 = Error by untis server;
	*  0006 = Error untis timestamp null
	*/
	
		
	private	$dbPath = "../lib/db.php";
	private $db = null;

	private $dbTMP = null;
	private $status = null;
	private $log = null;
	private $error = null;

	//Default Method for getting data from untis
	private function getData($methode,$parameters = null){
		if(is_array($parameters) || $parameters == null){
			if(is_string($methode)){
				$untisReturnJson = $this->sendWebUntisPost($methode,$parameters);
				$untisReturn = json_decode($untisReturnJson,true);
				if(isset($untisReturn["result"])){
					return $untisReturn["result"];
				}else{
					if(isset($untisReturn["error"])){
						$this->log->add("[Error] Untis '".$untisReturn["error"]["message"]."'");
						echo("Error: ".$untisReturn["error"]["message"]."<br>");
						if($this->error != null)
						  array_push($this->error,"0005");
						else
						  $this->error = array("0005");
						$this->webUntisLogout();
						die(json_encode($this->error));
						return null;
					}
				}
			}
		}
        return null;
	}

	// contructor
	public function __construct($log,$status){
        ini_set('max_execution_time', 3000);
        
         // include login data from external file
        include("../etc/eLogin.php");
		
        // // demo-server-login : user="api", passwd="api", and use the following URL:
        // $url = 'https://demo.webuntis.com/WebUntis/jsonrpc.do?school=demo_inf';

        // // local test
        // $url = 'http://localhost:8080'; // e.g. echo web server  
        
        parent::__construct($url,$username,$password);
        
        include($this->dbPath);
        $this->db = $db;
        $this->dbTMP = $dbTMP;
        
		$this->status = $status;	
		$this->log = $log;
    }

    public function __destruct() {
        parent::__destruct();
        //$this->log->add("[INFO] fetcher destructed.");
    }

	//Insert strcuture to default database
	private function insertStructure($filename){
		$templine = '';
		// Read in entire file
		$lines = file($filename);
		// Loop through each line
		foreach ($lines as $line)
		{
		// Skip it if it's a comment
		if (substr($line, 0, 2) == '--' || $line == '')
			continue;

		// Add this line to the current segment
		$templine .= $line;
		// If it has a semicolon at the end, it's the end of the query
			if (substr(trim($line), -1, 1) == ';')
			{
				// Perform the query
				$this->dbTMP->query($templine);
				// Reset temp variable to empty
				$templine = '';
			}
		}
	}

    // get timestamp of untis server
    public function getUntisTimeStampOnServer() {
        return $this->getData("getLatestImportTime");
    }

	//Check untis timestamp, but do not save it!
	private function checkImportTime($untisServerImportTime){
		if($untisServerImportTime != null){
				$lastUntisImportTime = $this->status->getUntisTimestamp();
				if ($lastUntisImportTime == $untisServerImportTime) {
					$this->log->add("[INFO] Untis Timestamp is same as saved timestamp");
					return "0003";
				}else{
                    // to be performed only, if everything else was successful => not here!
					//$this->status->writeUntisImportTime($importTime);
                    
                    $this->log->clear();
					$this->log->add("[INFO] Untis Timestamp is different to saved timestamp");
					return "";
				}
		}else{
			$this->log->add("[ERROR] Untis Timestamp is null by checking");
			return "0006";
		}
	}

	// Fetch all data, allow forcing
	public function fetch($force){
        $untisServerTimeStamp = $this->getUntisTimeStampOnServer();
        $result = $this->checkImportTime($untisServerTimeStamp);
        if ((!$force) && (strlen($result) > 0)) {
            // $this->log->add("[INFO] fetch abort with ".$result);
            $this->error = array($result);
        } else {
            if ($force) {
                $this->log->add("[INFO] update is forced");
            } 
            $this->log->add("[INFO] start fetch from Untis server");
            // ensure there is no old temporary database (in case of timeout ...)
            $this->deleteTMP(); 

            $this->createTMP();
            
            $arrayToFetch = array("0"=>"getTeachers","1"=>"getKlassen",	"2"=>"getSubjects",	"3"=>"getRooms","4"=>"getSchoolyears","5"=>"getTimetable","6"=>"getExams");
            if(!$this->truncateTable($this->dbTMP)){
                $this->error = array("0004");
                return json_encode($this->error);
            }
            $this->error = array("0001");
            for($i = 0; $i<count($arrayToFetch); $i++){
                switch($i){
                    case 0:
                        $this->fetchTeachers($arrayToFetch,$i);
                    break;
                    case 1:
                        $this->fetchForms($arrayToFetch,$i);
                    break;
                    case 2:
                        $this->fetchSubjects($arrayToFetch,$i);
                    break;
                    case 3:
                        $this->fetchRooms($arrayToFetch,$i);
                    break;
                    case 4:
                        $this->fetchSchoolyears($arrayToFetch,$i);
                    break;
                    case 5:
                        $this->fetchTimetables($arrayToFetch,$i);
                    break;
                }
            }
            $untisTimeStamp = $this->fetchUntisTimeStamp();

            $this->checkTeachers();
            $this->checkForms();
            $this->checkRooms();
            $this->checkSubjects();

            $this->copyTables();
            $this->deleteTMP(); 

            // wirte time stamp for import to status file
            $this->status->writeImportTime(time());

            // // update untisTimeStamp in status: 
            // // must be written as last action. If there has been 
            // // an error before, we may not declare this fetch as valid.
            $this->status->writeUntisImportTime($untisTimeStamp);

            array_push($this->error,"0002");
        } 
        return json_encode($this->error);
	}
	
	
	//Check if table in database is empty
	private function isEmptyTable($tableName,$db){
		//$sql = "SELECT id FROM ".$tableName." WHERE `id` IS NOT NULL";
		//$result = mysqli_num_rows($db->query($sql));
        $sql = "SELECT COUNT(*) FROM ".$tableName;
		$sqlresult = $db->query($sql);
        $row = $sqlresult->fetch_row();
        $result = $row[0];
		if($result>0){
			return false;
		}else{
			return true;
		}
	
	}
	
	//Truncate Tables from @param[arrayList]
	private function truncateTable($db,$arrayList = null){
		$success = true;
		if($arrayList != null && is_array($arrayList)){
			for($i = 0; $i<count($arrayList);$i++){
				$sql = "TRUNCATE ".$arrayList[$i];
				$result = $db->query($sql);
				if(!$result){
					$success = false;
					break;
				}
			}
		}else if($arrayList != null && is_string($arrayList)){
			$sql = "TRUNCATE ".$arrayList;
			$result = $db->query($sql);
			if(!$result){
				$success = false;
			}
		}else{
			$t = $db->query("show tables"); 
			while($table = mysqli_fetch_array($t)) { 
				$sql = "TRUNCATE ".$table[0] ;
				$result = $db->query($sql);
				if(!$result){
					$success = false;
					break;
				}
			}			
		}
		return $success;
	}
	//Copy all tables from temp database to main databse
	private function copyTables(){
        $errorText = "";
        $arrayList = array("metadata","forms","rooms","teachers","timetables","subjects",
                           "schoolyear","ttforms","ttteachers","ttsubjects","ttrooms");
        for($i  = 0;$i<count($arrayList);$i++){
            $tableName = $arrayList[$i];
            if(!$this->isEmptyTable($tableName,$this->dbTMP)){
                if($this->truncateTable($this->db,$tableName)){
                    $sql = "INSERT INTO ".$tableName." SELECT * FROM webschedulertmp.".$tableName;
                    $result = $this->db->query($sql);
                }else{
                    $errorText = $errorText."\nDB-Error: truncate in DB failed (".$arrayList[$i].")";
                }
            }else{
                $errorText = $errorText."\nDB-Error: empty table in DB (".$arrayList[$i].")";
            }
        }

        //truncate tmpDatabase
        if(!$this->truncateTable($this->dbTMP)){
            array_push($this->error,"0004");
        }
	}

    // creates tempory database
    private function createTMP() {
        // create tempory database
        $sql = "CREATE DATABASE webschedulertmp";
        $this->dbTMP->query($sql) or die($this->dbTMP->error);
        $this->dbTMP->query("USE webschedulertmp") or die($this->dbTMP->error);
        $this->insertStructure("../webscheduler.sql");
    }    
    
    
	//delete temp database
	private function deleteTMP(){
		$sql = "DROP DATABASE IF EXISTS webschedulertmp;";
		$result = $this->dbTMP->query($sql);
	}    

	
	private function fetchTeachers($arrayToFetch,$i){		
		$methode = $arrayToFetch[$i];
		$arrayUntis = $this->getData($methode);
		for($n = 0; $n<count($arrayUntis);$n++){
			$arrayPart = $arrayUntis[$n];
			$sql = "INSERT INTO teachers (utid,name,fname,fullname) VALUES ('".$arrayPart["id"]."','".$arrayPart["name"]."','".$arrayPart["foreName"]."','".$arrayPart["longName"]."')";
			$result = $this->dbTMP->query($sql);
		}
	}
	
    private function fetchUntisTimeStamp() {
        $importTime = $this->getUntisTimeStampOnServer();
        $timeStamp = (int) substr($importTime,0,strlen($importTime)-3);
		$sql = "INSERT INTO metadata (untisTimeStamp,lastFetchTimeStamp) VALUES ('".date("Y-m-d H:i:s",$timeStamp)."','".date("Y-m-d H:i:s",time())."')";
        $result = $this->dbTMP->query($sql);
        return $importTime;
	}
	


	private function checkTeachers(){
        $sql = "SELECT id FROM teachers;";
        $dbResult = $this->dbTMP->query($sql);
        while($row = $dbResult->fetch_assoc()){
            $teacherId = $row["id"];
            $sql = "SELECT id FROM ttteachers WHERE teacherId = '$teacherId'";
            $dbResultEx = $this->dbTMP->query($sql);

            if($dbResultEx != null && $dbResultEx->num_rows<=0){
                $sql = "DELETE FROM teachers WHERE id = '$teacherId'";
                $dbResultDel = $this->dbTMP->query($sql);
            }
        }
	}

	private function checkForms(){
		$sql = "SELECT id FROM forms;";
		$dbResult = $this->dbTMP->query($sql);
		while($row = $dbResult->fetch_assoc()){
			$formId = $row["id"];
			$sql = "SELECT id FROM ttforms WHERE formId = '$formId'";
			$dbResultEx = $this->dbTMP->query($sql);
			
			if($dbResultEx != null && $dbResultEx->num_rows<=0){
				$sql = "DELETE FROM forms WHERE id = '$formId'";
				$dbResultDel = $this->dbTMP->query($sql);
			}
		}
	}
    
	private function checkRooms(){
		$sql = "SELECT id FROM rooms;";
		$dbResult = $this->dbTMP->query($sql);
		while($row = $dbResult->fetch_assoc()){
			$roomId = $row["id"];
			$sql = "SELECT id FROM ttrooms WHERE roomId = '$roomId'";
			$dbResultEx = $this->dbTMP->query($sql);
			
			if($dbResultEx != null && $dbResultEx->num_rows<=0){
				$sql = "DELETE FROM rooms  WHERE id = '$roomId'";
				$dbResultDel = $this->dbTMP->query($sql);
			}
		}
	}
    
	private function checkSubjects(){
		$sql = "SELECT id FROM subjects;";
		$dbResult = $this->dbTMP->query($sql);
		while($row = $dbResult->fetch_assoc()){
			$subjectId = $row["id"];
			$sql = "SELECT id FROM ttsubjects WHERE subjectId = '$subjectId'";
			$dbResultEx = $this->dbTMP->query($sql);
			
			if($dbResultEx != null && $dbResultEx->num_rows<=0){
				$sql = "DELETE FROM subjects WHERE id = '$subjectId'";
				$dbResultDel = $this->dbTMP->query($sql);
			}
		}
	}
	
    private function fetchForms($arrayToFetch,$i){
		$methode = $arrayToFetch[$i];
		$arrayUntis = $this->getData($methode);
		for($n = 0; $n<count($arrayUntis);$n++){
			$arrayPart = $arrayUntis[$n];
			$sql = "INSERT INTO forms (utid,name,fullname) VALUES ('".$arrayPart["id"]."','".$arrayPart["name"]."','".$arrayPart["longName"]."')";
			$result = $this->dbTMP->query($sql);
		}		
	}
	
	private function fetchSubjects($arrayToFetch,$i){
		$methode = $arrayToFetch[$i];				
		$arrayUntis = $this->getData($methode);
		for($n = 0; $n<count($arrayUntis);$n++){
			$arrayPart = $arrayUntis[$n];
			$sql = "INSERT INTO subjects (utid,name,fullname) VALUES ('".$arrayPart["id"]."','".$arrayPart["name"]."','".$arrayPart["longName"]."')";
			$result = $this->dbTMP->query($sql);
		}		
	}

	private function fetchRooms($arrayToFetch,$i){
		$methode = $arrayToFetch[$i];				
		$arrayUntis = $this->getData($methode);
		for($n = 0; $n<count($arrayUntis);$n++){
			$arrayPart = $arrayUntis[$n];
			$sql = "INSERT INTO rooms (utid,name,fullname,building) VALUES ('".$arrayPart["id"]."','".$arrayPart["name"]."','".$arrayPart["longName"]."','".$arrayPart["building"]."')";
			$result = $this->dbTMP->query($sql);
		}	
		
	}
	
	private function fetchSchoolyears($arrayToFetch,$i){
		$methode = $arrayToFetch[$i];				
		$arrayUntis = $this->getData($methode);
		for($n = 0; $n<count($arrayUntis);$n++){
			$arrayPart = $arrayUntis[$n];
			$sql = "INSERT INTO schoolyear (utid,name,startDate,endDate) VALUES ('".$arrayPart["id"]."','".$arrayPart["name"]."','".$arrayPart["startDate"]."','".$arrayPart["endDate"]."')";
			$result = $this->dbTMP->query($sql);
		}
			
	}
	//
	private function fetchTimetables($arrayToFetch,$i){	
		$query = "SELECT * FROM forms";
		$todayh = getDate();
		$month = (date('m', strtotime('-0 month')));
		if($month<9){
			$stringYear = $todayh["year"];
			$year ="SELECT startDate FROM `schoolyear` WHERE `name` LIKE '%{$stringYear}%'";
			$year = $this->dbTMP->query($year);
			$startyear = mysqli_fetch_row($year);
			$year ="SELECT endDate FROM `schoolyear` WHERE `name` LIKE '%{$stringYear}%'";
			$year = $this->dbTMP->query($year);
			$endyear = mysqli_fetch_row($year);
		}else{
			$stringYear = $todayh["year"] + 1;
			$year ="SELECT startDate FROM `schoolyear` WHERE `name` LIKE '%{$stringYear}%'";
			$year = $this->dbTMP->query($year);
			$startyear = mysqli_fetch_row($year);
			$year ="SELECT endDate FROM `schoolyear` WHERE `name` LIKE '%{$stringYear}%'";
			$year = $this->dbTMP->query($year);
			$endyear = mysqli_fetch_row($year);
		}	
		$day = $todayh["mday"];
		$month = $todayh["mon"];
		$year = $todayh["year"];
		$pastMonthDays = date("t",strtotime("-1 month"));
		$weekDay = date("N",time());
		$day = $day - $weekDay +1;
		
		$monthDays = date("t",time());
		
		$pastMonth = $month;
		$pastDay = $day -7;
		$pastYear = $year;
		
		if($pastDay <=0){
			$pastDay = $pastMonthDays + $pastDay;
			$pastMonth = $month - 1;
			if($pastMonth == 0){
				$pastMonth = 12;
				$pastYear = $pastYear -1;
			}
			
		}
		$futurDay = $day+(4*7) +4;
		$futurMonth = $month;
		$futurYear = $year;
		if($futurDay > $monthDays){
			$diff = $futurDay - $monthDays;
			$futurDay = $diff;
			$futurMonth++;
			if($futurMonth > 12){
				$futurMonth = 1;
				$futurYear++;
			}
		}
		
		if(strlen($pastDay)==1){
			$pastDay = "0".$pastDay;
		}
		if(strlen($pastMonth)==1){
			$pastMonth = "0".$pastMonth;
		}
		
		if(strlen($futurDay)==1){
			$futurDay = "0".$futurDay;
		}
		if(strlen($futurMonth)==1){
			$futurMonth = "0".$futurMonth;
		}
			
		$startyearOwn = $pastYear.$pastMonth.$pastDay;
		$endyearOwn = $futurYear.$futurMonth.$futurDay;
	
		
		if($startyearOwn < $startyear[0]){
			$startyearOwn = $startyear[0];
		}
	
		if($endyearOwn > $endyear[0]){
			$endyearOwn = $endyear[0];
		}
		$queryResult = $this->dbTMP->query($query);
		while ($row = mysqli_fetch_assoc($queryResult)){
			$methode = $arrayToFetch[$i];			
			$param = array(
					"id"=>$row["utid"],
					"type"=>"1",
					"startDate"=>$startyearOwn,
					"endDate"=>$endyearOwn
			);
			$arrayUntis = $this->getData($methode,$param);
			for($n = 0; $n<count($arrayUntis);$n++){
					$arrayPart = $arrayUntis[$n];
					$arrayTimeTable = $arrayUntis[$n];
					$arrayKl = $arrayTimeTable["kl"];
					$forms = "";
					foreach($arrayKl as $form){
						$forms = $forms.$form["id"].";";
					}
					$arrayTe = $arrayTimeTable["te"];
					$teachers = "";
					foreach($arrayTe as $teacher){
						$teachers = $teachers.$teacher["id"].";";
					}
								
					$arrayRo = $arrayTimeTable["ro"];
					$rooms = "";
					foreach($arrayRo as $room){
						$rooms = $rooms.$room["id"].";";
					}
								
					$arraySub = $arrayTimeTable["su"];
					$subjects = "";
					foreach($arraySub as $subject){
						$subjects = $subjects.$subject["id"].";";
					}
					$replaced = 0;
					$cancelled = 0;
					if(isset($arrayTimeTable["code"])){
						$arrayStatus = $arrayTimeTable["code"];
						$status = $arrayStatus;
						if($status == "cancelled"){
							$cancelled  = 1;
						}
						if($status == "irregular"){
							$replaced  = 1;
						}
					}
					$startTime = $arrayPart["startTime"];
					$endTime = $arrayPart["endTime"];
					if($startTime == "930"){
						$endTime = "1015";
					}
					
					
					
					
					$sql = "INSERT INTO timetables(date,startTime,endTime,is_replacement,is_removed) VALUES 
					('".$arrayPart["date"]."','".$arrayPart["startTime"]."','".$endTime."','".$replaced."','".$cancelled."')";
					
					
					$result = $this->dbTMP->query($sql);
					
					$sql = "SELECT MAX(id) FROM timetables;";
					$id = $this->dbTMP->query($sql)->fetch_row();
					if(isset($id[0])){
						$id = $id[0];
						$this->setForeignKeys($id,$forms,$subjects,$rooms,$teachers);
					}
			}
			
		}	
	}
	
	//Set ForingKeys for data verification
	private function setForeignKeys($id,$forms,$subjects,$rooms,$teachers){
	
        $arrayUtid = $forms;
        $arrayUtid = explode(";",$arrayUtid);
        foreach($arrayUtid as $utid){
            if(is_numeric($utid)){
                $newSQL = "SELECT form.id FROM forms form WHERE utid='$utid'";
                $newResult = $this->dbTMP->query($newSQL);
                $formId = ($newResult->fetch_assoc()["id"]);
                $sql = "INSERT INTO ttforms (timetableId,formId) VALUES ('$id','$formId');";	
                $this->dbTMP->query($sql);
            }
        }

        $arrayUtid = $subjects;
        $arrayUtid = explode(";",$arrayUtid);
        foreach($arrayUtid as $utid){
            if(is_numeric($utid)){
                $newSQL = "SELECT subject.id FROM subjects subject WHERE utid='$utid'";
                $newResult = $this->dbTMP->query($newSQL);
                $subjectId = ($newResult->fetch_assoc()["id"]);
                $sql = "INSERT INTO ttsubjects (timetableId,subjectId) VALUES ('$id','$subjectId');";	
                $this->dbTMP->query($sql);
            }
        }
        $arrayUtid = $rooms;
        $arrayUtid = explode(";",$arrayUtid);
        foreach($arrayUtid as $utid){
            if(is_numeric($utid)){
                $newSQL = "SELECT room.id FROM rooms room WHERE utid='$utid'";
                $newResult = $this->dbTMP->query($newSQL);
                $roomId = ($newResult->fetch_assoc()["id"]);
                $sql = "INSERT INTO ttrooms (timetableId,roomId) VALUES ('$id','$roomId');";	
                $this->dbTMP->query($sql);
            }
        }
        $arrayUtid = $teachers;
        $arrayUtid = explode(";",$arrayUtid);
        foreach($arrayUtid as $utid){
            if(is_numeric($utid)){
                $newSQL = "SELECT teacher.id FROM teachers teacher WHERE utid='$utid'";
                $newResult = $this->dbTMP->query($newSQL);
                $teacherId = ($newResult->fetch_assoc()["id"]);
                $sql = "INSERT INTO ttteachers (timetableId,teacherId) VALUES ('$id','$teacherId');";	
                $this->dbTMP->query($sql);
            }
        }
	}
}
?>
