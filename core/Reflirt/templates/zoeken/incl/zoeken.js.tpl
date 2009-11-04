<script language="JavaScript" type="text/javascript">
{literal} 
$().ready(function() {
	initDatePickers();

	$("#category").change(function() {
		loadFields();
	});
}); 

function loadFields() {
	
	var cat = $("#category").val();
	if (cat == "") {
		removeFields();
		return;
	}

	var url = "/?page=spot&action=" + formFieldsUrl + "&cat=" + cat;

	$.ajax({
		url: url,
		success:  function(html) {
			removeFields();
			$("#spacer").after(html);
		}
	});
}

function removeFields() {
	$("tr[dynamicfield]").remove();
}

function initDatePickers() {
	$('#startdate').datePicker({
		clickInput: true,
		clickButton: false,
		verticalOffset: 20
	}).bind('dateSelected',	
		function(e, selectedDate, $td) 	{
			$("#regelmatig").attr("checked", false);
		}
	);

	if($("#regelmatig").attr("checked")) {
		$("#startdate").val("..vaker..").dpSetDisabled(true);
	}

	$("#regelmatig").click(function(){
		if($(this).attr("checked")) {
			$("#startdate").val("..vaker..").dpSetDisabled(true);
		} else {
			$("#startdate").val("").dpSetDisabled(false);
		}
	});

}

</script>
{/literal}