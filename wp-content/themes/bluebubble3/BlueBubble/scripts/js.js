$(document).ready(function(){	
	$(document).ready(function () {
		$('.boxsingle').fadeIn(2000);
      });


var nameDef = $("#fromName").val();
var messageDef = $("#message").val();
var emailDef = $("#emailTo").val();
	//
	$('#fromName').each(function () {
		if ($(this).val() == '') {
			$(this).val(nameDef);
		}
	}).focus(function () {
		if ($(this).val() == nameDef) {
			$(this).val('');
		}
	}).blur(function () {
		if ($(this).val() == '') {
			$(this).val(nameDef);
		}
	});
	
	$('#emailTo').each(function () {
		if ($(this).val() == '') {
			$(this).val(emailDef);
		}
	}).focus(function () {
		if ($(this).val() == emailDef) {
			$(this).val('');
		}
	}).blur(function () {
		if ($(this).val() == '') {
			$(this).val(emailDef);
		}
	});
	
	$('#message').each(function () {
		if ($(this).val() == '') {
			$(this).val(messageDef);
		}
	}).focus(function () {
		if ($(this).val() == messageDef) {
			$(this).val('');
		}
	}).blur(function () {
		if ($(this).val() == '') {
			$(this).val(messageDef);
		}
	});
	//
	$("#emailmebtn").click(function(){
		$("#form_status").hide("fast");
		$(".error").removeClass("error");
		$("#form_status").html('');
		$("#javacheck").val(Math.random());
		var javacheckVal = $("#javacheck").val();
		
		var hasError = false;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var emailToVal = $("#emailTo").val();
		if (emailToVal == '') {
			$("#emailTo").addClass("error");
			hasError = true;
		} else if (!emailReg.test(emailToVal)) {	
			$("#emailTo").addClass("error");
			hasError = true;
		}
		
		var fromVal = $("#fromName").val();
		if (fromVal == '' || fromVal == nameDef ) {
			$("#fromName").addClass("error");
			hasError = true;
		}
		
		var messageVal = $("#message").val();
		if(messageVal == '' || messageVal == messageDef ) {
			$("#message").addClass("error");
			hasError = true;
		}
		
		if (hasError == false) {
			$.post(mailpath,
   				{ fromName: fromVal, emailTo: emailToVal, message: messageVal, javacheck: javacheckVal },
   					function (data){
						$("#emailme").slideUp("normal", function() {				   
							$("#form_status").html('<strong>Success!</strong> Your email has been sent.').show("slow");
							window.setTimeout(function() {
								$("#form_status").hide("slow");
								$("#emailme").slideDown("normal");
							}, 5000);
						});
   					}
				 );
		} else {
			$("#form_status").html('Please fill in all required fields').show("slow");
		}
		
		return false;
	});
});