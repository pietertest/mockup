<h1>Oproep bekijken ({$oproep->getKey(0)})</h1>
{assign var=template value=$oproep->getShortCatName($cat)}
{include file=searchercall/view/$template.view.tpl}
