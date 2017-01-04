<?php

class BCEmail extends Email {
	public static $fromAddress = "info@bankingcheck.de";
	public function BCEmail($user, BCEmailGenerator $gen) {
		// Auf dem Testsystem nur an bankingcheck.de, nova-web.de und boedger.de Emails senden
		if ($_ENV["HTTP_HOST"] == "www.testsystem.de.bankingcheck.nova-web.de") {
			if (preg_match('/bankingcheck.de$|nova-web.de$|boedger.de$/', $user->mail) == true) {
				parent::Email($user->mail, self::$fromAddress, $gen->getSubject());
				$this->addHTML($gen->personalize($user));
			}
		} else {
			parent::Email($user->mail, self::$fromAddress, $gen->getSubject());
			$this->addHTML($gen->personalize($user));
		}
	}
}
?>