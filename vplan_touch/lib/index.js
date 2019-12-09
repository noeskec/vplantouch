if((parseInt($(document).height()))>(screen.height)) {
	$(".scrolldown").show();
	$(".scrollup").show();
} else {
	$(".scrollup").hide();
	$(".scrolldown").hide();
}
$(function () {
//	document.getElementByTagName('a').ondragstart = function() { return false; };
});
$('a').click(function() {
	showLoad();
});
function showLoad() {
	setTimeout(function () {
		$('#loading').show();
	},200);
}
$(function () {
	$('#loading').hide();
});
