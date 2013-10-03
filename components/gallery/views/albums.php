
<div class="gallery">
    
<?php foreach($albums as $album){
          $image = $this->Album->getImage($album['id']); ?>
    
    <div class="image_small">
        <a href="<?php echo $menu_link;?>/album/<?php echo $album['id'];?>">
            <img src="<?php echo $this->Image->getImageUrl($image['id'], 150, 100);/*base_url('images/'.$image['id'].'.'.$image['ext']);*/?>" alt="<?php echo $album['title'];?>" >
        </a>
        <div class="description" ><?php echo $album['title'];?></div>
    </div>
    
<?php } ?>

</div>