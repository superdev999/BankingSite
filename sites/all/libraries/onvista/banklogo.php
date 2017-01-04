<?php
class banklogo {
  private $link;
  public function __construct() {
  @require($_SERVER['DOCUMENT_ROOT']."/sites/default/settings.php");
  $url = parse_url($db_url);
  #echo $bankid;

  // Decode url-encoded information in the db connection string
  $url['user'] = urldecode($url['user']);
  // Test if database url has a password.
  $url['pass'] = isset($url['pass']) ? urldecode($url['pass']) : '';
  $url['host'] = urldecode($url['host']);
  $url['path'] = substr(urldecode($url['path']), 1);
  if (!isset($url['port'])) {
    $url['port'] = NULL;
  }
  $this->link = @mysql_connect( $url['host'], $url['user'], $url['pass']);
  mysql_select_db($url['path']);
  }
  
  public function getLogo($nid) {  
  $sql = "SELECT filepath, `field_financeaddsid_value` FROM `content_type_bank` JOIN `files` ON `content_type_bank`.`field_banklogo_fid`=`files`.`fid` WHERE content_type_bank.nid='".$nid."'";
  $result = mysql_query($sql);
  $row = mysql_fetch_assoc($result);
  $banklogo = $row["filepath"];
  return $banklogo;
  }

}
?>