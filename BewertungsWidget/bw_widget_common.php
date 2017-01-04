<?php
// ****************************************** //
// ****** Fertige Siegel bauen ************** //
// ****** Werte aus bw_widget.php oder ****** //
// ****** Werte aus bw_widget_JSON.php ****** //

// if not in array, check by new profile field value
if (is_null($host)) {
  $productid = intval($_GET["productid"]);
  $hasBought = false;
  $nid = NULL;
  $productNode = NULL;
  // check if number is Bank or Einzelprodukt
  if ($productid>10000) { 
    // Bank
    $financeAdsId = $productid-10000;
    #echo $financeAdsId;
    $query = db_query("SELECT nid FROM {content_type_bank} WHERE field_financeaddsid_value = %d",$financeAdsId);
    $result = db_fetch_array($query);

    $nid = $result["nid"];
    $productNode = node_load(array("nid" => $nid));
  } else {
    // Einzelprodukt
    $financeAdsId = $productid;
    #echo $financeAdsId;
    $query = db_query("SELECT nid FROM {content_type_productitem} WHERE field_prodfinanceaddsid_value = %d",$financeAdsId);
    $result = db_fetch_array($query);

    $productNid = $result["nid"];
    $productNode = node_load(array("nid" => $productNid));
    $nid = $productNode->field_proditemmybank[0]["nid"];
  }
  // load corresponding user(s)
  if (!is_null($nid)) {
    // get uids
    $query2 = db_query("SELECT uid FROM `profile_values` JOIN `profile_fields` USING(fid) WHERE name='profile_company_id' AND value=%d",intval($nid));
    while ($result2 = db_fetch_array($query2)) {
      try {
        $novaBCUser = NovaBCUser::buildUser($result2["uid"]);
        // check if user has bought the feature for this id
        #var_dump($novaBCUser->getApprovedFinanceIdsForWidget());
        #var_dump($productid);
        if (in_array($productid, $novaBCUser->getApprovedFinanceIdsForWidget())) {
          #echo "Freigeschaltet nach neuer Logik";
          $hasBought = true;
          break;
        }
      } catch (Exception $e) {
       continue;
      }
    }
    if ($hasBought) {
      // load URL
      $host = "http://".$_SERVER["HTTP_HOST"]."/".$productNode->path;
    }
  }
}

if (empty($host)) exit;

// Load node
$parsedUrl = parse_url($host);
$urlPath = substr($parsedUrl["path"], 1);
$nodePath = drupal_lookup_path('source', $urlPath);
$node = menu_get_object("node", 1, $nodePath);
$bank = false;

$typ = $node->type;

if ($typ != "productitem") {
  $bank = true;
}

// get product name or bankname
// if bank, else

$productName = strtolower(node_load(array("nid" => $node->field_prodmyproduct[0]["nid"]))->title);


if ($bank == false)
{
  $bcVotings = new BCVotings($node->nid, $productName);
}
else
{
  $bcVotings = new BCVotings($node->nid, "bank");
}

// load values
$img = $protocol . "://www.bankingcheck.de".$bcVotings->getSiegelUrl();
$imgbgsocial = $protocol . "://www.bankingcheck.de/themes/pixture_reloaded/images/bg-social-bewerten.png";
$anzahl = $bcVotings->calcVotingCount();


if ($bank == false)
{
  $overview = "http://www.bankingcheck.de/produkte/".$productName."/bewertungen/".$node->nid."/".BCImport::propperFileString($node->title);
}
else
{
  $overview = "http://www.bankingcheck.de/banken".$productName."/bewertungen/".$node->nid."/".BCImport::propperFileString($node->title);
}
$title = $node->title;

$title1 = $title." Bewertungen";
$title2 = $title." Bewertung abgeben";
$title3 = $title." Bewertungen einsehen";

$snipped_html = "";
if ($isWithSnippet)
{
  if ($bank == false)
  {
    $snipped_html = ' itemprop="reviewCount"';
  }
  else
  {
    $snipped_html = ' itemprop="ratingCount"';
  }
}


  $jsPopup='<script type="text/javascript">
			function popup(url)
			{
				fenster = window.open(url, "Popupfenster", "width=500,height=768,resizable=yes, scrollbars=yes");
				fenster.focus();
				return false;
			}
			</script>';


if ($popup=="true" || $popupClean=="true")
{
  $popup_js = "onclick='return popup(this.href)'";
}
else
{
  $popup_js = NULL;
}

// app=true Abfrage Bewertungsmaske
if ($popupClean=="true")
{
	if ($bank == false)
	{
		$rating = "http://www.bankingcheck.de/node/add/bewertung-".$productName."?itemId=".$node->nid."&app=true";
	}
	else
	{
		$rating = "http://www.bankingcheck.de/node/add/bewertung-"."bank"."?itemId=".$node->nid."&app=true";
	}
}
else
{
	if ($bank == false)
	{
		$rating = "http://www.bankingcheck.de/node/add/bewertung-".$productName."?itemId=".$node->nid;
	}
	else
	{
		$rating = "http://www.bankingcheck.de/node/add/bewertung-"."bank"."?itemId=".$node->nid;
	}
}

