<?php /*
                 
                                                               
	_|_|_|                              _|            _|  _|        
	_|    _|    _|_|    _|_|_|  _|_|        _|_|_|        _|  _|    
	_|    _|  _|    _|  _|    _|    _|  _|  _|    _|  _|  _|_|      
	_|    _|  _|    _|  _|    _|    _|  _|  _|    _|  _|  _|  _|    
	_|_|_|      _|_|    _|    _|    _|  _|  _|    _|  _|  _|    _|  
	                                                                
	                                                                
	                                                                            
	_|_|_|_|_|  _|                                          _|                                      _|  
	      _|          _|_|      _|_|_|    _|_|    _|_|_|    _|_|_|      _|_|_|    _|_|_|    _|_|    _|  
	    _|      _|  _|_|_|_|  _|    _|  _|_|_|_|  _|    _|  _|    _|  _|    _|  _|    _|  _|_|_|_|  _|  
	  _|        _|  _|        _|    _|  _|        _|    _|  _|    _|  _|    _|  _|    _|  _|        _|  
	_|_|_|_|_|  _|    _|_|_|    _|_|_|    _|_|_|  _|    _|  _|    _|    _|_|_|    _|_|_|    _|_|_|  _|  
	                                _|                                                _|           
	                            _|_|                                              _|_|     
	                        
	                        
*/ 

