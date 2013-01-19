
$(document).ready(function() {
    
    
    $('.prev, .next, .image').live('click', function(event) {
        showImage(this.href, true);
        return false;       
    });
    
    
    //$(window).bind('popstate', function() {
    //    alert('window loaded!');
    //    showImage(location.pathname, false);
    //});
    
    $(window).bind('keyup', function(event) {
        
        if(event.which == 37){
            showImage($('.navigation .prev').attr('href'), true);
        }
        else if(event.which == 39){
            showImage($('.navigation .next').attr('href'), true);
        }
        
    });
    
    
    $('#big_image').load(function(){
        //alert('image loaded!');
        $('a.image img').attr('src', $(this).attr('src'));
    });
    $('#big_image').trigger('load');
    
    function showImage(url, pushHistory)
    {

        if(pushHistory == true){
            url = url.split('#')[0];
            history.pushState({ path: this.path }, '', url);
        }
        
        $.get(url, function(data) {  
            
            $('.navigation').html($('.navigation', data).html());
            $('.description').html($('.description', data).html());
            
            $('a.image').attr('href', $('a.image', data).attr('href'));
            
            var src = $('a.image img', data).attr('src');
            $('a.image img').attr('src', src);            
            $('#big_image').attr('src', src.replace('images/thumbs/', 'images/'));

        });
    }
    
    
});