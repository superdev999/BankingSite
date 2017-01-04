<?php
abstract class NovaBCUser {
	public static function buildUser($uid) {
		$user = user_load(array("uid" => $uid));
		#var_dump($user->roles);
		if (in_array("Unternehmen", $user->roles)) {
			return new NovaBCCompanyUser($user);
		} else {
			//
			if ($user->name == "admin" || in_array("admin", $user->roles)) {
				// 2589: kautionsfrei.de
				// 2603: DKB/Nova Bank
				$user = new NovaBCCompanyUser(user_load(array("uid" => 2589)));
				drupal_set_message("Hinweis: Ausgabe der Unternehmensfunktionen als Benutzer für \"".$user->getMyBankNode()->title."\".", 'warning', FALSE);
				return $user;
			}
			throw new Exception($user->name." is not a Company user. This is not yet implemented.");
		}

	}

	public static function isCompanyUser($uid) {
		$user = user_load(array("uid" => $uid));
		return in_array("Unternehmen", $user->roles);
	}
}
