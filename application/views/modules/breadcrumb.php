<?php

if(count($menus) > 0 || $article_alias != ''){

    
    echo '<span>'.$text.'</span>';
    
    echo '<ul>';

    echo '    <li>';
    echo '        <a href="'.site_url($this->Menu->getDefault('alias')).'" >'.$this->Menu->getDefault('title_'.get_lang()).'</a>';
    echo '    </li>';

    $numb = 1;
    foreach($menus as $menu){
        
        echo '    <li class="separator" >'.$separator.'</li>';
        
        if(count($menus) == $numb && $article_alias == ''){
            echo '    <li class="current" >'.$menu['title'].'</li>'; 
        }
        else{
            echo '    <li>';
            echo '        <a href="'.$menu['link'].'" >'.$menu['title'].'</a>';
            echo '    </li>'; 
        }
        
        $numb++;
        
    }
    
    if($article_alias != ''){
        
        echo '    <li class="separator" >'.$separator.'</li>';
        echo '    <li class="current" >'.$this->Article->getByAlias($article_alias, 'title_'.get_lang()).'</li>';
        
    }
    
    echo '</ul>';

}
 
?>
