<div style="overflow: auto;" >
    <select name="params[display_in]" class="display_in" style="width: 280px;float: left;">
        <?php echo create_options_array($this->config->item('module_display'), set_value('params[display_in]', isset($params['display_in']) ? $params['display_in'] : "") );?>
    </select>
    <button class="styled styled_small toggle" type="button" >Toggle selection</button>
</div>

<div id="tabs">

    <?php 
    $menus_by_category = $this->Menu->getMenusByCategory(array());
    $tabs     = "";
    $contents = "";
    $tab_numb = 0;
    foreach($menus_by_category as $category => $menus_arr){

        $tab_numb++;

        $tabs .= "<li>";
        $tabs .= "	<a href=\"#tabs-".$tab_numb."\" >".$category."</a>";
        $tabs .= "</li>";

        $contents .= "<div id=\"tabs-".$tab_numb."\">";

        $display_menus = set_value('params[display_menus]', isset($params['display_menus']) ? $params['display_menus'] : "");

        foreach($menus_arr as $menu_id => $menu){

            $checked = "";
            if(@in_array($menu_id, $display_menus)){
                $checked = "checked";
            }           

            $contents .= "<div>";
            $contents .= " <input ".$checked." style=\"width: 15px;\" type=\"checkbox\" class=\"display_menus\" id=\"display_menu".$menu_id."\" name=\"params[display_menus][]\" value=\"".$menu_id."\" >";
            $contents .= " <label for=\"display_menu".$menu_id."\" >".$menu."</label>";
            $contents .= "</div>";

        }

        $contents .= "</div>";

    } 
    ?>    	  	

    <ul>
        <?php echo $tabs; ?>
    </ul>
    <?php echo $contents; ?>

</div>

<script type="text/javascript" >

    $('#tabs').tabs();
    $('.toggle').bind('click', function(){
        $('.display_menus').each(function(index){
            if($(this).attr('disabled') != 'disabled'){
                $(this).attr('checked', !$(this).attr('checked'));  
            }
        });
    });
    $('select.display_in').bind('change', function(){
        if($(this).val() == 'all'){
            $('.display_menus').attr('checked', true).attr('disabled', true);
        }
        else if($(this).val() == 'on_selected'){
            $('.display_menus').removeAttr('disabled');
        }
        else if($(this).val() == 'all_except_selected'){
            $('.display_menus').removeAttr('disabled');
        }
    });
    $('select.display_in').trigger('change');

</script>