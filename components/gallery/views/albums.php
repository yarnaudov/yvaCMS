
<div class="gallery">
    
<?php foreach($albums as $album){
          $image = $this->Album->getImage($album['id']); ?>
    
    <div class="image_small">
        <a href="<?=$menu_link;?>/album:<?=$album['id'];?>">
            <img src="<?=base_url('images/thumbs/'.$image['id'].'.'.$image['ext']);?>" alt="<?=$album['title'];?>" >
        </a>
        <div class="description" ><?=$album['title'];?></div>
    </div>
    
<?php } ?>

</div>