
    var map = new Array();
    var markers = new Array();
   
    function initialize(id, marker) {

	if($('input.zoom'+id).val() == '' || $('input.lat'+id).val() == '' || $('input.lng'+id).val() == ''){
	    map_zoom = 7;
	    map_lat  = 42.70311884052214;
	    map_lng  = 25.387890624999958;
	}
	else{
	    map_zoom = parseInt($('input.zoom'+id).val());
	    map_lat  = $('input.lat'+id).val();
	    map_lng  = $('input.lng'+id).val();
	}

	var mapOptions = {
	    zoom: map_zoom,
	    center: new google.maps.LatLng(map_lat, map_lng),
	    mapTypeId: google.maps.MapTypeId.ROADMAP,
	    mapTypeControl: false
	};
	
	map[id] = new google.maps.Map(document.getElementById('map_canvas'+id), mapOptions);

	google.maps.event.addListenerOnce(map[id], 'idle', function(){

	    google.maps.event.trigger(map[id], 'zoom_changed');
	    google.maps.event.trigger(map[id], 'center_changed');
	    
	    if(marker == true && $('input.lat'+id).val() != '' && $('input.lng'+id).val() != ''){
		markers[id] = new google.maps.Marker({
		    position: new google.maps.LatLng($('input.lat'+id).val(), $('input.lng'+id).val()),
		    map: map[id]
		});
	    }


	});
		
	google.maps.event.addListener(map[id], 'zoom_changed', function() {    
	    $('input.zoom'+id).val(map[id].getZoom());
	});
	
	if(marker == true){
	
	    google.maps.event.addListener(map[id], 'click', function(e) {

		$('input.lat'+id).val(e.latLng.lat());
		$('input.lng'+id).val(e.latLng.lng());

		if(markers[id] != undefined){
		    markers[id].setMap(null);
		}

		markers[id] = new google.maps.Marker({
		    position: new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()),
		    map: map[id]
		});

	    });
	
	}
	else{
	    
	    google.maps.event.addListener(map[id], 'center_changed', function() {
		var center = map[id].getCenter();
		$('input.lat'+id).val(center.lat());
		$('input.lng'+id).val(center.lng());
	    });
	
	}

    }
