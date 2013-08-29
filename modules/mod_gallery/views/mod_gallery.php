

<?php if(count($albums) == 1){ ?>
    
    <?php foreach($albums[0]['images'] as $image){ ?>
    
    <div class="image_small">
        <a href="<?=site_url('gallery/image/'.$image['id'].'/600/600');?>">
            <img src="<?=$this->Image->getImageUrl($image['id'], 80, 80);?>" alt="<?=$image['title'];?>" >
        </a>
        <div class="description" ><?=$image['title'];?></div>
    </div>
    
    <?php } ?>

<?php }else{ ?>

    <?php foreach($albums as $album){
              $image = $this->Album->getImage($album['id']); ?>
    
    <div class="image_small">
        <a href="<?=$menu_link;?>/album/<?=$album['id'];?>">
            <img src="<?=$this->Image->getImageUrl($image['id'], 150, 100);/*base_url('images/'.$image['id'].'.'.$image['ext']);*/?>" alt="<?=$album['title'];?>" >
        </a>
        <div class="description" ><?=$album['title'];?></div>
    </div>
    
    <?php } ?>

<?php } ?>
