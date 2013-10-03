<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?php echo lang('label_value');?>:</label></th>
    <td>
        <input class="image" type="text" readonly name="params[value]" id="custom_field_media" value="<?php echo set_value('params[value]', isset($params['value']) ? $params['value'] : "");?>" style="width: 58%">
                                       
	<a href="<?php echo site_url('media/browse');?>" 
	   class = "load_jquery_ui_iframe"
	   title="<?php echo lang('label_browse').' '.lang('label_media');?>"
	   lang  = "dialog-media-browser"
	   target = "custom_field_media" ><?php echo lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
											class = "clear_jquery_ui_inputs"
											lang  = "image" ><?php echo lang('label_clear');?></a>
    </td>
</tr>