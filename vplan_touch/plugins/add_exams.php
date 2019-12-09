<?php
$db->query("update timetable set is_exam = '1' where utid='890149'");
if($_GET["nav"]=="search"&&$_GET["q"]==" addexam") {
	echo "<br><br><br><br><br><h1 style=font-size:3em>Add Exams</h1>";
	if(isset($_POST["addexam_submit"])) {
		print_r($_POST);
		if($db->query("update timetable set is_exam = '1' where forms like '%".$_POST["form"]."%'")->num_rows)echo "<h1>Exam was added</h1>";else echo "<h1>Exam was NOT added!</h1>";
	} 
?>
<form method="post">
<select name="form">
<?php
	$res=$db->query("select * from forms ");
	while($row=$res->fetch_object()) {
		echo "<option value=utid>".$row->name."</option>";
	}
?>
</select><br><br><br><br><br><br><br>
<select name="subject">
<?php
	$res=$db->query("select * from subjects ");
	while($row=$res->fetch_object()) {
		echo "<option value=utid>".$row->name."</option>";
	}
?>
</select><br><br><br><br><br><br><br>
<select name="year" style="width:400px">
<?php
	echo "<option value=".date("Y").">".date("Y")."</option>";
	echo "<option value=".(date("Y")+1).">".(date("Y")+1)."</option>";
?>
</select>
<select name="month" style="left:410px;width:400px">
<?php
	for($i=12;$i>0;$i--) {
		echo "<option value=".$i.">".$i."</option>";
	}
?>
</select>
<select name="day" style="left:820px;width:331px">
<?php
	for($i=31;$i>0;$i--) {
		echo "<option value=".$i.">".$i."</option>";
	}
?>
</select><br><br><br><br><br><br><br>
<input name="addexam_submit" id=eintragen style="width:100%;border:none" type=submit value="Add Exam">
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<style type="text/css">
select , #eintragen{
	position: absolute;
	z-index: 99999;
	display: block !important;
	font-size:2em !important;
	max-width: 60%;
	margin-left: 20% !important;
	height:120px !important; 
	background:hsla(0,0%,100%,.5) !important;
	padding: 20px !important;
}
</style>
<?php 
}?>