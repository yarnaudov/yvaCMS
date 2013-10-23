
<?php !isset($articles) ? $articles = array() : ""; ?>

<div><?php echo lang('label_search_for');?> "<?php echo isset($query) ? $query : ""; ?>"</div>

<?php if(count($articles) == 0){ ?>

<div><br/><?php echo lang('search_msg_no_results_found');?></div>

<?php }else{ ?>

<br/>
<ol>  

    <?php foreach($articles as $article){ 
            $menu = $this->Menu->getByArticle($article['id']); ?>

    <li>
        <?php if($menu){ ?>
        <a href="<?php echo site_url($menu['alias']);?>" >
        <?php }else{ ?>
        <a href="<?php echo site_url('article/'.$article['alias']);?>" >
        <?php } ?>
            <?php echo $article['title'];?>
        </a>
        <div><?php echo word_limiter(strip_tags($article['text']), 10);?></div>
    </li>

    <?php } ?>

</ol> 

<?php } ?>