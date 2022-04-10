<?php
/*
this script minds about the touchscreen keyboardstuff as well as the pin thingy
*/

if (!$generate_head) {
?>

<script type="text/javascript">
function keyboard(key) {
	if (key=="_")key=" ";
	val=$("#searchfield").val();
	if ((key>="a"&&key<="z")||(key>="0"&&key<="9")||key==" ") {
		$("#searchfield").val(val+key);
	} else if (key=="A") {
		$("#searchfield").val(val+"ä");
	} else if (key=="O") {
		$("#searchfield").val(val+"ö");
	} else if (key=="U") {
		$("#searchfield").val(val+"ü");
	} else if (key=="S") {
		$("#searchfield").val(val+"ß");
	} else if (key=="-") {
		setTimeout(function(){$("#autocomplete-tiles").hide()},5);
		$("#keyboard").hide({'effect':'slide',direction:'down'});
	} else if (key=="<") {
		$("#searchfield").val(val.substring(0,(val.length-1)));
	} else if (key==">") {
		window.location.href="?nav=search&q="+decodeURI(val);
		$('#keyboard').hide();
		$('#loading').show();
	} else {
	}	
	if($.isFunction(window.after_keypress))
		after_keypress();
}

function pinpad(key) {
	val=$("#pinfield").val();
	if (key>="0"&&key<="9") {
		$("#pinfield").val(val+key);
	} else if (key=="<") {
		$("#pinfield").val(val.substring(0,(val.length-1)));
	} else if (key==">") {
		window.location.href="?nav=login&pin="+decodeURI(val);
		$('#loading').show();
		$('#pinpad').hide();
	} else {
		alert("Unknown Pinpad action: "+key);
	}
	val=$("#pinfield").val();
	if(val.length>3) {
		window.location.href="?nav=login&pin="+decodeURI(val);
		$('#loading').show();
		$('#pinpad').hide();
	}
}
</script>

<div id="keyboard" style="<?php if(isset($_SESSION["comps"])&&$_GET["cat"]=="teachers"&&$_GET["nav"]=="show")echo 'bottom:83px;';?>"><br>
<?php
$keys="1234567890S#qwertzuiopA#asdfghjklO<#-yxcvbnmU_>";
if(lang=="en")$keys="1234567890#qwertyuiop#asdfghjkl<#-zxcvbnm_>";
$keys=str_split($keys,1);
foreach($keys as $key) {
	if($key=="#")echo"<br>";
	else {
		echo "<div onclick=\"keyboard('".$key."');\" class='tile tile-sm tile-key'>";
		
		if($key=="<") echo "&nbsp;<span class='glyphicon glyphicon-triangle-left'></span>&nbsp;";
		elseif($key=="A") echo "&Auml;";
		elseif($key=="O") echo "&Ouml;";
		elseif($key=="U") echo "&Uuml;";
		elseif($key=="S") echo "<span style=text-transform:none>&szlig;</span>";
		elseif($key==">") echo "&nbsp;<span class='glyphicon glyphicon-search'></span>&nbsp;";
		elseif($key=="-") echo "&nbsp;<span class='glyphicon glyphicon-menu-down'></span>&nbsp;";
		else echo $key;
		
		echo "</div>";
	}
}
?>
</div>

<div id="pinpad"><br>
<?php
$keys="789#456#123#<0>";
$keys=str_split($keys,1);
foreach($keys as $key) {
	if($key=="#")echo"<br>";
	else {
		echo "<div onclick=\"pinpad('".$key."');\" class='tile tile-sm tile-key'>";

		if($key=="<") echo "&nbsp;<span class='glyphicon glyphicon-triangle-left'></span>&nbsp;";
		elseif($key==">") echo "&nbsp;<span class='glyphicon glyphicon-ok'></span>&nbsp;";
		else echo $key;
		
		echo "</div>";
	}
}
?>
</div>
<?php }?>