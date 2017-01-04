<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<?php
function novacompanyuser_testsealInner()
{
  $server = $_SERVER["HTTP_HOST"];
  $disableTextarea = "";
  if ($server == "www.testsystem.de.bankingcheck.nova-web.de") {
    drupal_set_message("Achtung: Die Ausgabe des Testsystems enthält Passwörter. Nicht weitergeben!", 'warning');
    $server = "bankingcheck:YhThI1Wg4rPynfishj@www.testsystem.de.bankingcheck.nova-web.de";
    drupal_add_js('$(document).ready(function() {
  $("name=[\"text\"]").bind("copy paste cut",function(e) {
    e.preventDefault(); //this line will help us to disable cut,copy,paste
  });
});', 'inline');
    $disableTextarea= " disabled='disabled' title='Das Kopieren wurde auf dem Testsystem aus Sicherheitsgründen deaktiviert' ";
  }
	$html = '<div class="node-type-bank">
	<h1>BankingCheck Testsiegel- & Widget Generator / SEO Optimierung</h1>
<p>Hier können Sie sich den Quellcode für Anbieter- und Produkttestsiegel generieren, um diese in Ihre Internetseiten und auf Landingpages zu integrieren.</p>

<p>Sie können das Widget nach Ihren Wünschen anpassen, bspw. mit Ausgabe der Bewertungen oder Bewertungsabgabe direkt in Ihrer Seite per PopUp.</p>

<h2>SEO Optimierung - Anzeige von Google Sternen</h2>
<p>Für die Ausgabe von Google Sternen (auch in anderen Suchmaschinen) in den generischen Suchergebnissen, nutzen Sie bitte das php include mit RichSnippet Funktion.</p>

<p>Bei Fragen wenden Sie sich bitte an <a href="mailto:widget@bankingcheck.de">widget@bankingcheck.de</a></p>

<form name="widget" action="'.$PHP_SELF.'" method="post">
<strong>Bank- oder Produkt: </strong>';
	# load bankId and its products
	$novaBCUser = NovaBCUser::buildUser($GLOBALS['user']->uid);
	$myProducts = $novaBCUser->getMyProducts();
	if (count($myProducts)>0) {
		$html .='<select name="productid">';
    $selectedId = intval($_POST['productid']);
    $html .= '<option value="'.($novaBCUser->getMyBankNode()->field_financeaddsid[0]["value"]+10000).'"';
    if ($selectedId == $novaBCUser->getMyBankNode()->field_financeaddsid[0]["value"]+10000) $html .= ' selected="selected"';
    $html .= '>'.$novaBCUser->getMyBankNode()->title.'</option>';
    foreach ($myProducts as $key => $product) {
			$html .= '<option value="'.$product->field_prodfinanceaddsid[0]["value"].'"';
      if ($selectedId == $product->field_prodfinanceaddsid[0]["value"]) $html .= ' selected="selected"';
      $html .='>'.$product->title.'</option>';
		}
		$html .= '</select>';
	} else {
		$html .='<input name="productid" type="hidden" value="'.($novaBCUser->getMyBankNode()->field_financeaddsid[0]["value"]+10000).'" />'.$novaBCUser->getMyBankNode()->title;
	}
	?>
	
<script>
	$(document).ready(function() {
			
		function init(){
			$('input[name="snippetdata"]').attr('checked', true);
			$('input[name="phpinclude"]').attr('checked', true);
			$('input[name="ankerimage"]').attr('checked', true);
			$('input[name="voteanker"]').attr('checked', true);
			$('input[name="votecount"]').attr('checked', true);
			$('input[name="ankercount"]').attr('checked', true);
		}    
		
			$('input[name="phpinclude"]').change(function(){
				if($(this).is(':checked')){
					$('input[name="javascript-use"]').attr('checked', false);
				} else {
					$('input[name="javascript-use"]').attr('checked', true).attr('disabled', false);
					
				}
			});
			
			$('input[name="javascript-use"]').change(function(){
				if($(this).is(':checked')){
					$('input[name="phpinclude"]').attr('checked', false);
				} else {
					$('input[name="phpinclude"]').attr('checked', true).attr('disabled', false);
					
				}
			});
					
			$('input[name="popup"]').change(function(){
				if($(this).is(':checked')){
					$('input[name="popupClean"]').attr('checked', false);
				} 
			});
			
			$('input[name="popupClean"]').change(function(){
				if($(this).is(':checked')){
					$('input[name="popup"]').attr('checked', false);
				} 
			});
		
		
		init();
		
	});


