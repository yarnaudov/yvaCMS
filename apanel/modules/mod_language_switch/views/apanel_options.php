<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_images');?>:</label></th>
    <td>
        <select name="params[images]" >
            <?=create_options_array($this->config->item('yes_no'), set_value('params[images]', isset($params['images']) ? $params['images'] : ""));?>
        </select>
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_type');?>:</label></th>
    <td>
        <?php $type = set_value('params[menu_type]', isset($params['menu_type']) ? $params['menu_type'] : ""); ?>
        <select name="params[menu_type]" >
            <option value="list"     <?=$type == 'list'     ? 'selected' : '';?> ><?=lang('label_mod_language_switch_list');?></option>
            <option value="dropdown" <?=$type == 'dropdown' ? 'selected' : '';?> ><?=lang('label_mod_language_switch_dropdown');?></option>            
        </select>
    </td>
</tr>