$(function(){

    function setImageWidth(){

        var width_100  = $('#jcrop_target').width();
        //alert(width_100);
        var width_real = $('#jcrop_target').attr('data-width');
        if(width_real < width_100){
            $('#jcrop_target').css('width', 'auto');
        }

    }
    setImageWidth();

    function refreshImage(image, timeout1, timeout2, tmp){

        jcrop_api.destroy();
        $('#jcrop_target').removeAttr('style');
        $('#jcrop_target').attr('data-width', image['width']);
        $('#jcrop_target').attr('data-height', image['height']);
        $('#jcrop_target').css('width', '100%');
        $('#jcrop_target').attr('src', base_url + '../' + image['src'] + '?' + new Date().getTime());
        $('#tmp').val(tmp);

        setTimeout(setImageWidth, timeout1);               
        setTimeout(initJcrop, timeout2);

    }


    var jcrop_api;    

    function initJcrop(){
             
        var aspectRatio  = parseInt($('#aspectRatio').val());

        var minWidth  = parseInt($('#min_width').val());
        var minHeight = parseInt($('#min_height').val());

        var maxWidth  = parseInt($('#max_width').val());
        var maxHeight = parseInt($('#max_height').val());

        var trueWidth  = parseInt($('#jcrop_target').attr('data-width'));
        var trueHeight = parseInt($('#jcrop_target').attr('data-height'));

        jcrop_api = $.Jcrop('#jcrop_target');
        jcrop_api.setOptions({
            onChange: saveCoordinates,
            onSelect: saveCoordinates,
            aspectRatio: aspectRatio,
            minSize: [minWidth, minHeight],
            maxSize: [maxWidth, maxHeight],
            trueSize: [trueWidth, trueHeight]
        });

        if(minWidth > 0 && minHeight > 0){
            jcrop_api.setOptions({ setSelect:  [0, 0, minWidth, minHeight] });      
        }

        $('.jcrop-holder').css('width', 'auto').css('background-color', 'transparent');
        
    }
                
    setTimeout(initJcrop, 500);
     
    function saveCoordinates(coords){
        
        $('#x').val(coords.x);
        $('#y').val(coords.y);
        $('#x2').val(coords.x2);
        $('#y2').val(coords.y2);
        $('#w').val(Math.round(coords.w));
        $('#h').val(Math.round(coords.h));

    }

    $('button.origin').click(function(){
        
        var url = $(this).attr('lang');
        var id  = $(this).attr('desc');

        $.post(url, {id: id}, function(data){
            var image = JSON.parse(data);
            refreshImage(image, 300, 500, 1);           
        });

    });
                
    $('#aspectRatio').change(function(){

        var ar = $(this).find('option:selected').text().split('/');
        jcrop_api.setOptions({ aspectRatio:  parseInt(ar[0])/parseInt(ar[1]) });        
        jcrop_api.focus();

    });
                
    $('button.crop').click(function(event){
        
        event.preventDefault();
        
        var url   = $(this).attr('lang');
        var image = $('#jcrop_target').attr('src');
        var x     = $('#x').val();
        var y     = $('#y').val();
        var w     = $('#w').val();
        var h     = $('#h').val();
        
        if(w <= 0 || h <= 0){
            return false;
        }

        $.post(url, {image: image, x: x, y: y, w: w, h: h}, function(data){
            var image = JSON.parse(data);
            refreshImage(image, 300, 500, 1);
             $('#w').val('');
             $('#h').val('');
        });
        
    });

    $('button.rotate').click(function(event){
        
        event.preventDefault();
        
        var url       = $(this).attr('data-url');
        var image_src = $('#jcrop_target').attr('src');
        var degrees   = $('#degrees').val();
        
        $.post(url, {image_src: image_src, degrees: degrees}, function(data){
            var image = JSON.parse(data);
            refreshImage(image, 0, 100, 1);
        });
        
    });

    // change image
    var form_origin_action = $('form[name=add]').attr('action');    
    $('#btn_change_image').click(function(){

        var form_action = $(this).attr('data-url');

        if($('input[name=file]').val() == ''){
            return false;
        }

        $('body').append('<iframe id="iframe_change_image" name="iframe_change_image" ><iframe>');
	
        $('#iframe_change_image').load(function(){
            //alert('done uploading image');
            var data = $(this).contents().find('span').html();
            $(this).remove();
            $('form[name=add]').attr('target', '');
            $('form[name=add]').attr('action', form_origin_action);
            var image = JSON.parse(data);
            refreshImage(image, 0, 100, 2);
	    $('form[name=add]').append('<input type="hidden" name="tmp_file_ext" value="'+image['ext']+'" >');
            $('button.change').trigger('click');           

        });
	
        $('form[name=add]').attr('target', 'iframe_change_image');
        $('form[name=add]').attr('action', form_action);
        $('form[name=add]').submit();


    });

    $('button.change').click(function(){

        $('.file_conteiner').toggle('slow', function(){
            if($(this).css('display') == 'none'){
                $('.input_file input').val('');
            }
        });


    });

});