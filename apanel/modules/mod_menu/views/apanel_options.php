<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?php echo lang('label_category');?>:</label></th>
    <td>
        <?php $categories = $this->Category->getForDropdown('menus'); ?>
        <select name="params[category_id]" >
            <option value="none" >- - -</option>
            <?php echo create_options_array($categories, set_value('params[category_id]', isset($params['category_id']) ? $params['category_id'] : ""));?>
        </select>
    </td>
</tr>