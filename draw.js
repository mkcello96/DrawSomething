var frame = 0;
var pointsArray = null;
var timer = null;
var guesstimer = null;
var guesstime = 0;
var abstractguessword = null;
var user = null;
var opponent = null;

function ajaxGetPoints(event) {
	if(!timer && !guesstimer) {
		guesstimer = setInterval(addTime, 1000);
	
		user = $("currentuser").innerHTML;
		opponent = this.id.substring(4);
		
		var guessspan = document.createElement("span");
		var guessbox = document.createElement("input");
		var passbutton = document.createElement("button");
		guessbox.type = "text";
		guessbox.id = "guessbox";
		guessbox.size = "10";
		guessbox.maxlength = "50";
		passbutton.innerHTML = "Pass";
		passbutton.id = "pass";
		guessspan.id = "guessspan";
		guessspan.appendChild(guessbox);
		guessspan.appendChild(passbutton);
		
		//change to guess span
		$(this.id).parentNode.replaceChild(guessspan, $(this.id));
		$correctmessages = $$(".correct" + opponent + ", .incorrect" + opponent);
		for(var i = 0; i < $correctmessages.length; i++) {
			$correctmessages[i].remove();
		}
		$("guessbox").focus();
		$("guessspan").parentNode.highlight();
		
		//get new word
		new Ajax.Request("get-word.php", {
			method:"get",
			parameters:{"user":user, "opponent":opponent},
			onSuccess: ajaxGetWord
		});
		
	}
}

function ajaxDrawPoints(ajax) {
	var pointsString = ajax.responseText;
	pointsArray = pointsString.split("\n");
	var message = pointsArray.shift();
	if (message != "") {
		alert(opponent + ": " + message);
	}
	timer = setInterval(timerPoints, 10);
}

function timerPoints() {
	if (frame < pointsArray.length - 1) {
		//le points
		var prevPoints = pointsArray[frame].split(",");
		var newPoints = pointsArray[frame + 1].split(",");
		
		//le draw rules
		if (newPoints[0] == "lift") {
			frame += 2;
		} else if (prevPoints[0] == "thick") {
			changeThickness(prevPoints[1]);
			frame++;
		} else if (prevPoints[0] == "color") {
			colorChange(prevPoints[1]);
			frame++;
		} else if (prevPoints[0] == "bkgrd") {
			backgroundChange(prevPoints[1]);
			frame++;
		} else if (prevPoints[0] == "clear") {
			clearScreen();
			frame++;
		} else {
			mouseMove(prevPoints[0], newPoints[0], prevPoints[1], newPoints[1]);
			frame++;
		}
	} else {
		resetTimer();
	}
}

function resetTimer() {
	clearInterval(timer);
	timer = null;
}

function ajaxGetWord(ajax) {
	if(ajax.responseText.length < 50) {
		abstractguessword = ajax.responseText;
		
		$("guessbox").observe("keyup", guessClick);
		$("pass").observe("click", passClick);
		
		//button state
		var buttons = $$("button");
		for(var i = 0; i < buttons.length; i++) {
			if(buttons[i].id != "pass") {
				 changeButtonDisabledState(buttons[i]);
			}
		}
		
		new Ajax.Request("get-points.php", {
			method:"get",
			parameters:{"user":user, "opponent":opponent},
			onSuccess: ajaxDrawPoints
		});
	} else {
		alert(opponent + " deleted you");
		$("div"+opponent).remove();
	}
	
	
}

function guessClick() {
	$("guessbox").addClassName("guessbox");
	var inputguess = $("guessbox").value;
	if(abstractguessword == inputguess) {
		displayCorrectness("Correct!", "correct");
		$("correctdiv").highlight();
		ajaxGuessed("c");
	}
}

function passClick() {
	var r = confirm("Pass?");
	if (r) {
		displayCorrectness("passed: " + abstractguessword, "incorrect");
		ajaxGuessed("w");
	}
}

function displayCorrectness(rightOrWrong, classname) {
	//add reload button
	var correctdiv = document.createElement("div");
	var reloadpar = document.createElement("p");
	var reloadbutton = document.createElement("button");
	correctdiv.id = "correctdiv";
	reloadbutton.innerHTML = "Choose a word to draw";
	reloadbutton.id = "reload";
	reloadpar.appendChild(reloadbutton);
	var correctpar = document.createElement("p");
	correctpar.innerHTML = rightOrWrong;
	correctpar.addClassName(classname);
	correctdiv.appendChild(correctpar);
	correctdiv.appendChild(reloadpar);
	$("guessspan").parentNode.replaceChild(correctdiv, $("guessspan"));

	//change pointage
	var matches = $("matches" + opponent).innerHTML;
	if(rightOrWrong == "Correct!") {
		var coins = $("coins").innerHTML;
		$("coins").shrink({
			afterFinish: function() {
				coins++;
				$("coins").innerHTML = coins;
				$("coins").grow({
					afterFinish: function() {
						$("coins").highlight();
					}
				});
			}
		});
		
		$("matches" + opponent).shrink({
			afterFinish: function() {
				matches++;
				$("matches" + opponent).innerHTML = matches;
				$("matches" + opponent).grow({
					afterFinish: function () {
						$("matches" + opponent).highlight();
					}
				});
			}
		});
	} else {
		$("matches" + opponent).puff({
			afterFinish: function() {
				$("matches" + opponent).innerHTML = 0;
				$("matches" + opponent).grow();
			}
		});
	}
	
	$("reload").observe("click", reloadPageLoadingToSend);
}

function reloadPageLoadingToSend() {
	reloadPageLoading("correctdiv");
}

function reloadPageLoading(replace) {
	var loadingpar = document.createElement("p");
	var loadingimg = document.createElement("img");
	loadingimg.src = "loading.gif";
	loadingimg.alt = "loading...";
	loadingpar.appendChild(loadingimg);
	
	$(replace).parentNode.replaceChild(loadingpar, $(replace));
	window.location.href = window.location.toString().split("?")[0];
}

function ajaxGuessed(correctness) {
	clearInterval(timer);
	timer = setInterval(timerPoints, 0);
	new Ajax.Request("word-guessed.php", {
			method:"post",
			parameters:{"user":user, "opponent":opponent, "correct":correctness, "word":abstractguessword, "time":guesstime},
			onSuccess: resetGuessTimer
	});
}

function addTime() {
	guesstime++;
}

function resetGuessTimer() {
	guesstime = 0;
	clearInterval(guesstimer);
	guesstimer = null;
}

function changeButtonDisabledState(button) {
	button.disabled = !button.disabled;
}