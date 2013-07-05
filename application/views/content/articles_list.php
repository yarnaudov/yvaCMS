<?php

echo '<ul class="articles_list" >';
        
foreach($articles as $article){
    
    echo '<li>';
    echo '    <a href="'.site_url($menu['alias'].'/article:'.$article['alias']).'" >'.$article['title'].'</a>';
    echo '</li>';

}

echo '</ul>';

?>
