<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_location');?>:</label></th>
    <td>
        
	<div class="map_canvas_custom_fields" id="map_canvas<?=$id;?>" ></div>
	
	<input type="hidden" name="params[zoom]" class="zoom<?=$id;?>" value="<?=set_value('params[zoom]', isset($params['zoom']) ? $params['zoom'] : "");?>" >
	<input type="hidden" name="params[lat]"  class="lat<?=$id;?>"  value="<?=set_value('params[lat]', isset($params['lat']) ? $params['lat'] : "");?>">
	<input type="hidden" name="params[lng]"  class="lng<?=$id;?>"  value="<?=set_value('params[lng]', isset($params['zoom']) ? $params['lng'] : "");?>" >
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
	<script type="text/javascript">
	    google.maps.event.addDomListener(window, 'load', function(){
		initialize('<?=$id;?>', false);
	    });
	</script>
	
    </td>
</tr>