// build html source code
if ($voteanker=="true" && $ankercount=="true")
{
  $ankerRating = ' href="'.$rating.'" target="_blank" ' .$popup_js;
  if ($ankerimage=="true")
  {
     $ankerLogo = ' href="'.$host.'" target="_blank"';
  }
  $ankerReview = ' target="_blank" href="'.$overview.'"';
  $bw = "Bewerten";
}
else if ($voteanker=="true" && $ankercount=="false")
{
  $ankerRating = ' href="'.$rating.'" target="_blank"' .$popup_js;
  if ($ankerimage=="true")
  {
     $ankerLogo = ' href="'.$host.'" target="_blank"';
  }
  $ankerReview = NULL;
  $bw = "Bewerten";
}
else if ($voteanker=="false" && $ankercount=="true")
{
  $ankerRating = NULL;
  if ($ankerimage=="true")
  {
     //$ankerLogo = ' href="'.$host.'" target="_blank"';
  }
  $ankerReview = ' target="_blank" href="'.$overview.'"';
  $bw = "Bewertungen";
}
else if ($voteanker=="false" && $ankercount=="false")
{
  $ankerRating = NULL;
  if ($ankerimage=="true")
  {
     //$ankerLogo = ' href="'.$host.'" target="_blank"';
  }
  $ankerReview = NULL;
  $bw = "Bewertungen";
}
else
  {
  $ankerRating = NULL;
  $ankerReview = NULL;
  $ankerLogo = 	NULL;
  $ankercount = NULL;
  //$bw = "Bewertungen";
  $bw = "Bewerten";

}

