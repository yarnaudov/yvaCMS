$(document).ready(function() {
        
    $(".contactForm").each(function(){
	
	$(this).validate({
	    submitHandler: function(form) {

		var submit_form = true;

		if(form.ct_captcha){
		    submit_form = check_captcha(form);
		    //console.log(submit_form);
		}

		if(submit_form == true){
		    $(form).append('<input type="hidden" name="send" value="1" >');
		    //console.log('submit!');
		    form.submit();             
		}

	    }
	});
    
    });
    
});