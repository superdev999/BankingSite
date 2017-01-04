<?php
if (isset($_GET["node"])) $target = "/shortnews/".intval($_GET["node"])."/rss.xml";
else                      $target = "/shortnews.xml";
header("HTTP/1.1 301 Moved Permanently");
header("Location: ".$target);
exit();
?> 