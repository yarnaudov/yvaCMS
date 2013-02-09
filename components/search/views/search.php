
<?php !isset($articles) ? $articles = array() : ""; ?>

<div><?=lang('label_search_for');?> "<?=isset($query) ? $query : ""; ?>"</div>

<?php if(count($articles) == 0){ ?>

<div><br/><?=lang('search_msg_no_results_found');?></div>

<?php }else{ ?>

<br/>
<ol>  

    <?php foreach($articles as $article){ 
            $menu = $this->Menu->getByArticle($article['id']); ?>

    <li>
        <?php if($menu){ ?>
        <a href="<?=site_url($menu['alias']);?>" >
        <?php }else{ ?>
        <a href="<?=site_url('article:'.$article['alias']);?>" >
        <?php } ?>
            <?=$article['title'];?>
        </a>
        <div><?=word_limiter(strip_tags($article['text']), 10);?></div>
    </li>

    <?php } ?>

</ol> 

<?php } ?>