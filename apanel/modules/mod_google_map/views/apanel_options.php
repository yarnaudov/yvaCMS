
<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_mod_google_map_map');?>:</th>
    <td style="text-align: right;background-color: #eee;padding: 4px 10px;" >
        <span><?=lang('label_mod_google_map_zoom');?>:</span>
        <input type="text" style="width: 20px;" class="zoom" name="params[zoom]" value="<?=set_value('params[zoom]', isset($params['zoom']) ? $params['zoom'] : '');?>">
        <span><?=lang('label_mod_google_map_center');?>:</span>
        <input type="text" style="width: 130px;" class="lat" name="params[lat]" value="<?=set_value('params[lat]', isset($params['lat']) ? $params['lat'] : '');?>">
        lat
        <input type="text" style="width: 130px;" class="lng" name="params[lng]" value="<?=set_value('params[lng]', isset($params['lng']) ? $params['lng'] : '');?>">
        lng
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th></label></th>
    <td>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
        <script type="text/javascript" >
          var db_markers = JSON.parse('<?=json_encode($params['markers']);?>');
        </script>
        <script type="text/javascript" src="<?=base_url('modules/mod_google_map/js/map_options.js');?>" ></script>
        <div id="map_canvas" style="height: 400px;" ></div>
        
        <div id="markers" ></div>
        
    </td>
</tr>