
<?php $this->load->model('../../modules/mod_google_map/models/mod_google_map');
      $markers = isset($id) ? $this->mod_google_map->getMarkers($id): array(); ?>

<tr>
    <td colspan="2" class="empty_line" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('modules/mod_google_map/css/mod_google_map.css');?>" />        
    </td>
</tr>
                                
<tr id="google_map" >	      			
    <!--<th><label><?php echo lang('label_mod_google_map_map');?>:</label></th>-->
    <td colspan="2" >

        <div id="map_main_content" >

            <div id="map_options" >
                <div id="zoom" >
                    <span><?php echo lang('label_mod_google_map_zoom');?>:</span>
                    <input type="text" class="zoom" name="params[zoom]" value="<?php echo set_value('params[zoom]', isset($params['zoom']) ? $params['zoom'] : '');?>">
                </div>

                <img id="map_fullscreen"      src="<?php echo base_url('modules/mod_google_map/img/iconFull_screen_enter.png');?>" title="Enter full screen mode" >
                <img id="map_fullscreen_exit" src="<?php echo base_url('modules/mod_google_map/img/iconFull_screen_exit.png');?>" title="Exit full screen mode" style="display: none;" >
                
                <div id="center" >
                    <span><?php echo lang('label_mod_google_map_center');?>:</span>
                    <input type="text" class="lat" name="params[lat]" value="<?php echo set_value('params[lat]', isset($params['lat']) ? $params['lat'] : '');?>">
                    <span>lat</span>
                    <input type="text" class="lng" name="params[lng]" value="<?php echo set_value('params[lng]', isset($params['lng']) ? $params['lng'] : '');?>">
                    <span>lng</span>
                </div>

                

            </div>
            
            <div id="map_canvas" ></div>
	    <img src="<?php echo base_url('img/iconAdministration.png');?>" style="display:none;" onload="initialize();"  >

        </div>

        <div id="markers" style="display: none;" >
            <?php foreach($markers as $key => $marker){ ?>
            <div class="marker" lang="<?php echo $key;?>" >
                <input type="text" name="markers[<?php echo $key;?>][lat]"         class="marker_lat"   value="<?php echo $marker['lat'];?>" >
                <input type="text" name="markers[<?php echo $key;?>][lng]"         class="marker_lng"   value="<?php echo $marker['lng'];?>" >
                <input type="text" name="markers[<?php echo $key;?>][title]"       class="marker_title" value="<?php echo $marker['title'];?>" >
                <textarea          name="markers[<?php echo $key;?>][description]" class="marker_descr" ><?php echo $marker['description'];?></textarea>
                <input type="text" name="markers[<?php echo $key;?>][image]"       class="marker_image" value="<?php echo $marker['image'];?>" >
            </div>
            <?php } ?>
        </div> 
        
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr> 
                                
<tr>	      			
    <th><label><?php echo lang('label_mod_google_map_markers_icon');?>:</label></th>
    <td>
        <input class="markers_image" type="text" readonly name="params[markers_image]" id="markers_image" value="<?php echo set_value('params[markers_image]', isset($params['markers_image']) ? $params['markers_image'] : "");?>" style="width: 58%">

        <a href="<?php echo site_url('media/browse');?>" 
           class = "load_jquery_ui_iframe"
           title="<?php echo lang('label_browse').' '.lang('label_media');?>"
           lang  = "dialog-media-browser"
	   target = "markers_image" ><?php echo lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                   class = "clear_jquery_ui_inputs"
                                                                                   lang  = "markers_image" ><?php echo lang('label_clear');?></a>

    </td>
</tr>

<?php echo $this->load->view('../../modules/mod_google_map/views/marker_options');?>