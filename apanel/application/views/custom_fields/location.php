<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_location');?>:</label></th>
    <td>
        
	<div id="map_canvas" style="height: 180px;"></div>
	
	<input type="hidden" name="params[zoom]" class="zoom" value="<?=set_value('params[zoom]', isset($params['zoom']) ? $params['zoom'] : "");?>" >
	<input type="hidden" name="params[lat]"  class="lat"  value="<?=set_value('params[lat]', isset($params['lat']) ? $params['lat'] : "");?>">
	<input type="hidden" name="params[lng]"  class="lng"  value="<?=set_value('params[lng]', isset($params['zoom']) ? $params['lng'] : "");?>" >
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
	<script type="text/javascript">
	    
	    function initialize() {

		if($("input.zoom").val() == "" || $("input.lat").val() == "" || $("input.lng").val() == ""){
		    map_zoom = 7;
		    map_lat  = 42.70311884052214;
		    map_lng  = 25.387890624999958;
		}
		else{
		    map_zoom = parseInt($("input.zoom").val());
		    map_lat  = $("input.lat").val();
		    map_lng  = $("input.lng").val();
		}

		var mapOptions = {
		    zoom: map_zoom,
		    center: new google.maps.LatLng(map_lat, map_lng),
		    mapTypeId: google.maps.MapTypeId.ROADMAP,
		    mapTypeControl: false
		};
	
		map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

		google.maps.event.addListenerOnce(map, "idle", function(){
		    
		    google.maps.event.trigger(map, "zoom_changed");
		    google.maps.event.trigger(map, "center_changed");


		});
		
		google.maps.event.addListener(map, 'zoom_changed', function() {    
		    $('input.zoom').val(map.getZoom());
		});
	
		google.maps.event.addListener(map, 'center_changed', function() {
		    var center = map.getCenter();
		    $('input.lat').val(center.lat());
		    $('input.lng').val(center.lng());
		});

	    }

	    google.maps.event.addDomListener(window, 'load', initialize);
	
	</script>
	
    </td>
</tr>