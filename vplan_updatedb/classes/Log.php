<?php
class Log{
    private $logFilePath;
    
    public function __construct($logFilePath){
        $this->logFilePath = $logFilePath;
    }
    
    function clear() {
        $logFile = fopen($this->logFilePath,"w") or die("Log File kann nicht geöffnet werden (".$this->logFilePath.")");
        $date = new DateTime();
        fwrite($logFile, $date->format("d-m-Y H:i:s").": "."Log-Datei cleared/erfolgreich erzeugt."."\n");	
        fclose($logFile);
    }

    //Write Text to file with date before
    function add($logLine){
        $logFile = fopen($this->logFilePath,"a") or die("Log File kann nicht geöffnet werden (".$this->logFilePath.")");
        $date = new DateTime();
        fwrite($logFile, $date->format("d-m-Y H:i:s").": ".$logLine."\n");	
        fclose($logFile);
    }

}
?>