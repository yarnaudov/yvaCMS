$(document).ready(function() {   

    // select all articles when one is checked
    $('.custom_articles').live('click', function(){
	$('.custom_articles[value='+$(this).val()+']').attr('checked', $(this).is(':checked'));
    });
    
});