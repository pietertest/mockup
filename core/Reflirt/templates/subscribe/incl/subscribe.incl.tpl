	Meldt je gratis aan en ga op zoek naar je flirt!
	<br/>
	<br/>
	<div class="panel">
		<form method="post" id="subscribeForm" class="subscribeForm" action="/">
			<input type="hidden" name="page" value="subscribe" />
			<input type="hidden" name="action" value="register" />
				<table width="100%" border="0">
					<tr>
						<td class="label">Gebruikersnaam{*t}l_gebruikersnaam{/t*}:</td>
						<td class="value"><input type="text" name="username" id="username" value="{$username}"}  maxlength="20"/></td>
						<td class="errorContainer">&nbsp;</td>
					</tr>
					<tr>
						<td class="label">Wachtwoord:</td>
						<td class="value"><input type="password" name="password" id="password" maxlength="20" /></td>
						<td class="errorContainer">&nbsp;</td>
					</tr>
					<tr>
						<td class="label">Wachtwoord nogmaals: </td>
						<td class="value"><input type="password" name="password2" id="password2"  maxlength="20"/></td>
						<td class="errorContainer">&nbsp;</td>
					</tr>
					<tr>
						<td class="label">E-mail adres:</td>
						<td class="value"><input type="text" name="email" value="{$email}"  maxlength="255"/></td>
						<td valign="top" class="errorContainer">&nbsp;</td>
					</tr>
					<tr>
						<td class="label">Geslacht:</td>
						<td class="value"><select name="sex">
								<option value="">Kies...</option>
								<option value="1">Man</option>
								<option value="0">Vrouw</option>
							</select>
						</td>
						<td class="errorContainer">&nbsp;</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<br/>
							<input type="submit" value="Aanmelden" />
						</td>
						<td></td>
					</tr>
				</table>
			
		</form>
	</div>

{literal}
<script>
$(document).ready(function() {
	var validateOptions = {
		errorElement: "div",
		rules: {
			username: {
				required: true,
				minlength: 5
			},
			password: {
				required: true,
				minlength: 5
			},
			password2: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
			sex: {
				required: true
			}
			
		},
		messages: {
			username: {
				required: "Kies een gebruikersnaam",
				minlength: "Gebruikersnaam moet teminste 5 karakters" 
			},
			password: {
				required: "Vul een wachtwoord in",
				minlength: "Het wachtwoord moet uit minimaal 5 karakters bestaan"
			},
			password2: {
				required: "Vul een wachtwoord in",
				minlength: "Het wachtwoord moet uit minimaal 5 karakters bestaan",
				equalTo: "De ingevulde wachtwoorden dienen gelijk te zijn"
			},
			email: {
				required: "Vul je e-mailadres in",
				email: "Vul een geldig e-mailadres in"
			},
			sex: {
				required: "Geef je geslacht aan"
			}
		},
		errorPlacement: function(error, element) {
			if ( element.is(":radio") )
				error.appendTo( element.parent().next().next() );
			else if ( element.is(":checkbox") )
				error.appendTo ( element.next() );
			else {
				element.parent().next().html(error);
			}
		},
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("&nbsp;").addClass("checked");
		}
	};
	//window.location.href = "/?page=account";
	var formId = "#subscribeForm";
	var options = { 
		dataType:  "json", 
        success:   function(data) {
			if (data.success) {
				done();
				window.location.href = "/?page=account";
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
		beforeSubmit: function() {
			return $("#subscribeForm").validate(validateOptions).form();
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
	$(formId).ajaxForm(options);

});
</script>
{/literal}