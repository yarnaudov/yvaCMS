
    <input id="search_v" type="text" name="search_v">
    <select id="category" name="field2">
	<option selected="" value="none">- Избери категория -</option>
	<option value="1">Стоматолози</option>
	<option value="2">Зъботехници</option>
	<option value="3">Ветеринари</option>	
    </select>
    <select id="city" name="field2">
	<option selected="" value="none">- Избери град -</option>
	<option value="1">София</option>
	<option value="0">Пловдив</option>
	<option value="2">Варна</option>
	<option value="3">Бургас</option>
	<option value="4">Благоевград</option>
	<option value="5">Видин</option>
	<option value="6">Монтана</option>
	<option value="7">Враца</option>
	<option value="8">Плевен</option>
	<option value="9">Велико Търново</option>
	<option value="10">Русе</option>
	<option value="11">Силистра</option>
	<option value="12">Добрич</option>
	<option value="13">Шумен</option>
	<option value="14">Разград</option>
	<option value="15">Търговище</option>
	<option value="16">Габрово</option>
	<option value="17">Ловеч</option>
	<option value="18">Перник</option>
	<option value="19">Кюстендил</option>
	<option value="20">Пазарджик</option>
	<option value="21">Стара Загора</option>
	<option value="22">Сливен</option>
	<option value="23">Ямбол</option>
	<option value="24">Хасково</option>
	<option value="25">Кърджали</option>
	<option value="26">Смолян</option>
    </select>
    <button class="search_map" >Търсене</button>

<div id="map_canvas" style="width: 100%; height: 400px;"></div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
<script src="<?php echo base_url(TEMPLATES_DIR.'/dynamic/js/markerclusterer.js');?>"></script>
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
	var markerCluster;

	<?php foreach($articles as $article){
		if(!isset($article['field4']->lat) || empty($article['field4']->lat)){
		    continue;
		} ?>
	
	    marker_position = new google.maps.LatLng(<?php echo $article['field4']->lat;?>, <?php echo $article['field4']->lng;?>);
	    
	    marker = new google.maps.Marker({
		position: marker_position,
		map: map,
		city: <?php echo $article['field2'];?>,
		category: <?php echo $article['category_id'];?>,
		//icon: 'marker.png',
		title: '<?php echo $article['title'];?>'
	    });
	    
	    markers.push(marker);	    
	    markerBounds.extend(marker_position);
	
	<?php } ?>
	
	markerCluster = new MarkerClusterer(map, markers);
	map.fitBounds(markerBounds);
	map.setCenter(markerBounds.getCenter());

	$('.search_map').on('click', function(event){
	    
	    event.preventDefault();
	    
	    var search_v = $('#search_v').val();
	    var city     = $('#city').val();
	    var category = $('#category').val();
	    
	    var new_markers = new Array();
	    var new_markerBounds = new google.maps.LatLngBounds();
	    
	    $(markers).each(function(index){
				
		if(city != 'none' && markers[index].city != city){
		    return;
		}

		if(category != 'none' && markers[index].category != category){
		    return;
		}

		if(search_v != '' && markers[index].title.search(new RegExp(search_v, "i")) == -1){
		    return;
		}
		
		new_markers.push(markers[index]);
		new_markerBounds.extend(markers[index].position);

	    });
	    
	    markerCluster.clearMarkers();
	    markerCluster = new MarkerClusterer(map, new_markers);
	    
	    map.fitBounds(new_markerBounds);
	    map.setCenter(new_markerBounds.getCenter());
	    
	});

    });
</script>