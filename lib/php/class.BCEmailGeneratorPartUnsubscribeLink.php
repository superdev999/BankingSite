<?php
class BCEmailGeneratorPartUnsubscribeLink extends BCEmailGeneratorPart {
	private $id;
	public function BCEmailGeneratorPartUnsubscribeLink($id) {
		$this->id = $id;
	}
	public function createHTML($user = null) {
		if(is_null($user)) {
			return "";
		} elseif($this->id == "node_sender" && !is_null($user)) {
			return '<br/><a
href="'.BCEmailGenerator::fl("/user/".$user->uid).'" '.BCEmailGenerator::styleAttribute('footerNormal').'">
Die Newsletter Einstellungen für Produkte und Intervalle können Sie unter Ihrem Profil verwalten.
</a>';
		} elseif(!is_null($user)) {
			return "<br/>".BCEmailGenerator::render(
				"a",
				"Wenn Sie diesen Newsletter in Zukunft nicht mehr erhalten wollen, klicken Sie bitte hier",
				array(
					"href"=>BCEmailGenerator::fl("/emails/unsubscribe.php?id=".md5($this->id)."&r=".md5($user->uid))
				),
				"footerNormal"
			);
		}
	}
}
?>