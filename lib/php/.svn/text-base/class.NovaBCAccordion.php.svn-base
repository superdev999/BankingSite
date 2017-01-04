<?php
class NovaBCAccordion {
	private $identifier;
	private $groups;

	public function __construct($identifier, $groups) {
		$this->identifier = $identifier;
		$this->groups = $groups;
	}

	public function getHTML() {
		$start=true;
		$html='';

		#dpm($this->groups);

		foreach ($this->groups as $key => $group) {
			if(!$start) $html .= '</div></div>';

			$start=false;

			$html .= '<div class="accordionItem">';
			$html .= '<h3 class="accordionTitle">'.$group->title.'</h3>';
			$html .= '<div class="accordionBody" id="'.$this->identifier.'element'.$group->nid.'"><div class="content">';

			$html .= "<img src='/themes/pixture_reloaded/images/ajax-loader.gif' /></div><div class='bewertung'></div>";

		}
		$html .= '</div></div>';
		#dpm($html);
		return $html;
	}

	public function setJavascript($otherAccordions, $listItemsUrl, $productItemsUrl) {
		drupal_add_js('$(function() {setupAccordion("'.$this->identifier.'", "'.$listItemsUrl.'", '.$otherAccordions.'); getProductItems("'.$this->identifier.'", "'.$productItemsUrl.'");});', 'inline');
	}

	public static function getGroupsByQuery($groups) {
		$result = array();
		while ($groupNode = db_fetch_object ($groups)) {
			$result[] = $groupNode;
		}
		return $result;
	}

	public static function getGroupsByCatNames($catNames) {
		$result = array();
		foreach ($catNames as $key => $catName) {
			$catNode = node_load(array('title' => $catName, 'type' => 'anbieterkategorie'));
			$group = new stdClass();
			// Titel der Kategorie
			$group->title = $catNode->title;
			// Nid der Kategorie
			$group->nid = $catNode->nid;
			$result[] = $group;
		}
		return $result;
	}

	public static function getAllGroups() {
		$nids = array();
		$result = array();
		
		$queryResult = db_query("SELECT nid FROM node WHERE type = '%s' ORDER BY title", "anbieterkategorie");

		while ($obj = db_fetch_object ($queryResult)) {
			$nids[] = $obj->nid;
		}
		foreach ($nids as $key => $nid) {
			$catNode = node_load(array('nid' => $nid, 'type' => 'anbieterkategorie'));
			$group = new stdClass();
			// Titel der Kategorie
			$group->title = $catNode->title;
			// Nid der Kategorie
			$group->nid = $catNode->nid;
			$result[] = $group;
		}
		return $result;
	}
}
