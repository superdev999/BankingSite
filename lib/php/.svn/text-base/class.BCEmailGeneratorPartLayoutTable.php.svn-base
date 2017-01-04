<?php
class BCEmailGeneratorPartLayoutTable extends BCEmailGeneratorPart {
	public $width;
	public $contentCellStyle;
	public $tableStyle;
	public function BCEmailGeneratorPartLayoutTable($width = "100%", $contentCellStyle="", $tableStyle="") {
		$this->width = $width;
		$this->contentCellStyle = $contentCellStyle;
		$this->tableStyle = $tableStyle;
		$this->parts = new BCEmailGeneratorPartCollection();
	}
	public function createHTML($user = null) {
		$r = '<table
'.BCEmailGenerator::styleAttribute($this->tableStyle).'
border="0"
cellpadding="0"
cellspacing="0"
width="'.$this->width.'">
<tr>
'.BCEmailGenerator::render("td", $this->createSubpartsHTML($user), array(), $this->contentCellStyle).'
</tr>
</table>
';
		return $r;
	}
}
?>