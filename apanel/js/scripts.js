$(document).ready(function() {   
    
    $('.custom_articles').live('click', function(){
	$('.custom_articles[value='+$(this).val()+']').attr('checked', $(this).is(':checked'));
    });
    
});