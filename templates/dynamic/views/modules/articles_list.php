<?php

echo '<ul>';
        
foreach($articles as $article){

    /* --- check language for article display --- */
    if($article['show_in_language'] != NULL && $article['show_in_language'] != $this->Language->getDetailsByAbbr(get_lang(), 'id')){
        continue;
    }            

    /* --- check start end date for article display --- */
    if($article['start_publishing'] != NULL && $article['start_publishing'] > date('Y-m-d')){
        continue;
    }
    elseif($article['end_publishing'] != NULL && $article['end_publishing'] < date('Y-m-d')){
        continue;
    }     
    
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
