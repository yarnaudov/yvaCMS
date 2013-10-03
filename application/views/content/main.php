
<div id="content" >
    
    <?php if(isset($menu['show_title']) && $menu['show_title'] == 'yes'){ ?>
    <h3 class="content_title" ><?php echo $menu['title'];?></h3>
    <?php } ?>

    <div class="content_content" ><?php echo $content;?></div>

</div>

