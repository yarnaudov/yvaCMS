var infowindow;
$.get(site_url + '/home/ajax/load?view=../../modules/mod_google_map/views/marker_options', function(data){
    infowindow = new google.maps.InfoWindow({
        content: data,
        maxWidth: 400
    });
});

var map;
var markers = new Array();
var numb = 0;

function initialize() {
    
    var map_zoom = 7;
    var map_lat  = 42.70311884052214;
    var map_lng  = 25.387890624999958;
    if($('input.zoom').val()){
        map_zoom = parseInt($('input.zoom').val());
        map_lat  = $('input.lat').val();
        map_lng  = $('input.lng').val();
    }
    
    var mapOptions = {
        zoom: map_zoom,
        center: new google.maps.LatLng(map_lat, map_lng),
        mapTypeId: google.maps.MapTypeId.ROADMAP
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
        
        marker_data = new Array();
        marker_data['lat'] = e.latLng.lat();
        marker_data['lng'] = e.latLng.lng();
        marker_data['title'] = '';
        marker_data['descr'] = '';
        marker_data['image'] = '';
        
        createMarker(marker_data, true);
        
    });
  
    google.maps.event.addListenerOnce(map, 'idle', function(){
        google.maps.event.trigger(map, 'zoom_changed');
        google.maps.event.trigger(map, 'center_changed');
        
        // create markers from database
        for(var i in db_markers){
            createMarker(db_markers[i], false);
        }
        
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
    marker.set('descr', marker_info['descr']);
    marker.set('image', marker_info['image']);
    markers.push(marker);
    numb++;
    
    var marker_data = document.createElement('div');
    $(marker_data).addClass('marker');
    $(marker_data).attr('lang', marker.id);
    $(marker_data).append('<input type="text" name="params[markers]['+marker.id+'][lat]"   class="marker_lat"   value="'+marker.position.lat()+'" >');
    $(marker_data).append('<input type="text" name="params[markers]['+marker.id+'][lng]"   class="marker_lng"   value="'+marker.position.lng()+'" >');
    $(marker_data).append('<input type="text" name="params[markers]['+marker.id+'][title]" class="marker_title" value="'+marker.title+'" >');
    $(marker_data).append('<input type="text" name="params[markers]['+marker.id+'][descr]" class="marker_descr" value="'+marker.descr+'" >');
    $(marker_data).append('<input type="text" name="params[markers]['+marker.id+'][image]" class="marker_image" value="'+marker.image+'" >');
    $('#markers').append(marker_data);
            
    google.maps.event.addListener(marker, 'click', function() {

        var content = document.createElement('div');
        $(content).append(infowindow.content);
        $(content).find('#marker_numb').attr('value', marker.id);
        $(content).find('#marker_title').attr('value', marker.title);
        $(content).find('#marker_description').text(marker.descr);
        $(content).find('#media').attr('value', marker.image);
        infowindow.content = $(content).html();

        infowindow.open(map, marker);

    });
        
    if(open_infowindow == true){
        google.maps.event.trigger(marker, 'click');
    }
    
}
   
$('#save_marker').live('click', function(){
    
    var marker_numb = $('#marker_numb').val();
    
    var title       = $('#marker_title').val();
    var description = $('#marker_description').val();
    var image       = $('#media').val();
    
    var marker_div = $('#markers div.marker[lang='+marker_numb+']');
    $(marker_div).find('.marker_title').val(title);
    $(marker_div).find('.marker_descr').val(description);
    $(marker_div).find('.marker_image').val(image);
    
    markers[marker_numb].title       = title;
    markers[marker_numb].description = description;
    markers[marker_numb].image       = image;
    
    infowindow.close();
    
});

$('#delete_marker').live('click', function(){
    var marker_numb = $('#marker_numb').val();
    
    $('#markers div.marker[lang='+marker_numb+']').remove();
    
    markers[marker_numb].setMap(null);
    
});
