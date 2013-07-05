<?php

echo "<ul>\n";
        
$ul_count = 0;
foreach($menus as $key => $menu){

    if($key == 0){
        $menu['class'] = $menu['class'].' first';
    }
    elseif($key+1 == count($menus)){
        $menu['class'] = $menu['class'].' last';
    }
    
    echo "<li".($menu['class'] != '' ? ' class="'.$menu['class'].'" ' : '').">\n";
    echo "  <a href=\"".$menu['link']."\" target=\"".$menu['target']."\" ><span>".$menu['title']."</span></a>\n";
    
    if(!empty($menu['image'])){
        echo "<img src=\"".base_url($menu['image'])."\" >\n";
    }

    if(@$menus[$key+1]['lavel'] > $menu['lavel']){                
        echo "<ul>\n";
        $ul_count++;
    }
    elseif(@$menus[$key+1]['lavel'] < $menu['lavel']){
        while($ul_count > 0){
            echo "</li>\n</ul>\n";
            $ul_count--;
        }
	echo "</li>\n";
    }
    else{
        echo "</li>\n";
    }

}

echo "</ul>\n";

?>