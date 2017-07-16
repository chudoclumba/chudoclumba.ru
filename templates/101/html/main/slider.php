<?
	$res=$this->db->get(TABLE_SLIDERS,array('enabled'=>1),array('ord'=>'asc'));
	$res1=$this->db->get(TABLE_SLBANNERS,array('enabled'=>1),array('place'=>'asc'));
?>
<!-- home-2 slider area start  -->   
<div class="slider-area mt30">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-sm-12 col-xs-12">
				<div class="left-slide">
					<!-- slider -->
					<div class="slider-area">
                    <iframe width="100%" height="426" src="https://www.youtube.com/embed/zJdeZluku7Q?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
<?php /*?>						<div class="bend niceties preview-2">
							<div id="ensign-nivoslider-2" class="slides" style="display: none">	
<?
	$di='';
	$cnt=1;
	foreach($res as $val){
		$di.='<div id="slider-direction-'.$cnt.'" class="t-cn slider-direction">
					
				<a href="'.SITE_URL.$val['vlink'].'"><div class="slider-content t-lfl slider-'.$cnt.'">
						<div class="title-container">
							<h1 class="title1">'.$val['str1'].'</h1>
							<h3 class="title3">'.$val['str2'].'</h3>
							
						</div>
					</div></a>
			</div>';
?>		
			<img src="<?=$val['foto']?>" alt="" title="#slider-direction-<?=$cnt?>" />

<?	
		$cnt++;
	}	?>
							</div>
<? 	echo $di; ?>						
						</div>
						<?php */?>
					</div>
					<!-- slider end--> 
				</div>
			</div>
<? if (count($res1)>0){?>
	

			<div class="col-md-4 col-sm-12 col-xs-12">
				<div class="left-slide-add">
					<div class="single-add vina-banner">
						<a href="<?=$res1[0]['vlink']?>"><img src="thumb.php?id=<?=$res1[0]['foto']?>&x=360&y=426&crop" alt=""></a>
					</div> 
<? if (isset($res1[1])){?>
	
<?php /*?>					<div class="single-add vina-banner mt20">
						<a href="<?=$res1[1]['vlink']?>"><img src="thumb.php?id=<?=$res1[1]['foto']?>&x=369&y=207&crop" alt=""></a>
					</div><?php */?> 
<?}?>
				</div>
			</div>
<?}?>
		</div>
	</div>
</div>                          
<script>
$('#ensign-nivoslider-2').nivoSlider({
				effect: 'fade',
				slices: 15,
				boxCols: 8,
				boxRows: 4,
				animSpeed: 500,
				pauseTime: 5000,
				startSlide: 0,
				directionNav: true,
				controlNavThumbs: true,
				pauseOnHover: true,
				manualAdvance: false
			 });
$(document).ready(function(){ $('#ensign-nivoslider-2').fadeIn(200);});			 

</script>                         
<!-- home-2 slider area end -->  
