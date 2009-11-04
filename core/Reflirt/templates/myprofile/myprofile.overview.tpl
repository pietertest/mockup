<div class="myprofile">
	<h2>Mijn Profiel</h2>
		<div class="panel">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="key">
						{assign var=profile value=$sessionuser->getProfile()}
						<div id="picture"> <img src="{$profile->getPhotoUrl()}" /> </div>
							
						</div>		
					</td>
					<td>
					<form enctype="multipart/form-data" action="/?page=myprofile&action=uploadphoto" method="post" id="pictureForm" >
						<input type="file" name="profilepicture" value="Zoeken.." />
						<input type="submit" value="Foto uploaden" />
					</form>
					{if $profilePictureUrl != ""}
						<a href="javascript:void(0)" id="deleteProfilePicture">Foto verwijderen</a>
					{else}
						<a href="javascript:void(0)" id="deleteProfilePicture" class="hidden">Foto verwijderen</a>
					{/if}
					</td>
				</tr>
			</table>
		</div>
		<br/>
		<br/>
		<h2>Persoonlijke gegevens</h2>
		<br/>
		
			
			<form action="/?page=myprofile&action=save" method="post" id="myprofileForm">
				<div class="panel">
					<input type="hidden" name="page" value="myprofile" />
					<input type="hidden" name="action" value="save" />
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td class="key">Naam:</td><td ><input type="text" name="firstname" value="{$firstname}" /></td>
						</tr>
						<tr>
							<td class="key">Achternaam:</td><td>{html_text field="lastname"}</td>
						</tr>
						<tr>
							<td  class="key">Geboortedatum:</td>
							
							<td >
							{if $birthdate}
								{html_select_date month_format="%m" start_year=1900 field_order="DMY" time=$birthdate reverse_years=true year_empty="" month_empty="" day_empty=""}
							{else}
								{html_select_date month_format="%m" start_year=1900 field_order="DMY" time="--" reverse_years=true year_empty="" month_empty="" day_empty=""}
							{/if}
							</td>
						</tr>
						<tr>
							<td class="key">Geslacht</td>
							<td>
								{html_options name=sex options=$options_sex selected=$sex}			
							</td>
						</tr>
						<tr>
							<td class="key">
								Land:
							</td>
								<td>
									{*html_options name="cicountryid" options=$countries selected=$cicountryid id="cicountryid"*}
									Nederland
								</td>
						</tr>
						<tr>
							<td class="key">Woonplaats</td>
							<td>
								<input type="text" tabindex="2" value="{$cicityname}" name="cicityname" id="cicityname" />
								<span id="countrylabel"></span>
								<input type="hidden" name="cityid" id="cityid" value="{$cityid}"/><br/>
							</td>
						</tr>
						<tr>
							<td class="key">Postcode</td><td>
		  						<input type="text" name="zipcode" value="{$zipcode}" size="6" maxlength="6" />
		  						<label>Dit zal zichtbaar zijn voor andere gebruikers</label>
		  					</td>
						</tr>
							
					</table>
				</div>
				<br/>
				<br/>
				<h2>Instellingen</h2>
				<br/>
				<div class="panel">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td>E-mailadres:</td>
							<td>
								<input type="text" name="email" value="{$email}" size="40"/>
							</td>
						</tr>
						<tr>
							<td>Nieuw wachtwoord:</td>
							<td>
								<input type="password" name="password" />
								nogmaals: <input type="password" name="password2" />
							</td>
						</tr>
					</table>
				
				</div>
				
				<br/>
				<br/>
				
				
				<div class="panel">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td colspan="2" align="right"><input type="submit" value="Bewaren" /></td>
						</tr>
					</table>
				</div>
				
				
			
			</form>
		
</div>


{literal}
<script>
function go(data) {
	alert(data);
}
$(function() {
	$("#deleteProfilePicture").click(function() {
		if(confirm("Weet je zeker dat je je profielfoto wilt verwijderen? Je oproepen zullen beter resultaten hebben wanneer je een profielfoto hebt")) {
			$.getJSON("/?page=myprofile&action=deletephoto", function (data) {
				if (data.success) {
					success("De profielfoto is verwijderd");
					$("#picture").html('<img src="' + data.photoUrl + '" />');
					$("#deleteProfilePicture").hide();
				} else if (data.fail) {
					warn(data.fail.message);
				}
			});
		}		
	});

	$("#pictureForm").ajaxForm({
		 beforeSubmit: function() {
	 		loading("De wijzigingen worden bewaard...");
		}, 
	     success:   function(data) {
	     	if(data.success) {
					success("Je profilefoto is aangepast.");
					$("#picture").html('<img src="' + data.photoUrl + '" />');
					$("#deleteProfilePicture").show();
		        } else if (data.fail){ 
					warn("Oeps! Er is een fout opgetreden bij het bewaren van je wijzigingen: " + data.fail.message);
		        } else { 
					warn("Oeps! Er is een fout opgetreden bij het bewaren van je wijzigingen. Probeer het nog een keer");
		        }
		       	done();
			}, 
	     dataType:  "json"
	 });
	 $("#myprofileForm").ajaxForm({
		 	beforeSubmit: function() {
		 		loading("De wijzigingen worden bewaard...");
	 		}, 
	        success:   function(data) {
	 			if (data.success) {
					done();
					success("Je wijzigingen zijn bewaard");
				} else if(data.fail) {
					done();
					warn(data.fail.message);
					var field = data.fail.field;
					if(field) {
						$("[name=" + field + "]").focus();
					}
				} else {
					log("No error or success specified in json callback");
					warn("Oeps! Het lijkt alsof er iets mis gegaan is. We hebben hier bericht van ontvangen. Probeer het nogmaals.");
				}
	 		}, 
	        dataType:  "json"
    });
	    
	$("#cicityname").autocomplete("/servlets/?servlet=autocomplete&action=city", {
		delay: 150,
		width: "260px",
		max: 10,
		formatItem: formatCityItem,
		formatResult: formatResult,
		selectFirst: false,
		mustMatch: true,
		extraParams: {
			cicountryid: function() {	return $("#cicountryid").val();}
		}
	});
	
	$("#cicityname").result(function(event, data, formatted) {
		$("#cityid").val(data[1]);
		$("#countrylabel").text(data[2]);
		$("#cicountryid").val(data[3]);
	});
	$("#cicityname").change(function() {
		if ($(this).val() == "" ) {
			$("#cityid").val("");
		}
	});
	
	function formatCityItem(row) {
		return row[0];
	}
	function formatResult(row) {
		return row[0];
	}	
	$("#cicountryid").change( function() {
		$("#cicityname").flushCache();
		$("#cityid").val("");
	});
	
	$(":input[name=zipcode]").blur(function(){
		if($(this).val() != "") {
			$("#showMeOnMap").show().check();
		} 
	});
	
});
</script>
{/literal}
