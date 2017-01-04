<?php
function addTextAsTitle($link) {
  $text = substr($link, 0, strrpos($link, "<")-strlen($link));
  $text = substr($text, strrpos($text, ">")+1);
  #echo strrpos($link, "<");
  $href = strstr($link, "\"");
  $href = substr($href, 1, strrpos($href, "\"")-strlen($href));
  $newLink = "<a href='".$href."' title='".$text."'>".$text."</a>";
  return $newLink;
}

?> 