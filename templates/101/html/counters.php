<?
global $gcounter;
$st=SITE_URL;
if (stripos($st,'test.chudo')===false)
 { ?>
<div class="counter" id="counter">

<? if (isset($_SESSION['script'])) {
	echo $_SESSION['script'];
	unset($_SESSION['script']);
}?>
</div><? } ?>