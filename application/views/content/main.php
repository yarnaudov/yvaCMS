
<div id="content" >
    
    <?php if(isset($menu['show_title']) && $menu['show_title'] == 'yes'){ ?>
    <h3 class="content_title" ><?=$menu['title'];?></h3>
    <?php } ?>

    <div class="content_content" ><?=$content;?></div>

</div>

