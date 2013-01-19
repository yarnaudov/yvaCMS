<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_images');?>:</label></th>
    <td>
        <select name="params[images]" >
            <?=create_options_array($this->config->item('yes_no'), set_value('parent[images]', isset($parent['images']) ? $parent['images'] : ""));?>
        </select>
    </td>
</tr>