
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
        var pull_id   = $(answer).attr('class');		  	  	
        var url       = $(form).attr('action');

        var current_url = location.pathname;

        $.post(url, { pull_id: pull_id, answer_id: answer_id }, function(data) {

            $.post(current_url, function(data){		      			      	

                $('ul.pull'+pull_id).html($('ul.pull'+pull_id, data).html());

            });

        });	  	

    });
	  
	  
    $('a.show_votes').live('click', function(event){

        event.preventDefault();

        $(this).parents('li').find('div.actions').css('display', 'none');
        $(this).parents('li').find('div.loading').css('display', 'inline');

        var pull_class = $(this).parents('ul').attr('class');

        $.post($(this).attr('href'), function(data){		      			      	
   
            $('ul.'+pull_class).html($('ul.'+pull_class, data).html());

        });

    });
	  
	  
    $('a.hide_votes').live('click', function(event){

        event.preventDefault();

        $(this).parents('li').find('div.actions').css('display', 'none');
        $(this).parents('li').find('div.loading').css('display', 'inline');

        var pull_class = $(this).parents('ul').attr('class');

        $.post($(this).attr('href'), function(data){		      			      	

            $('ul.'+pull_class).html($('ul.'+pull_class, data).html());

        });

    });
	  
    
});