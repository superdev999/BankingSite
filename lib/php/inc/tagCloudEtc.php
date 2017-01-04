<?php
include($_SERVER["DOCUMENT_ROOT"]."/lib/php/class.FileCache.php");
$options = array(
	"maxAgeSec"=>250
);
if(FileCache::cacheApplies($options)) {
	FileCache::printCache($options);
} else {
	FileCache::startRecord($options);
?>
<div id="bestRatedMin5">
 <h2 class="blueBigSignRight">Am besten bewertet<sup>*</sup></h2>
 <div class="rightInner">
   <div id="bestRatedMin5Table">
<?php
include($_SERVER["DOCUMENT_ROOT"]."/lib/php/inc/bestRatedMin100.php");
?>
   </div>
  <div class="rightColButton">
   <a href="/produktbewertungen" title="Alle Bewertungen" class="fa_button">Alle Bewertungen</a>
  </div>
  <div id="bestRatedMin5Description">
    <small><sup>*</sup> Kundenbewertungen (Durchschnitt aus mindestens 100 Bewertungen)</small>
  </div>
 </div>
</div>
<div class="accordion">
 <div class="accordionItem">
  <h3 class="accordionTitle">Häufig erwähnt</h3>
  <div class="accordionBody" style="display:none;">
<?php
$vocs= array(2); //  ids of vocabs for which you want to build a tag cloud
$tagcloud = theme('tagadelic_weighted',tagadelic_sort_tags(tagadelic_get_weighted_tags($vocs, 6, 20)));
print $tagcloud;
?>
  </div>
 </div>
 <div class="accordionItem newProducts">
  <h3 class="accordionTitle">Neue Produkte</h3>
  <div class="accordionBody" style="display:none;">
<?php
echo views_embed_view("new_products");
?>
  </div>
 </div>
 <div class="accordionItem bestRated">
  <h3 class="accordionTitle">Am besten bewertet</h3>
  <div class="accordionBody" style="display:none;">
<?php
include($_SERVER["DOCUMENT_ROOT"]."/lib/php/inc/bestRated.php");
?>
  </div>
 </div>
 <div class="accordionItem mostRated">
  <h3 class="accordionTitle">Am häufigsten bewertet</h3>
  <div class="accordionBody" style="display:none;">
<?php
include($_SERVER["DOCUMENT_ROOT"]."/lib/php/inc/mostRated.php");
?>
  </div>
 </div>
 <div class="accordionItem mostRated">
  <h3 class="accordionTitle">Am häufigsten gesucht</h3>
  <div class="accordionBody" style="display:none;">
<?php
include($_SERVER["DOCUMENT_ROOT"]."/lib/php/inc/mostSearched.php");
?>
  </div>
 </div>
</div>

<div id="latestRated">
 <h2 class="blueBigSignRight">Zuletzt bewertet</h2>
 <div id="latestRatedList">
<?php
echo views_embed_view("latestRated");
?>
 <div class="rightColButton">
  <a href="/produktbewertungen" title="Alle Bewertungen" class="fa_button">Alle Bewertungen</a>
 </div>
 </div>
</div>
<?php
	FileCache::endRecord($options);
}
?>
