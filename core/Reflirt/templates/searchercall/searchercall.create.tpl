<script type='text/javascript' src='/javascript/jquery/jquery.datepicker.js'></script>
<script type='text/javascript' src='/javascript/jquery/date.js'></script>
<script type='text/javascript' src='/javascript/jquery/jquery.dimensions.js'></script>

{if $type==3}
<h1>Nieuw Spot: {$categoryname}</h1>
{else}
<h1>Nieuw oproep: {$categoryname}</h1>
{/if}
{include file=searchercall/modify/$shortname.modify.tpl}





