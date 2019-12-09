<?php
//calls the timetable display plugin, this script works like a kind of glue :)
if(!isset($_COOKIE["teachers"]))$security_level=0;
else $security_level=2;
require("plugins/specificTimeTable.php");

