
$(document).ready(function() {
    
    
    $('input[name^=answers]').live('click, change', function(){
    	  
    	  if($('input[name^=answers]:checked').length == 1){
    	      $(this).parents('ul').find('button[name=vote]').removeAttr('disabled');
    	  }
    	  
    });
    $('input[name^=answers]').trigger('change');
    
    
    $('button[name=vote]').live('click', function(event){

        event.preventDefault();

        $(this).parents('li').find('div.actions').css('display', 'none');
        $(this).parents('li').find('div.loading').css('display', 'inline');

        var form      = $(this).parents('form');
        var answer    = $(form).find('input[type=radio]:checked');

        var answer_id = $(answer).val();		  	
        var poll_id   = $(answer).attr('class');		  	  	
        var url       = $(form).attr('action');

        $.post(url, { poll_id: poll_id, answer_id: answer_id }, function(data) {

            $.post(url, function(data){		      			      	

                $('ul.poll'+poll_id).html($('ul.poll'+poll_id, data).html());

            });

        });	  	

    });
	  
	  
    $('a.show_votes').live('click', function(event){

        event.preventDefault();

        $(this).parents('li').find('div.actions').css('display', 'none');
        $(this).parents('li').find('div.loading').css('display', 'inline');

        var poll_class = $(this).parents('ul').attr('class');

        $.post($(this).attr('href'), function(data){		      			      	
   
            $('ul.'+poll_class).html($('ul.'+poll_class, data).html());

        });

    });
	  
	  
    $('a.hide_votes').live('click', function(event){

        event.preventDefault();

        $(this).parents('li').find('div.actions').css('display', 'none');
        $(this).parents('li').find('div.loading').css('display', 'inline');

        var poll_class = $(this).parents('ul').attr('class');

        $.post($(this).attr('href'), function(data){		      			      	

            $('ul.'+poll_class).html($('ul.'+poll_class, data).html());

        });

    });
	  
    
});