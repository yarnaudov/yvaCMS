
$(function(){

    $( "#dialog:ui-dialog" ).dialog( "destroy" );

    $( "#marker_info" ).dialog({
        autoOpen: false,
        modal: true,
        width: 'auto',
        position: ['top', 100]
    });

    var map;
    var markers = new Array();
    var numb = 0;

    var map_zoom     = parseInt($('input.zoom').val());
    var map_lat      = $('input.lat').val();
    var map_lng      = $('input.lng').val();
    var markers_icon = $('input.markers_image').val();

    function initialize() {

        if(map_lat == ''){
            map_zoom = 7;
            map_lat  = 42.70311884052214;
            map_lng  = 25.387890624999958;
        }

        var mapOptions = {
            zoom: map_zoom,
            center: new google.maps.LatLng(map_lat, map_lng),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            //scrollwheel: false,
            mapTypeControl: false
        };
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

        google.maps.event.addListener(map, 'zoom_changed', function() {    
            $('input.zoom').val(map.getZoom());
        });

        google.maps.event.addListener(map, 'center_changed', function() {
            var center = map.getCenter();
            $('input.lat').val(center.lat());
            $('input.lng').val(center.lng());
        });

        google.maps.event.addListener(map, 'click', function(e) {

            //marker_data = new Array();
            //marker_data['lat']         = e.latLng.lat();
            //marker_data['lng']         = e.latLng.lng();
            //marker_data['title']       = '';
            //marker_data['description'] = '';
            //marker_data['image']       = '';

            $( "#marker_info" ).dialog('open');

            $('#position strong').first().html(e.latLng.lat());
            $('#position strong').last().html(e.latLng.lng());
            $('#marker_numb').attr('value', '');
            $('#marker_title').attr('value', '');
            tinyMCE.activeEditor.setContent('');
            $('#marker_image').attr('value', '');

            //createMarker(marker_data, true);

        });

        google.maps.event.addListenerOnce(map, 'idle', function(){
            google.maps.event.trigger(map, 'zoom_changed');
            google.maps.event.trigger(map, 'center_changed');

            // create markers from database
            $('#markers div').each(function(){
                var marker_info = new Array();
                marker_info['lat']         = $(this).find('.marker_lat').val();
                marker_info['lng']         = $(this).find('.marker_lng').val();
                marker_info['title']       = $(this).find('.marker_title').val();
                marker_info['description'] = $(this).find('.marker_descr').val();
                marker_info['image']       = $(this).find('.marker_image').val();
                createMarker(marker_info, false);
            });

        });

    }

    google.maps.event.addDomListener(window, 'load', initialize);


    function createMarker(marker_info, open_infowindow){

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(marker_info['lat'], marker_info['lng']),
            map: map,
            draggable: true
        });

        marker.set('id', numb);
        marker.set('title', marker_info['title']);
        marker.set('descr', marker_info['description']);
        marker.set('image', marker_info['image']);

        if(marker_info['image'] != ''){
            marker.set('icon', base_url+'../'+marker_info['image']);
        }
        else if(markers_icon != ''){
            marker.set('icon', base_url+'../'+markers_icon);
        }

        markers.push(marker);
        numb++;

        if(open_infowindow == true){

            var marker_data = document.createElement('div');
            $(marker_data).addClass('marker');
            $(marker_data).attr('lang', marker.id);
            $(marker_data).append('<input type="text" name="markers['+marker.id+'][lat]"         class="marker_lat"   value="'+marker.position.lat()+'" >');
            $(marker_data).append('<input type="text" name="markers['+marker.id+'][lng]"         class="marker_lng"   value="'+marker.position.lng()+'" >');
            $(marker_data).append('<input type="text" name="markers['+marker.id+'][title]"       class="marker_title" value="'+marker.title+'" >');
            $(marker_data).append('<textarea          name="markers['+marker.id+'][description]" class="marker_descr" >'+marker.descr+'</textarea>');
            $(marker_data).append('<input type="text" name="markers['+marker.id+'][image]"       class="marker_image" value="'+marker.image+'" >');
            $('#markers').append(marker_data);

        }

        google.maps.event.addListener(marker, 'click', function() {

            $( "#marker_info" ).dialog('open');

            $('#position strong').first().html(marker.position.lat());
            $('#position strong').last().html(marker.position.lng());
            $('#marker_numb').attr('value', marker.id);
            $('#marker_title').attr('value', marker.title);
            tinyMCE.activeEditor.setContent(marker.descr);
            $('#marker_image').attr('value', marker.image);

        });

        google.maps.event.addListener(marker, 'dragend', function() {
            var position = marker.getPosition();
            var marker_div = $('#markers div.marker[lang='+marker.id+']');
            $(marker_div).find('.marker_lat').val(position.lat());
            $(marker_div).find('.marker_lng').val(position.lng());
            $('#position strong').first().html(position.lat());
            $('#position strong').last().html(position.lng());
        });


        if(open_infowindow == true){
            google.maps.event.trigger(marker, 'click');
        }

    }

    $('#save_marker').live('click', function(){

        //console.log('save marker');

        var marker_numb = $('#marker_numb').val();
        
        //console.log('save marker step 1');

        var title = $('#marker_title').val();
        var descr = tinyMCE.activeEditor.getContent().replace('/"/gi', "'");
        var image = $('#marker_image').val();

        //console.log('marker_numb'+marker_numb);

        if(marker_numb == ''){

            //console.log('marker_numb: '+marker_numb);

            marker_data = new Array();
            marker_data['lat']         = $('#position strong').first().html();
            marker_data['lng']         = $('#position strong').last().html();
            marker_data['title']       = title;
            marker_data['description'] = descr;
            marker_data['image']       = image;

            marker_numb = numb;
            createMarker(marker_data, true);

            //console.log('marker_numb: '+marker_numb);

        }
        else{

            var marker_div = $('#markers div.marker[lang='+marker_numb+']');
            $(marker_div).find('.marker_title').val(title);
            $(marker_div).find('.marker_descr').val(descr);
            $(marker_div).find('.marker_image').val(image);

            markers[marker_numb].title = title;
            markers[marker_numb].descr = descr;
            markers[marker_numb].image = image;

        }

        if(image != ''){
            image = base_url+'../'+image;
        }
        else if(markers_icon != ''){
            image = base_url+'../'+markers_icon;
        }
        
        markers[marker_numb].set('icon', image);

        $( "#marker_info" ).dialog('close');

    });

    $('#delete_marker').live('click', function(event){

        event.preventDefault();

        var marker_numb = $('#marker_numb').val();
        markers[marker_numb].setMap(null);
        var marker_div = $('#markers div.marker[lang='+marker_numb+']');
        $(marker_div).remove();        

        $( "#marker_info" ).dialog('close');

    });

    
    $('#cancel_marker').live('click', function(event){

        event.preventDefault();

        $( "#marker_info" ).dialog('close');

    });
    
    $('.markers_image').click(function(){
               
        var image = $(this).val();        
        markers_icon = image;

        for(var i in markers){
            
            if(markers[i].image != ''){
                image = base_url+'../'+markers[i].image;
            }
            else if(image != ''){
                image = base_url+'../'+$(this).val();
            }
            
            markers[i].set('icon', image);
            
        }

    });
        
    tinyMCE.init({
        mode : "specific_textareas",
        editor_selector : "marker_description",
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        height: '100',
        width: '350'
    });

    $('#map_fullscreen').click(function(event){

        event.preventDefault();

        $('#map_main_content').attr('style', 'position: fixed; width: 100%; height: 100%; overflow: hidden;top: 0;left: 0;z-index: 100;');
        $('#map_canvas').css('height', '100%').css('width', '100%');

        $('#map_fullscreen_exit').css('display', 'inline');
        $('#map_fullscreen').css('display', 'none');
        $("body").css("overflow", "hidden");

        initialize();

    });

    $('#map_fullscreen_exit').click(function(event){

        event.preventDefault();

        $('#map_main_content').removeAttr('style');
        $('#map_canvas').css('height', '').css('width', '');

        $('#map_fullscreen_exit').css('display', 'none');
        $('#map_fullscreen').css('display', 'inline');
        $("body").css("overflow", "");

        initialize();

    });

});
