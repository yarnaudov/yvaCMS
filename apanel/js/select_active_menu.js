$(document).ready(function() {
	      
    /*
     * check witch sub menu is selected and select the related main menu
     */
    try{
        
        var current_sub_menu = $('#sub_actions ul li.current a').attr('href');
        
        if(!current_sub_menu){
           var current_sub_menu = $('.actions a').attr('href');
        }
        
        var current_main_menu = current_sub_menu.split('/');
        //alert(current_main_menu);
        //current_main_menu = current_main_menu[current_main_menu.length-2];
        //alert(current_main_menu);
                
        var main_menus = $('#menu ul li a');
        
        $('#menu ul li a').each(function() {
          
          if($(this).attr('href').search(current_main_menu[current_main_menu.length-1]) != -1){
              $(this).parent().addClass('current');
          }
          else if($(this).attr('href').search(current_main_menu[current_main_menu.length-2]) != -1 && current_main_menu[current_main_menu.length-2].length > 2){
              $(this).parent().addClass('current');
          }
          
        });
        
    }
    catch(err){
        
    }
	          
});