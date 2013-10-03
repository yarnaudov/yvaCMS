<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?php echo lang('label_image');?>:</label></th>
    <td>
        <input class="image" type="text" readonly name="params[image]" id="module_image" value="<?php echo set_value('params[image]', isset($params['image']) ? $params['image'] : "");?>" style="width: 58%">
       
        <a href  = "<?php echo site_url('media/browse');?>" 
           class = "load_jquery_ui_iframe"
           title="<?php echo lang('label_browse').' '.lang('label_media');?>"
           lang  = "dialog-media-browser"
	   target = "module_image" ><?php echo lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                  lass = "clear_jquery_ui_inputs"
                                                                                  lang  = "image" ><?php echo lang('label_clear');?></a>
                                                
    </td>
</tr>