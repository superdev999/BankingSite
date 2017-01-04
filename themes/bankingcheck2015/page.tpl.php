<?php // $Id: page.tpl.php,v 1.17 2009/05/07 17:00:40 jmburnz Exp $
/**
 * @file
 *  page.tpl.php
 *
 * Theme implementation to display a single Drupal page.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 */
?>
<!doctype html>
<html lang="de">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php print $head; ?>
		<title><?php print $head_title; ?></title>
        <meta name="HandheldFriendly" content="true">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1, target-densitydpi=device-dpi">
        <?php print $scripts; ?>
        <?php print $styles; ?>
		<link rel="icon" type="image/x-icon" href="<?php echo drupal_get_path('theme', 'bankingcheck2015'); ?>/favicon.ico">
	</head>
	<body>
		<div id="root">
			<header id="top">
				<h1><a href="./" accesskey="h">BankingCheck.de</a></h1>
				<nav id="skip">
					<ul>
						<li><a href="#nav" accesskey="n">Skip to navigation (n)</a></li>
						<li><a href="#content" accesskey="c">Skip to content (c)</a></li>
						<li><a href="#footer" accesskey="f">Skip to footer (f)</a></li>
					</ul>
				</nav>
				<nav id="login">
					<ul>
						<li class="b">
						<?php
						global $user;
						if (in_array('authenticated user', array_values($user->roles))) {
							echo "<div id='newLoginUsername' class='HelloUser Userhandling'><span>";
							$value = $user->roles;
							print_r($value[3]);
							$numObject = db_fetch_object(db_query("SELECT COUNT(*) as count FROM `node` WHERE `type` LIKE 'bewertung_%' and `uid`=%d", $user->uid));
							$bewertungen = " (".$numObject->count." Produkt";
							$bewertungen .= $numObject->count == 1 ? "" : "e";
							$bewertungen .= " bewertet)";
							$authType = "Bankingcheck.de";
							echo l($user->name.$bewertungen,'user/'.$user->uid);
							echo "</span></div>";
						} else if (in_array('anonymous user', array_values($user->roles))) {
						?>
							<span><a title="Registrierung für Unternehmen (Banken, Versicherungen und Finanzdienstleister" href="/user/registercompany">Für Unternehmen</a></span>
							<a accesskey="9" title="Login für bestehende Kunden, Leser und Unternehmen" href="javascript:showLogin()">Login</a>
							<a accesskey="0" href="/user/register" title="Registrierung für private Nutzer, Kunden und Leser">Registrieren</a>
							<?php
							}
						?>
						</li>
					</ul>
				</nav>
				<nav id="nav">
					<?php print $superfish; ?>
				</nav>
			</header>
			<div id="content-area">
          <?php
          	print $content_top;
          	if ($tabs) { ?>
          		<div class="secondNav"><?php print $tabs; ?></div>
          	<?php
          	}
          	if ($messages) { print $messages; }
						if ($content_right) {
							?>
							<div id="content-area-left">
								<?php print $content; ?>
							</div>
							<div id="content-area-right">
								<?php print $content_right; ?>
							</div>
							<br class="clear" />
							<?php
						} else {
							print $content;
							echo "HUHUHUHUHUHU";
						}
          ?>
        </div>
			<footer id="footer">
				<p class="link-a">Um sich für einen <span class="scheme-b">Alert</span>-Newsletter anzumelden, müssen Sie sich registrieren <a href="./">Jetzt registrieren!</a></p>
				<ul class="social-a">
					<li class="tw"><a rel="external" href="http://twitter.com/BankingCheck">Twitter</a></li>
					<li class="fb"><a rel="external" href="http://www.facebook.com/BankingCheck">Facebook</a></li>
					<li class="gl"><a rel="external" href="https://plus.google.com/115413476422580977786">Google+</a></li>
					<li class="rs"><a rel="external" href="https://plus.google.com/115413476422580977786">RSS</a></li>
				</ul>
				<?php if ($footer): print $footer; endif; ?>
				<nav>
					<div class="double">
						<h3>BankingCheck</h3>
						<ul>
							<li><a href="/presse">Presse</a></li>
							<li><a href="/karriere">Karriere</a></li>
							<li><a href="/sitemap">Sitemap</a></li>
							<li><a href="/news">News</a></li>
							<li><a href="./">Für Unternehmen</a></li>
						</ul>
						<ul>
							<li><a href="/kontakt">Kontakt</a></li>
							<li><a href="/impressum">Impressum</a></li>
							<li><a href="/agb-datenschutz">AGB</a></li>
							<li><a href="/datenschutzhinweise">Datenschutz</a></li>
							<li><a href="/apps">Apps</a></li>
						</ul>
					</div>
					<div>
						<h3>Awards</h3>
						<ul>
							<li><a href="/bankingcheck-award-2015">Award 2015</a></li>
							<li><a href="/bankingcheck-award-2014">Award 2014</a></li>
							<li><a href="/bankingcheck-award-2013">Award 2013</a></li>
							<li><a href="/bankingcheck-award-2012">Award 2012</a></li>
						</ul>
					</div>
					<div>
						<h3>Anbieter</h3>
						<ul>
							<li><a href="/direktbank">Direktbank</a></li>
							<li><a href="/filialbank">Filialbank</a></li>
							<li><a href="/regionalbank">Regionalbank</a></li>
							<li><a href="/versicherung">Versicherung</a></li>
							<li><a href="/finanzdienstleister">Finanzdienstleister</a></li>
							<li><a href="/paymentdienst">Paymentdienst</a></li>
							<li><a href="/fintech-startup">FinTech Startup</a></li>
						</ul>
					</div>
					<div>
						<h3>Ratgeber</h3>
						<ul>
							<li><a href="/ratgeber/allgemein">Allgemein</a></li>
							<li><a href="/ratgeber/tagesgeld">Tagesgeld</a></li>
							<li><a href="/ratgeber/festgeld">Festgeld</a></li>
							<li><a href="/ratgeber/girokonto">Girokonto</a></li>
							<li><a href="/ratgeber/kreditkarte">Kreditkarte</a></li>
							<li><a href="/ratgeber/depot">Depot</a></li>
							<li><a href="/ratgeber/ratenkredit">Ratenkredit</a></li>
							<li><a href="/ratgeber/autokredit">Autokredit</a></li>
							<li><a href="/ratgeber/lexikon">Lexikon</a></li>
							<li><a href="/forum">Forum</a></li>
						</ul>
					</div>
					<div>
						<h3>Produkte</h3>
						<ul>
							<li><a href="/produkte/tagesgeld">Tagesgeld</a></li>
							<li><a href="/produkte/festgeld">Festgeld</a></li>
							<li><a href="/produkte/girokonto">Girokonto</a></li>
							<li><a href="/produkte/kreditkarte">Kreditkarte</a></li>
							<li><a href="/produkte/depot">Depot</a></li>
							<li><a href="/produkte/ratenkredit">Ratenkredit</a></li>
							<li><a href="/produkte/autokredit">Autokredit</a></li>
							<li><a href="/produkte/baufinanzierung">Baufinanzierung</a></li>
							<li><a href="/produkte/mietkaution">Mietkaution</a></li>
						</ul>
					</div>
				</nav>
				<p>&copy; <span class="date">2016</span> <a href="./">BankingCheck.de</a>.<br> Echte Kundenbewertungen und Vergleiche für über 500 Banken, Versicherungen und Finanzdienstleister und mehr als 700 Produkte.</p>
			</footer>
		</div>
		<script>
			head.load('/<?php echo drupal_get_path('theme', 'bankingcheck2015'); ?>/js/scripts.js','/<?php echo drupal_get_path('theme', 'bankingcheck2015'); ?>/js/mobile.js','/<?php echo drupal_get_path('theme', 'bankingcheck2015'); ?>https://www.google.com/recaptcha/api.js')

		</script>

		<?php print $closure; ?>
	</body>
</html>