<?php

echo '<form action="'.site_url('search').'" method="post" >';
echo '    <div>';        
echo '        <label>'.$module['params']['label_'.get_lang()].'</label>';
echo '        <input type="text" 
                        name="search_v" 
                        value="'.$module['params']['field_text_'.get_lang()].'"
                        onfocus="" 
                        onblur="" >';
echo '        <button type="submit" name="search" >'.$module['params']['button_text_'.get_lang()].'</button>';
echo '    </div>';
echo '</form>';
        
?>
