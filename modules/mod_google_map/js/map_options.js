
var infowindow_content
var infowindows = new Array();
$.get(site_url + '/home/ajax/load?view=../../modules/mod_google_map/views/infowindow', function(data){
    infowindow_content = data;
});

var map;
var markers = new Array();

function initialize() {
    
    var map_zoom = parseInt(map_settings['zoom']);
    var map_lat  = map_settings['lat'];
    var map_lng  = map_settings['lng'];
    
    var mapOptions = {
        zoom: map_zoom,
        center: new google.maps.LatLng(map_lat, map_lng),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

    google.maps.event.addListenerOnce(map, 'idle', function(){
        
        // create markers from database
        $('#markers div').each(function(index){
            var marker_info = new Array();
            marker_info['lat']         = $(this).find('.marker_lat').val();
            marker_info['lng']         = $(this).find('.marker_lng').val();
            marker_info['title']       = $(this).find('.marker_title').val();
            marker_info['description'] = $(this).find('.marker_descr').val();
            marker_info['image']       = $(this).find('.marker_image').val();
            createMarker(marker_info, index);
        });
        
    });

}

google.maps.event.addDomListener(window, 'load', initialize);
   
   
function createMarker(marker_info, numb){
    
    var image = '';
    if(marker_info['image'] != '' && marker_info['image'] != undefined){
        image = base_url+marker_info['image'];
    }
    else if(map_settings['markers_image'] != '' && map_settings['markers_image'] != undefined){
        image = base_url+map_settings['markers_image'];
    }

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(marker_info['lat'], marker_info['lng']),
        map: map,
        icon: image,
        title: marker_info['title']
    });

    marker.id = numb;
    
    var content = document.createElement('div');
    $(content).append(infowindow_content);
    $(content).find('.title').html(marker_info['title']);
    $(content).find('.descr').html(marker_info['description']);
    content = $(content).html();
    
    infowindows[marker.id] = new google.maps.InfoWindow({
        content: content,
        maxWidth: 300
    });

    google.maps.event.addListener(marker, 'click', function() {

        infowindows[marker.id].open(map, marker);

    });
    
}
