
<div id="map_settings<?=$id;?>" style="display: none;" >

    <input type="text" class="lat"           value="<?=$params['lat'];?>" >
    <input type="text" class="lng"           value="<?=$params['lng'];?>" >
    <input type="text" class="zoom"          value="<?=$params['zoom'];?>" >
    <input type="text" class="markers_image" value="<?=$params['markers_image'];?>" >
    
    <div class="markers" >
	<?php foreach($params['markers'] as $key => $marker){ ?>
	<div class="marker" lang="<?=$key;?>" >
	    <input type="text" class="marker_lat"   value="<?=$marker['lat'];?>" >
	    <input type="text" class="marker_lng"   value="<?=$marker['lng'];?>" >
	    <input type="text" class="marker_title" value="<?=$marker['title'];?>" >
	    <textarea          class="marker_descr" ><?=$marker['description'];?></textarea>
	    <input type="text" class="marker_image" value="<?=$marker['image'];?>" >
	</div>
	<?php } ?>
    </div> 
    
</div>
    
<div id="map_canvas<?=$id;?>" style="height: 100%;width: 100%;" ></div>

<script type="text/javascript" >
  $(function(){      
      initialize(<?=$id;?>);
  });
</script>