var lastCircleX = null;
var lastCircleY = null;
var points = [];
var user = null;
var opponent = null;

window.onload = function() {
	//setup canvas
	var canvas = $("drawspace");
	paintbrush = canvas.getContext("2d");
	paintbrush.strokeStyle = 'black';
	lookForClick();
	user = $("currentuser").innerHTML;
	
	//thickness radio buttons
	var thickness = $$("#size input");
	for(var i = 0; i < thickness.length; i++) {
		$(thickness[i].id).observe("change", changeThicknessHelper);
	}
	changeThickness(5);
	$("5").checked = "checked";
	
	//color radio buttons
	var color = $$("#color input");
	for(var i = 0; i < color.length; i++) {
		$(color[i].id).observe("change", colorChangeHelper);
	}
	colorChange("black");
	$("black").checked = "checked";
	
	//clear and background buttons
	$("background").observe("click", backgroundChangeHelper);
	$("clear").observe("click", clearScreen);
	
	//send buttons
	var buttons = $$(".user button");
	for(var i = 0; i < buttons.length; i++) {
		if(buttons[i].id.substring(0, 4) == "send") {
			$(buttons[i].id).observe("click", ajaxPoints);
		}
	}
	
	//play buttons
	for(var i = 0; i < buttons.length; i++) {
		if(buttons[i].id.substring(0, 4) == "play") {
			$(buttons[i].id).observe("click", ajaxGetPoints);
		}
	}
	
	var deletepars = $$(".deleteparent");
	//delete buttons
	for(var i = 0; i < deletepars.length; i++) {
		$(deletepars[i].id).observe("mouseover", showDelete);
		$(deletepars[i].id).observe("mouseout", hideDelete);
		$$("#" + deletepars[i].id + " button")[0].observe("click", deleteUserAjax);
	}
	
	$("highlight").highlight();
	
	//set reload things
	if(window.location.toString().indexOf("reloaded") != -1) {
		Sound.play("pics/notification.wav");
		var url = window.location.toString().split("?");
		var opponent = url[1].split("=");
		document.title = opponent[1] + " sent you a drawing!";
	} else {
		if(!$$("#currentgames button")[0]) {
			//check for other player move if there aren't any buttons under current games
			setInterval(checkUpdate, 10000);
		}
	}

	//coins
	$("coins").observe("click", getCoinValues);
	
}

function checkUpdate() {
	new Ajax.Request("new-check.php", {
		method:"get",
		parameters:{"user":user},
		onSuccess:checkUpdateResponse
	});
}	

function checkUpdateResponse(ajax) {
	if(ajax.responseText != "") {
		window.location.href = window.location.href + "?reloaded=" + ajax.responseText;
	}
}

function showDelete(event) {
	$$("#" + this.id + " button")[0].show();
	
}

function hideDelete(event) {
	$$("#" + this.id + " button")[0].hide();
}

function deleteUserAjax(event) {
	opponent = this.id.substring(4);
	var r = confirm("Delete " + this.id.substring(4) + "?");
	if (r) {
		$(this.id).parentNode.remove();
		new Ajax.Request("delete-user.php", {
			method:"post",
			parameters:{"user":user, "opponent":opponent},
		});
	}
}	

function ajaxPoints(event) {
	var word = $("words" + this.id.substring(4)).value;
	if(word != "choose a word") {
		var r = prompt("Send " + word + " to " + this.id.substring(4) + "?", "[Optional message]");
		if(r != null) {
			var itemObj = {
				"items": points
			}
			opponent = this.id.substring(4);
			itemObj = JSON.stringify(itemObj);
			new Ajax.Request("store-points.php", {
				method:"post",
				parameters:{"points":itemObj, "user":user, "opponent":opponent, "word":word, "message":r},
				onSuccess:reloadPageFromSend
			});
		}
	} else {
		$(this.id).parentNode.highlight();
	}
}

function reloadPageFromSend() {
	reloadPageLoading("send" + opponent);
}

function changeThicknessHelper(event) {
	changeThickness(this.id);
	points.push(["thick", this.id]);
}

function changeThickness(thickness) {
	paintbrush.lineWidth = thickness;
}

function clearScreen() {
	paintbrush.clearRect(0, 0, parseInt($("drawspace").width), parseInt($("drawspace").height));
	backgroundChange("white");
	points.push(["clear", ""]);
}

function backgroundChangeHelper() {
	var colors = $$("#color input");
	for(var i = 0; i < colors.length; i++) {
		if(colors[i].checked) {
			backgroundChange(colors[i].id);
			points.push(["bkgrd", colors[i].id]);
		}
	}
}

function backgroundChange(color) {
	$("drawspace").style.backgroundColor = color;
}

function colorChangeHelper(event) {
	colorChange(this.id);
	points.push(["color", this.id]);
}

function colorChange(color) {
	paintbrush.strokeStyle = color;
	paintbrush.fillStyle = color;
}

function lookForClick() {
	$("drawspace").observe("mousedown", clickStart);
}

function clickStart(event) {
	lastCircleX = event.clientX;
	lastCircleY = event.clientY;
	points.push([lastCircleX - 10 - $("drawspace").offsetLeft, lastCircleY - 10 - $("drawspace").offsetTop]);
	$("drawspace").observe("mousemove", mouseMoveHelper);
	$("drawspace").observe("mouseup", mouseUp);
}

function mouseMoveHelper(event) {
	if(!timer) {
		mouseMove(lastCircleX - 10 - $("drawspace").offsetLeft, event.clientX - 10 - $("drawspace").offsetLeft, 
			lastCircleY - 10 - $("drawspace").offsetTop, event.clientY - 10 - $("drawspace").offsetTop);
		points.push([event.clientX - 10 - $("drawspace").offsetLeft, event.clientY - 10 - $("drawspace").offsetTop]);
		lastCircleX = event.clientX;
		lastCircleY = event.clientY;
	}
}

function mouseMove(prevX, x, prevY, y) {
	//circle
	paintbrush.beginPath();
	paintbrush.arc(prevX, prevY, paintbrush.lineWidth / 2, 0, Math.PI*2, true);
	paintbrush.closePath();
	paintbrush.fill();
	//line
	paintbrush.beginPath();
	paintbrush.moveTo(prevX, prevY);
	paintbrush.lineTo(x, y);
	paintbrush.stroke();
	//circle
	paintbrush.beginPath();
	paintbrush.arc(x, y, paintbrush.lineWidth / 2, 0, Math.PI*2, true);
	paintbrush.closePath();
	paintbrush.fill();
}

function mouseUp() {
	lastCircleX = null;
	lastCirlceY = null;
	$("drawspace").stopObserving("mousemove", mouseMoveHelper);
	$("drawspace").stopObserving("mouseup", mouseUp);
	points.push(["lift", ""]);
	lookForClick();
}

function getCoinValues() {
	alert("Colors in order of coin value:\n20 coins: green\n40 coins: brown\n60 coins: purple\n80 coins: grey\n100 coins: orange\n120 coins: tan\n140 coins: pink\n160 coins: light green\n180 coins: light blue");
	
}