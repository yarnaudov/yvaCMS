<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_value');?>:</label></th>
    <td>
        <input class="image" type="text" readonly name="params[value]" id="custom_field_media" value="<?=set_value('params[value]', isset($params['value']) ? $params['value'] : "");?>" style="width: 58%">
                                       
	<a href="<?=site_url('media/browse');?>" 
	   class = "load_jquery_ui_iframe"
	   title="<?=lang('label_browse').' '.lang('label_media');?>"
	   lang  = "dialog-media-browser"
	   target = "custom_field_media" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
											class = "clear_jquery_ui_inputs"
											lang  = "image" ><?=lang('label_clear');?></a>
    </td>
</tr>