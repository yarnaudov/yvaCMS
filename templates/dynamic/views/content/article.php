<div class="article" >
    
    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="title" ><?=$article['title'];?></div>
    <?php } ?>
    
    <div class="content" >
	
        <?=$article['text'];?>
		
	
	<?php if(isset($article['params']['images'])){ ?>
	<!-- load article images and build gallery -->
	<ul id="images_canvas">
	    <?php foreach(@$article['params']['images'] as $image){ ?>
	    <li>
		<a href="<?=base_url($image);?>" rel="lightbox[profile]" ><img src="<?=base_url($image);?>" ></a>
	    </li>
	    <?php } ?>
	</ul>
	<script src="<?=base_url(TEMPLATES_DIR.'/dynamic/lightbox/js/lightbox.js');?>"></script>
	<link href="<?=base_url(TEMPLATES_DIR.'/dynamic/lightbox/css/lightbox.css');?>" rel="stylesheet" />
	<?php } ?>
	
	
	<?php if(!empty($article['field4']->lat)){ ?>
	<!-- load google map and set marker -->
	<div id="map_canvas" style="width: 100%; height: 400px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" ></script>
	<script type="text/javascript" >
	    $(function(){    
	  
		var mapOptions = {
		    zoom: parseInt(<?=$article['field4']->zoom;?>),
		    center: new google.maps.LatLng(<?=$article['field4']->lat;?>, <?=$article['field4']->lng;?>),
		    mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
		
		var marker = new google.maps.Marker({
		    position: new google.maps.LatLng(<?=$article['field4']->lat;?>, <?=$article['field4']->lng;?>),
		    map: map,
		    //icon: 'marker.png',
		    title: '<?=$article['title'];?>'
		});
		
	    });
	</script>

	<?php } ?>
	
    </div>
    
</div>