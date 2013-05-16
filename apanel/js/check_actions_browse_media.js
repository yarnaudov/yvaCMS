
$(document).ready(function() {

    $('.folder').bind('click', function(){
        $('input[name=previous]').val($('input[name=folder]').val());
        $('input[name=folder]').val($('input[name=folder]').val()+$(this).attr('lang'));
        $('form').submit();
    });
    
    $('#up').bind('click', function(){
        $('form').append('<input type="hidden" name="up" >');
        $('form').submit();
    });
                   
        
    $( '#dialog-edit1' ).dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['top', 100],
        buttons: {
            'OK': function() {
                $( this ).dialog( 'close' );
            }
        }
    });

    $( '#dialog-edit2' ).dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['top', 100],
        buttons: {
            'OK': function() {
                $( this ).dialog( 'close' );
            }
        }
    });
    
    $( '#dialog-delete' ).dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['top', 100],
        buttons: {
            'Ok': function() {
                $('form').append('<input type="hidden" name="delete" >');
                $('form').submit();
                $( this ).dialog( 'close' );
            },
            'Cancel': function(){
                $( this ).dialog( 'close' );
            }
        }
    });
    
    $( '#dialog-rename' ).dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        position: ['top', 100],
        buttons: {
            'Ok': function() {
                $('form').append('<input type="hidden" name="rename" >');
                $('form').append('<input type="hidden" name="new_name" value="'+$('#new_name').val()+'">');
                $('form').submit();
                $( this ).dialog( 'close' );                                
            },
            'Cancel': function(){
                $( this ).dialog( 'close' );
            }
        }
    });
                    
    $('a.delete').bind('click', function(){
        
        if($('.checkbox:checked').length == 0){

            $( '#dialog-edit1' ).dialog( 'open' );
            $( '.ui-widget-overlay' ).css('opacity', '0');
            return false;

        }

        $( '#dialog-delete' ).dialog( 'open' );
        $( '.ui-widget-overlay' ).css('opacity', '0');        
        return false;

    });
                    
    $('a.rename').bind('click', function(){
        if($('.checkbox:checked').length == 0){
            $( '#dialog-edit1' ).dialog( 'open' );
            $( '.ui-widget-overlay' ).css('opacity', '0');
            return false;
        }
        else if($('.checkbox:checked').length > 1){
            $( '#dialog-edit2' ).dialog( 'open' );
            $( '.ui-widget-overlay' ).css('opacity', '0');
            return false;
        }
        $( '#dialog-rename' ).dialog( 'open' );
        $( '.ui-widget-overlay' ).css('opacity', '0');
        $( '#new_name' ).val($('.checkbox:checked').val());
        return false;
    });
    
    $('a.select').bind('click', function(){
        
        if($('.checkbox:checked').length == 0){
            $( '#dialog-edit1' ).dialog( 'open' );
            $( '.ui-widget-overlay' ).css('opacity', '0');
            return false;
        }
        else if($('.checkbox:checked').length > 1){
            $( '#dialog-edit2' ).dialog( 'open' );
            $( '.ui-widget-overlay' ).css('opacity', '0');
            return false;
        }
        
        var media = $('input[name=folder]').val()+$('.checkbox:checked').val();

        var target = window.frameElement.getAttribute('target');
        
        if(target != null && parent.$('#'+target)){
            parent.$('#'+target).val(media);       
        }
        else if(parent.$('#media')){
            parent.$('#media').val(media);       
        }
        else{
            
            var ext = media.split('.');
            ext = ext[ext.length-1].toLowerCase();
            
            if(ext == 'swf' || ext == 'flv' || ext == 'f4v' || ext == 'f4p' || ext == 'f4a' || ext == 'f4b'){
            
                var html = '<object type="application/x-shockwave-flash" data="'+media+'" border="0" >';
                html = html+'    <param name="movie" value="'+media+'" >';
                html = html+'    <param name="allowscriptaccess" value="always" />';
                html = html+'</object>';
            
            }
            else if(ext == 'mp4'){
                var html = '<video controls="controls" src="'+media+'" >';
                html = html+'    Your browser does not support the video tag.';
                html = html+'</video>';
            }
            else{
                var html = '<img src="'+media+'" >';
            }
           
            parent.tinyMCE.execCommand('mceInsertContent', false, html);
            
        }
     
        parent.$( '#jquery_ui' ).dialog( 'close' ); 
        return false;
        
    });
    
    $('input.file').bind('change', function(){
        
        var files = new Array();
        
        $(this.files).each(function(){
            files.push(this.name);
        });
        
        $('input.text').val(files.join(','));
        
    });
    
});