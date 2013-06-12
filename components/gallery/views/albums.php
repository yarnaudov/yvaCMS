
<div class="gallery">
    
<?php foreach($albums as $album){
          $image = $this->Album->getImage($album['id']); ?>
    
    <div class="image_small">
        <a href="<?=$menu_link;?>/album/<?=$album['id'];?>">
            <img src="<?=$this->Image->getImageUrl($image['id'], 150, 100);/*base_url('images/'.$image['id'].'.'.$image['ext']);*/?>" alt="<?=$album['title'];?>" >
        </a>
        <div class="description" ><?=$album['title'];?></div>
    </div>
    
<?php } ?>

</div>