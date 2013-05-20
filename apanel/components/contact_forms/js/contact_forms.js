
$(document).ready(function() {
    
    $('select.type').live('change', function(){
        var params = $(this).parents('li').find('.params');
        $.get(site_url+'/home/ajax/load?view=contact_forms/'+$(this).val(), function(data){
            $(params).css('display', 'none');
            $(params).html(data);
            $(params).toggle('slow');
        });
   });
                       
    function replaceAll( text, old_string, new_string ){
       while (text.toString().indexOf(old_string) != -1){
           text = text.toString().replace(old_string, new_string);
       }
       return text;
   }
   
    function reorderFields(){
       
        $('#form_fields > li').each(function(index){

            var html = $(this).html();

            var number = $(this).find('fieldset legend span').html();
            number = parseInt(number);

            if(number != index+1){
                html = replaceAll(html, 'fields['+number+']', 'fields['+(index+1)+']');
                $(this).html(html);
                $(this).find('fieldset legend span').html(index+1);
            }

         });
       
    }

    $('#form_fields').sortable({ 
        handle: ".handle",
        stop: function( event, ui ) {
            reorderFields();
        }
    });
   
    $('a#add_field').bind('click', function(){

        var li = $('#form_fields > li').first();
        
        if($(li).css('display') == 'none'){

            $(li).toggle(250);

        }else{

            var clone_li = $(li).clone();
            var html     = $(clone_li).html();
            var number   = $('#form_fields > li').length+1;
            
            html = replaceAll(html, 'fields[1]', 'fields['+number+']');
            $(clone_li).html(html);
 
            $(clone_li).css('display', 'none');
            $('#form_fields').append(clone_li);
            
            $(clone_li).find('fieldset legend span').html(number);
            $(clone_li).find('fieldset a').attr('lang', 'field'+number);
            
            $(clone_li).find('select.type option:first').attr('selected', true).trigger('change');
            $(clone_li).toggle(250);

        }
              
   });
   
    $('a.delete_field').live('click', function(event){
       
        event.preventDefault();
       
        $(this).parents('li').toggle('slow', function() {
                        
            if($('#form_fields > li').length > 1){
                $(this).remove();
            }
       
            reorderFields();
            
        });
       
   });
   
    // checkboxes
    //$('a.delete_option').die('click');
    //$('input.option').die('click change');
    //$('input.optgroup').die('click change');
    
    $('a.add_option').live('click', function(event){

        event.preventDefault();
        
        var li = $(this).parents('.params').find('.checkboxes li').first();

        if($(li).css('display') == 'none'){

            $(li).toggle(250);

        }else{

            var clone_li = $(li).clone();

            $(clone_li).css('display', 'none');
            $(clone_li).find('input.text').val('');
            $(clone_li).find('input.option').removeAttr('checked');
            $(clone_li).find('input.option').removeAttr('disabled');
            $(clone_li).find('input.optgroup').removeAttr('checked');
            $(this).parents('.params').find('.checkboxes').append(clone_li);

            $(clone_li).toggle(250);

        }

    });
    
    $('a.delete_option').live('click', function(event){

        event.preventDefault();

        $(this).parent().toggle('slow', function() {
            if($(this).parents('.params').find('.checkboxes li').length > 1){
                $(this).remove();
            }
            else{
                $(this).toggle('slow');
            }
        });

    });
    
    $('input.option').live('click change', function(){

        $('input.option').each(function(){

            if($(this).is(':checked')){
                $(this).parent().find('input.option_hidden').attr('disabled', true);
            }
            else{
                $(this).parent().find('input.option_hidden').removeAttr('disabled'); 
            }

        });

    });

    $('input.optgroup').live('click change', function(){

        if($(this).is(':checked')){
            $(this).parent().find('input.text').css('font-weight', 'bold');
            $(this).parent().find('input.option').attr('disabled', true);
            $(this).parent().find('input.optgroup_hidden').attr('disabled', true);
        }
        else{
            $(this).parent().find('input.text').css('font-weight', 'normal');
            $(this).parent().find('input.option').removeAttr('disabled');
            $(this).parent().find('input.optgroup_hidden').removeAttr('disabled');
        }

    });

    $('input.option').first().trigger('change');
            
    $('input.optgroup').each(function(){
        $(this).trigger('change');
    });
            
    $('.checkboxes').sortable({ handle: ".handle" });
    
});