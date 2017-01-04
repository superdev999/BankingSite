<?php
global $user;
if (in_array('authenticated user', array_values($user->roles))) {
	echo "<div id='newLoginUsername' class='HelloUser Userhandling'><span>";
	$numObject = db_fetch_object(db_query("SELECT COUNT(*) as count FROM `node` WHERE `type` LIKE 'bewertung_%' and `uid`=%d", $user->uid));
	$bewertungen = " (".$numObject->count." Produkt"; 
	$bewertungen .= $numObject->count == 1 ? "" : "e";
	$bewertungen .= " bewertet)";
	$authType = "Bankingcheck.de";
	echo l($user->name.$bewertungen,'user/'.$user->uid);		
	echo "</span></div>";
} else if (in_array('anonymous user', array_values($user->roles))) {
?>
<div id="newLoginUsername" class="HelloUser Userhandling"><span><a title="Login für bestehende Kunden, Leser und Unternehmen" href="javascript:showLogin()" id="newLoginShowLoginWindow">Login</a> &nbsp; <a title="Registrierung für private Nutzer, Kunden und Leser" href="/user/register">Registrieren</a> &nbsp; <a style="margin-left:50px;" title="Registrierung für Unternehmen (Banken, Versicherungen und Finanzdienstleister" href="/user/registercompany">Für Unternehmen</a></span></div>
<?php	
} else {
	#print_r($user->roles);
}

#if (in_array('authenticated user', array_values($user->roles)))
if (user_access("edit any bewertung_autokredit content")) {
	?>
	<script type="text/javascript">
	function hideAnonymousFields(mailadress, mailapproved, token) {
	  var mail = '<?php echo $user->mail; ?>';
	  if ($(mailadress).length!=0 && $('#edit-name').val() != "") {
	    $(mailadress).val(mail);
      $(mailapproved).val(0);
      $(token).val('5f4dcc3b5aa765d61d8327deb882cf99');      
      $('.group-mailadress').hide();   
    }
  }
	
	$(function() {
	$('#edit-field-carloans-mailapproved-0-value-wrapper').css('display', 'block');
	$('#edit-field-mortgages-mailapproved-0-value-wrapper').css('display', 'block');
	$('#edit-field-brokerage-mailapproved-0-value-wrapper').css('display', 'block');
	$('#edit-field-fixeddeposits-mailapproved-0-value-wrapper').css('display', 'block');
	$('#edit-field-currentaccount-mailapprove-0-value-wrapper').css('display', 'block');
	$('#edit-field-creditcard-mailapproved-0-value-wrapper').css('display', 'block');
	$('#edit-field-rentalbonds-mailapproved-0-value-wrapper').css('display', 'block');
	$('#edit-field-loans-mailapproved-0-value-wrapper').css('display', 'block');
	$('#edit-field-dailyallowances-mailapprov-0-value-wrapper').css('display', 'block');
	$('#edit-field-bank-mailapproved-0-value-wrapper').css('display', 'block');
	hideAnonymousFields('#edit-field-carloans-mailadress-0-value', '#edit-field-carloans-mailapproved-0-value', '#edit-field-carloans-token-0-value');
	hideAnonymousFields('#edit-field-mortgages-mailadress-0-value', '#edit-field-mortgages-mailapproved-0-value', '#edit-field-mortgages-token-0-value');
	hideAnonymousFields('#edit-field-brokerage-mailadress-0-value', '#edit-field-brokerage-mailapproved-0-value', '#edit-field-brokerage-token-0-value');
	hideAnonymousFields('#edit-field-fixeddeposits-mailadress-0-value', '#edit-field-fixeddeposits-mailapproved-0-value', '#edit-field-fixeddeposits-token-0-value');
	hideAnonymousFields('#edit-field-currentaccount-mailadress-0-value', '#edit-field-currentaccount-mailapprove-0-value', '#edit-field-currentaccount-token-0-value');
	hideAnonymousFields('#edit-field-creditcard-mailadress-0-value', '#edit-field-creditcard-mailapproved-0-value', '#edit-field-creditcard-token-0-value');
	hideAnonymousFields('#edit-field-rentalbonds-mailadress-0-value', '#edit-field-rentalbonds-mailapproved-0-value', '#edit-field-rentalbonds-token-0-value');
	hideAnonymousFields('#edit-field-loans-mailadress-0-value', '#edit-field-loans-mailapproved-0-value', '#edit-field-loans-token-0-value');
	hideAnonymousFields('#edit-field-dailyallowances-mailadress-0-value', '#edit-field-dailyallowances-mailapprov-0-value', '#edit-field-dailyallowances-token-0-value');
	hideAnonymousFields('#edit-field-bank-mailadress-0-value', '#edit-field-bank-mailapproved-0-value', '#edit-field-bank-token-0-value');
	});
	</script>
	<?php
}
#print_r($user->mail);
?>
<div id="newLoginForm" style="display:none">
  <div class="simplemodal-close" id="simplemodalCloseDiv">Schließen <img src="/themes/pixture_reloaded/images/modal_schliessen.png" style="position: relative;top: 2px;" /></div>
	<h1 style="margin-top:8px;">Login</h1>
  <div id="modalLoginButtonBC" class="modalLoginButton">mit bestehendem Bankingcheck.de-Account</div>
  <img src="/themes/pixture_reloaded/images/modal_logo.png" id="modalLoginLogo" style="position: relative;top: -18px;left: 5px;" />  
	<div id="newLoginFormStandardLogin" style="clear:both">
	</div> 
	<div id="modalLoginButtonFB" class="modalLoginButton">mit Facebook-Account</div>
	<?php if (function_exists('fboauth_action_display')) print fboauth_action_display('connect'); ?>
</div>
<?php /*if ($user->uid) { ?>
<div class="HelloUser Userhandling"><div style="float:left">Hallo: <?php print l($user->name,'user/'.$user->uid); ?></div>
<ul>
<li>
<?php print l("Logout","logout",array("class"=>"blue")); ?></li>
<li><?php print l("Meine Profildaten",'user/'.$user->uid); ?></li>
</ul>
</div>
<?php }  */ ?>
