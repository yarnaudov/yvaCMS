
<?php $this->load->language('modules/mod_google_map'); ?>

<div style="width: 400px;padding: 10px;" >

    <input type="hidden" id="marker_numb" >
    
    <table class="box_table" cellpadding="0" cellspacing="0" >
    
        <tr>
            <th><label><?=lang('label_position');?>:</label></th>
            <td id="position" >
                <strong></strong> lat &nbsp;
                <strong></strong> lng
            </td>
        </tr>
        
        <tr><td colspan="2" class="empty_line" ></td></tr> 
        
        <tr>
            <th><label><?=lang('label_title');?>:</label></th>
            <td><input type="text" id="marker_title" ></td>
        </tr>
                            
        <tr><td colspan="2" class="empty_line" ></td></tr> 
        
        <tr>
            <th><label><?=lang('label_description');?>:</label></th>
            <td><textarea class="marker_description" id="marker_description" ></textarea></td>
        </tr>
                            
        <tr><td colspan="2" class="empty_line" ></td></tr> 
                                
        <tr>	      			
            <th><label><?=lang('label_mod_google_map_icon');?>:</label></th>
            <td>
                <input class="markerimage" type="text" readonly id="media" style="width: 58%">

                <a href="<?=site_url('media/browse');?>" 
                   class = "load_jquery_ui_iframe"
                   title="<?=lang('label_browse').' '.lang('label_media');?>"
                   lang  = "dialog-media-browser" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                                 class = "clear_jquery_ui_inputs"
                                                                                                 lang  = "markerimage" ><?=lang('label_clear');?></a>

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