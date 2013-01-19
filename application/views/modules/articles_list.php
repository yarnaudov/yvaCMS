<?php

echo '<ul>';
        
foreach($articles as $article){

    /* --- check language for article display --- */
    if($article['language_id'] != NULL && $article['language_id'] != $this->Language->getDetailsByAbbr(get_lang(), 'language_id')){
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
    echo '    <a href="'.site_url('article:'.$article['alias']).'" >'.$article['title_'.get_lang()].'</a>';
    echo '</li>';

}

echo '</ul>';

?>
