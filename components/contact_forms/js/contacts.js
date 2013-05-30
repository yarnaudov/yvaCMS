
//var check_captcha_flag = true;

function check_captcha(form){

    var captcha = false;
    
    $(form.send).attr('disabled', true);

    var url = site_url+"/contact_forms/check_captcha";

    $.ajax({
	type:  "POST",
	async: false,   
	url:   url,   
	data:  {code: form.ct_captcha.value},
	success:  function(data) {

	    if(data == 1){
		captcha = true;
	    }
	    else{
		$(form.send).removeAttr('disabled');
		$('#refresh_image').trigger('click');
		$(form.ct_captcha).val('');
		$(form.ct_captcha).addClass('error');
		var label = $(form.ct_captcha).parent().find('label.error');
		if(label.html()){
		    label.html(data);
		    label.css('display', 'block');
		}
		else{
		    $(form.ct_captcha).parent().append('<label class="error" for="ct_captcha" generated="true">'+data+'</label>');                           
		}

		captcha = false;

	    }
		
	}
    });
	
    return captcha;

}

$(document).ready(function() {
        
    $(".contactForm").validate({
	submitHandler: function(form) {

	    var submit_form = true;
	    
	    if(form.ct_captcha){
		submit_form = check_captcha(form);
		console.log(submit_form);
	    }

	    if(submit_form == true){
		$(form).append('<input type="hidden" name="send" value="1" >');
		console.log('submit!');
		form.submit();             
	    }

	}
    });
    
});