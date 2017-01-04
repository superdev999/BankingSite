<?php
class BCEmailGeneratorPartSalutation extends BCEmailGeneratorPart {
	public function createHTML($user = null) {
		if(is_null($user)) {
			return "";
		} else {
			$this->parts->clear();
			$this->addPartGoInto(new BCEmailGeneratorPartLayoutTable("100%","box","marginBottom"))
			->addPart(new BCEmailGeneratorPartHtml('
<table
width="100%"
border="0"
cellpadding="0"
cellspacing="0"
style="border-bottom:1px solid #FFFFFF"
>
<tr>
<td
width="60"
>
<img
src="'.BCEmailGenerator::fl("/sites/default/files/bcgrafiken/people.png").'" />
</td>
<td
'.BCEmailGenerator::styleAttribute("salutation").'
>
Hallo '.$user->name.'!
</td>
<td width="90">
<a
href="'.BCEmailGenerator::fl("/user/".$user->uid).'">
<img
src="'.BCEmailGenerator::fl("/sites/default/files/bcgrafiken/myProfile.png").'" />
</a>
</td>
</tr>
</table>
'));
			return $this->createSubpartsHTML($user);
		}
	}
}
?>