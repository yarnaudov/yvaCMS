<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_category');?>:</label></th>
    <td>
	
        <?php $categories = $this->Category->getForDropdown('articles');
	      $categories['most_popular'] = '- '.lang('label_most_popular').' -';
	      $categories['most_commented'] = '- '.lang('label_most_commented').' -'; ?>
	
	<div class="menu_list categories_list" >
            <table class="menu_list" cellpadding="0" cellspacing="0" >    
                                                      
                <?php $sel_categories = set_value('params[categories]', isset($params['categories']) ? $params['categories'] : "");
                      foreach($categories as $category_id => $category){ 
                          $checked = "";
                          if(@in_array($category_id, $sel_categories)){
                              $checked = "checked";
                          } ?>

                <tr>
                    <td style="width: 1%;" >
                        <input type="checkbox" <?=$checked;?> name="params[categories][]" id="category<?=$category_id;?>" value="<?=$category_id;?>" >
                    </td>
                    <td>
                        <label for="category<?=$category_id;?>" ><?=$category;?></label>
                    </td>
                </tr>

                <?php } ?>
		
            </table>
        </div>
	
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_order');?>:</label></th>
    <td>
	<?php $orders = array('order ASC' => lang('order_asc'), 'order DESC' => lang('order_desc'), 'created_on DESC' => lang('created_on_desc'), 'created_on ASC' => lang('created_on_asc'), 'updated_on DESC' => lang('updated_on_desc'), 'updated_on ASC' => lang('updated_on_asc') ); ?>
        <select name="params[order]" >
            <?=create_options_array($orders, set_value('params[order]', isset($params['order']) ? $params['order'] : ""));?>            
        </select>
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_number');?>:</label></th>
    <td>
	<?php $numbers = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25);
	      $numbers = array_combine($numbers, $numbers); ?>
        <select name="params[number]" >
	    <option value="all" ><?=lang('label_all');?></option>
            <?=create_options_array($numbers, set_value('params[number]', isset($params['number']) ? $params['number'] : ""));?>            
        </select>
    </td>
</tr>