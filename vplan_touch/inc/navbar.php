<nav id=thenav>
	<?php echo '<li><a class="waves-effect waves-yellow';
	echo(isset($_GET["showdash"]))?'" style="opacity:.3;" onclick="window.location.href=\'?showdash\';"':'" style="cursor:pointer" onclick="window.history.back(1)"';
	echo '><span class="glyphicon glyphicon-chevron-left"></span>&nbsp;</a></li>';?>
	<li><a <?php if($school_config["home"]["tiles"]["forms"]["auto_select_page"]>0){echo "href=\"?nav=home&cat=forms&subnav=page".$school_config["home"]["tiles"]["forms"]["auto_select_page"]."\"";} else {echo 'onclick="showLinkgroup(\'forms\');"';}?> class="<?php if($_GET["cat"]=="forms")echo"active ";?>waves-effect waves-yellow"><span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo t("Klassen");?></a></li>
	<?php
	if($teachers_enabled=="1") {
	?><li><a <?php if($school_config["home"]["tiles"]["teachers"]["auto_select_page"]>0){echo "href=\"?nav=home&cat=teachers&subnav=page".$school_config["home"]["tiles"]["teachers"]["auto_select_page"]."\"";} else {echo 'onclick="showLinkgroup(\'teachers\');"';}?> class="<?php if($_GET["cat"]=="teachers")echo"active ";?>waves-effect waves-yellow"><span class="glyphicon glyphicon-education"></span>&nbsp;<?php echo t("Lehrer");?></a></li>
	<li><a <?php if($school_config["home"]["tiles"]["rooms"]["auto_select_page"]>0){echo "href=\"?nav=home&cat=rooms&subnav=page".$school_config["home"]["tiles"]["rooms"]["auto_select_page"]."\"";} else {echo 'onclick="showLinkgroup(\'rooms\');"';}?> class="<?php if($_GET["cat"]=="rooms")echo"active ";?>waves-effect waves-yellow"><span class="glyphicon glyphicon-home"></span>&nbsp;<?php echo t("Räume");?></a></li>
	<?php
	}
	?>
	<li><span style="float:left;width:60%;"><input onchange="window.location.href='?nav=search&q='+$(this).val();" value="<?php if(isset($_GET["q"]))echo $_GET["q"];?>" onclick="$('#pinpad').hide({effect:'slide',direction:'down'});$('#keyboard').show({'effect':'slide',direction:'down'});$(this).val('');" id="searchfield" style="margin-top:5px;" placeholder="<?php echo t("Suchen");?> ..."></span></li>
</nav>
<div id=time style="position:fixed;top:10px;right:30px;z-index:99999999;font-size:40px;font-weight:300;opacity:.58"><?php echo date("H");?><span style=transition:.23s; id=blink2hz>:</span><?php echo date("i")." ".t("Uhr");?></div>
<br><br><br><br><br><script>setInterval(j,2000);function j(){$('#blink2hz').css("opacity","1");setTimeout(f,1000);}function f() {$('#blink2hz').css("opacity","0.18");}setTimeout(f,1000);</script>


<script>
function showLinkgroup(cat) {
       $(".linkgroup-cat").hide();
       $("#linkgroup-"+cat).show();
       $(".nav-linkgroup-hover").show();
       if(cat=="rooms")$(".nav-linkgroup-hover-img").css("left","440px"); 
       else if(cat=="teachers")$(".nav-linkgroup-hover-img").css("left","299px"); 
       else $(".nav-linkgroup-hover-img").css("left","145px"); 
}  
</script>

<style>#linkgroup-wrapper {
    position:absolute;
    z-index:99;
    top:80px;
    background:#F8FBFC;
    padding: 10px;
    border-radius:5px;
    width:auto;
} #linkgroup-wrapper a div.tile {
    font-size:30px;
    margin:2px !important;
    padding: 30px 25px !important;
    text-align:center;
    min-width:200px;
    width:auto;
    box-shadow:  0px 0px 1px hsla(0,0%,0%,.05) !Important;
} .linkgroup-cat {
    width:auto;
} .nav-linkgroup-hover {
    display:none;
}
</style>



<img class="nav-linkgroup-hover nav-linkgroup-hover-img" style="position:absolute;height:25px;top:65px;left:145px;z-index:909999999999;" src="img/corner.png">
<div id=linkgroup-wrapper class="nav-linkgroup-hover tile">
    
    <div id=linkgroup-forms class=linkgroup-cat>
				<?php 
                echo "<a href=?nav=home&cat=forms&subnav=page1><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["forms"][1]["html"]."</div></a>
				<a href=?nav=home&cat=forms&subnav=page2><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["forms"][2]["html"]."</div></a>
				<a href=?nav=home&cat=forms&subnav=page3><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["forms"][3]["html"]."</div></a>
				<a href=?nav=home&cat=forms&subnav=page4><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["forms"][4]["html"]."</div></a>
                ";?>
    </div>
    
    <div id=linkgroup-teachers class=linkgroup-cat>
				<?php 
				echo "<a href=?nav=home&cat=teachers&subnav=page1><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["teachers"][1]["html"]."</div></a>
				<a href=?nav=home&cat=teachers&subnav=page2><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["teachers"][2]["html"]."</div></a>
				<a href=?nav=home&cat=teachers&subnav=page3><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["teachers"][3]["html"]."</div></a>
				<a href=?nav=home&cat=teachers&subnav=page4><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["teachers"][4]["html"]."</div></a>
				";?>
    </div>
    
    <div id=linkgroup-rooms class=linkgroup-cat>
				<?php
                echo "<a href=?nav=home&cat=rooms&subnav=page1><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["rooms"][1]["html"]."</div></a>
				<a href=?nav=home&cat=rooms&subnav=page2><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["rooms"][2]["html"]."</div></a>
				<a href=?nav=home&cat=rooms&subnav=page3><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["rooms"][3]["html"]."</div></a>
				<a href=?nav=home&cat=rooms&subnav=page4><div class='tile tile-md waves-effect waves-yellow  form-tile tile-md-font'>".$school_config["home"]["tiles"]["rooms"][4]["html"]."</div></a>
				";?>
    </div>


</div>
