<?php
/* 
 * Copyright 2015, 2019 by Carsten Noeske, all rights reserved.
 *
 * A license is granted for non-commercial use for the benefit of GHSE 
 * pupils AND teachers. 
 *
 * For avoidance of doubt, this means that the application using this file 
 * respects personal rights of pupils and teachers and the copyright of  
 * lesson materials.
 * The protection of privacy data has to be accomplished, e.g. teacher's 
 * abbreviations may only to be displayed to users, who are proven to be 
 * member of the GHSE school community. 
 * These annotation are not to be considered as exhaustive.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * All modifications and extensions have to be returned to the copyright 
 * holder, who obtains all rights for any purpose including to license it
 * to a third party.
 */
	include "untis_post.php";
    $sessionID=""; $cUrlHandle = null; $personType=""; $personId=""; $klasseId="";
 
 
    function webUntisAuth($username,$passwd) {
        global $sessionID;
        $paramArray = array("user" => $username,
                            "password" => $passwd,
                            "client" => "CLIENT"
                      );
        $jsonObj = sendWebUntisPostAndDecode("authenticate",$paramArray);
		if (array_key_exists('error', $jsonObj)) {
			
			$sessionID = "";
			$klasseId = "";
			$personId = "";
			$personType= "";

            return -1;
		}else{		
			
			$jsonResultObj = $jsonObj->{'result'};
			$sessionID = $jsonResultObj->{'sessionId'};
			$klasseId = $jsonResultObj->{'klasseId'};
			$personId = $jsonResultObj->{'personId'};
			$personType= $jsonResultObj->{'personType'};
			
			return $sessionID;
		}
    }
    
    function webUntisLogout() {
        global $sessionID; global $cUrlHandle;
        $result = sendWebUntisPost("logout",null);
        curl_close($cUrlHandle); $cUrlHandle = null;
        $sessionID="";
        return $result;
    }	

    function webUntisGetLastImportTime() {
        $jsonObj = sendWebUntisPostAndDecode("getLatestImportTime",null);
        $jsonResultObj = $jsonObj->{'result'};
        // Untis delivers ms, PHP assumes seconds
        $timeStamp = (int) substr($jsonResultObj,0,strlen($jsonResultObj)-3);
        return $timeStamp;
    }

    function webUntisGetLastImportTimeAsString() {
        $timeStamp = webUntisGetLastImportTime();
        return date("d.m.Y, H:i:s", $timeStamp);
    }

    function webUntisRequestBaseClasses() {
        $jsonObj = sendWebUntisPostAndDecode("getKlassen",null);
        $jsonResultObj = $jsonObj->{'result'};
        return $jsonResultObj;
    }


?>
