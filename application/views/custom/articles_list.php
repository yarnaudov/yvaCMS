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
    
    $images = $this->Image->getImagesByArticle($article['article_id']);
    
    $image = '';
    if(isset($images[0])){
        $image = '<img src="'.base_url('images/thumbs/'.$images[0]['image_id'].'.'.$images[0]['ext']).'" alt="'.$images[0]['title_'.get_lang()].'" title="'.$images[0]['title_'.get_lang()].'" >';
    }
    else{
        $image = '<img src="'.base_url('img/no_photo.jpg').'" >';
    }

    
    echo '<li '.($class != '' ? 'class="'.$class.'"' : '').' >';
    echo '    <div class="image" >'.$image.'</div>';
    echo '    <div class="content" >';
    echo '      <div class="title" >';
    echo '        <a href="'.site_url('article:'.$article['alias']).'" >'.$article['title_'.get_lang()].'</a>';
    echo '      </div>';
    echo '      <div class="text" >'.$article['text_'.get_lang()].'</div>';
    echo '    </div>';
    echo '</li>';
    
 

}

echo '</ul>';

?>