if ($counter=="false")
{
	$counterhtml = "";
}
else
{
  // Anzahl einbeziehen
  // 10-99
	if (((int)$anzahl > 9) AND ((int)$anzahl < 100))
	{ //<img style="border:none;" height="150" width="150" src="'.$img.'">
		//$imgbgsocial = $protocol . "://www.bankingcheck.de/themes/pixture_reloaded/images/bg-social-bewerten.png";
		//$counterhtml = '<div style="height:18px; background:url('.$protocol.'://www.bankingcheck.de/themes/pixture_reloaded/images/bg-social-bewerten.png); background-repeat:no-repeat; padding-left:20px;margin-left:20px;"><a style="text-decoration: none; color: #2677B8;"' .$ankerRating. ' title="'.$title2.'" alt="'.$title2.'">'.$bw.'</a><a title="'.$title3.'" alt="'.$title3.'" style="text-decoration: none; color: #000;"' .$ankerReview. ' ><span style="background: url('.$protocol.'://www.bankingcheck.de/themes/pixture_reloaded/images/bg-commentCountBubble.gif); background-repeat: no-repeat; padding-left: 8px; padding-bottom: 7px; padding-right: 12px; padding-top: 2px; font-size:12px;"'.$snipped_html.'>'.$anzahl.'</span></a></div>';
		$counterhtml = '<div>
		<img style="vertical-align: middle; height: 16px; width: 16px;" src="'.$imgbgsocial.'">
		<a style="text-decoration: none; color: #2677B8; padding-right: 3px; padding-left: 3px; vertical-align: bottom;"' .$ankerRating. ' title="'.$title2.'" alt="'.$title2.'">'.$bw.'</a>
		<a title="'.$title3.'" alt="'.$title3.'" style="text-decoration: none; color: #000;"' .$ankerReview. '>
		<span style="background: url('.$protocol.'://www.bankingcheck.de/themes/pixture_reloaded/images/bg-commentCountBubble.gif); background-repeat: no-repeat; padding-left: 8px; padding-bottom: 3px; padding-right: 6px; padding-top: 3px; vertical-align: bottom; font-size:12px;"'.$snipped_html.'>'.$anzahl.'</span>
		</a>
		</div>';
	}
	// 100-999
	else if (((int)$anzahl > 100) AND ((int)$anzahl < 1000))
	{
		$counterhtml = '<div>
		<img height="16px" style="vertical-align: middle; height: 16px; width: 16px;" src="'.$imgbgsocial.'">
		<a style="text-decoration: none; color: #2677B8; padding-right: 3px; padding-left: 3px; vertical-align: bottom;"' .$ankerRating. ' title="'.$title2.'" alt="'.$title2.'">'.$bw.'</a>
		<a title="'.$title3.'" alt="'.$title3.'" style="text-decoration: none; color: #000;"' .$ankerReview. ' ><span style="background: url('.$protocol.'://www.bankingcheck.de/themes/pixture_reloaded/images/bg-commentCountBubble-middle.gif); background-repeat: no-repeat; padding-left: 8px; padding-bottom: 3px; padding-right: 6px; padding-top: 3px; vertical-align: bottom; font-size:12px;"'.$snipped_html.'>'.$anzahl.'</span></a></div>';
	}
	// 1000-9999
	else if (((int)$anzahl > 1000) AND ((int)$anzahl < 10000))
	{	
		$length = strlen((string)$anzahl);
		$stringNumb = (string)$anzahl;
		if($length > 3) 
		{
			$newNumb = $stringNumb[0];
		}
		$counterhtml = '<div>
		<img height="16px" style="vertical-align: middle; height: 16px; width: 16px;" src="'.$imgbgsocial.'">
		<a style="text-decoration: none; color: #2677B8; padding-right: 3px; padding-left: 3px; vertical-align: bottom;"' .$ankerRating. ' title="'.$title2.'" alt="'.$title2.'">'.$bw.'</a>
		<a title="'.$title3.'" alt="'.$title3.'" style="text-decoration: none; color: #000;"' .$ankerReview. ' ><span style="background: url('.$protocol.'://www.bankingcheck.de/themes/pixture_reloaded/images/bg-commentCountBubble-big.gif); background-repeat: no-repeat; padding-left: 8px; padding-bottom: 3px; padding-right: 6px; padding-top: 3px; vertical-align: bottom; font-size:12px;"'.$snipped_html.'>'.$newNumb."k".'</span></a></div>';
	}
	// 0-9
	else if((int)$anzahl > 9999) 
	{	
		$length = strlen((string)$anzahl);
		$stringNumb = (string)$anzahl;
		if($length > 4) 
		{
			$newNumb = $stringNumb[0] . $stringNumb[1];
		}
		$counterhtml = '<div>
		<img height="16px" style="vertical-align: middle; height: 16px; width: 16px;" src="'.$imgbgsocial.'">
		<a style="text-decoration: none; color: #2677B8; padding-right: 3px; padding-left: 3px; vertical-align: bottom;"' .$ankerRating. ' title="'.$title2.'" alt="'.$title2.'">'.$bw.'</a>
		<a title="'.$title3.'" alt="'.$title3.'" style="text-decoration: none; color: #000;"' .$ankerReview. ' ><span style="background: url('.$protocol.'://www.bankingcheck.de/themes/pixture_reloaded/images/bg-commentCountBubble.gif); background-repeat: no-repeat; padding-left: 11px; padding-bottom: 7px; padding-right: 6px; padding-top: 2px; font-size:12px;"'.$snipped_html.'>'.$newNumb ."k".'</span></a></div>';
	}
	
	else 
	{
		$counterhtml = '<div>
		<img height="16px" style="vertical-align: middle; height: 16px; width: 16px;" src="'.$imgbgsocial.'">
		<a style="text-decoration: none; color: #2677B8; padding-right: 3px; padding-left: 3px; vertical-align: bottom;"' .$ankerRating. ' title="'.$title2.'" alt="'.$title2.'">'.$bw.'</a>
		<a title="'.$title3.'" alt="'.$title3.'" style="text-decoration: none; color: #000;"' .$ankerReview. ' ><span style="background: url('.$protocol.'://www.bankingcheck.de/themes/pixture_reloaded/images/bg-commentCountBubble.gif); background-repeat: no-repeat; padding-left: 11px; padding-bottom: 7px; padding-right: 6px; padding-top: 2px; font-size:12px;"'.$snipped_html.'>'.$anzahl.'</span></a></div>';
	}
    $voteStarA 	= '<div><img height="16px" style="vertical-align: middle; height: 16px; width: 16px;" src="'.$imgbgsocial.'">';
	$voteStarE	= '</div>';

	if(($votebutton=="true") && ($votecount=="true"))
	{
		if (((int)$anzahl > 9) AND ((int)$anzahl < 100))
		{
			$commentBubble = "bg-commentCountBubble.gif";
		}
		else if (((int)$anzahl > 100) AND ((int)$anzahl < 1000))
	    {
			$commentBubble = "bg-commentCountBubble-middle.gif";
		}
		else if (((int)$anzahl > 1000) AND ((int)$anzahl < 10000))
	    {
			$commentBubble = "bg-commentCountBubble-big.gif";
		}
		else  // anzahl 0-9
	    {
			$commentBubble = "bg-commentCountBubble.gif";
		}
		$voteButton = '<a style="text-decoration: none; color: #2677B8; padding-right: 3px; padding-left: 3px; vertical-align: bottom;"' .$ankerRating. ' title="'.$title2.'" alt="'.$title2.'">'.$bw.'</a>';
		$voteCount = '<a title="'.$title3.'" alt="'.$title3.'" style="text-decoration: none; color: #000;"' .$ankerReview. ' ><span style="background: url('.$protocol.'://www.bankingcheck.de/themes/pixture_reloaded/images/'.$commentBubble.'); background-repeat: no-repeat; padding-left: 8px; padding-bottom: 3px; padding-right: 6px; padding-top: 3px; vertical-align: bottom; font-size:12px;"'.$snipped_html.'>'.$anzahl.'</span></a>';
		$counterhtml = $voteStarA.$voteButton.$voteCount.$voteStarE;
	}
	else if(($votebutton=="true") && ($votecount=="false"))
	{
		$voteButton = '<a style="text-decoration: none; color: #2677B8; padding-right: 3px; padding-left: 3px; vertical-align: bottom;"' .$ankerRating. ' title="'.$title2.'" alt="'.$title2.'">'.$bw.'</a>';
		$counterhtml = $voteStarA.$voteButton.$voteStarE;
	}
	else if(($votebutton=="false") && ($votecount=="true"))
	{
		// $bw = '<a style="text-decoration: none; color: #2677B8;" title="'.$title2.'" alt="'.$title2.'">Bewertungen</a>';
		$bw = '<a style="text-decoration: none; color: #2677B8; padding-right: 3px; padding-left: 3px; vertical-align: bottom;" title="'.$title2.'" alt="'.$title2.'">Bewerten</a>';
		if (((int)$anzahl > 9) AND ((int)$anzahl < 100))
		{
			$commentBubble = "bg-commentCountBubble.gif";
		}
		else if (((int)$anzahl > 100) AND ((int)$anzahl < 1000))
	    {
			$commentBubble = "bg-commentCountBubble-middle.gif";
		}
		else if (((int)$anzahl > 1000) AND ((int)$anzahl < 10000))
	    {
			$commentBubble = "bg-commentCountBubble-big.gif";
		}
		else  // 0-9
	    {
			$commentBubble = "bg-commentCountBubble.gif";
		}
		$padding = "11px";
		$voteCount = $bw.'<a title="'.$title3.'" alt="'.$title3.'" style="text-decoration: none; color: #000;"' .$ankerReview. ' ><span style="background: url('.$protocol.'://www.bankingcheck.de/themes/pixture_reloaded/images/'.$commentBubble.'); background-repeat: no-repeat; padding-left:'.$padding.' ; padding-bottom: 7px; padding-right: 6px; padding-top: 2px; font-size:12px;"'.$snipped_html.'>'.$anzahl.'</span></a>';
		$counterhtml = $voteStarA.$voteCount.$voteStarE;
	}
	else if(($votebutton=="false") && ($votecount=="false"))
	{
		$counterhtml = "";
	}
}

