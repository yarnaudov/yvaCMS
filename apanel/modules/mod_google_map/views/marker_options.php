
<?php $this->load->language('modules/mod_google_map'); ?>

<div class="marker_info_conteiner" >

    <input type="hidden" id="marker_numb" >
    
    <div>
        <label><?=lang('label_position');?>:</label>
        <span id="position" >
                <strong></strong> lat &nbsp;
                <strong></strong> lng
        </span>
    </div>
        
    <div>
        <label><?=lang('label_title');?>:</label>
        <span><input type="text" id="marker_title" ></span>
    </div>
                            
    <div>
        <label><?=lang('label_description');?>:</label>
        <textarea class="marker_description" id="marker_description" ></textarea>
    </div>
                            
    <div>	      			
        <label><?=lang('label_mod_google_map_icon');?>:</label>
        <span>
            <input class="marker_image" type="text" readonly id="marker_image" style="width: 58%">
            <a href   = "<?=site_url('media/browse');?>" 
               class  = "load_jquery_ui_iframe"
               title  = "<?=lang('label_browse').' '.lang('label_media');?>"
               lang   = "dialog-media-browser"
               target = "marker_image" ><?=lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                      class = "clear_jquery_ui_inputs"
                                                                                      lang  = "marker_image" ><?=lang('label_clear');?></a>
        </span>
    </div>
                                
    <div>	
        <label>&nbsp;</label>  			
        <a id="save_marker" class="styled apply" ><?=lang('label_apply');?></a>
        <a id="delete_marker" class="styled delete" ><?=lang('label_delete');?></a>
        <span style="display: inline;" >|</span>
        <a id="cancel_marker" href="#" ><?=lang('label_cancel');?></a>
    </div>

</div>