
<script type="text/javascript" src="<?=base_url('modules/mod_poll/js/mod_poll.js');?>" ></script>

<ul class="poll<?=$poll['id'];?>" >
	
    <li><?=$poll['title'];?></li>

    <?php foreach($poll['answers'] as $key => $answer){ ?>
    <li>
        <span class="number" ><?=$key+1;?>.</span>
        <?=$answer['title'];?> - 
        <?=$votes == 0 ? 0 : round(($answer['votes']*100)/$votes, 1);?>%
    </li>
    <?php } ?>
	  
    <li><?=lang('label_mod_poll_total_votes');?>: <?=$votes;?></li>

    <?php if($answer_id != false){ ?>
    <li>         
        <?=lang('label_mod_poll_you_already_voted_for');?>:
        <span class="poll_answer" ><?=poll::getAnswer($answer_id, 'title');?></span>
    </li>
    <?php }else{ ?>
    <li>	      
        <div class="actions" >
            <a href="<?=current_url();?>" class="hide_votes" ><?=lang('label_mod_poll_back_to_poll');?></a>
        </div>
        <div class="loading" style="display: none;" >
            <span><?=lang('label_mod_poll_please_wait');?></span>
            <img src="<?=base_url('modules/mod_poll/images/loading.gif');?>" alt="loading..." >
        </div>
    </li>
    <?php } ?>
	  
</ul>
