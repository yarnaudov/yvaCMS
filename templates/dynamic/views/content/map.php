
<div id="map_canvas" style="width: 100%; height: 400px;"></div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
<script src="<?=base_url(TEMPLATES_DIR.'/dynamic/js/markerclusterer.js');?>"></script>
<script type="text/javascript" >
    $(function(){    

	var mapOptions = {
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	var markerBounds = new google.maps.LatLngBounds();
	
	var marker_position;
	var markers = new Array();
	var marker;

	<?php foreach($articles as $article){
		if(!isset($article['field4']->lat) || empty($article['field4']->lat)){
		    continue;
		} ?>
	
	    marker_position = new google.maps.LatLng(<?=$article['field4']->lat;?>, <?=$article['field4']->lng;?>);
	    
	    marker = new google.maps.Marker({
		position: marker_position,
		map: map,
		//icon: 'marker.png',
		title: '<?=$article['title'];?>'
	    });
	    
	    markers.push(marker);	    
	    markerBounds.extend(marker_position);
	
	<?php } ?>
	
	var markerCluster = new MarkerClusterer(map, markers);
	map.fitBounds(markerBounds);
	map.setCenter(markerBounds.getCenter());

    });
</script>