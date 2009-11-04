$(document).ready(function() {
	$(".location").click(function(){
		var id = this.getAttribute("key");
		var cat = this.getAttribute("cat");
		$.getJSON("/servlets/users/by_most_populair_spot.php?format=json",
		{
			id: id,
			cat: cat,
			user: _user
		},
        function(data) {
        	fillSpotsResults(data);
    	});
   	});
});

function fillSpotsResults(data){
	$("#spots_pictures").empty();
	$.each(data.items, function(i,item){
		var photo = "<div class=\"result_photo_container\">" +
		"<div class=\"result_photo\" style=\"background: url("+item.photo+") 50% no-repeat\" >"+
			"<div style=\"background: url(/images/global/photo_frame.gif) no-repeat; height: 58px; height: 58px;\"></div>" +
		"</div>"+
		"<a class=\"nick\" href=\"/?page=profile&action=view&user="+item.user+"\">"+item.user+"</a>"+
		"</div>";
		$(photo).appendTo("#spots_pictures");
	});
	$("#label1").html(data.title);
	$("#label2").html(data.addition);
	$("#showall").html("Toon allen (" + data.nrofresults + ")");
}