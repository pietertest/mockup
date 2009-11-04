;(function($) {
	
$.fn.extend({
	eAutocomplete: function(options) {
	
		return this.each(function() {
			new $.eAutocompleter(this, options);
		});
	},
	setResult: function(value) {
		return this.trigger("setResult", value);
	},
	loadById: function(id) {
		return this.trigger("loadById", id);
	},
	onMatch: function(handler) {
		return this.trigger("onMatch", handler);
	},
	onNoMatch: function(handler) {
		return this.trigger("onNoMatch", handler);
	}

});

$.eAutocompleter = function(input, options) {
	
	var opts = jQuery.extend({}, {
		delay: 150,
		width: function() { return $input.width() + "px"; },
		max: 10,
		formatItem: formatItem,
		formatResult: formatResult,
		selectFirst: false,
		mustMatch: false,
		extraParams: {},
		onMatch: null,	// Handle new result
		onNoMatch: null	// Handle new result
	}, options);
	
	var $input = $(input);
	
	var match = false;
	
	
	var resultName  = opts.resultId || $input.attr("name") + "_result";
	var $resultField = $('<input type="hidden" name="' + resultName + '" id="' + opts.resultId + '"/>');
	$input.after($resultField);
	
	// Initialiseer autocompleten
	$input.autocomplete("/servlets/", opts
	).bind("setResult", function(){
		getResultField().val(arguments[1]);
	}).bind("onMatch", function() {
		opts.onMatch = arguments[1];
		//$.extend(opts.extraParams, {allowNew: true});
		// Add param to extraParam form original autocomplete plugin
		//$input.setOptions(opts);
	}).bind("onNoMatch", function(){
		opts.onNoMatch = arguments[1];
	}).bind("loadById", function(){
		loadById(arguments[1]);
	});
	
	
	// Load if "id" parameter specified
	if(opts.id) {
//		console.debug(opts);
		loadById(opts.id);
	}
	
	if(opts.dependsOn) {
		var $other = $("#" + opts.dependsOn);
		
		/* This must be done via "blur" event */
//		$other.bind("change", function() {
//			console.debug("value changed, flushinf cahce");
//			$input.val("").flushCache();
//			$resultField.val("");
//		});
		
		var lastValue = $other.val();
		$other.bind("blur", function(){
//			console.info(options.dependsOn + " changed" );
			if($other.val() != lastValue) {
//				console.info(" flushing cache for " + $input.attr("name"));
				// Leeg het veld en clear de cache
				$input.val("").flushCache();
				
				// Leeg het resultveld
				$resultField.val("");
				
			}
		});
		
	}
	
	$input.change(function(){
		match = false;
	});
	
	var previousValue = "";
	
	$input.result(function(event, data, formatted) {
		var val = data[1];
		$resultField.val(val);
		match = true; 
//		console.info("setting resultvalue: " + val);
		$(this).focus();
	});
	
	
	$input.blur(function(){
		// Delay the blur event so the "result" function is called first
		setTimeout(function(){
			var val = $input.val();
//			console.debug("match: " + match);
			if (val == "") {
//				console.debug("emtpying resultvalue...");
				$resultField.val("");
			}
			if(match) {
				if(opts.onMatch) {
					opts.onMatch.call($input);
				}
			} else {
//				console.debug("no match");
				$resultField.val("");
				
				// Handle new result
				if(opts.onNoMatch) {
					opts.onNoMatch.call(input, val);
				}
				return;
			}
			
		}, 100);
	});
	
	function formatItem(row) {
//		console.info("match!");
		return row[0];
	}
	
	var initValue = "";
	
	function formatResult(row) {
		
		initValue = row;
		return row[0].replace(/(<.+?>)/gi, '');
	}
	
	function getLoadValue() {
		return initValue;
	}
	
	function getResultField() {
		return $resultField;
	}
	
	function loadById(id) {
		var newOpts = $.extend({}, opts); 
		newOpts.extraParams.id = id;
		
		// Prevent that the first value in memory is set as value when blurring the field with another not existing value
		newOpts.selectFirst = false;
		
		$input.setOptions(opts);
		
		// Override the "result" handler
		$input.search(function(result) {
			match = true;
			var data = getLoadValue();
			$input.val(data[0]);
			$resultField.val(data[1]);
			
			
			
			// Remove parameter to avoid posting it at next request
			newOpts.extraParams.id = "";
			
			// Set back to initial value. Op de een of andere manier moet dit erbij
			//TODO: zou moeten gaan via: newOpts.selectFirst = opts.selectFirst;
			newOpts.selectFirst = opts.selectFirst;
			
//			console.debug(newOpts);
			$input.setOptions(newOpts);
		});
	}

}

})(jQuery);