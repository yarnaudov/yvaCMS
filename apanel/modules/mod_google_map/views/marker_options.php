
<div style="padding: 10px;" >

    <input type="hidden" id="marker_numb" >
    
    <table class="box_table" cellpadding="0" cellspacing="0" >
    
        <tr>
            <th><label><?=lang('label_title');?>:</label></th>
            <td><input type="text" name="title" id="marker_title" ></td>
        </tr>
                            
        <tr><td colspan="2" class="empty_line" ></td></tr> 
        
        <tr>
            <th><label><?=lang('label_description');?>:</label></th>
            <td><textarea name="description" id="marker_description" ></textarea></td>
        </tr>
                            
        <tr><td colspan="2" class="empty_line" ></td></tr> 
                                
        <tr>	      			
            <th><label><?=lang('label_image');?>:</label></th>
            <td>
                <input class="image" type="text" readonly name="image" id="media" style="width: 58%">

                <a href="<?=site_url('media/browse');?>" 
                   class = "load_jquery_ui_iframe"
                   title="<?=lang('label_browse').' '.lang('label_media');?>"
                   lang  = "dialog-media-browser" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                                 class = "clear_jquery_ui_inputs"
                                                                                                 lang  = "image" ><?=lang('label_clear');?></a>

            </td>
        </tr>
        
        <tr><td colspan="2" class="empty_line" ></td></tr> 
                                
        <tr>	      			
            <th></th>
            <td>
                <a id="save_marker" class="styled save" ><?=lang('label_save');?></a>
                <a id="delete_marker" class="styled delete" ><?=lang('label_delete');?></a>
            </td>
        </tr>
    
    </table>

</div>