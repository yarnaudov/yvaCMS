$(document).ready(function() {
        
    $(".contactForm").each(function(){
	
		$(this).validate({
			debug: true,
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
		/* add this later !!!
		$('.captcha_input').rules('add', {
			required: true,
			remote: site_url+'/check_captcha'
		});
		*/
		
    
    });
	
	jQuery.validator.addMethod("maxfilesize", function(value, element) {
		try{
			if($(element).rules('get').maxfilesize > 0 && element.files[0].size > $(element).rules('get').maxfilesize){
				return false;
			}
			return true;
		}catch(err){return true;}
	});
	
	$('.file').each(function(){
		$(this).rules('add', {
			accept: $(this).data('mimes'),
			maxfilesize: $(this).data('size')
		});
		$(this).removeAttr('data-mimes').removeAttr('data-size');
	});
	
    
});