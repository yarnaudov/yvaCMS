$(document).ready(function() {
        
    $('form[name=add]').validate({
        errorPlacement: function(error, element) {}
    });
                        
    $('.load_jquery_ui_iframe').live('click', function(event) {    
        
        event.preventDefault();
        
        var title = $(this).attr('title');     
        var src   = $(this).attr('href');
                        
        var div = document.createElement("div");
        $(div).attr('title', title);
        $(div).attr('id', 'jquery_ui');

        var iframe = document.createElement("iframe");
        $(iframe).attr('src', src);
        $(iframe).attr('frameBorder', 0);
        $(iframe).attr('scrolling', 'no');
        $(iframe).attr('class', $(this).attr('lang'));
        $(iframe).attr('target', $(this).attr('target'));
        $(iframe).attr('id', 'jquery_ui_iframe');

        $(div).append(iframe);            
        $('body').append(div);
        
        $(div).dialog({
            autoOpen: true,
            resizable: true,
            modal: true,
            position: ['top', 50],
            width: 'auto',
            close: function(event, ui){
               $(div).remove();
            }
        });
        
    });
    
    $('.clear_jquery_ui_inputs').live('click', function(){
        $('.'+$(this).attr('lang')).val('');
        return false;
    });
    
    $('.categories_list input').live('click load', function(event){
        console.log(event.type);
        var value = $(this).val();
        console.log(value);
        if(value == 'most_popular' || value == 'most_commented'){
            $('.categories_list input:not([value^=most_])').attr('disabled', $(this).is(':checked'));
            
            if(value == 'most_popular'){
                $('.categories_list input[value=most_commented]').removeAttr('checked');
            }
            else if(value == 'most_commented'){
                $('.categories_list input[value=most_popular]').removeAttr('checked');
            }
            
        }
        
    });

    $('.categories_list input[value=most_commented]').trigger('load');
     
});