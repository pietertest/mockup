		<tr>
			<td width="170">{$catlabel}: </td>
			<td>
				<input type="text" name="name" id="name" />
				<a nohref id="getsimilar">Check</a>
				<label />
			</td>
		</tr>
		<tr>
			<td>Straat: </td>
			<td>
				<input type="text" name="street" id="street" />
				Huisnr:<input type="text" name="houseno" id="houseno" size="4"/>
				<label />
			</td>
		</tr>
		<tr>
			<td>Postcode: </td>
			<td>
				<input type="text" name="zipcode" id="zipcode" />
				<label />
			</td>
		</tr>
		<tr>
			<td>Stad: </td>
			<td>
				<input type="text" name="cityname" id="cityname"/>
				<label />
				<input type="hidden" name="cityid" id="cityid" />
			</td>
		</tr>

{literal}
<script language="JavaScript" type="text/javascript">

$().ready(function() {
	$("#name").change(function() {
		getSimilar();
	});
	$("#getsimilar").click(function(){
		getSimilar();
	});
	initValidation();
});

function getSimilar() {
	$.getJSON("/servlets/?servlet=spots&action=getsimilarbyaddres", {
		cat: function() { return $("#category").val(); },
		name: $("#name").val(),
		street: $("#street").val(),
		houseno: $("#houseno").val(),
		zipcode: $("#zipcode").val(),
		cityid: $("#cityid").val(),
		start: start,
		end: end
	},
	function(data) {
		processSimilarSpotData(data);
	});
}

function formatMapInfoHtml(item) {
	return "<b>" + item.name + "</b><br/>" + item.street + " " + 
				item.houseno + "<br/>" + item.zipcode + "<br/>"+item.cicityname
}

function formatSimilarLink(item) {
	return "<li>"+item.name+"<span> - "+item.cicityname+"</span></li>";
}

function formatEnteredInfoHtml() {
	return "<b>" + $("#name").val() + "</b><br/>" + $("#street").val() + " " +
		$("#houseno").val() + "<br/>" + $("#zipcode").val() + $("#cityname").val();
}

function getAddress() {
	var GQuery = new Array();
	//GQuery[GQuery.length] = $("#name").val();
	if (!isEmpty($("#street").val())) {
		GQuery[GQuery.length] = $("#street").val() + " " + $("#houseno").val();
	}
	if(!isEmpty($("#zipcode").val())) {
		GQuery[GQuery.length] = $("#zipcode").val();
	}
	if(!isEmpty($("#cityname").val())) {
		GQuery[GQuery.length] = $("#cityname").val();
	}
	return GQuery.join(","); 
}

function formatGMapResponse(response) {
	var blaat = response;
	debugger;
}

function initValidation() {
	var validator = $("#newspotform").validate({
		rules: {
			name: {
				required : true
			},
			street: {
				required: true
			},
			zipcode: {
				required: true				
			},
			houseno: {
				required: true
			}, 
			cityname: {
				required: true
			}
		},
		messages: {
			name: {
				required: "Please enter your firstname"
			},
			street: {
				required: "Vul een straatnaam in"
			},
			zipcode: {
				required: "Vul een postcode in"
			},
			houseno: {
				required: "Vul een huisnummer in"
			},
			cityname: {
				required: "Vul een plaatnaam in"
			}			
		},
		errorPlacement: function(error, element) {
			error.appendTo( element.next());
		},
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	});
}

</script>

{/literal}

