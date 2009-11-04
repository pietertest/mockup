var _onloadListeners = new Array();

function executeOnloadListeners() {
	for(listener in _onloadListeners) {
		eval(_onloadListeners[listener]);
	}
}

document.onload = new function() { executeOnloadListeners(); };