<html lang="en">

<head>
    <?php
    // Ignore user aborts and allow the script
    // to run forever
    ignore_user_abort(true);
    ?>
</head>

<body>
    <?php
    //Show all erros
    ini_set('display_startup_errors', '0');
    ini_set('display_errors', 'stderr');
    error_reporting(0);    // E_ALL

    define("UPDATE_CHECK_MINUTES", 10);  // minutes for update check
    define("WORKING_FLAG_TIMEOUT", 30);  // minutes for resetting the lock

    function is_connected()
    {
        $connected = @fsockopen("www.google.com", 80);

        if ($connected) {
            $is_conn = true;

            fclose($connected);
        } else {
            $is_conn = false;
            echo ("no Internet Conntection<br>");
        }

        return $is_conn;
    }

    //wird alle 30 minuten ausgeführt oder wenn der GET Paramter "force" gesetzt ist
    include("../classes/Status.php");
    $status = new Status();

    $now = new DateTime();
    $actualTimeStamp = $now->getTimestamp();
    $timeStampDiff = $actualTimeStamp - $status->getLastTimeStampCheck();
    if ((($timeStampDiff > UPDATE_CHECK_MINUTES * 60) || (isset($_GET["force"])))
        && (is_connected()) ) {
        
        $status->setLastTimeStampCheck($actualTimeStamp);
        include("../classes/Log.php");
        $logFile = "../etc/fetch.log";
        $log = new Log($logFile);

        // stop, if there is another data fetch active
        if ($status->isWorking()) {
            if ($status->getWorkingActiveTime() < WORKING_FLAG_TIMEOUT*60) {
                die("<p>Already Working!</p>\n</body>\n</html>");
            } else {
                // seems something got wrong, reset lock
                $status->setWorking(false);
                $log->add("[WARNING] Working flag timeout (Flag reseted)");
            }
        }

        //$log->add("[INFO] Include Fetch.php.");
        include("../classes/UntisFetch.php");
        //$log->add("[INFO] Constrcut Fetcher.");
        $untis = new Untis($log, $status);
        //$log->add("[INFO] Start Fetch.");

        $date = new DateTime();
        $startStamp = $date->getTimestamp();
        $status->setWorking(true);

        
        $forceFetch = isset($_GET["force"]);
        
        // force fetch every two weeks, even if there is no data update on server
        $impTime = $status->getImportTime();
        if ($startStamp - $impTime > 3600*24*7*2) {
            $forceFetch = true;
        }
        
        echo ("<h3>" . $untis->fetch($forceFetch) . "</h3>");

        $date = new DateTime();
        $endStamp = $date->getTimestamp();
        
        $stamp = $endStamp - $startStamp;
        echo ("<br><u><strong>Request time: " . $stamp . "s</strong></u><br>More information in vplan_updatedb/etc/fetch.log");

        //LOG
        $log->add("[INFO] Fetch erfolgreich beendet (Request time: " . $stamp . "s)");

        unset($untis);
        
        $status->setWorking(false);
    } else {
        $wait = (UPDATE_CHECK_MINUTES * 60 - $timeStampDiff);
        $min = (int) ($wait / 60);
        $sec = $wait - $min * 60;
        $waitTime = $sec . " sec";
        if ($min > 0) {
            $waitTime = $min . " min " . $waitTime;
        }
        echo "<p>Skipped ... please wait for " . $waitTime . "</p>";
    }
    ?>
</body>

</html>