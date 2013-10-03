<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?php echo lang('label_image');?>:</label></th>
    <td>
        <input class="image" type="text" readonly name="params[image]" id="banner_image" value="<?php echo set_value('params[image]', isset($params['image']) ? $params['image'] : "");?>" style="width: 58%">

        <a href="<?php echo site_url('media/browse');?>"
           class = "load_jquery_ui_iframe"
           title="<?php echo lang('label_browse').' '.lang('label_media');?>"
           lang  = "dialog-media-browser"
	   target = "banner_image" ><?php echo lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                  class = "clear_jquery_ui_inputs"
                                                                                  lang  = "image" ><?php echo lang('label_clear');?></a>

    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?php echo lang('label_link');?>:</label></th>
    <td>
        <input type="text" name="params[link]" value="<?php echo set_value('params[link]', isset($params['link']) ? $params['link'] : "");?>" >
    </td>
</tr> 