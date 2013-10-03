
<div class="module module_<?php echo $module['type'].$module['css_class_sufix'];?>" >
    
    <?php if($module['show_title'] == 'yes'){ ?>
    <h4 class="title" ><?php echo $module['title'];?></h4>
    <?php } ?>
    
    <div class="content" >
        <?php echo $content;?>
    </div>
    
</div>