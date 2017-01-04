<?php
class BCEmailGeneratorPartHtmlTag extends BCEmailGeneratorPart {
	/**
	 * @var string
	 */
	private $tagname;
	/**
	 * @var string
	 */
	private $content;
	/**
	 * @var array
	 */
	private $attributes = null;
	/**
	 * @var string
	 */
	private $style = "";
	/**
	 * @param string $tagname
	 * @param string $content
	 * @param array $attributes
	 * @param string $style
	 */
	public function BCEmailGeneratorPartHtmlTag($tagname, $content, array $attributes = array(), $style="") {
		$this->tagname = $tagname;
		$this->content = $content;
		$this->attributes = $attributes;
		$this->style = $style;
		$this->parts = new BCEmailGeneratorPartCollection();
	}
	public function createHTML($user = null) {
		return BCEmailGenerator::render(
			$this->tagname,
			$this->content.$this->createSubpartsHTML($user),
			$this->attributes,
			$this->style
		);
	}
	/**
	 * @param string $tagname
	 */
	public function setTagname($tagname) {
		$this->tagname = $tagname;

	}
	/**
	 * @return string
	 */
	public function getTagname() {
		return $this->tagname;

	}
	/**
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;

	}
	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;

	}
	/**
	 * @param array $attributes
	 */
	public function setAttributes($attributes) {
		$this->attributes = $attributes;

	}
	/**
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;

	}
	/**
	 * @param string $style
	 */
	public function setStyle($style) {
		$this->style = $style;

	}
	/**
	 * @return string
	 */
	public function getStyle() {
		return $this->style;

	}

}
?>