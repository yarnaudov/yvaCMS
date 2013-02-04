<?php

echo '<form action="'.site_url('search').'" method="post" >';
echo '    <div>';        
echo '        <label>'.$module['params']['label'][$this->language_id].'</label>';
echo '        <input type="text" 
                        name="search_v" 
                        value="'.$module['params']['field_text'][$this->language_id].'"
                        onfocus="" 
                        onblur="" >';
echo '        <button type="submit" name="search" >'.$module['params']['button_text'][$this->language_id].'</button>';
echo '    </div>';
echo '</form>';
        
?>
