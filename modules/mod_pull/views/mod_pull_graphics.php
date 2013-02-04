
<script type="text/javascript" src="<?=base_url('modules/mod_pull/js/mod_pull.js');?>" ></script>

<ul class="pull<?=$pull['id'];?>" >
	
    <li><?=$pull['title'];?></li>

    <?php foreach($pull['answers'] as $key => $answer){ ?>
    <li>
        <span class="number" ><?=$key+1;?>.</span>
        <?=$answer['title'];?> - 
        <?=$votes == 0 ? 0 : round(($answer['votes']*100)/$votes, 1);?>%
    </li>
    <?php } ?>
	  
    <li><?=lang('label_mod_pull_total_votes');?>: <?=$votes;?></li>

    <?php if($answer_id != false){ ?>
    <li>         
        <?=lang('label_mod_pull_you_already_voted_for');?>:
        <span class="pull_answer" ><?=pull::getAnswer($answer_id, 'title');?></span>
    </li>
    <?php }else{ ?>
    <li>	      
        <div class="actions" >
            <a href="<?=current_url();?>" class="hide_votes" ><?=lang('label_mod_pull_back_to_pull');?></a>
        </div>
        <div class="loading" style="display: none;" >
            <span><?=lang('label_mod_pull_please_wait');?></span>
            <img src="<?=base_url('modules/mod_pull/images/loading.gif');?>" alt="loading..." >
        </div>
    </li>
    <?php } ?>
	  
</ul>
