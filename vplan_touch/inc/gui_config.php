<script>
function config_pinpad(key) {
	val=$("#pinfield2").val();
	if (key>="0"&&key<="9") {
        if(val.length>12) return;
        $("#pincount2").append("*");
		$("#pinfield2").val(val+key);
	} else if (key=="<") {
		window.location.href="?showdash";
	} else if (key==">") {
		window.location.href="?nav=search&q= config&mastercode="+decodeURI(val);
	} else {
		alert("Unknown Pinpad action: "+key);
	}
}
</script>
<br><br><br><br>
<h1 style="text-align:center;width:100%;">Bitte geben Sie den Kane-Config PIN ein:</h1>

<input id=pinfield2 style=display:none>

<div style=font-size:200px;text-align:center;width:100%; id=pincount2></div>

<div id="pinpad" style=display:block><br>
<?php
$keys="789#456#123#<0>";
$keys=str_split($keys,1);
foreach($keys as $key) {
	if($key=="#")echo"<br>";
	else {
		echo "<div onclick=\"config_pinpad('".$key."');\" class='tile tile-sm tile-key'>";

		if($key=="<") echo "&nbsp;<span class='glyphicon glyphicon-remove'></span>&nbsp;";
		elseif($key==">") echo "&nbsp;<span class='glyphicon glyphicon-ok'></span>&nbsp;";
		else echo $key;
		
		echo "</div>";
	}
}
?>
</div>
