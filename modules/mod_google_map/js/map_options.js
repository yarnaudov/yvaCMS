//var infowindow;
//$.get(site_url + '/home/ajax/load?view=../../modules/mod_google_map/views/marker_options', function(data){
//    infowindow = new google.maps.InfoWindow({
//        content: data,
//        maxWidth: 400
//    });
//});

var map;
var markers = new Array();
var numb = 0;

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
        for(var i in map_settings['markers']){
            createMarker(map_settings['markers'][i]);
        }
        
    });

}

google.maps.event.addDomListener(window, 'load', initialize);
   
   
function createMarker(marker_info){
    
    var image = '';
    if(marker_info['image'] != ''){
        image = base_url+marker_info['image'];
    }
    
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(marker_info['lat'], marker_info['lng']),
        map: map,
        icon: image,
        draggable: true
    });
            
    google.maps.event.addListener(marker, 'click', function() {

        //infowindow.open(map, marker);

    });
    
}
