<?php

echo '<ul>';
        
foreach($menus as $menu){

    echo '<li '.($menu['class'] != '' ? 'class="'.$menu['class'].'"' : '').' >';
    echo '  <a href="'.$menu['link'].'" target="'.$menu['target'].'" ><span>'.$menu['title'].'</span></a>';
    echo '</li>';

}

echo '</ul>';

?>
