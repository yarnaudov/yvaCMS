

<?php if(count($albums) == 1){ ?>
    
<ul>
    
    <?php foreach($albums[0]['images'] as $image){ ?>
    
    <li>
        <a href="<?php echo site_url('gallery/get_image/'.$image['id'].'/600/600');?>">
            <img src="<?php echo $this->Image->getImageUrl($image['id'], 80, 80);?>" alt="<?php echo $image['title'];?>" >
        </a>
        <div class="description" ><?php echo $image['title'];?></div>
    </li>
    
    <?php } ?>

</ul>
    
<?php }else{ ?>

<ul>
    
    <?php foreach($albums as $album){
              $image = $this->Album->getImage($album['id']); ?>
    
    <li>
        <a href="<?php echo $menu_link;?>/album/<?php echo $album['id'];?>">
            <img src="<?php echo $this->Image->getImageUrl($image['id'], 150, 100);/*base_url('images/'.$image['id'].'.'.$image['ext']);*/?>" alt="<?php echo $album['title'];?>" >
        </a>
        <div class="description" ><?php echo $album['title'];?></div>
    </li>
    
    <?php } ?>

</ul>

<?php } ?>