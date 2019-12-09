<?php
class Status{
    const VERSION = "2.9";
	private $status_file = "../etc/status.json";

    // define default values for non-existing entries
    private function ensureExistence($array) {
        if ($array == null) {
            $array = array();
        }
        if (is_array($array)) {
            if (!isset($array['importTime'])) {
                if ( is_array($array) ) { $array['importTime'] = 0; }
            }

            if (!isset($array['working'])) {
                $array['working']=false;
            }

            // version is always upgraded to the latest one
            $array['version']=Status::VERSION;

            if (!isset($array['untis-timestamp'])) {
                $array['untis-timestamp']=0;
            }

            if (!isset($array['lastTimeStampCheck'])) {
                $array['lastTimeStampCheck']=0;
            }
            if (!isset($array['workingFlagTimeStamp'])) {
                $array['workingFlagTimeStamp']=0;
            }
        } else {
            echo("issue, since we have no array");
            print_r($array);
        }
        return $array;
    }

    private function readData() {
        $array = json_decode(file_get_contents($this->status_file),true);
        $array = $this->ensureExistence($array);
        return $array;
    }

    private function writeData($array = null) {
        $array = $this->ensureExistence($array);
		$text = json_encode($array);

        $myfile = fopen($this->status_file, "w") or die("Unable to open file!");
		fwrite($myfile, $text);
		fclose($myfile);
    }

    public function writeImportTime($timestamp){
		$array = $this->readData();
		$array['importTime']=$timestamp;
        $this->writeData($array);
	}

	public function writeUntisImportTime($timestamp){
		$array = $this->readData();
		$array['untis-timestamp']=$timestamp;
        $this->writeData($array);
	}
	
	public function getImportTime(){
		$array = $this->readData();
        return $array["importTime"];
	}

	public function getUntisTimestamp(){
		$array = $this->readData();
        return $array["untis-timestamp"];
	}
	
	public function setWorking($bool){
		$array = $this->readData();
		$array['working']=$bool;
        if ($bool) {
            $array['workingFlagTimeStamp']=time();
        } else {
            $array['workingFlagTimeStamp']=0;
        }
        $this->writeData($array);
	}
	
	public function isWorking(){
		$array = $this->readData();
        return $array["working"];
	}
    
    public function getWorkingActiveTime() {
		$array = $this->readData();
        if ($array["working"]) {
            return time()-$array['workingFlagTimeStamp'];
        } else {
            return 0;
        }
    }
	
	public function getVersion(){
		$array = $this->readData();
        return $array["version"];
	}

    public function setLastTimeStampCheck($timestamp) {
		$array = $this->readData();
		$array['lastTimeStampCheck']=$timestamp;
        $this->writeData($array);
    }


    public function getLastTimeStampCheck() {
		$array = $this->readData();
        return $array['lastTimeStampCheck'];
    }
}
?>
