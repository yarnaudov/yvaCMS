
<?php $alias = current(explode('/', uri_string())); ?>

<div class="gallery">

    <?php if($url1[0] == 'albums'){ ?>
    <div class="navigation">
        <a href="<?=$menu_link;?>" ><?=lang('com_gallery_all_albums');?></a>
    </div>
    <?php } ?>
    
    <?php foreach($images as $image){ ?>
    
    <div class="image_small">
        <a href="<?=current_url();?>/image:<?=$image['id'];?>">
            <img src="<?=$this->Image->getImageUrl($image['id'], 150, 100);/*base_url('images/'.$image['id'].'.'.$image['ext']);*/?>" alt="<?=$image['title'];?>" >
        </a>
        <div class="description" ><?=$image['title'];?></div>
    </div>
    
    <?php } ?>

</div>