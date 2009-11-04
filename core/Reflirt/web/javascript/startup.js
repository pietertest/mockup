function init() {
	for(listener in _onloadListeners) {
		eval(_onloadListeners[listener]);
 	}
}

var buttons = {
	home 		: "/?page=home",
	//search 		: "/?page=search&action=simplesearch",
	search		: "?page=spotsearch&action=search",
	subscribe	: "/?page=subscribe"
};

$().ready(function() {
	$("#username").focus(function() {
		$(this).val("");
	});
	$("#wachtwoord").focus(function() {
		$(this).val("");
	});

    
    for(buttonName in buttons) {
    	setClass(buttonName, buttons[buttonName]);
    }
    $('div#top_button_'+currentPage).addClass(currentPage + '_active');
    
});



function setClass(buttonName, url) {
	$('div#top_button_'+buttonName).click( function(){
		window.location = url;
	});
	if(buttonName == currentPage) {
		return;
	}
	$('div#top_button_'+buttonName).hover(function() {
		$(this).addClass(buttonName+'_active');
	}, function() {
		$(this).removeClass(buttonName+'_active');
	});
}

function bye() {
	if (typeof GUnload == 'function') {
		try {
			GUnload();
		} catch(e) {
			log(e);
		}  
	 }
}