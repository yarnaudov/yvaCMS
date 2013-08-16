
<?php 

$alias = '';
if($url2[0] == 'album'){
    $alias = '/'.implode('/', $url2);
}

?>

<div class="gallery">

    <div class="image_big" id="image" >
        
        <div class="navigation">
            <span>
                <?=lang('com_gallery_image');?> <?=$image_key+1;?> <?=lang('com_gallery_of');?> <?=count($images);?> | <?=$image['title'];?> |                
                <?php if($url2[0] == 'album'){ ?>
                <a href="<?=$menu_link;?>" ><?=lang('com_gallery_all_albums');?></a>
                | <a href="<?=$menu_link.$alias;?>" ><?=$this->Album->getDetails($url2[1], 'title');?></a>                
                <?php }else{ ?>
                <a href="<?=$menu_link;?>" ><?=lang('com_gallery_back_to_gallery');?></a>
                <?php } ?>
            </span>
            <?php
            $prev_id = !isset($images[$image_key-1]['id']) ? $images[count($images)-1]['id'] : $images[$image_key-1]['id'];
            $next_id = !isset($images[$image_key+1]['id']) ? $images[0]['id']                : $images[$image_key+1]['id'];
            ?>
            <a class="prev" href="<?=$menu_link.$alias.'/image/'.$prev_id;?>#image" >&larr;</a>
            <a class="next" href="<?=$menu_link.$alias.'/image/'.$next_id;?>#image" >&rarr;</a>
        </div>
        
        <a class="image" href="<?=$menu_link.$alias.'/image/'.$next_id;?>#image" >
            <img src="<?=$this->Image->getImageUrl($image['id'], 150, 100);/*base_url('images/thumbs/'.$image['id'].'.'.$image['ext']);*/?>" alt="<?=$image['title'];?>" >
        </a>
        
        <div class="description" >
            <?=$image['description'];?>
        </div>
        
    </div>
    
    <img src="<?=site_url('gallery/image/'.$image['id'].'/600/600');/*$this->Image->getImageUrl($image['id'], 600, 600);base_url('images/'.$image['id'].'.'.$image['ext']);?>" alt="<?=$image['title'];*/?>" id="big_image" style="display: none;" >

</div>