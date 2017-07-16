<div class="fotogall">
<div style="position:relative;">
<?php

$fotos = $this->get_photo();
foreach( $fotos as $id=>$foto )
{
	echo '<div id="pht_'.$id.'" style="position:absolute; opacity:1; width:400; height:391; z-index:1000; left:0px; top:0px; background:#FBE29F url('.SITE_URL.'thumb.php?id=data/images/'.$foto.'&x=400&y=391) center no-repeat">&nbsp;</div>';
}

?>
<script type="text/javascript">
var img = <?php echo (count($fotos)-1)?>;
function set_opacity(did, val)
{
	document.getElementById(did).style.opacity = val;
	setElementOpacity(document.getElementById(did), val);
}

function set_zindex(did, val)
{
	document.getElementById(did).style.zIndex = val;
}

function get_opacity(did)
{
	return document.getElementById(did).style.opacity;
}

function get_zindex(did)
{
	return document.getElementById(did).style.zIndex;
}

function chop()
{
	val = get_opacity('pht_' + img) - 0.05;
	set_opacity('pht_' + img, val);
	
	if(val > 0 )
	{
		setTimeout('chop()', 150);
	}
	else
	{
		set_zindex('pht_' + img, get_zindex('pht_' + img) - 1);
		set_opacity('pht_' + img, 1);

		img--;
		if(img < 0)
		{
			img = <?php echo (count($fotos)-1)?>;
		}
		setTimeout('chop()', 1300);
	}
}

setTimeout('chop()', 2000);
</script>
</div>