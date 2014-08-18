$(function () {
	var password1 = $("#password1");
	var password2 = $("#password2");
	var submit = $("#submit");
	var options = {minimumChars:8, strengthScaleFactor:0.7};

	submit.attr('disabled', 'disabled');

	complexifyField(password1, $('#progress1'));
	complexifyField(password2, $('#progress2'));

	function complexifyField(passwordField, progressbar) {
        passwordField.complexify(options, function (valid, complexity) {
        	
            var passwordsMatch = (password1.val() == password2.val());

            if(valid && passwordsMatch) {
            	submit.removeAttr('disabled');
            } else {
            	submit.attr('disabled', 'disabled');
            }

            if (!valid) {
                progressbar.css({'width':complexity + '%'}).removeClass('progressbarValid').addClass('progressbarInvalid');
            } else {
                progressbar.css({'width':complexity + '%'}).removeClass('progressbarInvalid').addClass('progressbarValid');
            }
        });
	}
});