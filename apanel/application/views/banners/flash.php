<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_flash');?>:</label></th>
    <td>
        <input class="flash" type="text" readonly name="params[flash]" id="media" value="<?=set_value('params[flash]', isset($params['flash']) ? $params['flash'] : "");?>" style="width: 58%">

        <a href  = "<?=site_url('media/browse');?>" 
           class = "load_jquery_ui_iframe"
           title="<?=lang('label_browse').' '.lang('label_media');?>"
           lang  = "dialog-media-browser" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                         class = "clear_jquery_ui_inputs"
                                                                                         lang  = "flash" ><?=lang('label_clear');?></a>

    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_width');?>:</label></th>
    <td>
        <input type="text" name="params[width]" value="<?=set_value('params[width]', isset($params['width']) ? $params['width'] : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_height');?>:</label></th>
    <td>
        <input type="text" name="params[height]" value="<?=set_value('params[height]', isset($params['height']) ? $params['height'] : "");?>" >
    </td>
</tr>