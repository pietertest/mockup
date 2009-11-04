function replaceAll(str, from, to) {
	var pos = str.indexOf(from);
	while(pos > -1) {
		str = str.replace(from, to);
		pos = str.indexOf(from);
	}
	return str;
}

function mergeJSON(str, object) {
	if ( object.length == undefined ) {
		for ( var name in object ) {
			str = replaceAll(str, "{"+name+"}", object[name]);
		}
	} else {
		for (var i = 0; i < data.length; i++) {
			str = replaceAll("{"+data[0]+"}", data[1]);
		}
	}
	return str;
}

function debug(data) {
	var s = "";
	for (key in data) {
		s += key + ": " + data[key] + "\n";
	}
	alert(s);
}

function isEmpty(s) {
	if(typeof s != "string") {
		return
	}
	return s == "" || s == null || s.length == 0;
}

function log(s) {
	//implement logging
	$.post("/servlets/?servlet=log&message=", {
		message: s
	});
}
function processJsonResponse(data, options) {
	if (data.success) {
		done();
		if (options.success) options.success.apply(data);
	} else if(data.fail) {
		done();
		if(options.fail) fail.apply(data);
	} else {
		log("No error or success specified in json callback");
		if(options.error) 
			options.error.apply(data);
		else 
			warn("Oeps! Het lijkt alsof er iets is mis gegaan. Dit is gelogd en wordt zo snel mogelijk verholpen. Probeer het nogmaals.");
	}
}
function ajaxForm(formId, successCallback, errorCallback) {
	var options = { 
			dataType:  "json", 
	        success:   function(data) {
				if (data.success) {
					done();
					successCallback.apply($(formId), [data]);
				} else if(data.fail) {
					done();
					errorCallback.apply($(formId), [data]);
				} else {
					log("No error or success specified in json callback");
					warn("Oeps! Het lijkt alsof er iets mis gegaan is. We hebben hier bericht van ontvangen. Probeer het nogmaals.");
				}
			},
			beforeSubmit: function() {
				loading("Een moment geduld...");
			},
			error: function(request, error) {
				done();
				if (_env == "DEVELOPMENT") {
					warn(request.responseText);
				} else {
					log("Error: " + error + "\n\nResponseText: " + request.responseText);
					warn("Oeps! Het lijkt alsof er iets mis gegaan is. We hebben hier bericht van ontvangen. Probeer het nogmaals.");
				}
				
			}
	    };
	 return $(formId).ajaxForm(options);
}

function warn(s) {
	scrollToTop(function() {
		$("#messageDiv").html('<div class="warn">' + s + '</div>').hide().fadeIn(1);
	});
}

function success(s) {
	scrollToTop(function() {
		$("#messageDiv").html('<div class="ok">' + s + '</div>').hide().fadeIn();
	});
}

function loading(s) {
	if (s) {
		$("#_loading span").text(s)
	} else {
		$("#_loading span").text("Laden...");
	}
	
	$("#_loading").show().fadeTo("fast", 1).center();
}

function done() {
	$("#_loading").fadeTo("fast", 0).hide();
}

function scrollToTop(callback) {
	var height = $("body").height();
	$('html, body').animate({scrollTop:0}, "fast", "linear", callback);			
}

function jsonUrlDecode(url) {
	return url.replace(/&amp;/g, "&");
}