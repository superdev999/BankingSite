<html>
<head>

</head>
<body>


<div id="bankingcheck"></div>
<script type="text/javascript">
	var aktiv;
	function load_script(path){
   		var head = document.getElementsByTagName("head")[0]; 
   		var script = document.createElement("script"); 
   		script.type = "text/javascript"; 
   		script.src = path; 
   		head.appendChild(script);
		aktiv = window.setInterval("bw()", 250);	
	}
	
	if(typeof jQuery != "function"){
		load_script(document.location.protocol+"//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js");
	}
	
	function bw(){
		jQuery(document).ready(function(){
			var ssl="no";
			if(document.location.protocol=="https:") ssl="yes";
			jQuery.getJSON(document.location.protocol+"//www.bankingcheck.de/BewertungsWidget/bw_widget_JSON.php?ssl="+ssl+"&productid=5&callback=?", function(data){
				jQuery("#bankingcheck").html(data.html);
			});
		});
		window.clearInterval(aktiv);
		}
</script>

</body>
</html>
