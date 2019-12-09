<?php
class Log{
    private $logFile;
    private $logFilePath;
    
    public function __construct($logFilePath){
        $this->logFilePath = $logFilePath;

    }
    

    //Write Text to file with date before
    function add($logLine){
        $log = file_get_contents("../etc/fetch.log");	
        $this->logFile = fopen("../etc/fetch.log","w") or die("Log File kann nicht geöffnet werden (".$this->logFilePath.")");
        $date = new DateTime();
        fwrite($this->logFile, $log . $date->format("d-m-Y H-i-s").": ".$logLine."\n");	
        fclose($this->logFile);
    }

}
?>