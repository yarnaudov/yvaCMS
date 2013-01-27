
$(document).ready(function() {
    
   function replaceAll( text, old_string, new_string ){
   while (text.toString().indexOf(old_string) != -1)
       text = text.toString().replace(old_string, new_string);
       return text;
   }

   $('a.add').bind('click', function(){
       var html = $('tbody#field0').html();
       
       var number = $('table.form_fields tbody').length;
              
       html = replaceAll(html, 'fields[0]', 'fields['+number+']');
              
       $('table.form_fields').append('<tbody id="field'+number+'" >'+html+'</tbody>');
       $('tbody#field'+number+' fieldset legend span').html(number);
       $('tbody#field'+number+' fieldset a').attr('lang', 'field'+number);
              
   });
   
   $('a.delete').live('click', function(){
       $('tbody#'+$(this).attr('lang')).remove();
       
       $('table.form_fields tbody').each(function(index){
                      
           var html = $(this).html();
           
           $(this).attr('id', 'field'+index);
           
           var number = $('tbody#field'+index+' fieldset legend span').html();
           number = parseInt(number);
           
           if(number != index){
               html = replaceAll(html, 'fields['+number+']', 'fields['+index+']');
               $(this).html(html);
           }
           
           
           $('tbody#field'+index+' fieldset legend span').html(index);
           $('tbody#field'+index+' fieldset a').attr('lang', 'field'+index);
           
       });
       
   });
    
});