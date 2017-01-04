jQuery().ready(function(){

	setAccordion();




	
	$jq('.html-advertisement:nth-child(3n+3) .boxStartpage').addClass('lastbox');
    $jq('.html-advertisement:nth-child(3n+3) .adEmpfehlung').addClass('lastbox');
	$jq("#edit-search-block-form-1").val("Bankname oder Produkt ...");
	$jq("#edit-search-block-form-1").focus(function () {
		$jq(this).val('');
	});
	
	
	jQuery('#superfish li a').each(function(){
	
		if(jQuery(this).attr('href')=='/bewerten') jQuery(this).addClass('bewerten');
	})

	jQuery('.node-form').append('<input type="hidden" name="formdaten" id="formdaten">');

	
	// Hide Token and mail-affirmed fields in "bewertung"
	$jq($jq(".group-mailadress").children()[2]).hide();
	$jq($jq(".group-mailadress").children()[3]).hide();
	
	nodeId = getParameter('itemId');
	var save_url = jQuery('#node-form').attr("action");
	
	
	if (document.URL.indexOf("/node/add/bewertung") != -1) {
		

		
		//jQuery('#node-form').attr("action", "/bewerten?action=bewertung-zeigen&node=" + getParameter('itemId'));
	

	
		jQuery('#node-form').submit(function(){
	
			if (validateRatingForm()) {
				// Bewertung speichern
				/*jQuery.ajax({
					type: 'POST',
					async: false,
					url: save_url,
					
					data: jQuery("#node-form").serialize(),
					success: function(){alert('Ihre Bewertung wurde gespeichert.')}
					
				});*/
				
				return true;
			} else {
				jQuery('#content-header').append("<div class='messages error'><ul><li>Bitte füllen Sie alle Pflichtfelder aus!</li><ul></div>");
				return false;
			}
		});
	
	
	
	}
	
	
	if (jQuery('#ratings').length) {
		//$jq('#ratings a').remove();
	}
	
	
	
	HTTP_GET_VARS=new Array();
	strGET=document.location.search.substr(1,document.location.search.length);
	if(strGET!='')
	    {
	    gArr=strGET.split('&');
	    for(i=0;i<gArr.length;++i)
	        {
	        v='';vArr=gArr[i].split('=');
	        if(vArr.length>1){v=vArr[1];}
	        HTTP_GET_VARS[unescape(vArr[0])]=unescape(v);
	        }
	    }
 
	function GET(v)
	{
	if(!HTTP_GET_VARS[v]){return 'undefined';}
	return HTTP_GET_VARS[v];
	}
	
	if (GET('action') == 'bewertung-zeigen' || GET('action') == 'mailApproved') {
		jQuery('div.node-inner-3 p').hide();
	}
});


/**
	Get URL Parameters
**/
function getParameter(paramName) {
  var searchString = window.location.search.substring(1),
      i, val, params = searchString.split("&");

  for (i=0;i<params.length;i++) {
    val = params[i].split("=");
    if (val[0] == paramName) {
      return unescape(val[1]);
    }
  }
  return null;
}

/**
	Validate Bewertungs-Form
**/
function validateRatingForm() {
	var errors = 0;
	
	// Select Boxen und E-MailAdresse
	jQuery('.node-form .required').each(function(index) {	
		if (jQuery(this).isEmpty()) {
			//alert("required");
			errors++;
		} 
	});
	
	// Radio Buttons: Weiterempfehlen
	if (jQuery('.form-radio').length==2) {
		if (!(jQuery(jQuery('.form-radio')[0]).attr("checked") || jQuery(jQuery('.form-radio')[1]).attr("checked"))) errors++;
	}
	if (jQuery('.form-radio').length==4) {
		if (!(jQuery(jQuery('.form-radio')[0]).attr("checked") || jQuery(jQuery('.form-radio')[1]).attr("checked"))) errors++;
		if (!(jQuery(jQuery('.form-radio')[2]).attr("checked") || jQuery(jQuery('.form-radio')[3]).attr("checked"))) errors++; 
	}	
	
	// Validate E-Mail
	if (jQuery('.group-mailadress').length) {
		var email_rege = /^([A-Za-z0-9_\-\.\+])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})jQuery/;
		if (!email_rege.test(jQuery(jQuery('.group-mailadress .required.text')[0]).val())){ 
			//alert("wrong email");
			errors++; 
		}
	}
	
	if (errors > 0) {
		return false;
	} else {
		return true;
	}
}

