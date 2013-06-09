<form action="<?=site_url('search');?>" method="post" >
    <div>

        <?php if(isset($module['params']['label'][$this->language_id])){ ?>
        <label><?=$module['params']['label'][$this->language_id];?></label>
        <?php } ?>

        <input type="text" 
               name="search_v" 
               value="<?=@$module['params']['field_text'][$this->language_id];?>"
               onfocus="if(this.value == '<?=@$module['params']['field_text'][$this->language_id];?>'){this.value = '';}"
               onblur="if(this.value == ''){this.value='<?=@$module['params']['field_text'][$this->language_id];?>';}" >

        <?php if($module['params']['show_button'] == 'yes'){ ?>
        <button type="submit" name="search" ><?=@$module['params']['button_text'][$this->language_id];?></button>
        <?php } ?>

    </div>
</form>