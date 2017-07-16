<? if(count($msgs) > 0) { ?>
<div class="review-upper">
<? foreach($msgs as $msg) { ?>
<div class="review_item">
<div><span class="review_user"><strong><?=htmlspecialchars($msg['name'],ENT_COMPAT | ENT_XHTML,'utf-8')?></strong></span>, <span class="review_time"><?
		 
		 $mes = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
		 
		 $di = explode(' ', $msg['date']);
		 $di2 = explode('-', $di['0']);
		 echo intval($di2['2']).' '.$mes[intval($di2['1'])-1].' '.$di2['0'].' г.';
		 
		 ?></span></div>
<div class="review_text"><p><?=nl2br(htmlspecialchars($msg['msg'],ENT_COMPAT | ENT_XHTML,'utf-8'))?></p></div>
</div>
<? } ?>                                            
</div><? } ?>