jQuery.fn.isEmpty = function() {
	try {
		if (element.val().length() == 0) {
			return true;
		}
	} catch (e) {
		if ($(this).val() == '-' || $(this).val() == '') {
			return true;
		}
	}
	return false;
}




function bankHTML(response) {

		var array = $jq.parseJSON(response);
		if(array.length==1) 
		{	
		
			jQuery('#belement'+array[0]['item']).html('<div class="content keinebank">Keine Bank vorhanden</div>'); 
		
		}
		else{
			var selector;
			var html='<table>';
			var i;
			for (i = 1; i < array.length; i++) {
				var node = array[i];
				
				if ((i-1) % 3 == 0) {
					if (i==1) {
						html = html + '<tr>';
					}
					else {					
					html = html + '</tr><tr>';
					}
				}
				selector=node['item'];
				html= html+'<td class="bankminilogo" width="276"><a href="#" id="bank'+node['id']+'" onclick="highlightBank(\'#bank'+node['id']+'\');">';
				if(node['url']=='sites/default/files/imports/') html =html+node['title'];
				else html =html+'<img src="/'+node['url']+'" title="'+node['title']+'" width="276" height="69" />';
				html =html+ '</a></td>';
				
			}
			
			var rest= (i-1) % 3;
			
			if (rest == 1) {
				html =html+ '<td class="bankminilogo" width="276">&nbsp;</td><td class="bankminilogo" width="276">&nbsp;</td>';	
			
			}
			
			else if (rest == 2) {
					html =html+ '<td class="bankminilogo" width="276">&nbsp;</td>';	
			}
			
			
			
			html = html + '</tr></table>';
			
			
			jQuery('#belement'+selector+' .content').hide().html(html).slideDown();	
		}
		
		
		
			
			
}

function highlightBank(node) {
	jQuery('.bankminilogo>a>img').css('outline', 'none');
	jQuery(node+'>img').css('outline', '2px solid lightblue');	
}

