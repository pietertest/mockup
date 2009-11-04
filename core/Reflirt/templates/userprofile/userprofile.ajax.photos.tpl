{literal}<script>
var _username = '{/literal}{$profileuser->getString('username')}{literal}';
var _userid = '{/literal}{$profileuser->getKey()}{literal}';
var curPhotoId = '{/literal}{$photoid}{literal}';
var _photos = new Array();
var currentIndex = 0;
var _buffered = new Array();

$().ready( function(){
	$.getJSON("/servlets?servlet=photos&action=byuser",
	{
		userid: _userid
	},
	function(data) {
		initPhotoArray(data);
		start();
	});
	$("#butnext").click(function() {
		next();
	});
	$("#butprev").click(function() {
		previous();
	});
		
});

function initPhotoArray(data) {
	$.each(data.items, function(i, item) {
		var info =  new Array();
		info['id'] = item.id;
		info['filename'] = item.filename;
		info['descr'] = item.descr;
		info['orig_filename'] = item.orig_filename;
		var index =_photos.length;
		_photos[index] = info;
		_buffered[_buffered.length] = false;
	});
}

function start() {
	if(!curPhotoId) {
		curPhotoId = 0;
	}
	initCurrentIndex();
	loadImage(currentIndex);
	showPhoto(currentIndex);
	bufferNext();
	bufferPrevious();
	disableButtonsIfNecessary();
}

function initCurrentIndex() {
	for(var i = 0; i < _photos.length; i++) {
		if(_photos[i]['id'] == curPhotoId) {
			currentIndex = i;
			break;
		}			
	}
}

function showPhoto(index) {
	var id = _photos[index]['id'];
	var descr = _photos[index]['descr'] == "" ? _photos[index]['orig_filename'] : _photos[index]['descr'];
	//loadImage(index);
	$("#photocontainer > *").addClass("hidden");
	$("#myphoto_"+id).removeClass("hidden");
	$("#title").text(descr);
}

function loadImage(index) {
	if(_buffered[index]) {
		return;
	}
	var id = _photos[index]['id'];
	var name = _photos[index]['filename'];
	var descr = _photos[index]['descr'];
	var html = '<img src="/servlets/?servlet=media&resize=2&entity=Photo&id='+id+'" width="400" id="myphoto_'+id+'"/>';
	$(html).addClass("hidden").appendTo("#photocontainer");
	_buffered[index] = true;
}

function bufferPrevious() {
	if(currentIndex > 0) {
		// Buffer previous image
		loadImage(currentIndex-1);
	}
}

function bufferNext() {
	if(currentIndex < _photos.length-1 ) {
		// Buffer next image
		loadImage(currentIndex+1);
	}
}
function previous() {
	currentIndex--;
	bufferPrevious();
	showPhoto(currentIndex);
	disableButtonsIfNecessary();	
}

function disableButtonsIfNecessary() {
	// Disable/enable buttons
	if(currentIndex == 0) {
		$("#butprev").attr("disabled",true);
	} else {
		$("#butprev").attr("disabled", false);
	}	
	if(currentIndex < _photos.length-1) {
		$("#butnext").attr("disabled",false);
	}
	
	// next
	// Disable/enable buttons
	if(currentIndex == _photos.length-1) {
		//alert("Dit is de laatste foto");
		$("#butnext").attr("disabled",true);
	} else {
		$("#butnext").attr("disabled", false);
	}
	if(currentIndex > 0) {
		$("#butprev").attr("disabled",false);
	}
}

function next() {
	currentIndex++;
	bufferNext();
	showPhoto(currentIndex);
	disableButtonsIfNecessary();
}

{/literal}</script>
	<div class="userdata">
	<br/>
					
		{assign var=class value=" odd"}
		<table border="0" cellpadding="0" cellspacing="0" width="400">
			<tr>
				<td valign="top">
					<div class="messageTop{$class}">
						<div class="messageDing">
							
							<div class="photonavigation">
								<input type="button" value="Volgende" id="butnext" style="float: right"/>
								<input type="button" value="Vorige" id="butprev" style="float: right"/>
							</div>
							
							<div class="photoframe" id="photoframe">
								<h2><div id="title" class="title"></div></h2>
								<br/>
								<div id="photocontainer" ></div>
							</div>
						
						</div>
					</div>
					
					<div class="messageBottom{$class}">					
					</div>
				</td>
			</tr>
		</table>
	</div>
		
