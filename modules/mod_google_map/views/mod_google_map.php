
<div id="markers" style="display: none;" >
    <?php foreach($params['markers'] as $key => $marker){ ?>
    <div class="marker" lang="<?=$key;?>" >
        <input type="text" name="markers[<?=$key;?>][lat]"         class="marker_lat"   value="<?=$marker['lat'];?>" >
        <input type="text" name="markers[<?=$key;?>][lng]"         class="marker_lng"   value="<?=$marker['lng'];?>" >
        <input type="text" name="markers[<?=$key;?>][title]"       class="marker_title" value="<?=$marker['title'];?>" >
        <textarea          name="markers[<?=$key;?>][description]" class="marker_descr" ><?=$marker['description'];?></textarea>
        <input type="text" name="markers[<?=$key;?>][image]"       class="marker_image" value="<?=$marker['image'];?>" >
    </div>
    <?php } ?>
</div> 
<div id="map_canvas" style="height: 100%;width: 100%;" ></div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
<script type="text/javascript" >
  var map_settings = new Array();
  map_settings['lat']           = '<?=$params['lat'];?>';
  map_settings['lng']           = '<?=$params['lng'];?>';
  map_settings['zoom']          = '<?=$params['zoom'];?>';
  map_settings['markers_image'] = '<?=$params['markers_image'];?>';
</script>
<script type="text/javascript" src="<?=base_url('modules/mod_google_map/js/map_options.js');?>" ></script>