</script>
	
	
	
<?php

$html .= '
<br/><br/>

 <fieldset>
  <legend>Einbindung des Widget:</legend>
<input name="javascript-use" type="checkbox" value="JavaScript Integration"
'.$checkboxJS.' />JavaScript Integration<br/>
<input name="snippetdata" type="checkbox" value="Rich Snippet Daten ausgeben"
'.$checkboxRS.' />Rich Snippet Daten ausgeben<br/>
<input name="phpinclude" type="checkbox" value="php-include verwenden"
'.$checkboxPhpInc.' />php-include verwenden (bei Rich-Snippet Daten ist dies Pflicht !)<br/>
 </fieldset>

<fieldset>
  <legend>KlickEvents & Optionen:</legend>
  <input name="ankerimage" type="checkbox" value="Image klickbar"
'.$checkboxSealClick.' />Testsiegel klickbar<br/><hr/>
<input name="voteanker" type="checkbox" value="Link klickbar"
'.$checkboxClickableBewerten.' />Bewerten Link klickbar<br/><hr/>
<input name="votecount" type="checkbox" value="Anzahl ja/nein"
'.$checkboxShowRating.' />Anzahl Bewertungen anzeigen</br>
<input name="ankercount" type="checkbox" value="Anzahl klickbar"
'.$checkboxClickCount.' />Anzahl Bewertungen klickbar</br>

 </fieldset>

<fieldset>
  <legend>Popup:</legend>
<input name="popup" type="checkbox" value="Bewertungsmaske im neuen Fenster"
'.$checkboxPopupBrand.' />Bewertungsmaske als Popup mit BankingCheck Brand<br/>
<input name="popupClean" type="checkbox" value="Bewertungsmaske im neuen Fenster ohne Branding" '.$checkbox41.'
/>Bewertungsmaske als Popup ohne BankingCheck Brand<br/>
 </fieldset>
<input name="submit" type="submit" value="Widget generieren und Code erzeugen" /><br/><br/>';

