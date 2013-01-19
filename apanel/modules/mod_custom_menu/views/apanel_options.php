<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_menus');?>:</label></th>
    <td>
        <div class="menu_list" >
            <table class="menu_list" cellpadding="0" cellspacing="0" >    
            <?php foreach($menus_by_category as $category => $menus_arr){ ?>

                <tr>
                    <th colspan="2" ><?=$category;?></th>
                </tr>
                                                      
                <?php $custom_menus = set_value('params[custom_menus]', isset($params['custom_menus']) ? $params['custom_menus'] : "");
                      foreach($menus_arr as $menu_id => $menu){ 
                          $checked = "";
                          if(@in_array($menu_id, $custom_menus)){
                              $checked = "checked";
                          } ?>

                <tr>
                    <td style="width: 1%;" >
                        <input type="checkbox" style="width:16px;" <?=$checked;?> name="params[custom_menus][]" id="custom_menu<?=$menu_id;?>" value="<?=$menu_id;?>" >
                    </td>
                    <td>
                        <label for="custom_menu<?=$menu_id;?>" ><?=$menu;?></label>
                    </td>
                </tr>

                <?php } ?>
                
                <tr><td colspan="2" >&nbsp;</td></tr>
                
            <?php } ?>
            </table>
        </div>
    </td>
</tr>