
var check_captcha_flag = true;

function check_captcha(form){

    $(form.send).attr('disabled', true);

    var url = site_url+"/contact_forms/check_captcha";

    $.post(url, { code: form.ct_captcha.value},
        function(data) { 

            alert(data);

            if(data == 1){                    
                //$(form).append('<input type="hidden" name="send" >');                        
                //$(form).submit();
                check_captcha_flag = false;
                return true;
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
                check_captcha_flag = true;
                return false;
            }

        }
    );

}

$(document).ready(function() {
  
   $("#contactForm").validate({
       submitHandler: function(form) {
           
           var submit_form = true;
           
           if(check_captcha_flag == true){
               submit_form = check_captcha(form);
           }
           
           if(submit_form == true){
               alert('submit');
               form.submit();             
           }

       }
   });
    
});