if (isset($_POST['submit'])) {
  $count="&counter=true";

 
  $votecount="&votecount=true";
  //votecount abfragen
  if(isset($_POST['votecount']))
    $votecount="&votecount=true";
  else
  {
    // xxx ggf noch für neu
    $votecount="&votecount=false";
  }
	
  $js="&js=false";
  //snippet abfragen
  if (isset($_POST['javascript-use']))
    $js="&js=true";
  else
    $js="&js=false";
  
  $snippet="&snippet=true";
  //snippet abfragen
  if (isset($_POST['snippetdata']))
    $snippet="&snippet=true";
  else
    $snippet="&snippet=false";

  $phpinc = True;
  //snippet abfragen
  if (isset($_POST['phpinclude']) OR isset($_POST['snippetdata']))
    $phpinc = True;
  else
    $phpinc = False;

  $popup="&popup=true";
  //popupabfragen
  if (isset($_POST['popup']))
  {
    $count="&counter=true";
    $popup="&popup=true";
    $popupClean="&popupClean=false";
  }
  else
    $popup="&popup=false";

  $popupClean="&popupClean=true";
  //popupabfragen
  if (isset($_POST['popupClean']))
  {
    $count="&counter=true";
    $popup="&popup=false";
    $popupClean="&popupClean=true";
  }
  else
    $popupClean="&popupClean=false";

  //voteanker abfragen
  if (isset($_POST['voteanker']))
  {
    $voteanker="&voteanker=true";
 
  }
  else
    $voteanker="&voteanker=false";

  //ankercount abfragen
  if (isset($_POST['ankercount']))
  {
    $ankercount="&ankercount=true";
    $votecount="&votecount=true";
  }
  else
    $ankercount="&ankercount=false";

  if(isset($_POST['ankerimage']))
    $ankerimage="&ankerimage=true";
  else
    $ankerimage="&ankerimage=false";



  // Quellcode Berechnungen
  $id = $_POST['productid'];
  $url1 = "http://".$server."/BewertungsWidget/generator.php?productid=";
  $final1 = $url1 . $id . $count . $js . $snippet . $popup . $popupClean . $voteanker .$ankerimage . $votecount . $ankercount;


  // Vorschau Berechnungen
  $url2 = "http://".$server."/BewertungsWidget/bw_widget.php?productid=";
  $final2 = $url2 . $id . $count . $snippet . $popup . $popupClean . $voteanker . $ankerimage . $votecount . $ankercount;

  // Quellcode für php-include
  // ohne ssl
  $url31 = "http://".$server."/BewertungsWidget/bw_widget.php?ssl=no&productid=";
  $final31 = $url31 . $id . $count . $snippet . $popup . $popupClean . $voteanker . $ankerimage . $votecount . $ankercount;

  // mit ssl
  $url32 = "https://".$server."/BewertungsWidget/bw_widget.php?ssl=yes&productid=";
  $final32 = $url32 . $id . $count . $snippet . $popup . $popupClean . $voteanker . $ankerimage . $votecount . $ankercount;

  // html Inhalte holen
  if ($phpinc)
  {
  $code31 = "include '" . $final31 . "'; ";
  $code32 = "include '" . $final32 . "'; ";
  $code = "<?php if (!\$_SERVER['HTTPS']) {" . $code31 ."} else {" . $code32 ."} ?>";

  }
  else
  {
    $code = file_get_contents($final1);
  }
  $preview = file_get_contents($final2);

  if (empty($preview))
  {
  $preview = "Das Widget wurde noch nicht freigegeben. Bitte wenden Sie sich zwecks Angebot an <a href='mailto:widget@bankingcheck.de?subject=BankingCheck%20Widget%20Freischaltung&Body=Sehr%20geehrtes%20BankingCheck%20Team,%0D%0A%0D%0Abitte%20schalten%20Sie%20uns%20für%20folgende%20Produkte%20frei:%0D%0A%0D%0A...%20bitte%20Produkte%20oder%20Anbieter%20eintragen%20...'>widget@bankingcheck.de</a>."; #.$final2;
  $code = "Das Widget wurde noch nicht freigegeben. Bitte wenden Sie sich zwecks Angebot an widget@bankingcheck.de."; #.$final1;
  }
  else if ($phpinc)
  {
    //nix abschneiden...
  }
  else
  {
  // Nachbearbeitung des Quellcodes
  $startpos = strpos($code ,'<pre>');
  $startpos+=7;
  $endpos = strpos($code ,'</pre>');
  $endpos-=1;
  $length = $endpos-$startpos;
  $substr = substr($code ,$startpos, $length);
  $code = $substr;
  }

} else {
  // beim ersten Aufruf
  $code = "Ausgabe des Quellcode";
  $preview = "";
  $_POST['snippetdata'] = "checked";
  $_POST['phpinclude'] = "checked";
  $_POST['ankerimage'] = "checked";
  $_POST['voteanker'] = "checked";
  $_POST['votecount'] = "checked";
  $_POST['ankercount'] = "checked";
}

$html .='<p><strong>Quellcode für Ihre Seite:</strong></p>
<textarea style="width: 98%" rows="10" name="text" '.$disableTextarea.'>
'.$code.'
</textarea>

<p><strong>Vorschau</strong></p>
'.$preview.'

</form>

	</div>';

	return $html;
}
?>