<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_category');?>:</label></th>
    <td>
        <select name="params[category_id]" >
            <option value="none" >- - -</option>
            <?=create_options('categories', 'category_id', 'title_'.$this->Language->getDefault(), set_value('params[category_id]', isset($params['category_id']) ? $params['category_id'] : ""), array('extension' => 'menus'));?>
        </select>
    </td>
</tr>