if ($generate_head) {
?>
<link rel="stylesheet" href="lib/jquery-ui.css">
<link rel="stylesheet" href="lib/bootstrap.css">
<link rel="stylesheet" href="lib/touchvplan.css">
<link rel="stylesheet" href="lib/domtis.css">
<link rel="stylesheet" href="lib/custom.css">
<script src="lib/jquery.js"></script>
<script src="lib/jquery-ui.js"></script>
<script src="lib/bootstrap.js"></script>
<script src="lib/bootbox.js"></script>
<link rel="stylesheet" href="lib/materialize.min.css">
<script src="lib/materialize.min.js"></script>
<style>
*:not(.glyphicon) {
	font-family: Ubuntu !important;
} h1, .tile-lg *, .tile-lg {
	font-weight:300 !important;
}
</style>
<meta name="author" content='dominik ziegenhagel'>

<style type="text/css">.loader{color:#334;font-size:20px;margin:0px auto;width:1em;height:1em;border-radius:50%;position:relative;text-indent:-9999em;-webkit-animation:load4 0.7s infinite linear;animation:load4 0.7s infinite linear;-webkit-transform:translateZ(0);-ms-transform:translateZ(0);transform:translateZ(0)}@-webkit-keyframes load4{0%,100%{box-shadow:0 -3em 0 .2em,2em -2em 0 0,3em 0 0 -1em,2em 2em 0 -1em,0 3em 0 -1em,-2em 2em 0 -1em,-3em 0 0 -1em,-2em -2em 0 0}12.5%{box-shadow:0 -3em 0 0,2em -2em 0 .2em,3em 0 0 0,2em 2em 0 -1em,0 3em 0 -1em,-2em 2em 0 -1em,-3em 0 0 -1em,-2em -2em 0 -1em}25%{box-shadow:0 -3em 0 -.5em,2em -2em 0 0,3em 0 0 .2em,2em 2em 0 0,0 3em 0 -1em,-2em 2em 0 -1em,-3em 0 0 -1em,-2em -2em 0 -1em}37.5%{box-shadow:0 -3em 0 -1em,2em -2em 0 -1em,3em 0 0 0,2em 2em 0 .2em,0 3em 0 0,-2em 2em 0 -1em,-3em 0 0 -1em,-2em -2em 0 -1em}50%{box-shadow:0 -3em 0 -1em,2em -2em 0 -1em,3em 0 0 -1em,2em 2em 0 0,0 3em 0 .2em,-2em 2em 0 0,-3em 0 0 -1em,-2em -2em 0 -1em}62.5%{box-shadow:0 -3em 0 -1em,2em -2em 0 -1em,3em 0 0 -1em,2em 2em 0 -1em,0 3em 0 0,-2em 2em 0 .2em,-3em 0 0 0,-2em -2em 0 -1em}75%{box-shadow:0 -3em 0 -1em,2em -2em 0 -1em,3em 0 0 -1em,2em 2em 0 -1em,0 3em 0 -1em,-2em 2em 0 0,-3em 0 0 .2em,-2em -2em 0 0}87.5%{box-shadow:0 -3em 0 0,2em -2em 0 -1em,3em 0 0 -1em,2em 2em 0 -1em,0 3em 0 -1em,-2em 2em 0 0,-3em 0 0 0,-2em -2em 0 .2em}}@keyframes load4{0%,100%{box-shadow:0 -3em 0 .2em,2em -2em 0 0,3em 0 0 -1em,2em 2em 0 -1em,0 3em 0 -1em,-2em 2em 0 -1em,-3em 0 0 -1em,-2em -2em 0 0}12.5%{box-shadow:0 -3em 0 0,2em -2em 0 .2em,3em 0 0 0,2em 2em 0 -1em,0 3em 0 -1em,-2em 2em 0 -1em,-3em 0 0 -1em,-2em -2em 0 -1em}25%{box-shadow:0 -3em 0 -.5em,2em -2em 0 0,3em 0 0 .2em,2em 2em 0 0,0 3em 0 -1em,-2em 2em 0 -1em,-3em 0 0 -1em,-2em -2em 0 -1em}37.5%{box-shadow:0 -3em 0 -1em,2em -2em 0 -1em,3em 0 0 0,2em 2em 0 .2em,0 3em 0 0,-2em 2em 0 -1em,-3em 0 0 -1em,-2em -2em 0 -1em}50%{box-shadow:0 -3em 0 -1em,2em -2em 0 -1em,3em 0 0 -1em,2em 2em 0 0,0 3em 0 .2em,-2em 2em 0 0,-3em 0 0 -1em,-2em -2em 0 -1em}62.5%{box-shadow:0 -3em 0 -1em,2em -2em 0 -1em,3em 0 0 -1em,2em 2em 0 -1em,0 3em 0 0,-2em 2em 0 .2em,-3em 0 0 0,-2em -2em 0 -1em}75%{box-shadow:0 -3em 0 -1em,2em -2em 0 -1em,3em 0 0 -1em,2em 2em 0 -1em,0 3em 0 -1em,-2em 2em 0 0,-3em 0 0 .2em,-2em -2em 0 0}87.5%{box-shadow:0 -3em 0 0,2em -2em 0 -1em,3em 0 0 -1em,2em 2em 0 -1em,0 3em 0 -1em,-2em 2em 0 0,-3em 0 0 0,-2em -2em 0 .2em}}</style>

<?php } else {?>

<body ondragstart="return false;" ondrop="return false;">
<div id="loading" style="background:none !important;box-shadow:none !important" onclick="$(this).hide()">
<div class="loader"></div>
</div>
<script type="text/javascript">
$('#loading').delay(500).show();
</script>
<div id='touchbody'>
<div class="weekbtn scrollup" onclick="$(document).scrollTop(($(document).scrollTop()-40))"><span class="glyphicon glyphicon-chevron-up"></span></div>
<div class="weekbtn scrolldown" onclick="$(document).scrollTop(($(document).scrollTop()+40))"><span class="glyphicon glyphicon-chevron-down"></span></div>
<script type="text/javascript">
if((parseInt($(document).height()))>(screen.height)) {
	$(".scrolldown").show();
	$(".scrollup").show();
} else {
	$(".scrollup").hide();
	$(".scrolldown").hide();
}
$('a').click(function() {
	showLoad();
});
<?php if(!isset($_GET["showdash"]))echo "
function showLoad() {
	setTimeout(function () {
		\$('#loading').show();
	},200);
}";else echo "\$('#loading').hide();";?>
$(function () {
	$('#loading').fadeOut(160);
});
</script>
<?php } ?>