<?php
//this file isn't going to be affected by any updates

// call http://localhost/vplan_touch/?nav=bake_config after making changes in this file in order to apply thoose changes

$school_config["conf"]["lang"]="de"; // recommended: $school_config["conf"]["lang"]="de"; || Possible values: en, de, fr, es

/*
* Do you want to have your configuration on a server, so all the vplan touchscreens can be configured at once? please 
* change settings in this file once. Then do the stuff explained in "README.md".- Now upload the file school_config.json 
* to your server which is supposed to become a config server. Then come back to this file and change the value of
* $school_config["conf"]["conf_server_path"] to the file, e.g: https://www.example.com/direcory/school_config.json
* Then rerun the steps in README.md and your done. (Now the contents of school_config.json changed again). If you now have
* a second vplan touchscreen, please just copy the newer school_config.json, which is located in the same directory as this file,
* to your second touchscreen and so on...
*/
$school_config["conf"]["conf_server_path"]=""; // set false, if you want to use the config, used in this file

if($school_config["conf"]["conf_server_path"]=="") {

	$pin="1234";
	$school_config["dash"]["password"]["teacher"]=hash("sha512","0MjM5dTg5F03101998dTJuFog5VSg9KVovJlRSejk4cjM0cj187QzMnI0".$pin."VCYvUsKnCWi8oIlpSPVUoKSGHSELCp1UoKVJ1N42DM5dXI");
	$pin=null;

	/*division of forms*/
	$school_config["home"]["tiles"]["forms"][1]["html"]="TG/SG";
	$school_config["home"]["tiles"]["forms"][1]["regex"]="/\b[ts]g/i";

	$school_config["home"]["tiles"]["forms"][2]["html"]="BF/BK";
	$school_config["home"]["tiles"]["forms"][2]["regex"]="/b[kf]/i";

	$school_config["home"]["tiles"]["forms"][3]["html"]="BS";
	$school_config["home"]["tiles"]["forms"][3]["regex"]="/\b.[0-9]/i";

	$school_config["home"]["tiles"]["forms"][4]["html"]="Mehr";

	$school_config["home"]["tiles"]["forms"]["auto_select_page"]=0; //0(default)=disabled, 1-4=show page N

	/*division of teachers*/
	$school_config["home"]["tiles"]["teachers"][1]["html"]="A-G";
	$school_config["home"]["tiles"]["teachers"][1]["regex"]="/[a-g]/i";

	$school_config["home"]["tiles"]["teachers"][2]["html"]="H-K";
	$school_config["home"]["tiles"]["teachers"][2]["regex"]="/[h-k]/i";

	$school_config["home"]["tiles"]["teachers"][3]["html"]="L-R";
	$school_config["home"]["tiles"]["teachers"][3]["regex"]="/[l-r]/i";

	$school_config["home"]["tiles"]["teachers"][4]["html"]="S-Z";

	$school_config["home"]["tiles"]["teachers"]["auto_select_page"]=0; //0(default)=disabled, 1-4=show page N

	/*division of rooms*/ /*ml25*/
	$school_config["home"]["tiles"]["rooms"][1]["html"]="Gewerbliche";
	$school_config["home"]["tiles"]["rooms"][1]["regex"]="/\b[0-9]/i";

	$school_config["home"]["tiles"]["rooms"][2]["html"]="Hauswirtschaft";
	$school_config["home"]["tiles"]["rooms"][2]["regex"]="/\bh/i";

	$school_config["home"]["tiles"]["rooms"][3]["html"]="C / Werkstätten";
	$school_config["home"]["tiles"]["rooms"][3]["regex"]="/\b[wc]/i";

	$school_config["home"]["tiles"]["rooms"][4]["html"]="Mehr";

	$school_config["home"]["tiles"]["rooms"]["auto_select_page"]=0; //0(default)=disabled, 1-4=show page N

	/* timetable config */
	$school_config["plugins"]["timetable"]["skip_2nd_periods"]=true; // == true if your only having double periods   || Recommended value: FALSE
	$school_config["plugins"]["timetable"]["show_teachers_email_adress"]=true; // == true if your only having double periods   || Recommended value: FALSE

	// this is, if you want to force times to be displayed, even if there is no period in the whole week | otherwise, just delete this line or set it =null
	$school_config["plugins"]["timetable"]["period_times_always_visible"]=array("0745-0830","0830-0915","0930-1015","1015-1100","1120-1205","1205-1250"); 
	
	$school_config["conf"]["pinlock_enabled"]=false;

}

include("../etc/v.php");

$school_config["meta"]["ts"]=time();
$school_config["meta"]["v"]=$v;

if(is_file("tmp.school_config_generate_file_insert.php") && strstr(file_get_contents("tmp.school_config_generate_file_insert.php"),"<?php"))include("tmp.school_config_generate_file_insert.php");

chmod("../config/school_config.json",0755);
if(file_put_contents("school_config.json",json_encode($school_config))) {
	echo "config was written to ../config/school_config.json";
} else {
	echo "error writing config to ../config/school_config.json. please check writing permissions";
}
echo("<iframe src=../bin/refresh_caches.php?force style=border:none;width:100%;margin-left:-8px></iframe>");
