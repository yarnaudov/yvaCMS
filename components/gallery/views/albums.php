
<div class="gallery">
    
<?php foreach($albums as $album){
          $image = $this->Album->getImage($album['album_id']); ?>
    
    <div class="image_small">
        <a href="<?=current_url();?>/album:<?=$album['album_id'];?>">
            <img src="<?=base_url('images/thumbs/'.$image['image_id'].'.'.$image['ext']);?>" alt="<?=$album['title_'.get_lang()];?>" >
        </a>
        <div class="description" ><?=$album['title_'.get_lang()];?></div>
    </div>
    
<?php } ?>

</div>