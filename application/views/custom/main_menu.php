<?php


echo '<ul>';
        
$ul_count = 0;
foreach($menus as $key => $menu){

    if($key == 0){
        $menu['class'] = $menu['class'].' first';
    }
    elseif($key+1 == count($menus)){
        $menu['class'] = $menu['class'].' last';
    }
    
    echo '<li '.($menu['class'] != '' ? 'class="'.$menu['class'].'"' : '').' >';
    echo '  <a href="'.$menu['link'].'" target="'.$menu['target'].'" ><span>'.$menu['title'].'</span></a>';            

    if(@$menus[$key+1]['lavel'] > $menu['lavel']){                
        echo '<ul>';
        $ul_count++;
    }
    elseif(@$menus[$key+1]['lavel'] < $menu['lavel']){
        while($ul_count > 0){
            echo '</ul>';
            $ul_count--;
        }               
    }
    else{
        echo '</li>';
    }

}

echo '</ul>';


?>