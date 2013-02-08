
<div class="module module_<?=$module['params']['type'].$module['css_class_sufix'];?>" >
    
    <?php if($module['show_title'] == 'yes'){ ?>
    <div class="title" ><?=$module['title'];?></div>
    <?php } ?>
    
    <div class="content" >
        <?=$content;?>
    </div>
    
</div>