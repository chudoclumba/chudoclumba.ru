//var cm_bt = 0;
if(typeof cm_style == "undefined") { // проверяем, указан ли темплейт
	var cm_style = "default"; 
}

//document.write(unescape("%3Clink rel='stylesheet' href='/callme/templates/"+cm_style+"/style.css' type='text/css'%3E")); 

if(!window.jQuery) { 
document.write(unescape("%3Cscript src='/callme/js/jquery.js' type='text/javascript'%3E%3C/script%3E")); 
}

document.write(unescape("%3Cscript src='/callme/js/core.js' charset='utf-8' type='text/javascript'%3E%3C/script%3E")); 