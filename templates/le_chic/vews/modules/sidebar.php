
<div class="block block_content module module_<?=$module['type'];?>" >
    <div class="block_header title" ></div>
    
    <?php if($module['show_title'] == 'yes'){ ?>    
    <h3><?=$module['title_'.$this->lang_lib->get()];?></h3>
    <?php } ?>
    
    <div class="block_content_content" >
        <?=$content;?>
    </div>    
    
    <div class="block_bottom"></div>
</div>