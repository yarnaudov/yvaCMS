<?php $this->load->model('Menu'); ?>
<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_menu');?>:</label></th>
    <td>
        <?php $menus = $this->Menu->getMenusByCategory(array(), "`order`"); ?>
        <select name="params[menu_id]" >
            <option value="none" >- - -</option>
            <?=create_options_array($menus, set_value('params[menu_id]', isset($params['menu_id']) ? $params['menu_id'] : ""));?>
        </select>
    </td>
</tr>