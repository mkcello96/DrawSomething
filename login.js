var timer = null;
var lewindow = null;

window.onload = function() {
	/*new Ajax.Request("get-source.php", {
		method:"get",
		onSuccess:ajaxSuccess
	});*/
	timer = setInterval(getContents, 1000);
	lewindow = window.open("https://sdb.admin.washington.edu/timeschd/uwnetid/tsstat.asp?QTRYR=AUT+2012&CURRIC=CSE");
};

function ajaxSuccess(ajax) {
	alert("hi");
}

function getContents() {
	var body = document.getElementsByTagName("body")[0].innerHTML;
	alert(body);
}