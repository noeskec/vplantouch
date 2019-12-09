<?php
/*

	This file MUST be set read-only!!!

	It can overwrite and give permission to access private data without the need of a cookie to be set

*/


// in case teachers are activated, equals 1 if correct pin was entered or if pin is disabled, equals $_COOKIE["login"] if pin needs to be entered, equals 0 if teachers and rooms are deactivated
$login=$_COOKIE["login"];

//activate or deactivate tabbing
$teachers_enabled=1;

