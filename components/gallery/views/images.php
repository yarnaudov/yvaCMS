
<?php $alias = current(explode('/', uri_string())); ?>

<div class="gallery">

    <?php if($url1[0] == 'albums'){ ?>
    <div class="navigation">
        <a href="<?=site_url($alias);?>" ><?=lang('com_gallery_all_albums');?></a>
    </div>
    <?php } ?>
    
    <?php foreach($images as $image){ ?>
    
    <div class="image_small">
        <a href="<?=current_url();?>/image:<?=$image['id'];?>">
            <img src="<?=base_url('images/thumbs/'.$image['id'].'.'.$image['ext']);?>" alt="<?=$image['title'];?>" >
        </a>
        <div class="description" ><?=$image['title'];?></div>
    </div>
    
    <?php } ?>

</div>