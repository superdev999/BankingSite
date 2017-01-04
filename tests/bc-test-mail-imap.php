<?php

 require_once "./mail/Mail.php";
 
 $from = "bankingcheck.de <info@bankingcheck.de>";
 $to = "schneider@nova-web.de";
 $subject = "Hi!";
 $body = "Hi,\n\nHow are you?";
 
 $host = "mail.novahq.de";
 $username = "bankingcheck-test@novahq.de";
 $password = "vtbk2U18tx";
 
 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 var_dump($smtp );
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
  } else {
   echo("<p>Message successfully sent!</p>");
  }
 ?>