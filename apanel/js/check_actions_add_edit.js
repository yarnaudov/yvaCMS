$(document).ready(function() {
        
    $('form[name=add]').validate({
        errorPlacement: function(error, element) {}
    });
    
    function categories_list(element, event){
	
	if($(element).is(':checked') == false && event.type == 'load'){
	   return; 
	}
	
        var value = $(element).val();
	
	$('.categories_list input:not([value^=most_])').attr('disabled', $(element).is(':checked'));
            
	if(value == 'most_popular' && $(element).is(':checked')){
	    $('.categories_list input[value=most_commented]').removeAttr('checked');
	}
	else if(value == 'most_commented' && $(element).is(':checked')){
	    $('.categories_list input[value=most_popular]').removeAttr('checked');
	}
        
    }
    
    $('.categories_list input[value^=most_]').live('click', function(event){
	categories_list(this, event);
    });
    
    $('.categories_list input[value^=most_]').on('load', function(event){
	categories_list(this, event);
    });

    $('.categories_list input[value^=most_]').trigger('load');
     
});

function create_datepicker(){
    $('.datepicker').datepicker({
	showOn: 'button',
	dateFormat: 'yy-mm-dd',
	buttonImage: base_url+'img/iconCalendar.png',
	buttonImageOnly: true
    });
}
