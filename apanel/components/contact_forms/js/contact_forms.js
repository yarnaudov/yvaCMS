
$(document).ready(function() {
    
    function replaceAll( text, old_string, new_string ){
       while (text.toString().indexOf(old_string) != -1){
           text = text.toString().replace(old_string, new_string);
       }
       return text;
   }
   
    function reorderFields(){
       
        $('#form_fields li').each(function(index){

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
   
   $('a.add').bind('click', function(){
       
        var li = $('#form_fields li').first();
        
        if($(li).css('display') == 'none'){

            $('#form_fields li').first().removeAttr('style');

        }else{

            var clone_li = $(li).clone();
            var html     = $(clone_li).html();
            var number   = $('#form_fields li').length+1;
            
            html = replaceAll(html, 'fields[1]', 'fields['+number+']');
            $(clone_li).html(html);
 
            $(clone_li).css('display', 'none');
            $('#form_fields').append(clone_li);
            
            $(clone_li).find('fieldset legend span').html(number);
            $(clone_li).find('fieldset a').attr('lang', 'field'+number);
            
            $(clone_li).toggle(250);

        }
              
   });
   
    $('a.delete').live('click', function(event){
       
        event.preventDefault();
       
        $(this).parents('li').toggle('slow', function() {
                        
            if($('#form_fields li').length > 1){
                $(this).remove();
            }
       
            reorderFields();
            
        });
       
   });
    
});