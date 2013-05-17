$(function(){

    function setImageWidth(){

        var width_100  = $('#jcrop_target').width();
        //alert(width_100);
        var width_real = $('#jcrop_target').attr('lang');
        if(width_real < width_100){
            $('#jcrop_target').css('width', 'auto');
        }

    }
    setImageWidth();


    var jcrop_api;    

    function initJcrop(){
             
        var aspectRatio  = parseInt($('#aspectRatio').val());

        var minWidth  = parseInt($('#min_width').val());
        var minHeight = parseInt($('#min_height').val());

        var maxWidth  = parseInt($('#max_width').val());
        var maxHeight = parseInt($('#max_height').val());

        jcrop_api = $.Jcrop('#jcrop_target');
        jcrop_api.setOptions({
            onChange: showPreview,
            onSelect: showPreview,
            onRelease: hidePreview,
            aspectRatio: aspectRatio,
            minSize: [minWidth, minHeight],
            maxSize: [maxWidth, maxHeight]
        });

        if(minWidth > 0 && minHeight > 0){
            jcrop_api.setOptions({ setSelect:  [0, 0, minWidth, minHeight] });      
        }

        $('.jcrop-holder').css('width', 'auto').css('background-color', 'transparent');
        
    }
                
    setTimeout(initJcrop, 500);
                
    $('button.origin').click(function(){
        
        var url = $(this).attr('lang');
        var id  = $(this).attr('desc');
        var size = $(this).attr('size');

        alert(url);

        $.post(url, {id: id}, function(data){
            //if(data['success'] == true){
                alert(data);
                jcrop_api.destroy();
                $('#jcrop_target').removeAttr('style');
                $('#jcrop_target').attr('lang', size);
                $('#jcrop_target').css('width', '100%');
                $('#jcrop_target').attr('src', base_url + '../' + data + '?' + new Date().getTime());
                $('#tmp').val(1);

                setTimeout(setImageWidth, 300);               
                setTimeout(initJcrop, 500);

            //}
        });

    });
                
    $('#aspectRatio').change(function(){               

        var ar = $(this).find('option:selected').text().split('/');
        jcrop_api.setOptions({ aspectRatio:  parseInt(ar[0])/parseInt(ar[1]) });        
        jcrop_api.focus();

    });
               
    var $preview = $('#preview');
    var preview_width_default = $('#preview').width();
    // Our simple event handler, called from onChange and onSelect
    // event handlers, as per the Jcrop invocation above
    function showPreview(coords){
        
        var aspect_ratio = coords.w/coords.h;
        
        $('#x').val(coords.x);
        $('#y').val(coords.y);
        $('#x2').val(coords.x2);
        $('#y2').val(coords.y2);
        $('#w').val(coords.w);
        $('#h').val(coords.h);

        if (parseInt(coords.w) > 0){
            
            var preview_width  = preview_width_default;
            var preview_height = Math.round(preview_width/aspect_ratio);
        
            if(preview_height > preview_width){
                preview_height = preview_width;
                preview_width  = Math.round(preview_height*aspect_ratio);
            }
            
            $preview.parent().width(preview_width);
            $preview.parent().height(preview_height);

            var rx = preview_width / coords.w;
            var ry = preview_height / coords.h;

            $preview.css({
              width: Math.round(rx * $('#jcrop_target').width() ) + 'px',
              height: Math.round(ry * $('#jcrop_target').height() ) + 'px',
              marginLeft: '-' + Math.round(rx * coords.x) + 'px',
              marginTop: '-' + Math.round(ry * coords.y) + 'px'
            }).show();

        }

    }

    function hidePreview(){
        $preview.stop().fadeOut('fast');
    }
                
    $('button.crop').click(function(event){
        
        event.preventDefault();
        
        var url   = $(this).attr('lang');
        var image = $('#jcrop_target').attr('src');
        var x     = $('#x').val();
        var y     = $('#y').val();
        var w     = $('#w').val();
        var h     = $('#h').val();
        
        $.post(url, {image: image, x: x, y: y, w: w, h: h}, function(data){
            //if(data['success'] == true){
                //alert(base_url + '../' + data);
                jcrop_api.destroy();
                $('#jcrop_target').removeAttr('style');
                $('#jcrop_target').attr('src', base_url + '../' + data + '?' + new Date().getTime());                        
                setTimeout(initJcrop, 500);
                $('#tmp').val(1);
            //}
        });
        
    });

    $('button.rotate').click(function(event){
        
        event.preventDefault();
        
        var url     = $(this).attr('lang');
        var image   = $('#jcrop_target').attr('src');
        var degrees = $('#degrees').val();
        
        $.post(url, {image: image, degrees: degrees}, function(data){
            //if(data['success'] == true){
                //alert(base_url + '../' + data);
                jcrop_api.destroy();
                //$('#jcrop_target').removeAttr('style');
                //$('#jcrop_target').attr('lang', $(this).attr('desc'));
                $('#jcrop_target').css('width', '100%');
                $('#tmp').val(1);
                $('#jcrop_target').attr('src', base_url + '../' + data + '?' + new Date().getTime());
                setImageWidth();                    
                setTimeout(initJcrop, 500);
            //}
        });
        
    });

});