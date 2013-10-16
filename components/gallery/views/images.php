
<?php $alias = current(explode('/', uri_string())); ?>

<div class="gallery">

    <?php if(key($url1) == 'albums'){ ?>
    <div class="navigation">
        <a href="<?php echo $menu_link;?>" ><?php echo lang('com_gallery_all_albums');?></a>
    </div>
    <?php } ?>
    
    <?php foreach($images as $image){ ?>
    
    <div class="image_small">
        <a href="<?php echo current_url();?>/image/<?php echo $image['id'];?>">
            <img src="<?php echo $this->Image->getImageUrl($image['id'], 150, 100);/*base_url('images/'.$image['id'].'.'.$image['ext']);*/?>" alt="<?php echo $image['title'];?>" >
        </a>
        <div class="description" ><?php echo $image['title'];?></div>
    </div>
    
    <?php } ?>

</div>