
<div class="module module_<?=$module['type'].$module['css_class_sufix'];?>" >
    
    <?php if($module['show_title'] == 'yes'){ ?>
    <h4 class="title" ><?=$module['title'];?></h4>
    <?php } ?>
    
    <div class="content" >
        <?=$content;?>
    </div>
    
</div>