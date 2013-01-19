$(document).ready(function() {
    
    $( '#dialog:ui-dialog' ).dialog( 'destroy' );
                        
    $( '.jquery_ui_iframe' ).dialog({
        autoOpen: false,
        resizable: true,
        modal: true,
        position: ['top', 50],
        width: 'auto',
        close: function(event, ui){
            $('#jquery_ui_iframe').remove();
        }
    });
    
    $('.load_jquery_ui_iframe').bind('click', function() {    
        
        var id = $(this).attr('lang');
     
        var src = $( '#'+id ).attr('lang');
        
        if(src != undefined){
            
            var iframe = document.createElement("iframe");
            $(iframe).attr('src', src);
            $(iframe).attr('frameBorder', 0);
            $(iframe).attr('scrolling', 'no');
            $(iframe).attr('class', $(this).attr('lang'));
            $(iframe).attr('id', 'jquery_ui_iframe');
            $( '#'+id ).append(iframe);
            
        }
        
        $( '#'+id ).dialog( "open" );
        return false;
        
    });
    
    $('.clear_jquery_ui_inputs').bind('click', function(){
        $('.'+$(this).attr('lang')).val('');
        return false;
    });
        
    $('select.template').bind('change', function(){
        if($(this).val() == 'value'){
            $(this).css('display', 'none');
            $(this).attr('disabled', true);
            $('input.template').css('display', 'inline');
            $('input.template').attr('disabled', false);
            $('input.template').focus();
        }
    });
    
    $('input.template').bind('blur', function(){
        if($(this).val() == ''){
            $(this).css('display', 'none');
            $(this).attr('disabled', true);
            $('select.template').val('default');
            $('select.template').css('display', 'inline');
            $('select.template').attr('disabled', false);
        }
    });
    
    
    $('#tabs').tabs();
    $('.toggle').bind('click', function(){
        $('.display_menus').each(function(index){
            if($(this).attr('disabled') != 'disabled'){
                $(this).attr('checked', !$(this).attr('checked'));  
            }
        });
    });
    $('select[name=display_in]').bind('change', function(){
        if($(this).val() == 'all'){
            $('.display_menus').attr('checked', true).attr('disabled', true);
        }
        else if($(this).val() == 'on_selected'){
            $('.display_menus').removeAttr('disabled');
        }
        else if($(this).val() == 'all_except_selected'){
            $('.display_menus').removeAttr('disabled');
        }
    });
    $('select[name=display_in]').trigger('change');
    
});