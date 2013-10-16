
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
                <?php echo lang('com_gallery_image');?> <?php echo $image_key+1;?> <?php echo lang('com_gallery_of');?> <?php echo count($images);?> | <?php echo $image['title'];?> |                
                <?php if($url2[0] == 'album'){ ?>
                <a href="<?php echo $menu_link;?>" ><?php echo lang('com_gallery_all_albums');?></a>
                | <a href="<?php echo $menu_link.$alias;?>" ><?php echo $this->Album->getDetails($url2[1], 'title');?></a>                
                <?php }else{ ?>
                <a href="<?php echo $menu_link;?>" ><?php echo lang('com_gallery_back_to_gallery');?></a>
                <?php } ?>
            </span>
            <?php
            $prev_id = !isset($images[$image_key-1]['id']) ? $images[count($images)-1]['id'] : $images[$image_key-1]['id'];
            $next_id = !isset($images[$image_key+1]['id']) ? $images[0]['id']                : $images[$image_key+1]['id'];
            ?>
            <a class="prev" href="<?php echo $menu_link.$alias.'/image/'.$prev_id;?>#image" >&larr;</a>
            <a class="next" href="<?php echo $menu_link.$alias.'/image/'.$next_id;?>#image" >&rarr;</a>
        </div>
        
        <a class="image" href="<?php echo $menu_link.$alias.'/image/'.$next_id;?>#image" >
            <img src="<?php echo $this->Image->getImageUrl($image['id'], 150, 100);/*base_url('images/thumbs/'.$image['id'].'.'.$image['ext']);*/?>" alt="<?php echo $image['title'];?>" >
        </a>
        
        <div class="description" >
            <?php echo $image['description'];?>
        </div>
        
    </div>
  
    <img src="<?php echo $this->gallery->get_image($image['id'], 600, 600); ?><?php /* echo $menu_link.'/get_image/'.$image['id'].'/600/600';*/ ?>" id="big_image" style="display: none;" >

</div>