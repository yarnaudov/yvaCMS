
<div class="banner banner_<?php echo $banner['type']; ?>" >
    
    <?php if(isset($menu['show_title']) &&  $banner['show_title'] == 'yes'){ ?>
    <h3 class="title" ><?php echo $banner['title']; ?></h3>
    <?php } ?>

    <?php echo $content;?>

</div>

