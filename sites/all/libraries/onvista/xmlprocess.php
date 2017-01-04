<?php
class nova_xmlprocess {
  private $link;
  function __construct() {
   $db_url = 'mysqli://bankidbk:znNaf4ni@localhost/usrdb_bankidbk_bchde';
$url = parse_url($db_url);
$url['user'] = urldecode($url['user']);
// Test if database url has a password.
$url['pass'] = isset($url['pass']) ? urldecode($url['pass']) : '';
$url['host'] = urldecode($url['host']);
$url['path'] = substr(urldecode($url['path']), 1);
if (!isset($url['port'])) {
  $url['port'] = NULL;
}
$this->link = mysql_connect( $url['host'], $url['user'], $url['pass']);
mysql_select_db($url['path']);
}

  public function getXMLDataForProductId($nid) {
    $sql = "SELECT * FROM BCImportXML WHERE nid ='".$nid."' LIMIT 1";
    $result = mysql_query($sql, $this->link);
    if (!$result) {
      $message  = 'Ungültige Abfrage: ' . mysql_error() . "\n";
      $message .= 'Gesamte Abfrage: ' . $linksql;
      die($message);
    }
    $row = mysql_fetch_assoc($result);
    return $row["xml"];
  }
  
  public function getProductURLForProductId($nid) {
    $rawxml = (string) $this->getXMLDataForProductId($nid);
    #echo $rawxml;
    #echo empty($rawxml);
    #die();
    #die ($row["xml"]);
    #$doc = new DOMDocument();
    #$doc->loadXML($rawxml);
    $xml = new SimpleXMLElement($rawxml);
    $xmlelement = $xml->xpath('/product/url');
    $brokerlink = $xmlelement[0][0];
    #echo $brokerlink;
    return $brokerlink;
  }
}   
?>