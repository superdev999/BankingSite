<?php


 $bankRating = array(
 "@context" => "http:'/'/schema.org",
	"name" => "DKB",
		  "@type" => "BankOrCreditUnion",
		  "aggregateRating" => array(
			"@type" => "AggregateRating",
			"ratingValue" => 4.5, 
			"reviewCount" => 10,
			  "bestRating" => 5
		  ));
	


		  
echo "ohne Param <br />";		  
echo json_encode($bankRating);





?>