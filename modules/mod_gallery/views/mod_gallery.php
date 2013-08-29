

<?php if(count($albums) == 1){ ?>
    
<ul>
    
    <?php foreach($albums[0]['images'] as $image){ ?>
    
    <li>
        <a href="<?=site_url('gallery/image/'.$image['id'].'/600/600');?>">
            <img src="<?=$this->Image->getImageUrl($image['id'], 80, 80);?>" alt="<?=$image['title'];?>" >
        </a>
        <div class="description" ><?=$image['title'];?></div>
    </li>
    
    <?php } ?>

</ul>
    
<?php }else{ ?>

<ul>
    
    <?php foreach($albums as $album){
              $image = $this->Album->getImage($album['id']); ?>
    
    <li>
        <a href="<?=$menu_link;?>/album/<?=$album['id'];?>">
            <img src="<?=$this->Image->getImageUrl($image['id'], 150, 100);/*base_url('images/'.$image['id'].'.'.$image['ext']);*/?>" alt="<?=$album['title'];?>" >
        </a>
        <div class="description" ><?=$album['title'];?></div>
    </li>
    
    <?php } ?>

</ul>

<?php } ?>