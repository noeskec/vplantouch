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
		$("#keyboard").hide({'effect':'slide',direction:'down'});
	} else if (key=="<") {
		$("#searchfield").val(val.substring(0,(val.length-1)));
	} else if (key==">") {
		window.location.href="?nav=search&q="+decodeURI(val);
		$('#keyboard').hide();
		$('#loading').show();
	} else {
		alert("Unknown Keyboard action: "+key);
	}	
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
