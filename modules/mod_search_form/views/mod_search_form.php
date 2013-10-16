<form action="<?php echo site_url('search');?>" method="get" >
    <div>

        <?php if(isset($module['params']['label'][$this->language_id])){ ?>
        <label><?php echo $module['params']['label'][$this->language_id];?></label>
        <?php } ?>

        <input type="text" 
               name="query" 
               value="<?php echo @$module['params']['field_text'][$this->language_id];?>"
               onfocus="if(this.value == '<?php echo @$module['params']['field_text'][$this->language_id];?>'){this.value = '';}"
               onblur="if(this.value == ''){this.value='<?php echo @$module['params']['field_text'][$this->language_id];?>';}" >

        <?php if($module['params']['show_button'] == 'yes'){ ?>
        <button type="submit" name="search" onclick="this.disabled=true;" ><?php echo @$module['params']['button_text'][$this->language_id];?></button>
        <?php } ?>

    </div>
</form>