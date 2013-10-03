<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?php echo lang('label_flash');?>:</label></th>
    <td>
        <input class="flash" type="text" readonly name="params[flash]" id="banner_flash" value="<?php echo set_value('params[flash]', isset($params['flash']) ? $params['flash'] : "");?>" style="width: 58%">

        <a href  = "<?php echo site_url('media/browse');?>" 
           class = "load_jquery_ui_iframe"
           title="<?php echo lang('label_browse').' '.lang('label_media');?>"
           lang  = "dialog-media-browser"
	   target = "banner_flash" ><?php echo lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                  class = "clear_jquery_ui_inputs"
                                                                                  lang  = "flash" ><?php echo lang('label_clear');?></a>

    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?php echo lang('label_width');?>:</label></th>
    <td>
        <input type="text" name="params[width]" value="<?php echo set_value('params[width]', isset($params['width']) ? $params['width'] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?php echo lang('label_height');?>:</label></th>
    <td>
        <input type="text" name="params[height]" value="<?php echo set_value('params[height]', isset($params['height']) ? $params['height'] : "");?>" >
    </td>
</tr>