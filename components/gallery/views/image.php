
<?php 

$alias_current = current(explode('/', uri_string()));
if($url2[0] == 'album'){
    $alias = $alias_current.'/'.implode(':', $url2);
}
else{
    $alias = $alias_current;
}

?>

<div class="gallery">

    <div class="image_big" id="image" >
        
        <div class="navigation">
            <span>
                <?=lang('com_gallery_image');?> <?=$image_key+1;?> <?=lang('com_gallery_of');?> <?=count($images);?> | <?=$image['title_'.get_lang()];?> |                
                <?php if($url2[0] == 'album'){ ?>
                <a href="<?=site_url($alias_current);?>" ><?=lang('com_gallery_all_albums');?></a>
                | <a href="<?=site_url($alias);?>" ><?=$this->Album->getDetails($url2[1], 'title_bg');?></a>                
                <?php }else{ ?>
                <a href="<?=site_url($alias_current);?>" ><?=lang('com_gallery_back_to_gallery');?></a>
                <?php } ?>
            </span>
            <?php
            $prev_image_id = !isset($images[$image_key-1]['image_id']) ? $images[count($images)-1]['image_id'] : $images[$image_key-1]['image_id'];
            $next_image_id = !isset($images[$image_key+1]['image_id']) ? $images[0]['image_id']                : $images[$image_key+1]['image_id'];
            ?>
            <a class="prev" href="<?=site_url($alias . '/image:'.$prev_image_id);?>#image" >&larr;</a>
            <a class="next" href="<?=site_url($alias . '/image:'.$next_image_id);?>#image" >&rarr;</a>
        </div>
        
        <a class="image" href="<?=site_url($alias . '/image:'.$next_image_id);?>#image" >
            <img src="<?=base_url('images/thumbs/'.$image['image_id'].'.'.$image['ext']);?>" alt="<?=$image['title_'.get_lang()];?>" >
        </a>
        
        <div class="description" >
            <?=$image['description_'.get_lang()];?>
        </div>
        
    </div>
    
    <img src="<?=base_url('images/'.$image['image_id'].'.'.$image['ext']);?>" alt="<?=$image['title_'.get_lang()];?>" id="big_image" style="display: none;" >

</div>