if ($isWithSnippet=="true")
{
	// test counter --> daten nachträglich einfügen
	if ($counterhtml == "")
	{
		//$counterhtml = "blabla";
		$counterhtml = '<meta'.$snipped_html.' content='.$anzahl.'>';
	}
	if ($bank == false)
	{
		$html.=$jsPopup.'<div style="width:160px; font-family: arial,verdana,sans-serif; font-size: 13px; text-align: center;" itemscope itemtype="http://schema.org/Product"><meta itemprop="name" content="'.$title.'" /><div style="padding-bottom: 10px;"><a title="'.$title1.'" alt="'.$title1.'"' .$ankerLogo.'><img style="border:none;" height="150" width="150" src="'.$img.'"></a><br></div><div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"> '.$counterhtml.'<meta itemprop="ratingValue" content="'.$bcVotings->getAverage().'" /><meta itemprop="bestRating" content="'.BCVotings::votingBase.'" /></div></div>';
	}
	else
	{
		$html.=$jsPopup.'<div style="width:160px; font-family: arial,verdana,sans-serif; font-size: 13px; text-align: center;" itemscope itemtype="http://schema.org/BankOrCreditUnion"><meta itemprop="name" content="'.$title.'" /><div style="padding-bottom: 10px;"><a title="'.$title1.'" alt="'.$title1.'"'.$ankerLogo.'><img style="border:none;" height="150" width="150" src="'.$img.'"></a><br></div><div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"> '.$counterhtml.'<meta itemprop="ratingValue" content="'.$bcVotings->getAverage().'" /><meta itemprop="bestRating" content="'.BCVotings::votingBase.'" /></div></div>';
	}
}
else
{
	$html.=$jsPopup.'<div style="width:160px; font-family: arial,verdana,sans-serif; font-size: 13px; text-align: center;"><div style="padding-bottom: 10px;"><a title="'.$title1.'" alt="'.$title1.'"'.$ankerLogo.'><img style="border:none;" height="150" width="150" src="'.$img.'"></a><br></div>'.$counterhtml."</div>";
}

?>
