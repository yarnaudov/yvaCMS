
<?php !isset($id) ? $id = '' : ''; ?>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?php echo lang('label_location');?>:</label></th>
    <td>
        
	<div class="map_canvas_custom_fields" id="map_canvas<?php echo $id;?>" ></div>
	
	<input type="hidden" name="params[zoom]" class="zoom<?php echo $id;?>" value="<?php echo set_value('params[zoom]', isset($params['zoom']) ? $params['zoom'] : "");?>" >
	<input type="hidden" name="params[lat]"  class="lat<?php echo $id;?>"  value="<?php echo set_value('params[lat]', isset($params['lat']) ? $params['lat'] : "");?>">
	<input type="hidden" name="params[lng]"  class="lng<?php echo $id;?>"  value="<?php echo set_value('params[lng]', isset($params['zoom']) ? $params['lng'] : "");?>" >
		
	<img src="<?php echo base_url('img/iconAdministration.png');?>" style="display:none;" onload="initialize('<?php echo $id;?>', false);"  >
		   	
    </td>
</tr>