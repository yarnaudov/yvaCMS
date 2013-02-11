var contentString;
$.get(site_url + '/home/ajax/load?view=../../modules/mod_google_map/views/marker_options', function(data){
    contentString = data;
});

var map;
var markers = new Array();
var numb = 0;

function initialize() {
    
    var mapOptions = {
        zoom: 7,
        center: new google.maps.LatLng(42.70311884052214, 25.387890624999958),
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
        
        var marker = new google.maps.Marker({
          position: e.latLng,
          map: map,
          draggable: true
        });
        
        marker.set('id', numb);
        markers.push(marker);
        numb++;
            
        google.maps.event.addListener(marker, 'click', function() {

            var content = document.createElement('div');
            $(content).append(contentString);
            $(content).find('#marker_numb').attr('value', marker.id);

            var infowindow = new google.maps.InfoWindow({
                content: content,
                maxWidth: 400
            });
            infowindow.open(map, marker);

        });
        
    });
  
    google.maps.event.addListenerOnce(map, 'idle', function(){
        google.maps.event.trigger(map, 'zoom_changed');
        google.maps.event.trigger(map, 'center_changed');
    });

}

google.maps.event.addDomListener(window, 'load', initialize);
            
$('#save_marker').live('click', function(){
    
    var title       = $('#marker_title').val();
    var description = $('#marker_description').val();
    var image       = $('#media').val();
    
    //infowindow.close(map, marker);
    
});

$('#delete_marker').live('click', function(){
    var marker_numb = $('#marker_numb').val();
    markers[marker_numb].setMap(null);
});