function productHTML(response) {

	     
		  
		  
		var array = $jq.parseJSON(response);
		
		var selector;
		var html='';
		for (var i = 0; i < array.length; i++) {
			var node = array[i];
			selector=node['produkt'];
			html= html+'<div class="bankminilogo"><a href="#" class="bank'+node['bank']+'" id="produkt'+node['produkt']+'" onclick="highlightBank(\'.bank'+node['bank']+'\');">';
			if(node['url']=='sites/default/files/imports/') html =html+node['title'];
			else html =html+'<img src="/'+node['url']+'" width="276" height="69" class="produktminiicon" title="'+node['title']+'" />';
			html =html+ '</a></div>';
		}
			html =html+ '<p class="clear"></p>';
		
		jQuery('#pelement'+selector+' .content').hide().html(html).slideDown();	
		
		
		
			
			
}
function setAccordion() {
	//$jq(" div.accordion h3:eq(0)").addClass("closed");
	jQuery("div#content-area-right div.accordion h3").live("click",function () {
	
		if(	jQuery(this).toggleClass("closed").parent().find("div.accordionBody:visible").size()){
			jQuery(this).parent().find("div.accordionBody:visible").slideUp().addClass("closed");
			return;
		}
		jQuery(this).parent().find("div.accordionBody").slideDown().removeClass("closed");
	});
	
	//******************************BANKACCORDION******************************

	jQuery("div#content-area-left div.baccordion h3").live("click",function () {   
		//2. Accordion deaktivieren (egal ob ich im aktuellen Accordion �ffne/schlie�e -> max. 1 Fenster offen)
		jQuery('.paccordion .accordionBody').each(function(){
					jQuery(this).hide();
					jQuery(this).prev().removeClass('closed');
					jQuery(this).children('.bewertung').html('');
		});
		
		// alle blauen Rahmen um Banken deaktivieren
		jQuery('.bankminilogo>a>img').css('outline', 'none');
		
		//im aktuellen accordiondion Fenster �ffnen bzw. schlie�en
		var element=jQuery(this).next().attr('id').replace('belement','');
		var sichtbarkeit=jQuery(this).next().css('display');
		//Fenster war offen -> schlie�en und nichts weiter, da maximal 1 Fenster offen sein kann
		if(sichtbarkeit=='block')
		{
			jQuery(this).next().hide();
			jQuery(this).removeClass('closed');
			jQuery(this).parent().css('border-right','none');
		}
		//Fenster war geschlossen => alle anderen Fenster schlie�en und dann �ffnen
		else {
		
			jQuery(this).parent().parent().children().children('.accordionBody').each(function(){
					jQuery(this).hide();
					jQuery(this).prev().removeClass('closed');
					jQuery(this).children('.bewertung').html('');
					jQuery(this).parent().css('border-right','none');
			}); 
			
			
			
			jQuery('#currentB').attr('value',element);
			jQuery(this).next().children('.bewertung').html('<div id="productitemselect"></div><button type="button" onclick="sendRatingForm()" id="ratingFormSendButton">zur Bewertung</button>');
		
			if(jQuery(this).next().children('.content').html().indexOf('ajax-loader.gif')!=-1)
			{
				jQuery.ajax({
					type: "POST",
					url: "/sites/all/modules/quick_rating_wv/quickrating/listItems-2.php",
					
					data: jQuery('#ratingForm').serialize(),	
					dataType: 'text',	
					success: bankHTML,	
					error: bankHTML
							
				});	
			}
			jQuery(this).addClass('closed');
			jQuery(this).parent().css('border-right','1px solid #CCC');
			jQuery(this).next().show();
			
		}
	
	});
	
	//******************************PRODUKTACCORDION******************************
	jQuery("div#content-area-left div.paccordion h3").live("click",function () {
	
		//2. Accordion deaktivieren (egal ob ich im aktuellen Accordion �ffne/schlie�e -> max. 1 Fenster offen)
		jQuery('.baccordion .accordionBody').each(function(){
					jQuery(this).hide();
					jQuery(this).prev().removeClass('closed');
					jQuery(this).children('.bewertung').html('');
		});
		
		// alle blauen Rahmen um Banken deaktivieren
		jQuery('.bankminilogo>a>img').css('outline', 'none');
		
		//im aktuellen Accordion Fenster �ffnen bzw. schlie�en
		var element=jQuery(this).next().attr('id').replace('pelement','');
		var sichtbarkeit=jQuery(this).next().css('display');
		//Fenster war offen -> schlie�en und nichts weiter, da maximal 1 Fenster offen sein kann
		if(sichtbarkeit=='block') 
		{
			jQuery(this).next().hide();
			jQuery(this).removeClass('closed');
		}
		//Fenster war geschlossen => alle anderen Fenster schlie�en und dann �ffnen
		else {
		
			jQuery(this).parent().parent().children().children('.accordionBody').each(function(){
					jQuery(this).hide();
					jQuery(this).prev().removeClass('closed');
					jQuery(this).children('.bewertung').html('');
			});
			
			
			
			jQuery('#currentP').attr('value',element);
			jQuery(this).next().children('.bewertung').html('<div id="productitemselect"></div><button type="button" onclick="sendRatingForm()" id="ratingFormSendButton">Zur Bewertung</button>');
		
			if(jQuery(this).next().children('.content').html().indexOf('ajax-loader.gif')!=-1)
			{
				jQuery.ajax({
					type: "POST",
					url: "/sites/all/libraries/quickrating/listItems.php",
					
					data: jQuery('#ratingForm').serialize(),	
					dataType: 'text',	
					success: productHTML,	
					error: productHTML
							
				});	
			}
			jQuery(this).addClass('closed');
			jQuery(this).next().show();
			
		}
			
			
	
	
	});
	
	
}



