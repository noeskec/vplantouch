<?php
/*
 * Copyright 2015 by Carsten Noeske, all rights reserved.
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
    $id = 42;
    function sendWebUntisPost($methodname,$params = null) {
        global $id; global $sessionID; global $cUrlHandle;
        
        $jsonMsg = array("id"     => "GHSE-req-".$id, 
                         "method" => $methodname,
                         "params" => $params,
                         "jsonrpc"=> "2.0"
                   );
        
        $id=$id+1;

        //$postfields = array('field1'=>$jsonmsg);
        $data_string = json_encode($jsonMsg);  
        //print $data_string."<br />";
        
        if ($cUrlHandle == null) {
            $cUrlHandle = curl_init();
        }
		
        // include login data from external file
        include("../etc/eLogin.php");
		
        // // demo-server-login : user="api", passwd="api", and use the following URL:
        // $url = 'https://demo.webuntis.com/WebUntis/jsonrpc.do?school=demo_inf';

        // // local test
        // $url = 'http://localhost:8080'; // e.g. echo web server
        curl_setopt($cUrlHandle, CURLOPT_URL, $url);
       
        
        curl_setopt($cUrlHandle, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cUrlHandle, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($cUrlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cUrlHandle, CURLOPT_FOLLOWLOCATION, true);
        $httpHeaderFields = array(                                                                          
            'Content-Type: application/json-rpc',                                                                                
            'Content-Length: ' . strlen($data_string)
        ); 
        if (strlen($sessionID) > 0) {
            curl_setopt($cUrlHandle,CURLOPT_COOKIE,'JSESSIONID='.$sessionID);
        } else {
            curl_setopt($cUrlHandle,CURLOPT_COOKIESESSION,true);
        }
        curl_setopt($cUrlHandle, CURLOPT_HTTPHEADER, $httpHeaderFields);
        
        // On development server only! PHP-server should be configured correctly
        // and must be supplied with valid root certificates! NEVER USE THIS
        // ON A REAL SERVER!
        curl_setopt($cUrlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($cUrlHandle, CURLOPT_SSL_VERIFYPEER, 1);
        
        $result = curl_exec($cUrlHandle);
        
        // // for decoding, please use the following statement outside this function
        // $json=json_decode($result,true);
        
        return $result;
        
    }

    function sendWebUntisPostAndDecode($methodname,$params = null) {
        $result = sendWebUntisPost($methodname,$params);
        $jsonObj = json_decode($result);

		if (isset($jsonObj->{'error'})) {
			$jsonResultObj = $jsonObj->{'error'};
			$code = $jsonResultObj->{'code'};
			$message = $jsonResultObj->{'message'};
			error_log("untis_post error ".$code.": ".$message." (method: ".$methodname.")");
		}

        return $jsonObj;
    }
?>
