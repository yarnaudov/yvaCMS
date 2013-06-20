
<?php $categories = $this->Category->getForDropdown('articles'); ?>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_category');?>:</label></th>
    <td>
	
	<?php $categories['most_popular'] = '- '.lang('label_most_popular').' -'; ?>
	
	<div class="menu_list" >
            <table class="menu_list" cellpadding="0" cellspacing="0" >    
                                                      
                <?php $sel_categories = set_value('params[categories]', isset($params['categories']) ? $params['categories'] : "");
                      foreach($categories as $category_id => $category){ 
                          $checked = "";
                          if(@in_array($category_id, $sel_categories)){
                              $checked = "checked";
                          } ?>

                <tr>
                    <td style="width: 1%;" >
                        <input type="checkbox" style="width:16px;" <?=$checked;?> name="params[categories][]" id="category<?=$category_id;?>" value="<?=$category_id;?>" >
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