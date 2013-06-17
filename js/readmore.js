$(document).ready(function() {
	
	  $('a.readmore').bind('click', function(event) {
	  	  
	  	  event.preventDefault();
	  	  
	  	  var readmore_link = this;
	  	  var readmore_hide_link = $('a.readmore_hide[href='+$(this).attr('href')+']');
	  	  
	  	  $('#'+$(this).attr('href')).show('slow', function(){
	  	  	  $(readmore_link).hide('slow');
	  	  	  $(readmore_hide_link).show('slow');
	  	  });
	  	  	  	  
	  });
	
	  $('a.readmore_hide').bind('click', function(event) {
	  	  
	  	  event.preventDefault();
	  	  
	  	  var readmore_link = $('a[href='+$(this).attr('href')+']');
	  	  var readmore_hide_link = this;
	  	  
	  	  $('#'+$(this).attr('href')).hide('slow', function(){
	  	  	  $(readmore_link).show('slow');
	  	  	  $(readmore_hide_link).hide('slow');
	  	  });
	  	  
	  });
	
});