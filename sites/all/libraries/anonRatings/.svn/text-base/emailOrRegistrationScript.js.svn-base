jQuery().ready(startUpEmailOrRegistration);
function startUpEmailOrRegistration() {
	$("#fakeMailNodeCreation").focus(function(){focusHandler($(this));}).blur(function(){blurHandler($(this));});
	$("#fakeMailNodeCreation").blur(function(){fillRealForm($('fieldset.group-mailadress>div:first>input'), $(this));});
	$('form#node-form input[type=submit]').hide();
	if ($('fieldset.group-mailadress>div:first>input').val().length>0) $("#fakeMailNodeCreation").val($('fieldset.group-mailadress>div:first>input').val());
}

function fillRealForm(realInput, fakeInput) {
	realInput.val(fakeInput.val());
}

function registrationAfterRating() {	
	$('#node-form').attr("action", window.location.pathname+window.location.search+"&novaafternewnodeaction=goToRegister");
	if(validateRatingForm()) {
		$jq('#fakeMailNodeCreationSubmitRegister').html("wird übermittelt");
		$jq('#fakeMailNodeCreationSubmitRegister').attr('onclick', '');
		$jq('#fakeMailNodeCreationSubmitRegister').css('cursor', 'progress');
		$jq('#fakeMailNodeCreationSubmit').fadeOut();
	}
	$('#node-form').submit();
}

function ratingShowAfterRating() {	
	$('#node-form').attr("action", window.location.pathname+window.location.search+"&novaafternewnodeaction=goToRating&novaafternewnodenid="+getParameter('itemId'));
	if(validateRatingForm()) {
		$jq('#fakeMailNodeCreationSubmit').html("wird übermittelt");
		$jq('#fakeMailNodeCreationSubmit').attr('onclick', '');
		$jq('#fakeMailNodeCreationSubmit').css('cursor', 'progress');
		$jq('#fakeMailNodeCreationSubmitRegister').fadeOut();
	}
	$('#node-form').submit();
}