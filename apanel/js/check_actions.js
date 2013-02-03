$(document).ready(function() {
	  
    $( "#dialog:ui-dialog" ).dialog( "destroy" );

    /*
     * create UI dialogs
     */
    $( "#dialog-edit1" ).dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['top', 100],
        buttons: {
            "OK": function() {
                $( this ).dialog( "close" );
            }
        }
    });
	  
    $( "#dialog-edit2" ).dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['top', 100],
        buttons: {
            "OK": function() {
                $( this ).dialog( "close" );
            }
        }
    });
	  
    $( "#dialog-delete" ).dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['top', 100],
        buttons: {
            "Ok": function() {
                $('form').append('<input type="hidden" name="delete" >');
                $('form').submit();
                $( this ).dialog( "close" );
            },
            "Cancel": function(){
                $( this ).dialog( "close" );
            }
        }
    });
	  	  
    $('a.edit').live('click', function(){

        if($(".checkbox:checked").length == 0){
            $( "#dialog-edit1" ).dialog( "open" );
            return false;
        }
        else if($(".checkbox:checked").length > 1){
            $( "#dialog-edit2" ).dialog( "open" );
            return false;
        }
        
        var href = $(this).attr("href");        
        href = href.replace('edit', 'edit/'+$(".checkbox:checked").val());
        
        $(this).attr("href", href);
        return true;

    });
		
    $('a.delete').live('click', function(){

        if($(".checkbox:checked").length == 0){

            $( "#dialog-edit1" ).dialog( "open" );
            return false;

        }

        $( "#dialog-delete" ).dialog( "open" );
        return false;

    });
    
    
    /*
     * add onclick event to checkboxes and add class 'selected' to table row if the checkbox is checked 
     */
    $(".checkbox").bind('click', function(){
    
       if($(this).is(':checked') == true){
           $(this).parents('tr').addClass('selected');
       }
       else{
           $(this).parents('tr').removeClass('selected');
       }
       
    });
    
    
    /*
     * add onhover event to table rows and add class 'highlight'
     */
    $(".row").hover(
        function(){
            $(this).addClass('highlight');
        },
        function(){
            $(this).removeClass('highlight');
        }
    );
    
    
    /*
     * add onchange event to filter select elements
     */
    $('.filter select').bind('change', function(){
        $('button[name=search]').trigger('click');
    });
    
    
    /*
     * add onclick event to status images
     */
    $('.status_img').bind('click', function(){
        
        var element_id = $(this).parents('tr').find('.checkbox').val();
        var status     = $(this).attr('alt');
            
        $('form').append('<input type="hidden" name="element_id" value="'+element_id+'" >');
        $('form').append('<input type="hidden" name="change_status" value="'+status+'" >');
        $('form').submit();
        
    });
    
    
    /*
     * add onclick event to order images
     */
    $('.order_img').bind('click', function(){
        
        var element_id = $(this).parents('tr').find('.checkbox').val();
        var order = $(this).attr('alt');
            
        $('form').append('<input type="hidden" name="element_id" value="'+element_id+'" >');
        $('form').append('<input type="hidden" name="change_order" value="'+order+'" >');
        $('form').submit();
        
    });
    
    
    /*
     * add onclick event to table header for ordering the results
     */
    $('.sortable').bind('click', function(){                
        $('form').append('<input type="hidden" name="order_by" value="'+$(this).attr('id')+'" >');        
        $('form').submit();        
    });
    
    
    /*
     * add onchange event to results per page select
     */
    $('select[name=page_results]').bind('change', function(){
        $('form').append('<input type="hidden" name="limit" value="'+$(this).attr('id')+'" >');
        $('form').submit();
    });
           
		
});