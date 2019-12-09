<?php
include("lang/".lang.".php");
function t($index) {
	global $lang;
	return(!isset($lang[lang][$index]))?$index:$lang[lang][$index];
}
