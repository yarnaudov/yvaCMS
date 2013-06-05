 
var infowindow_content;
var infowindows = new Array();
$.get(site_url + '/home/ajax/load?view=../../modules/mod_google_map/views/infowindow', function(data){
    infowindow_content = data;
});


var map = new Array();
var markers = new Array();

function initialize(module_id) {
    
    infowindows[module_id] = new Array();
    
    var map_settings = $('#map_settings'+module_id);
    
    var map_zoom      = parseInt($(map_settings).find('.zoom').val());
    var map_lat       = $(map_settings).find('.lat').val();
    var map_lng       = $(map_settings).find('.lng').val();
    var markers_image = $(map_settings).find('.markers_image').val();
    
    var mapOptions = {
        zoom: map_zoom,
        center: new google.maps.LatLng(map_lat, map_lng),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    //console.log(mapOptions);
    map[module_id] = new google.maps.Map(document.getElementById('map_canvas'+module_id), mapOptions);
    //console.log(map[module_id]);
    
    google.maps.event.addListenerOnce(map[module_id], 'idle', function(){
        
        // create markers from database
        $('#map_settings'+module_id+' .markers div').each(function(index){
            var marker_info = new Array();
            marker_info['lat']         = $(this).find('.marker_lat').val();
            marker_info['lng']         = $(this).find('.marker_lng').val();
            marker_info['title']       = $(this).find('.marker_title').val();
            marker_info['description'] = $(this).find('.marker_descr').val();
            marker_info['image']       = $(this).find('.marker_image').val();
            createMarker(marker_info, index, module_id, markers_image);
        });
        
    });
   

}

google.maps.event.addDomListener(window, 'load', initialize);
   
   
function createMarker(marker_info, numb, module_id, markers_image){
    
    var image = '';
    if(marker_info['image'] != '' && marker_info['image'] != undefined){
        image = base_url+marker_info['image'];
    }
    else if(markers_image != '' && markers_image != undefined){
        image = base_url+markers_image;
    }

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(marker_info['lat'], marker_info['lng']),
        map: map[module_id],
        icon: image,
        title: marker_info['title']
    });

    marker.id = numb;
    
    var content = document.createElement('div');
    $(content).append(infowindow_content);
    $(content).find('.title').html(marker_info['title']);
    $(content).find('.descr').html(marker_info['description']);
    content = $(content).html();
    
    //console.log(module_id+' - module_id');
    
    infowindows[module_id][marker.id] = new google.maps.InfoWindow({
        content: content,
        maxWidth: 300
    });

    google.maps.event.addListener(marker, 'click', function() {

        infowindows[module_id][marker.id].open(map[module_id], marker);

    });
    
}
