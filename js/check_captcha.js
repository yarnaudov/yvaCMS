function check_captcha(form){

    var captcha = false;
    
    $(form.send).attr('disabled', true);

    var url = site_url+"/check_captcha";

    $.ajax({
        type:  "POST",
        async: false,   
        url:   url,   
        data:  {code: $(form.ct_captcha).val(), ct_namespace: $(form.ct_namespace).val()},
        success:  function(data) {

            if(data == 1){
                captcha = true;
            }
            else{
                $(form.send).removeAttr('disabled');
                $(form).find('.refresh_image').trigger('click');
                $(form.ct_captcha).val('');
                $(form.ct_captcha).addClass('error');
                var label = $(form.ct_captcha).parent().find('label.error');
				if(label.html()){
					label.html(data);
					$(label).toggle();
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