
<div id="map_settings<?php echo $id;?>" style="display: none;" >

    <input type="text" class="lat"           value="<?php echo $params['lat'];?>" >
    <input type="text" class="lng"           value="<?php echo $params['lng'];?>" >
    <input type="text" class="zoom"          value="<?php echo $params['zoom'];?>" >
    <input type="text" class="markers_image" value="<?php echo $params['markers_image'];?>" >
    
    <div class="markers" >
	<?php foreach($params['markers'] as $key => $marker){ ?>
	<div class="marker" lang="<?php echo $key;?>" >
	    <input type="text" class="marker_lat"   value="<?php echo $marker['lat'];?>" >
	    <input type="text" class="marker_lng"   value="<?php echo $marker['lng'];?>" >
	    <input type="text" class="marker_title" value="<?php echo $marker['title'];?>" >
	    <textarea          class="marker_descr" ><?php echo $marker['description'];?></textarea>
	    <input type="text" class="marker_image" value="<?php echo $marker['image'];?>" >
	</div>
	<?php } ?>
    </div> 
    
</div>
    
<div id="map_canvas<?php echo $id;?>" style="height: 100%;width: 100%;" ></div>

<?php if(!defined('GOOGLE_MAP_LOADED')){ 
        define('GOOGLE_MAP_LOADED', TRUE); ?> 
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
<script type="text/javascript" src="<?php echo base_url('modules/mod_google_map/js/map_options.js');?>" ></script>
<?php } ?> 
<script type="text/javascript" >
  $(function(){      
      initialize(<?php echo $id;?>);
  });
</script>