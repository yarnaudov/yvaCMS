<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_image');?>:</label></th>
    <td>
        <input class="image" type="text" readonly name="params[image]" id="media" value="<?=set_value('params[image]', isset($params['image']) ? $params['image'] : "");?>" style="width: 58%">
       
        <a href="#" 
           class = "load_jquery_ui_iframe" 
           lang  = "dialog-media-browser" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                         class = "clear_jquery_ui_inputs"
                                                                                         lang  = "image" ><?=lang('label_clear');?></a>
        
        <!-- start jquery UI -->
        <div id="dialog-media-browser"
             class = "jquery_ui_iframe"
             title="<?=lang('label_browse').' '.lang('label_media');?>" 
             lang="<?=site_url('home/media/simple_ajax');?>" ></div>
                                                
    </td>
</tr>