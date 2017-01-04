<?php
class BCFARechner {
	public static $wfid = 13289;
	// eigene ID für comonea
	public static $wfidcm = 17756;
	public static $linkBase = "/zum_Anbieter.php?";
	public static $baseDir  = "/sites/all/libraries/rechner-seiten";
	public static $allSupportedRechner = array(
		"Autokreditrechner"=>"autokreditrechner",
		"Girokontorechner"=>"girokontorechner",
		"Studentenkontorechner"=>"studentenkontorechner",
		"depotrechner"=>"depotrechner",
		"kreditkartenrechner"=>"kreditkartenrechner",
		"studentenkreditkarterechner"=>"studentenkreditkarterechner",
		"festgeldrechner"=>"festgeldrechner",
		"ratenkreditrechner"=>"ratenkreditrechner",
		"tagesgeldrechner"=>"tagesgeldrechner",
		"baufinanzierungrechner"=>"baufinanzierungrechner",
		"mietkautionrechner"=>"mietkautionrechner"
	);
	public static $homePageRechner = array(
		"Tagesgeld"=>"tagesgeldrechner",
		"Festgeld"=>"festgeldrechner",
		"Girokonto"=>"girokontorechner",
		"Kreditkarte"=>"kreditkartenrechner",
		"Depot"=>"depotrechner",
		"Kredit"=>"ratenkreditrechner",
		"Baufinanzierung"=>"baufinanzierungrechner",
		"Mietkaution"=>"mietkautionrechner"
//		,
//		"Autokreditrechner"=>"autokreditrechner",
//		"kreditkarterechner"=>"kreditkarterechner",
//		"Studentenkontorechner"=>"studentenkontorechner",
//		"studentenkreditkarterechner"=>"studentenkreditkarterechner",
	);
	public static function showRechner($name)	{
		$fa_wfid 	= self::$wfid;
		$fa_wfidcm = self::$wfidcm;
		$fa_rechner = $name;
		if ($_GET["ref"] == "comonea")
			$_POST["wf"] = $fa_wfidcm;
		else
			$_POST["wf"] = $fa_wfid;
		$_POST["linkbase"]="http://".$_SERVER['SERVER_NAME'].self::$linkBase;
    if ($fa_rechner == "kreditkartenrechner")
    {
      if (!isset($_POST["zahlungsart"])) 	$_POST["zahlungsart"] = "5";
    }
    if ($fa_rechner == "mietkautionrechner")
    {
      if (!isset($_POST["kreditbetrag"])) 	$_POST["kreditbetrag"] = "1500";
      if (!isset($_POST["laufzeit"])) 	    $_POST["laufzeit"] = "5";
    }
		$fa_url = $_SERVER["REQUEST_URI"];
		$bc = new BCImport();
		$freitext_array = array(
			$name => $bc->getFARechnerArray($name)
		);
		$freitextDefault = array(
			1=>"#",
			2=>"-"
		);
		$oldDir = getcwd();
		chdir($_SERVER['DOCUMENT_ROOT'].self::$baseDir);
		include("fa_rechner_tpl.php");
		chdir($oldDir);
	}
	public static function all()	{
		$r = '<div id="rechnertabs" class="tabs"><ul>';
		$class = 'class="active"';
		foreach(self::$homePageRechner as $title => $value)		{
			$r .= '<li><a '.$class.' href="#'.$value.'">'.$title."</a></li>";
			if($class != "")			{
				$class = "";
			}
		}
		$r .= "</ul>";
		foreach(self::$homePageRechner as $title => $value)		{
			$r .= '<div id="'.$value.'" class="rechner">'.self::grepForm($value)."</div>";
		}
		$r .= '</div>';
		echo $r;
	}

	private static function grepForm($rechner)	{
		$input = file_get_contents($_SERVER['DOCUMENT_ROOT'].self::$baseDir."/templates/$rechner.tpl");
		preg_match("/<%%beginform%%>(.*)<%%endform%%>/mis", $input, $m);
		$form = $m[1];
		foreach (self::grepFormValues(self::loadRechnerTPL($rechner)) as $name => $value)		{			$form = str_replace(
				"{feld:".$name."}",
				file_get_contents("http://tools.financeads.net/template_form.php?rechner=".$rechner."&feld=".$name."&wert=".$value."&q=".$post_data),
				$form
			);
		}
		return $form;
	}
	private static function grepFormValues($input) {
		$r = array();
		preg_match("/{(.*?)}/s", $input, $m);
		foreach(explode(",", $m[1]) as $line)		{
			$p = explode(":", $line);
			$r[trim($p[0])] = trim($p[1]);
		}
		return $r;
	}
	private static function loadRechnerTPL($name)	{
		return file_get_contents("http://tools.financeads.net/".$name."_tpl.php");
	}
}
?>