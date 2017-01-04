jQuery().ready(function(){

	doToolTip();
	setUpHomeTabs();
	setAccordion();
	setUpSearch();
	setUpRegister();
	toggleAGBstatus();
	setUpShowVotingsForTimespan()
	$jq('.html-advertisement:nth-child(3n+3) .boxStartpage').addClass('lastbox');
	$jq('.html-advertisement:nth-child(3n+3) .adEmpfehlung').addClass('lastbox');
	$jq("#edit-search-block-form-1").val("Bankname oder Produkt ...");
	$jq("#edit-search-block-form-1").focus(function () {
		$jq(this).val('');
	});

	$('#superfish li a').each(function(){

		if($(this).attr('href')=='/bewerten') $(this).addClass('bewerten');
	})

	jQuery('.node-form').append('<input type="hidden" name="formdaten" id="formdaten">');


	nodeId = getParameter('itemId');
	var save_url = $('#node-form').attr("action");


	if (document.URL.indexOf("/node/add/bewertung") != -1) {

		jQuery('#node-form').submit(function(){

			if (validateRatingForm()) {
				return true;
			} else {
				$('#content-header').append("<div class='messages error'><ul><li>Bitte füllen Sie alle Pflichtfelder aus!</li><ul></div>");
				return false;
			}
		});
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

	var selector = '';

	/** Hier Container auf Bewertungszielseite ausblenden**/
	if (GET('action') == 'bewertung-zeigen' || GET('action') == 'mailApproved') {
		jQuery('div.node-inner-3 p').hide();
		jQuery('div.node-inner-3 table').hide();
		jQuery('div.node-inner-3 h3').hide();
		jQuery('div.node-inner-3 table#bewertung').show();
		jQuery('div.node-inner-3 table#bewertung tbody tr td div#ratings div.rating_table table').show();
		jQuery('div.reg_teaser>p').show();
		//jQuery('div.node-inner-3 table#bewertung *').show();

		selector = '#content-area .node-type-page';

	} else {
		selector = '#content-area .rating-overview';
	}

	jQuery(selector).each(function(){
		// get available total space
		var tableh=jQuery(this).find('.middle').height();
		// calculate space for comment
		spaceAvail = tableh - jQuery('#bewertung').find('.rat_abs>b>a').height() - jQuery('#bewertung').find('#ratings-2').height() - 6 - 15;
		// get line height
		var lineheight = parseInt(jQuery('.commenttext.short').css('line-height'));

		// set correct height
		jQuery(this).find('.commenttext.short').css('height', spaceAvail+'px');

		// find height of full text (minus place for closing link)
		fullHeight = jQuery(this).find('.commenttext.long').height()-lineheight;

		// if the text takes more space than available, build popup code
		if (fullHeight > spaceAvail) {
			// find how many lines have place
			var lines = ((spaceAvail/lineheight)-0.5).toFixed(0);
			// minus one line for the link
			var roundedHeight = (lines-1)*lineheight;

			jQuery(this).find('.commenttext.short').css('height', roundedHeight+'px');
			// append rounding errors as padding-bottom
			jQuery(this).find('.commenttext.short').css('margin-bottom', (spaceAvail-roundedHeight-lineheight)+'px');
			jQuery(this).find('.commenttext.short').after('<div style="text-align:right;"><a class="weiterlesen" href="#" style="font-size: 12px;">hier weiterlesen >></a></div>');

			jQuery(this).find('.commenttext.long').css('top', '-'+(spaceAvail+9)+'px');
		}
	});



	jQuery('a.weiterlesen').live('click',function(){
		 jQuery(jQuery(this).parent().parent().next().children()[0]).show();
		 return false;
	});

	jQuery('a.bwschliessen').live('click',function(){
		 jQuery(this).parent().parent().hide();
		 return false;
	});

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
	$('.node-form .required').each(function(index) {
		if ($(this).isEmpty()) {
			//alert("required");
			errors++;
		}
	});

	// Radio Buttons: Weiterempfehlen
	if ($('.form-radio').length==2) {
		if (!($($('.form-radio')[0]).attr("checked") || $($('.form-radio')[1]).attr("checked"))) errors++;
	}
	if ($('.form-radio').length==4) {
		if (!($($('.form-radio')[0]).attr("checked") || $($('.form-radio')[1]).attr("checked"))) errors++;
		if (!($($('.form-radio')[2]).attr("checked") || $($('.form-radio')[3]).attr("checked"))) errors++;
	}

	// Validate E-Mail
	if ($('.group-mailadress').length) {
		var email_rege = /^([A-Za-z0-9_\-\.\+])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		if (!email_rege.test($($('.group-mailadress .required.text')[0]).val())){
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

$.fn.isEmpty = function() {
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

function doToolTip() {
	jQuery("div.tooltip").hover(function(){
			jQuery(this).parent().parent().find("a.fa_link").css("z-index",200);
			jQuery(this).width(170).height(210).css("margin-left","0px").css("z-index",100).find("div").fadeIn("fast").css("z-index" ,101);
	}, function(){
			jQuery(this).parent().parent().find("a.fa_link").css("z-index",0);
			jQuery(this).width(50).height(35).css("margin-left","0px").css("z-index",0).find("div").fadeOut("fast");
	});
}


function setUpShowVotingsForTimespan() {
	$("#showVotingsForTimespan").change(function() {
		var t = new Date();
		$("#activeContent").fadeTo('200',0.2).load(
			"/lib/php/inc/bcVotingsInTimespan.php",
			{
				"nid":$("#showVotingsForTimespan").attr("name"),
				"productType":$("#showVotingsForTimespan").attr("rel"),
				"m":$("#showVotingsForTimespan").val(),
				"t":t.getTime()
			},
			function() {
				$("#activeContent").stop(true).fadeTo('200',1);
			}
		);
	});
}

function setUpHomeTabs() {
	$("#rechnertabs ul a").click(function() {
		$("#rechnertabs ul a").removeClass("active");
		$("#rechnertabs div.rechner").hide();
		$("#rechnertabs div"+$(this).addClass("active").attr("href")).show();
		return false;
	});
	$("#rechnertabs > div.rechner:gt(0)").hide();
}

function setUpSearch() {
	$("#search-block-form").attr("method","get").attr("action","/search/node").submit(function () {
		$.post(
			"/sites/all/modules/bcnav/saveSearchQuery.php",
			{
				q:$(this).find("input[name=keys]").val()
			},
			function () {
				location.href = "/search/node?keys="+$("#search-block-form").find("input[name=keys]").val()
			}
		);
		return false;
	}).find("input[name=search_block_form]").attr("name","keys").parent().parent().find("input[type=hidden]").remove();
	if(window.location.href.match(/\/admin\//)) return;
	var $dd = $("#edit-type");
	$dd.find("option").each(function() {
		switch($(this).html()) {
			case "Seite":
			case "Webformular":
			case "Nur Content":
			case "Advertisement":
			case "Umfrage":
				$(this).remove();
				break;
			case "Artikel":
				$(this).html("News");
				break;
		}
	});
	if ($dd.length > 0) { // make sure we found the select we were looking for

			// save the selected value
			var selectedVal = $dd.val();

			// get the options and loop through them
			var $options = $('option', $dd);
			var arrVals = [];
			$options.each(function(){
					// push each option value and text into an array
					arrVals.push({
							val: $(this).val(),
							text: $(this).text()
					});
			});

			// sort the array by the value (change val to text to sort by text instead)
		arrVals.sort(function(a, b){
				if(a.val>b.val){
						return 1;
				}
				else if (a.val==b.val){
						return 0;
				}
				else {
						return -1;
				}
		});
			// loop through the sorted array and set the text/values to the options
			for (var i = 0, l = arrVals.length; i < l; i++) {
					$($options[i]).val(arrVals[i].val).text(arrVals[i].text);
			}

			// set the selected value back
			$dd.val(selectedVal);
	}

}

function setUpRegister() {
	if($("form#user-register #edit-profile-agb").is(':checked') || $("form#user-register #edit-profile-bank-agb").is(':checked')) {
		$("form#user-register input[type=submit]").show();
		$("form#user-register input[type=submit]").removeAttr('disabled');
	} else {
		$("form#user-register input[type=submit]").hide();
		$("form#user-register input[type=submit]").attr('disabled', 'disbled');
	}
	$("form#user-register #edit-profile-agb, form#user-register #edit-profile-company-agb").click(function() {
		if($(this).is(':checked')) {
			$("form#user-register input[type=submit]").fadeIn();
			$("form#user-register input[type=submit]").removeAttr('disabled');

		} else {
			$("form#user-register input[type=submit]").hide();
			$("form#user-register input[type=submit]").attr('disabled', 'disbled');
		}
	});
}

function toggleAGBstatus() {
		if ($('#user-profile-form #edit-profile-agb').is(':checked')) {
				$('#user-profile-form #edit-profile-agb:input').attr('disabled', true);
		} else {
				$('#user-profile-form #edit-profile-agb:input').removeAttr('disabled');
		}
}

//Bewertungsstrecke Einzelprodukte

function highlightBank(node) {
	$('.bankminilogo>a>img').css('outline', 'none');
	$(node+'>img').css('outline', '2px solid lightblue');
}

// function for bulding click handlers on all h3s
function setupAccordion(identifier, ajaxUrl, otherIdentifiers) {
	$("div#content-area-left div."+identifier+"accordion h3").click(function () {

		//deactive other Accordions
		$(otherIdentifiers).each(function(){
			var otherAccordion = $("."+this+"accordion .accordionBody");
			otherAccordion.hide();
			otherAccordion.prev().removeClass('closed');
			otherAccordion.children('.bewertung').html('');
		});

		// deactivate all blue borders
		$('.bankminilogo>a>img').css('outline', 'none');

		// open or close one content field?
		var element=$(this).next().attr('id').replace(identifier+'element','');
		var sichtbarkeit=$(this).next().css('display');
		// content field was visible => hide now
		if(sichtbarkeit=='block')
		{
			$(this).next().hide();
			$(this).removeClass('closed');
			$(this).parent().css('border-right','none');
		}
		// content field was closed => hide all other windows, open current one
		else {
			$(this).parent().parent().children().children('.accordionBody').each(function(){
					$(this).hide();
					$(this).prev().removeClass('closed');
					$(this).children('.bewertung').html('');
					$(this).parent().css('border-right','none');
			});

			$('#current'+identifier).attr('value',element);
			$(this).next().children('.bewertung').html('<div id="productitemselect"></div><button type="button" onclick="sendRatingForm()" id="ratingFormSendButton">Zur Bewertung</button>');

			if($(this).next().children('.content').html().indexOf('ajax-loader.gif')!=-1)
			{
				$.ajax({
					type: "POST",
					url: ajaxUrl,

					data: $('#ratingForm').serialize(),
					dataType: 'text',
					success: function (data, textStats, jqXHR) {
						accordionInnerHTML(data, identifier); },
					error: function (data, textStats, jqXHR) {
						accordionInnerHTML(data, identifier); },
				});
			}
			$(this).addClass('closed');
			$(this).parent().css('border-right','1px solid #CCC');
			$(this).next().show();
		}
	});
}

// function for bulding HTML code after item call from setupAccordion()
function accordionInnerHTML(response, identifier) {
	var array = $jq.parseJSON(response);

	var selector;
	var html='';
	for (var i = 0; i < array.length; i++) {
		var node = array[i];
		selector=node['groupedBy'];
		html= html+'<div class="bankminilogo"><a href="#" class="bank'+node['bank']+'" id="'+identifier+node['groupedBy']+'" onclick="highlightBank(\'.bank'+node['bank']+'\');">';
		if(node['url']=='sites/default/files/imports/') html =html+node['title'];
		else html =html+'<img src="/'+node['url']+'" class="produktminiicon" title="'+node['title']+'" />';
		html =html+ '</a></div>';
	}
	html =html+ '<p class="clear"></p>';

	$('#'+identifier+'element'+selector+' .content').hide().html(html).slideDown();
}

function setAccordion() {
	$("div#content-area-right div.accordion h3").click(function () {
		if(	$(this).toggleClass("closed").parent().find("div.accordionBody:visible").size()){
			$(this).parent().find("div.accordionBody:visible").slideUp().addClass("closed");
			return;
		}
		$(this).parent().find("div.accordionBody").slideDown().removeClass("closed");
	});
}

// function for ajax call for products
function getProductItems(identifier, ajaxUrl) {
	$('#ratingForm #'+identifier+' .content a').live('click',function(){
			var bank=$(this).attr('class').replace('bank','');
			$('#bankval').attr('value',bank);
			if (identifier != 'bank') {
				var selectBy=$(this).attr('id').replace(identifier,'');
				$('#'+identifier+'val').attr('value',selectBy);
			}
			$('#ratingFormErrorField').fadeOut();
			$('#productitemselect').html("<img src='/themes/pixture_reloaded/images/ajax-loader.gif' />");
			$.ajax({
				type: "POST",
				url: ajaxUrl,
				data: $('#ratingForm').serialize(),
				dataType: 'text',
				success: buildProductItems,
				error: buildProductItems
			});
		return false;
		});
}

// function for building HTML from ajax response from getProductItems()
function buildProductItems(response) {
	var array = $jq.parseJSON(response);
	if (array.length==0) {
		var html="Für diese Auswahl sind leider keine Produkte vorhanden.";
	} else {
		var html = "";
		//Bank einfuegen
		html = html+ "<div><input name='productitem' value='"+array[0]["bank"]+"' type='radio' /><a id='product"+array[0]["bank"]+"'href='/"+array[0]["bank_alias_url"]+"' target='_blank'>"+array[0]["bank_name"]+"</a><input type='hidden' id='"+array[0]["bank"]+"-target' value='node/add/bewertung-bank?itemId="+array[0]["bank"]+"' /></div>";

		// bool isProduct ist immer true wenn der Array Produkte enthält
		if (array[0]["isProduct"] == true) {
			for (var i = 0; i < array.length; i++) {
				var node = array[i];
				html = html+ "<div><input name='productitem' value='"+node["nid"]+"' type='radio' /><a id='product"+node["nid"]+"'href='/"+node["url"]+"' target='_blank'>"+node["title"]+"</a><input type='hidden' id='"+node["nid"]+"-target' value='node/add/bewertung-"+node["productNodeTitle"].toLowerCase()+"?itemId="+node["nid"]+"' /></div>";
			}
		}

	}
	$('#productitemselect').hide().html(html).fadeIn('slow');
}


function sendRatingForm() {
	var form = $('#ratingForm');
	var array = form.serializeArray();

	var productSet = false;
	for (var i in array) {
		if (array[i].name == "productitem") productSet = true;
	}
	if (productSet) {

		var id = $('[name="productitem"]:checked').attr('value');
		$('#edit-field-dmexco-einzelprodukt-nid-nid').val(id).attr('selected',true);
		window.location = "/" + $('#'+id+'-target').attr('value');
	} else {
		$('#ratingFormErrorField').hide().html("Bitte wählen sie zuerst ein Einzelprodukt aus.").fadeIn('slow');
	}
}


function backToFirstForm() {
	$('#firstFormWrapper').slideDown();
	$('#node-form').slideUp();
	$('#backToFirstFormWrapper').slideUp();
}

$jq('#edit-field-dmexco-einzelprodukt-nid-nid-wrapper').hide();
$jq('#node-form').hide();
$jq('#backToFirstFormWrapper').hide();
var token = '<?php echo md5(mt_rand()); ?>';
$jq('#edit-field-dmexco-token-0-value-wrapper').hide();
$jq('#edit-field-dmexco-token-0-value').attr('value', token);