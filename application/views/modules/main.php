
<div class="module module_<?=$module['type'].$module['params']['css_class'];?>" >
    
    <?php if($module['show_title'] == 'yes'){ ?>
    <div class="title" ><?=$module['title_'.get_lang()];?></div>
    <?php } ?>
    
    <?=$content;?>
    
</div>