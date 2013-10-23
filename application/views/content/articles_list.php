<?php

echo '<ul class="articles_list" >';
        
foreach($articles as $article){
    
    echo '<li>';
    echo '    <a href="'.$menu['link'].'/article/'.$article['alias'].'" >'.$article['title'].'</a>';
    echo '</li>';

}

echo '</ul>';

?>
