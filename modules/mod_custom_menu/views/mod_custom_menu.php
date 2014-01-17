<?php

echo "<ul>\n";
        
foreach($menus as $menu){

    echo "<li ".($menu['class'] != '' ? 'class="'.$menu['class'].'"' : '')." >\n";
    echo "  <a href=\"".$menu['link']."\" target=\"".$menu['target']."\" >\n";
    
    if(!empty($menu['image'])){
        echo "<img src=\"".base_url($menu['image'])."\" >\n";
    }
    
    echo "  <span>".$menu['title']."</span>\n";
    echo "</a>\n";
    
    echo "</li>\n";

}

echo "</ul>\n";

?>
