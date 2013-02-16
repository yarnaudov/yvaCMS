
<tr>
    <td colspan="2" class="empty_line" >

        <link rel="stylesheet" type="text/css" href="<?=base_url('modules/mod_google_map/css/mod_google_map.css');?>" />
        
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
        <script type="text/javascript" >
          var db_markers = JSON.parse('<?=json_encode($params['markers'], JSON_HEX_APOS | JSON_HEX_QUOT);?>');
          //var db_markers = JSON.parse('[{"lat":"41.521045421471406","lng":"23.317108154296875","title":"","descr":"","image":""},{"lat":"41.53325414281322","lng":"23.326034545898438","title":"","descr":"","image":""},{"lat":"41.53145510756294","lng":"23.294620513916016","title":"\u041c\u0430\u0440\u043a\u0435\u0440 1","descr":"<p>sasa <strong>dfsfs<\/strong> qwqw<\/p><p>&nbsp;<a href=\'http:\/\/abv.bg\'>here<\/a><\/p>","image":""}]');
        </script>
        <script type="text/javascript" src="<?=base_url('modules/mod_google_map/js/map_options.js');?>" ></script>
        <!--
        '[{"lat": "41.521045421471406","lng": "23.317108154296875","title": "","descr": "","image": ""},{"lat": "41.53325414281322","lng": "23.326034545898438","title": "","descr": "","image": ""},{"lat": "41.53145510756294","lng": "23.294620513916016","title": "Маркер 1","descr": "<p>sasa <strong>dfsfs</strong> qwqw</p><p>&nbsp;<a href=\"http://abv.bg\">here</a></p>","image": ""}]'
        -->
    </td>
</tr>
                                
<tr id="google_map" >	      			
    <th><label><?=lang('label_mod_google_map_map');?>:</label></th>
    <td>
        <div id="map_options" >
            <div id="zoom" >
                <span><?=lang('label_mod_google_map_zoom');?>:</span>
                <input type="text" class="zoom" name="params[zoom]" value="<?=set_value('params[zoom]', isset($params['zoom']) ? $params['zoom'] : '');?>">
            </div>
            <div id="center" >
                <span><?=lang('label_mod_google_map_center');?>:</span>
                <input type="text" class="lat" name="params[lat]" value="<?=set_value('params[lat]', isset($params['lat']) ? $params['lat'] : '');?>">
                <span>lat</span>
                <input type="text" class="lng" name="params[lng]" value="<?=set_value('params[lng]', isset($params['lng']) ? $params['lng'] : '');?>">
                <span>lng</span>
            </div>
        </div>
        
        <div id="map_canvas" ></div>        
        <div id="markers" style="display: none;" ></div> 
        
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr> 
                                
<tr>	      			
    <th><label><?=lang('label_mod_google_map_markers_icon');?>:</label></th>
    <td>
        <input class="markers_image" type="text" readonly name="params[markers_image]" id="media" value="<?=set_value('params[markers_image]', isset($params['markers_image']) ? $params['markers_image'] : "");?>" style="width: 58%">

        <a href="<?=site_url('media/browse');?>" 
           class = "load_jquery_ui_iframe"
           title="<?=lang('label_browse').' '.lang('label_media');?>"
           lang  = "dialog-media-browser" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                         class = "clear_jquery_ui_inputs"
                                                                                         lang  = "markers_image" ><?=lang('label_clear');?></a>

    </td>
</tr>