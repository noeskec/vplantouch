<?php session_start(); 
/*
 * Copyright 2022 by Carsten Noeske.
 *
 * license: GNU General Public License v3.
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>VplanTouch Configurator</title>
        <style>
            /* CSS design by Thomas Perschke */
            * {
                font-family: Verdana;
            }
            body {
                margin: 50px;
            }
            body>section {
                margin: 50px 0;
                padding: 20px;
                border: 1px solid #000;
                border-radius: 5px;
                background-color: #eee;
            }
            h1, h2 {
                font-variant: small-caps;
            }
            h1 {
                letter-spacing: 4px;
            }
            h2 {
                margin-bottom: 16px;
            }
            p {
                margin-bottom: 10px;
            }
            ul {
                margin: 10px 20px;
            }
            code {
                font-family: monospace;
                font-size: 16px;
            }
            form {
                margin: 20px 0;
            }
            button {
                width: 200px;
            }
            table {
                margin: 20px;
            }
            td, th {
                padding: 8px;
            }
            .success, .failure {
                display: inline-block;
                margin: 0 10px;
                width: 12px;
                height: 12px;
                border-radius: 50%;
            }
            .success {
                background-color: green;
                border: 1px solid green;
            }
            .failure {
                background-color: red;
                border-left: 1px solid red;
                border-right: 1px solid red;
            }

            section section {
                margin: 20px 20px 50px 20px;
            }

            section>header, section>main, section>footer {
                padding: 10px;
            }

            section>header {
                font-weight: bold;
                background-color: #666;
                color: #fff;
                border: 1px solid #666;
                border-top-right-radius: 5px;
                border-top-left-radius: 5px;
            }

            section>main {
                border-left: 4px solid #666;
                border-right: 4px solid #666;
            }

            section>footer {
                padding: 4px;
                border: 1px solid #666;
                background-color: #666;
                color: #fff;
                border-bottom-right-radius: 5px;
                border-bottom-left-radius: 5px;
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0,0,0);
                background-color: rgba(0,0,0,0.4);
            }

            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 50%;
            }
            
            input {
                width: 99%;
                margin-bottom: 8px;
            }
        </style>
    </head>
    <body>

        <h1>VplanTouch Configuration</h1>

        <?php
        if (!isset($_SESSION["step"])) {
            $_SESSION['Config'] = array(
                'MySQL-Database' => array(
                    'Username' => 'root', 
                    'Password' => '', 
                    'Database-Name' => 'vplantouch', 
                    'Url' => 'localhost' 
                ),
                'WebUntis' => array(
                    'Url' => "https://erato.webuntis.com/WebUntis/jsonrpc.do?school=demo", 
                    'Username' => 'demotouch', 
                    'Password' => ''
                ),
                'Mastercode' => array(
                    'PIN' => '1234'
                )
            );
            $_SESSION["step"]=0;
        }
        
        $skey = 'Config';
        $groups = array_keys($_SESSION[$skey]);
        for ($i=0; $i < count($groups); $i++) {
            $group = $groups[$i];
            echo '<section id="'.$group.'">';
            echo '<header>'.$group.'</header>';
            echo '<main>';
            
            echo '<form action="vplan_config.php#'.$group.'" method="post">';
            $items = array_keys($_SESSION[$skey][$groups[$i]]);
            for ($k=0; $k < count($items); $k++) {
                $item=$items[$k];
                $itemId = str_replace("-","_",$group.'_'.$item);
                
                if (isset($_POST[$itemId])) {
                    $_SESSION[$skey][$group][$item] = $_POST[$itemId];
                }
                
                $type = $item=='Password' ? "password" : "text";
                echo '<label for="'.$itemId.'">'.$item.':</label><br>';
                echo '<input type="'.$type.'" id="'.$itemId.'" name="'.$itemId.'" value="'.$_SESSION[$skey][$group][$item].'"><br>';
            }
            echo '<input type="submit" value="Save '.$group.' configuration">';
            echo '</form>';
            
            echo '</main>';
            echo '<footer>';
            
            if (isset($_POST[str_replace("-","_",$group.'_'.$items[0])])) {
                handleSaving($group);
            }
            
            echo '</footer>';
            echo '</section>';
        }
        
        function handleSaving($group) {
            $result = false;
            switch ($group) {
                case 'MySQL-Database':
                    $result = saveVplanTouchConfig();
                    if ($result) saveUpdateDbConfig();
                    break;
                case 'WebUntis':
                    saveUpdateDbConfig();
                    
                    $updateUrl = getBaseUrl().'/vplan_updatedb/bin/cron_untis.php?force';
                    echo "<p>Use this link to update the database: <a href='".$updateUrl."'>".$updateUrl."</a></p>";
                    
                    break;
                case 'Mastercode':
                    $hash = hash("sha512",$_SESSION['Config']['Mastercode']['PIN']);
                    $filename = "../vplan_touch/config/pin_config.sha512";
                    if (!file_put_contents($filename,$hash)) {
                        echo "<p>Error: cannot write '".$filename."'.</p>";
                    } else {
                        echo "<p>writing of '".$filename."' successful.</p>";
                    }
                    
                    break;
                default: echo "<p>Internal error: Save request for unknown section received.<p>";
            }
            return $result;
        }
        
        function saveVplanTouchConfig() {
            $result = false;
            $sqlServer=$_SESSION['Config']['MySQL-Database']['Url']; 
            $user=$_SESSION['Config']['MySQL-Database']['Username']; 
            $passwd=$_SESSION['Config']['MySQL-Database']['Password']; 
            $dbname=$_SESSION['Config']['MySQL-Database']['Database-Name']; 
            
            $db=@mysqli_connect($sqlServer,$user,$passwd,$dbname);
            if (mysqli_connect_errno()) {
                $db=@mysqli_connect($sqlServer,$user,$passwd);
                if (mysqli_connect_errno()) {
                    $msg="Error: unable to connect to the database with these parameters (".mysqli_connect_error().")";
                } else {   
                    if (!$db->query("CREATE DATABASE IF NOT EXISTS ".$dbname)) {
                        $msg="Error: cannot access database '".$dbname."' with these parameters (".$db->error.")";    
                    } else {
                        $db->query("USE ".$dbname);
                        $DB_FILENAME = "../vplan_updatedb/webscheduler.sql";
                        $lines = file_get_contents($DB_FILENAME);
                        $db->multi_query($lines);
                        $result = true;
                    }
                }
            } else {
                $result = true;
            }
            
            if ($result) {
                try {
                    $msg = "MySQL successfully configured on ".$sqlServer;
                    $_SESSION["step"]=1;

                    // create vplan_touch db config file
                    $FILENAME = "../vplan_touch/bin/dbcon.php";
                    $dbfile = fopen($FILENAME, "w") or $msg="Error: Unable to open configuration file.";
                    if ($dbfile!=false) {
                        fwrite($dbfile,'<?php $db=mysqli_connect("'.$sqlServer.'","'.$user.'","'.$passwd.'","'.$dbname.'"); ?>');
                        fclose($dbfile);
                        $msg = $msg." / GUI dbcon.php written";
                        $_SESSION["step"]=2;
                    } else {
                        $result = false; 
                    }   
                } catch (Exception $ex) {
                    $msg="Error: unable to write '".$FILENAME."'.";
                    $result = false;
                }
            }
            if (isset($msg)) {
                echo "<p>".$msg."</p>";    
            }    
            return $result;
        }
        
        function saveUpdateDbConfig() {
            $result = false;
            $updateDB = array(
                    'Database' => array(
                        'Username' => $_SESSION['Config']['MySQL-Database']['Username'], 
                        'Password' => $_SESSION['Config']['MySQL-Database']['Password'], 
                        'Database-Name' => $_SESSION['Config']['MySQL-Database']['Database-Name'], 
                        'Host' => $_SESSION['Config']['MySQL-Database']['Url'] 
                    ),
                    'Data-Server' => array(
                        'Url' => $_SESSION['Config']['WebUntis']['Url'], 
                        'Username' => $_SESSION['Config']['WebUntis']['Username'], 
                        'Password' => $_SESSION['Config']['WebUntis']['Password']
                    )
            );
            
            $updateDbJSON = json_encode($updateDB,JSON_UNESCAPED_SLASHES);

            $FILENAME = "../vplan_updatedb/etc/logins.json";
            $dbfile = fopen($FILENAME, "w") or $msg="Error: Unable to open updatedb logins file.";
            if ($dbfile!=false) {
                fwrite($dbfile,$updateDbJSON);
                fclose($dbfile);
                echo "<p>UpdateDB logins.json written</p>";
                $result = true;
            }
            return $result;
        }
        
        
        function getActualUrl() {
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                $url = "https://";   
            else  
                $url = "http://";   
            
            // Append the host(domain name, ip) to the URL.   
            $url.= $_SERVER['HTTP_HOST'];   

            // Append the requested resource location to the URL   
            $url.= $_SERVER['REQUEST_URI'];    
            
            return $url;
        }
        
        function getBaseUrl() {
            $baseUrl = getActualUrl();
            $baseUrl = substr($baseUrl,0,strlen($baseUrl)-strlen(strrchr($baseUrl,"/")));
            $baseUrl = substr($baseUrl,0,strlen($baseUrl)-strlen(strrchr($baseUrl,"/")));
            return $baseUrl;
        }
        
        ?>                   

    </body>
</html>