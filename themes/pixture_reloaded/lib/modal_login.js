function startUpModalLogin() {
	jQuery('#newLoginFormStandardLogin').html(jQuery('#block-user-0').html());
	jQuery('#block-user-0').html("");
	$("#user-login-form>div>div>input#edit-name").attr('title', 'Benutzername');
	$('#user-login-form>div>div>input#edit-pass').attr('title', 'Passwort');
	$('#user-login-form>div>div>input#edit-pass').hide();
	$('#user-login-form>div>#edit-submit').attr('value', "Anmelden");
	$('#user-login-form>div>div>input#edit-pass').after('<input type="text" name="fakepass" class="newLoginBlurred" id="edit-pass-fake" maxlength="60" size="15" class="form-text required" value="Passwort" title="Passwort">')
	bindFillHandler();
	if (jQuery('#block-user-0').length == 0) $('#newLoginShowLoginWindow').attr('href', '/user');
}

function showLogin() {
	$('#newLoginForm').modal();
	$('#newLoginForm').focus();
}

function blurHandler(field) {
	if(field.val() == ""){
		field.val(field.attr("title"));
		field.addClass('newLoginBlurred');
	}
}

function focusHandler(field) {
	if(field.val() == field.attr("title")){
		field.val("");
		field.removeClass('newLoginBlurred');
	}
}

function pwdFocus() {
    $('#user-login-form>div>div>input#edit-pass-fake').hide();
    $('#user-login-form>div>div>input#edit-pass').show();
    $('#user-login-form>div>div>input#edit-pass').focus();
}

function pwdBlur() {
    if ($('#user-login-form>div>div>input#edit-pass').attr('value') == '') {
        $('#user-login-form>div>div>input#edit-pass').hide();
        $('#user-login-form>div>div>input#edit-pass-fake').show();
    }
}

function bindFillHandler() {
	$("#user-login-form>div>div>input#edit-name").focus(function(){focusHandler($(this));}).blur(function(){blurHandler($(this));});
	$("#user-login-form>div>div>input#edit-pass-fake").focus(pwdFocus);
	$("#user-login-form>div>div>input#edit-pass").blur(pwdBlur);	
}

jQuery().ready(startUpModalLogin);

