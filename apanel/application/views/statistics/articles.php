
<!--[if IE]><script language="javascript" type="text/javascript" src="<?=base_url('js/jqplot/excanvas.js');?>"></script><![endif]-->

<form name="list" action="<?=current_url(true);?>" method="post" >
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconArticles_25.png');?>" >
            <span><?=lang('label_articles');?></span>
	    <span>&nbsp;»&nbsp;</span>
            <span><?=lang('label_statistics');?>Statistics</span>
        </div>
	
	<div class="actions" >
            <a href="<?=site_url('articles');?>" class="styled cancel" ><?=lang('label_cancel');?></a>            
	</div>
        
    </div>
    <!-- end page header -->
    
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>
    
    <!-- start page content -->
    <div id="page_content" >

	<div id="filter_content" >
		
            <div class="search" >
		<label><?=lang('label_from');?>:</label>
		<input type="text" class="start_date datepicker" name="filters[start_date]" value="<?=$filters['start_date'];?>" >
		<label><?=lang('label_to');?>:</label>
		<input type="text" class="end_date datepicker" name="filters[end_date]" value="<?=$filters['end_date'];?>" >
                <button class="styled" type="submit" name="search" ><?=lang('label_show');?></button>
            </div>
		
            <div class="filter" >
			
                <select name="filters[article]" >
                    <option value="none" > - <?=lang('label_select');?> <?=lang('label_article');?> - </option>
                    <?=create_options_array($articles, isset($filters['article']) ? $filters['article'] : "");?>
                </select>

            </div>
		
	</div>
	
	<br/>
	<div id="chart1" style="height:300px;margin-right: -7px;"></div>
	
	<script class="code" type="text/javascript">
	    
	    var line1= JSON.parse('<?=json_encode($line1);?>');
	    //console.log(line1);
	    var plot1 = $.jqplot ('chart1', [line1], {
		seriesColors: [ "#0D82DB", "#EAA228"],
		axes:{
		    xaxis:{
			renderer: $.jqplot.DateAxisRenderer,
			tickOptions:{
                            formatString:'%d/%m',
                            markSize: 1,
			    fontSize: '9pt'
                        },
			min: '<?=$filters['start_date'];?>',
			max: '<?=$filters['end_date'];?>',
			tickInterval: '1 day'
		    },
		    yaxis:{
                        tickOptions:{
                            markSize: 0,
			    fontSize: '9pt'
                        },
                        min: 0,
                        max: <?=($max_views+1);?>
		    }
		},
		series:[{
                    lineWidth: 1,
		    label: '<?=lang('label_read');?>'
                }],
		highlighter: {
		    show: true,
		    sizeAdjust: 10.0,
		    lineWidthAdjust: 5.5
		},
		cursor: {
		    show: false
		},
                grid: {
                    borderWidth: 0,
                    shadow: false
                },
		legend: {
		    show: true,
		    location: 'ne',     // compass direction, nw, n, ne, e, se, s, sw, w.
		    xoffset: 12,        // pixel offset of the legend box from the x (or x2) axis.
		    yoffset: 12,        // pixel offset of the legend box from the y (or y2) axis.
		}
	    });
	    
	    $(window).on('resize load', function() {
		plot1.replot();
	    });

	    $( ".start_date" ).datepicker({
		showOn: 'focus',
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		onClose: function( selectedDate ) {
		  $( ".end_date" ).datepicker( "option", "minDate", selectedDate );
		}
	    });
	    $( ".end_date" ).datepicker({
		showOn: 'focus',
		dateFormat: 'yy-mm-dd',
		numberOfMonths: 1,
		maxDate: 0,
		onClose: function( selectedDate ) {
		  $( ".start_date" ).datepicker( "option", "maxDate", selectedDate );
		}
	    });
	    
	    $( ".start_date" ).datepicker( "option", "maxDate", "<?=$filters['end_date'];?>" );
	    $( ".end_date" ).datepicker( "option", "minDate", "<?=$filters['start_date'];?>" );
		    
	</script>
	
        
        <style>
            .search{
                float: right !important;
            }
            .search label{
                float: left;
                display: block;
                margin: 3px 3px 0 10px;
            }
            .filter{
                float: left !important;
            }
	    .filter select{
		max-width: 300px;
	    }
        </style>
        
        <br/><br/>
        <table class="list" cellpadding="0" cellspacing="2" >
            
            <tr>
                <th style="width:1%;"  >#</th>
                <th style="width:30%;" ><?=lang('label_date');?></th>
                <th style="width:69%;"  ><?=lang('label_read');?></th>
            </tr>
            
            <?php foreach($statistics as $numb => $statistic){ ?>
            <tr>
                <td><?=($numb+1);?></td>
                <td><?=$statistic['date'];?></td>
                <td><a class="details" href="<?=site_url('statistics/details/article/'.$filters['article'].'/'.$statistic['date']);?>" ><?=$statistic['views'];?></a></td>
            </tr>
                
            <?php } ?>
		
            <tr>
                <th colspan="2" ></th>
                <th><?=lang('label_total');?>: <?=$total_views;?></th>
            </tr>
                
	    <?php if(count($statistics) == 0){ ?>
	    <tr>
                <td colspan="3" >No data to display</td>
            </tr>
	    <?php } ?>
            
        </table>

    </div>
    <!-- end page content -->
    
</form>

<!-- start jquery UI -->
<div id="dialog-statistic-details" title="<?=lang('label_details');?>" >
    <p></p>
</div>