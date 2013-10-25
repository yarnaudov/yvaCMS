<?php

echo '<ul>';
        
foreach($articles as $article){    
    
    $class = '';
    if($article['alias'] == $article_alias){
        $class = 'current';
    }
    
    echo '<li '.($class != '' ? 'class="'.$class.'"' : '').' >';
    echo '    <a href="'.site_url('article:'.$article['alias']).'" >'.$article['title'].'</a>';
    echo '    <div>'.$article['text'].'</div>';
    echo '</li>';

}

echo '</ul>';

?>
