
<div id="content" >
    
    <?php if(isset($menu['show_title']) && $menu['show_title'] == 'yes'){ ?>
    <div class="content_title" ><?=$menu['title'];?></div>
    <?php } ?>

    <div class="content_content" ><?=$content;?></div>

</div>

