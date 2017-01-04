<?php
function novacompanyuser_companyInner()
{
	$html = '<h1>Anbieter</h1>
	<p>Hier können Sie Ihre Anbieterdetails und Beschreibung bearbeiten. Die Anbieterkonditionen wie Einlagensicherung, Art der Bank und Produkte werden automatisch alle 3h aktualisiert.</p>';
	// redirect to node
	$novaBCUser = NovaBCUser::buildUser($GLOBALS['user']->uid);
	$bankNode = $novaBCUser->getMyBankNode();
	drupal_goto('node/'.$bankNode->nid.'/edit');
	return $html;
}
?>