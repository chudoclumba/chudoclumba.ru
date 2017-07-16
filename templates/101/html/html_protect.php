<script type="text/javascript">
function disableSelection(target){
if (typeof target.onselectstart!="undefined")
    target.onselectstart=function(){return false}
else if (typeof target.style.MozUserSelect!="undefined")
    target.style.MozUserSelect="none"
else
    target.onmousedown=function(){return false}
target.style.cursor = "default"
}

if (document.getElementById("noselect")) {
disableSelection(document.getElementById("noselect"));
}

if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
}
 function rtclickcheck(keyp){
  if (document.layers && keyp.which != 1) {
    //alert(mymessage);
    return false;
  }
  if (document.all && event.button != 1) { 
    //alert(mymessage);
    return false;
  }
}
document.onmousedown = rtclickcheck;


$('img').oncontextmenu=function(){
return false;
}

$('img').ondragstart=function(){
return false;
}

</script>