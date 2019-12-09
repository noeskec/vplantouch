<?php
if(isset($_GET["autocomplete"])) {
	if($_GET["autocomplete"]=="searchonly") {
		$_GET["q"]=utf8_decode($_GET["q"]);
		$result=$db->query("select id as utid,name from forms where name like '".$_GET["q"]."%' or fullname like '".$_GET["q"]."%' union select id as utid,name from teachers where name like '".$_GET["q"]."%' union  select  id as utid,name from rooms where name like '".$_GET["q"]."%' limit 20");
		$res="";	
		if($result->num_rows) while($row=$result->fetch_object()) {
			if($db->query("select id as utid from forms where name = '".$row->name."'")->num_rows)$cat="forms";
			if($db->query("select id as utid from teachers where name = '".$row->name."'")->num_rows)$cat="teachers";
			if($db->query("select id as utid from rooms where name = '".$row->name."'")->num_rows)$cat="rooms";
			if($result->num_rows == "1") echo "<script>window.location.href='?nav=show&cat=".$cat."&id=".($row->utid)."';</script>";
			$res.="<a onclick='showload();' href=?nav=show&cat=".$cat."&id=".($row->utid).">".utf8_encode($row->name)."</a> ";
		}
		die($res);
	}
}
?>

<div id=autocomplete-tiles style="<?php if((isset($_SESSION["comps"])&&$_GET["cat"]=="teachers"&&$_GET["nav"]=="show" )||$_GET["cat"]=="startcomp")echo 'bottom:473px;';?>">
</div>
<script>
function after_keypress() {
	$("#autocomplete-tiles").show();
	val=$("#searchfield").val();
	$("#autocomplete-tiles").load("?autocomplete=searchonly&q="+val);
}
function showload() {
	$('#keyboard').hide();
	$("#autocomplete-tiles").hide();
	$('#loading').show();
};
</script>
<style>
#autocomplete-tiles {
	overflow:hidden;
	max-height:100px;
	display:none;
	position:fixed;
	bottom:390px;
	left:0;
	right:0;
	background:#546e7a;
	z-index:9999;
	text-align:center;
	box-shadow:0px 0px 2px gray;
} #autocomplete-tiles a {
	display:inline-block;
	text-align:center;
	padding:10px 30px;
	font-size:60px;
	color:white;
	border-right:1px solid #90a4ae;
} #autocomplete-tiles a:last-of-type {
	border-right:none;
}
</style>
