<?php
/*
 * Copyright 2015, 2022 by Carsten Noeske, all rights reserved.
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
class UntisPost {
    private $id = 42;
    private $sessionID; 
    private $cUrlHandle;

    public function __construct($url) {
        $curlHndl = curl_init();
        $this->cUrlHandle = $curlHndl;
        
        curl_setopt($curlHndl, CURLOPT_URL, $url);
       
        curl_setopt($curlHndl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curlHndl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHndl, CURLOPT_FOLLOWLOCATION, true);   
    }
    
    public function __destruct() {
        curl_close($this->cUrlHandle); 
        $this->cUrlHandle = null;
    }
    
    public function setSessionID($sessionID) {
        $this->sessionID=$sessionID;
    }
    
    public function sendWebUntisPost($methodname,$params = null) {
        $jsonMsg = array("id"     => "GHSE-req-".$this->id, 
                         "method" => $methodname,
                         "params" => $params,
                         "jsonrpc"=> "2.0"
                   );
        $this->id++;
        
        $result = '{"error"= { "code" = "0008", message" = "Cannot access the Untis server."}}';
        if ($this->cUrlHandle == null) {
            return $result;
        }
        
		$curlHndl=$this->cUrlHandle;    

        if (strlen($this->sessionID) > 0) {
            curl_setopt($curlHndl,CURLOPT_COOKIE,'JSESSIONID='.$this->sessionID);
        } else {
            curl_setopt($curlHndl,CURLOPT_COOKIESESSION,true);
        }

        $data_string = json_encode($jsonMsg);  
        curl_setopt($curlHndl, CURLOPT_POSTFIELDS, $data_string);

        $httpHeaderFields = array(                   
            'Content-Type: application/json-rpc',
            'Content-Length: '.strlen($data_string)
        ); 
        curl_setopt($curlHndl, CURLOPT_HTTPHEADER, $httpHeaderFields);

        // On development server only! PHP-server should be configured correctly
        // and must be supplied with valid root certificates! NEVER USE THIS
        // ON A REAL SERVER!
        curl_setopt($curlHndl, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($curlHndl, CURLOPT_SSL_VERIFYPEER, 1);
        
        //echo "call ".$methodname."<br>\n";
        $res = curl_exec($curlHndl);
        if ($res != false) {
            $result = $res;
        }
        //echo $result."<br>\n";
        // // for decoding, please use the following statement outside this function
        // $json=json_decode($result,true);
        
        return $result;
        
    }

    public function sendWebUntisPostAndDecode($methodname,$params = null) {
        $result = $this->sendWebUntisPost($methodname,$params);
        $jsonObj = json_decode($result);
		if (isset($jsonObj->error)) {
			$jsonResultObj = $jsonObj->{'error'};
            $code = $jsonResultObj->{'code'};
            $message = $jsonResultObj->{'message'};
            error_log("untis_post error ".$code.": ".$message." (method: ".$methodname.")");
        }
        return $jsonObj;
    }
}
?>
