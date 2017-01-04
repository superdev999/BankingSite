<?php
class BCEmailGeneratorPartSection extends BCEmailGeneratorPartLayoutTable {
	/**
	 * @var array
	 */
	private $upperLineLinks;
	/**
	 * @var string
	 */
	private $headline;
	/**
	 * @var string
	 */
	private $imgUrl;
	/**
	 * @var string
	 */
	private $imgLink;
	/**
	 * @var string
	 */
	private $text;
	/**
	 * @var string
	 */
	private $linkText;
	/**
	 * @var string
	 */
	private $linkUrl;
	
	private $headerBar;
	/**
	 * @param array $upperLineLinks
	 * @param string $headline
	 * @param string $imgUrl
	 * @param string $imgLink
	 * @param string $text
	 * @param string $linkText
	 * @param string $linkUrl
	 */
	public function BCEmailGeneratorPartSection(array $upperLineLinks=null, $headline=null, $imgUrl=null, $imgLink=null, $text=null, $linkText=null, $linkUrl=null, $headerBar = null) {
		$this->upperLineLinks = $upperLineLinks;
		$this->headline = $headline;
		$this->imgUrl = $imgUrl;
		$this->imgLink = $imgLink;
		$this->text = $text;
		$this->linkText = $linkText;
		$this->linkUrl = $linkUrl;
		$this->headerBar = $headerBar;
		parent::BCEmailGeneratorPartLayoutTable("100%", "box", "marginBottom");
	}
	public function createHTML($user = null) {
		$this->parts->clear();
		$upperlineHTML = '';
		if(!is_null($this->headerBar)) $upperlineHTML .= $this->headerBar;
		$upperlineHTML .= '
<p
'.BCEmailGenerator::styleAttribute("upperLine").'
>
'.format_date(time(), "custom", "d. F Y");
		if(count($this->upperLineLinks)) {
			foreach($this->upperLineLinks as $href=>$text) {
				$upperlineHTML .= ' / '.BCEmailGenerator::render("a", $text, array("href"=>BCEmailGenerator::fl($href)), "upperLineLink");
			}
		}
		$upperlineHTML .= '
</p>
';
		$this->addPart(new BCEmailGeneratorPartHtml($upperlineHTML));
		if(!is_null($this->headline)) {
			$this->addPart(new BCEmailGeneratorPartHtmlTag("h1", $this->headline,array(),"sectionHeadline"));
		}
		$img = null;
		if(!is_null($this->imgUrl)) {
			$img = new BCEmailGeneratorPartLayoutTable("100%", "sectionImg");
			if(is_null($this->imgLink)) {
				$append = $img;
			} else {
				$append = $img->addPartGoInto(new BCEmailGeneratorPartHtmlTag("a", "" , array("href"=>BCEmailGenerator::fl($this->imgLink))));
			}
			$append->addPart(new BCEmailGeneratorPartHtmlTag("img", "", array(
				"src"=>BCEmailGenerator::fl($this->imgUrl),
				"border"=>"0"
			)));
		}
		$text = null;
		if(!is_null($this->text)) {
			$text = new BCEmailGeneratorPartHtml('
<div
'.BCEmailGenerator::styleAttribute("sectionText").'
>
'.$this->text.'
</div>');
		}
		if(!is_null($img) && !is_null($text)) {
			$this->addPart(new BCEmailGeneratorPartHtml('
<table
width="100%"
border="0"
cellpadding="0"
cellspacing="0"
>
<tr>
<td
width="110px"
>
'.$img->createHTML($user).'
</td>
<td
valign="top"
'.BCEmailGenerator::styleAttribute('sectionText').'
>
'.$text->createHTML($user).'
</td>
</tr>
</table>
'));
		} elseif (!is_null($text)) {
			$this->addPart($text);
		} elseif (!is_null($img)) {
			$this->addPart($img);
		}
		if(!is_null($this->linkText)) {
			$this->addPartGoInto(new BCEmailGeneratorPartLayoutTable("100%", "readMore"))
				->addPart(new BCEmailGeneratorPartHtmlTag("img","",array(
					"border"=>"0",
					"src"=>BCEmailGenerator::fl("/sites/default/files/bcgrafiken/readMore.png")
				)))
				->addPart(new BCEmailGeneratorPartHtmlTag(
					"a",
					$this->linkText,
					array(
						"href"=>BCEmailGenerator::fl($this->linkUrl),
					),
					"readMoreLink"
				));
		}
//		$this->addPart($img);
		return parent::createHTML();
	}
	/**
	 * @param array $upperLineLinks
	 */
	public function setUpperLineLinks($upperLineLinks) {
		$this->upperLineLinks = $upperLineLinks;
	}
	/**
	 * @return array
	 */
	public function getUpperLineLinks() {
		return $this->upperLineLinks;

	}
	/**
	 * @param string $headline
	 */
	public function setHeadline($headline) {
		$this->headline = $headline;

	}
	/**
	 * @return string
	 */
	public function getHeadline() {
		return $this->headline;

	}
	/**
	 * @param string $imgUrl
	 */
	public function setImgUrl($imgUrl) {
		$this->imgUrl = $imgUrl;

	}
	/**
	 * @return string
	 */
	public function getImgUrl() {
		return $this->imgUrl;

	}
	/**
	 * @param string $imgLink
	 */
	public function setImgLink($imgLink) {
		$this->imgLink = $imgLink;

	}
	/**
	 * @return string
	 */
	public function getImgLink() {
		return $this->imgLink;

	}
	/**
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;

	}
	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;

	}
	/**
	 * @param string $linkText
	 */
	public function setLinkText($linkText) {
		$this->linkText = $linkText;

	}
	/**
	 * @return string
	 */
	public function getLinkText() {
		return $this->linkText;

	}
	/**
	 * @param string $linkUrl
	 */
	public function setLinkUrl($linkUrl) {
		$this->linkUrl = $linkUrl;

	}
	/**
	 * @return string
	 */
	public function getLinkUrl() {
		return $this->linkUrl;

	}

}
?>