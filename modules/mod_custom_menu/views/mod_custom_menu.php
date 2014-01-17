<?php

echo '<ul>';
        
foreach($menus as $menu){

    echo '<li '.($menu['class'] != '' ? 'class="'.$menu['class'].'"' : '').' >';
    echo '  <a href="'.$menu['link'].'" target="'.$menu['target'].'" ><span>'.$menu['title'].'</span></a>';
    
    if(!empty($menu['image'])){
        echo "<img src=\"".base_url($menu['image'])."\" >\n";
    }
    
    echo '</li>';

}

echo '</ul>';

?>