//Bewertungsstrecke Einzelprodukte 

		function buildProductItems(response) {
	
			
			var array = $jq.parseJSON(response);
			if (array.length==0) {
				var html="Für diese Auswahl sind leider keine Produkte vorhanden.";
			} else {
				var html = "";
				//Bank einfuegen
				html = html+ "<div style='padding-top:10px'><input name='productitem' id='"+array[0]["bank"]+"' value='"+array[0]["bank"]+"' type='radio' /><label for='"+array[0]["bank"]+"'>"+array[0]["bank_name"]+"</label><input type='hidden' id='"+array[0]["bank"]+"-target' value='node/add/bewertung-bank?itemId="+array[0]["bank"]+"' /></div>";
				//console.log("bank-id: "+array[0]["bank"]);

				// bool isProduct ist immer true wenn der Array Produkte enthält
				if (array[0]["isProduct"] == true) {
					for (var i = 0; i < array.length; i++) {
						var node = array[i];
						//console.log(node["nid"]);
						html = html+ "<div style='padding-top:10px'><input name='productitem' id='"+node["nid"]+"' value='"+node["nid"]+"' type='radio' /><label for='"+node["nid"]+"'>"+node["title"]+"</label><input type='hidden' id='"+node["nid"]+"-target' value='node/add/bewertung-"+node["productNodeTitle"].toLowerCase()+"?itemId="+node["nid"]+"' /></div>";
					}
				}
				
			}
			jQuery('#productitemselect').hide().html(html).fadeIn('slow');			
		}
		
		
		jQuery('#ratingForm #bank .content a').live('click',function(){
 			var bank=jQuery(this).attr('id').replace('bank','');
			jQuery('#bankval').attr('value',bank);
			jQuery('#ratingFormErrorField').fadeOut();
			jQuery('#productitemselect').html("<img src='/themes/pixture_reloaded/images/ajax-loader.gif' />");
			jQuery.ajax({
				type: "POST",
				url: "/sites/all/modules/quick_rating_wv/quickrating/getProductItems.php",
				
				data: jQuery('#ratingForm').serialize(),	
				dataType: 'text',	
				success: buildProductItems,	
				error: buildProductItems			
			});		
		return false;			
		});
		
		
			jQuery('#ratingForm #produkt .content a').live('click',function(){
 			var bank=jQuery(this).attr('class').replace('bank','');
			var produkt=jQuery(this).attr('id').replace('produkt','');
			jQuery('#bankval').attr('value',bank);
			jQuery('#produktval').attr('value',produkt);
			jQuery('#ratingFormErrorField').fadeOut();
			//jQuery(this).css('background-image', 'url("/themes/pixture_reloaded/images/bg-accordion-up.gif")');
			jQuery('#productitemselect').html("<img src='/themes/pixture_reloaded/images/ajax-loader.gif' />");
			jQuery.ajax({
				type: "POST",
				url: "/sites/all/libraries/quickrating/getProductItems-2.php",
				
				data: jQuery('#ratingForm').serialize(),	
				dataType: 'text',	
				success: buildProductItems,	
				error: buildProductItems		
			});		
		return false;			
		});
		
		
		
		
		function sendRatingForm() {
			var form = jQuery('#ratingForm');
			var array = form.serializeArray();
			
			var productSet = false;
			for (var i in array) {
				if (array[i].name == "productitem") productSet = true;
			}
			if (productSet) {
			
				var id = jQuery('[name="productitem"]:checked').attr('value');
				jQuery('#edit-field-dmexco-einzelprodukt-nid-nid').val(id).attr('selected',true);
				//jQuery('#firstFormWrapper').slideUp();
				//jQuery('#node-form').slideDown();
				//jQuery('#backToFirstFormWrapper').slideDown();
				//console.log("Goto "+jQuery('#'+id+'-target').attr('value'));
				var loc = jQuery('#'+id+'-target').attr('value');
				//window.location = jQuery('#'+id+'-target').attr('value');
                var app = "&app=true"
                // hier dann noch den Parameter fuer Bewertung alleine hinsetzen
				// Unterscheidung http / https
				//if (document.location.protocol=="https"
				window.location.href = document.location.protocol+"//www.bankingcheck.de/"+loc+app;
				//form.submit();		
			} else {				
				jQuery('#ratingFormErrorField').hide().html("Bitte wählen sie zuerst ein Einzelprodukt aus.").fadeIn('slow');
			}
			//console.log(array+array["productitem"]);			
		}
		function backToFirstForm() {
			jQuery('#firstFormWrapper').slideDown();
			jQuery('#node-form').slideUp();
			jQuery('#backToFirstFormWrapper').slideUp();
		}	
		
		$jq('#edit-field-dmexco-einzelprodukt-nid-nid-wrapper').hide();
		$jq('#node-form').hide();	
		$jq('#backToFirstFormWrapper').hide();
		var token = '<?php echo md5(mt_rand()); ?>';
		$jq('#edit-field-dmexco-token-0-value-wrapper').hide();
		$jq('#edit-field-dmexco-token-0-value').attr('value', token);		
		
	//Bewertungsstrecke Einzelprodukte Ende