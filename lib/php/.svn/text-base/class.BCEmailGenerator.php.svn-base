<?php
class BCEmailGenerator {
	/**
	 * @var array
	 */
	private static $styles = array(
		"upperLine"=>array(
			"font-size"=>"11px",
			"color"=>"#333333",
			"text-decoration"=>"none",
			"font-family"=>"arial,sans-serif",
		),
		"upperLineLink"=>array(
			"font-size"=>"11px",
			"color"=>"#005192",
			"text-decoration"=>"none",
			"font-family"=>"arial,sans-serif",
		),
		"border"=>array(
			"border"=>"1px solid #ccc",
		),
		"subjectLine"=>array(
			"background-color"=>"#005192",
			"color"=>"#FFFFFF",
			"text-align"=>"right",
			"font-weight"=>"bold",
			"padding-right"=>"20px",
			"padding-top"=>"5px",
			"padding-bottom"=>"5px",
			"font-family"=>"arial,sans-serif",
			"font-size"=>"12px",
			"height"=>"20px"
		),
		"contentCell"=>array(
			"padding"=>"20px",
		),
		"footer"=>array(
			"background-color"=>"#005192",
			"color"=>"#FFFFFF",
			"padding"=>"20px",
		),
		"footerBold"=>array(
			"color"=>"#FFFFFF",
			"font-weight"=>"bold",
			"font-size"=>"11px",
			"font-family"=>"arial,sans-serif",
		),
		"footerNormal"=>array(
			"color"=>"#FFFFFF",
			"font-size"=>"11px",
			"font-family"=>"arial,sans-serif",
			"text-decoration"=>"none",
			"text-align"=>"right",
		),
		"box"=>array(
			"border"=>"1px solid #ccc",
			"padding"=>"5px 10px",
			"background-color"=>"#efefef",
		),
		"marginBottom" => array(
			"margin-bottom"=>"20px",
		),
		"salutation"=>array(
			"color"=>"#000000",
			"font-size"=>"14px",
			"font-family"=>"arial,sans-serif",
			"font-weight"=>"bold",
		),
		"sectionHeadline"=>array(
			"color"=>"#005192",
			"font-size"=>"16px",
			"font-family"=>"arial,sans-serif",
			"font-weight"=>"normal",
		),
		"sectionImg"=>array(
			"border"=>"1px solid #CCC",
			"padding"=>"12px 4px 10px 4px",
			"background-color"=>"white",
			"text-align"=>"center",
		),
		"sectionText"=>array(
			"padding"=>"5px 10px",
			"font-family"=>"arial,sans-serif",
			"font-size"=>"12px",
		),
		"readMore"=>array(
			"text-align"=>"right",
		),
		"readMoreLink"=>array(
			"font-family"=>"arial,sans-serif",
			"font-size"=>"12px",
			"color"=>"#005192",
			"font-weight"=>"bold",
			"text-decoration"=>"none",
		),
	);
	/**
	 * @var string
	 */
	private $id;
	/**
	 * @var BCEmailGeneratorPart
	 */
	private $rootElement;
	/**
	 * @var BCEmailGeneratorPart
	 */
	private $contentElement;
	/**
	 * @var string
	 */
	private $subject;
	/**
	 * @var string
	 */
	private $staticFile;
	/**
	 * @var string
	 */
	private $staticFileUrl;
	public function BCEmailGenerator($id, $staticFile, $subject) {
		$this->id = $id;
		$this->subject = $subject;
		$this->staticFile = $staticFile;
		$this->staticFileUrl = "http://"
			.$_SERVER['SERVER_NAME']
			.str_replace($_SERVER['DOCUMENT_ROOT'], "", $this->staticFile);
		$this->rootElement = new BCEmailGeneratorPartLayoutTable("640", "border");
		$this->contentElement = $this->buildRootElementAndGetContentElement();
	}
	public function addContent(BCEmailGeneratorPart $part) {
		$this->contentElement->parts->addItem($part);
	}
	/**
	 * return personalized HTML-Code
	 *
	 * @return string
	 */
	public function personalize($user) {
		return $this->createHTML($user);
	}
	/**
	 * Saves unpersonalized HTML-Code to $staticFile
	 *
	 * @return void
	 */
	public function saveStatic() {
		if(!is_dir(dirname($this->staticFile))) {
			mkdir(dirname($this->staticFile), 0777, true);
		}
		file_put_contents($this->staticFile, $this->createHTML(null));
	}
	/**
	 * @return string
	 */
	public function getStaticFileUrl() {
		return $this->staticFileUrl;
	}
	/**
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}
	public static function render($tagname, $content, array $attributes = array(), $style="") {
		$r = "<".$tagname."
";
		$r .= self::styleAttribute($style);
		foreach($attributes as $name=>$value) {
			$r .= ' '.$name.'="'.$value.'"
';
		}
		$r .= ">
".$content."
</$tagname>";
		return $r;
	}
	public static function styleAttribute($style) {
		if(is_array(self::$styles[$style])) {
			$r = 'style="';
			foreach(self::$styles[$style] as $prop=>$value) {
				$r .= $prop.":".$value.";
";
			}
			$r .= '"';
		}
		return $r;
	}
	public static function fl($url) {
		return "http://".$_SERVER['SERVER_NAME'].$url;
	}
	/**
	 * Basis wrapper method for HTML-rendering
	 *
	 * @param stdClass $user
	 * @todo Footer
	 * @return string
	 */
	private function createHTML($user = null) {
		$r = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content-type="text/html;charset=utf-8">
<style>
a:hover {
text-decoration:underline;
}
</style>
</head>
<body style="margin:0;padding:0;"><center>
'.$this->rootElement->createHTML($user).'
</center></body></html>';
		return $r;
	}
	/**
	 * @return BCEmailGeneratorPart
	 */
	private function buildRootElementAndGetContentElement() {
		$this->rootElement->addPart(new BCEmailGeneratorPartHtml('
<table
width="100%"
border="0"
cellpadding="20"
cellspacing="0"
>
<tr>
<td>
<a href="'.$this->getStaticFileUrl().'"
'.BCEmailGenerator::styleAttribute('upperLine').'
>Wenn dieser Newsletter in Ihrem E-Mail-Programm nicht
richtig angezeigt wird,<br/>
klicken Sie bitte hier für die Online-Version
</td>
<td>
<img
border="0"
usemap="#map"
src="'.self::fl("/themes/pixture_reloaded/images/assets-header.png").'" />
<map name="map">
<area
shape="rect"
coords="1,0,23,23" href="mailto:info@bankingcheck.de" />
<area
shape="rect"
coords="28,0,52,24" href="'.self::fl("/rss.xml").'" />
<area
shape="rect"
coords="57,0,82,24" href="http://www.facebook.com/BankingCheck" />
<area
shape="rect"
coords="87,0,110,24" href="http://www.twitter.com/BankingCheck" />
<area
shape="rect"
coords="115,0,139,24" href="https://plus.google.com/115413476422580977786" />			
</map>
</td>
</tr>
</table>
'))->addPart(new BCEmailGeneratorPartHtml('
<a href='.self::fl("/").'>
<img border="0"
src="'.self::fl("/sites/default/files/bcgrafiken/emailheader.png").'" />
</a>
'))->addPartGoInto(new BCEmailGeneratorPartLayoutTable("100%","subjectLine"))
   ->addPart(new BCEmailGeneratorPartHtml($this->getSubject()));
		$r = $this->rootElement->addPartGoInto(new BCEmailGeneratorPartLayoutTable("100%", "contentCell"));
		$r->addPart(new BCEmailGeneratorPartSalutation());
		$this->rootElement->addPartGoInto(new BCEmailGeneratorPartLayoutTable("100%", "footer"))
			->addPart(new BCEmailGeneratorPartHtml('
<p
'.BCEmailGenerator::styleAttribute("footerBold").'
>Copyright © '.date("Y").' BankingCheck.de
</p>
'))->addPart(new BCEmailGeneratorPartHtml('
<table
width="100%"
border="0"
cellpadding="0"
cellspacing="0"
style="border-bottom:1px solid #FFFFFF"
>
<tr
>
<td
'.BCEmailGenerator::styleAttribute("footerNormal").'
>
<br/>
<br/>
</td>
<td
'.BCEmailGenerator::styleAttribute("footerNormal").'
>
<a href="'.self::fl("/kontakt").'" '.BCEmailGenerator::styleAttribute('footerNormal').'>Kontakt</a><br>
<a href="'.self::fl("/Impressum").'" '.BCEmailGenerator::styleAttribute('footerNormal').'>Impressum</a>
<br/>
<br/>
</td>
</tr>
</table>
'))->addPart(new BCEmailGeneratorPartUnsubscribeLink($this->id));
		return $r;
	}
}
?>