/* jQuery.Callme: author Nazar Tokar * www.dedushka.org * Copyright 2010-2013 */
/* jQuery.Storage: author Dave Schindler * Distributed under the MIT License * Copyright 2010 */
 
(function(jQuery) {
var isLS=typeof window.localStorage!=='undefined';
function wls(n,v){var c;if(typeof n==="string"&&typeof v==="string"){localStorage[n]=v;return true;}else if(typeof n==="object"&&typeof v==="undefined"){for(c in n){if(n.hasOwnProperty(c)){localStorage[c]=n[c];}}return true;}return false;}
function wc(n,v){var dt,e,c;dt=new Date();dt.setTime(dt.getTime()+31536000000);e="; expires="+dt.toGMTString();if(typeof n==="string"&&typeof v==="string"){document.cookie=n+"="+v+e+"; path=/";return true;}else if(typeof n==="object"&&typeof v==="undefined"){for(c in n) {if(n.hasOwnProperty(c)){document.cookie=c+"="+n[c]+e+"; path=/";}}return true;}return false;}
function rls(n){return localStorage[n];}
function rc(n){var nn, ca, i, c;nn=n+"=";ca=document.cookie.split(';');for(i=0;i<ca.length;i++){c=ca[i];while(c.charAt(0)===' '){c=c.substring(1,c.length);}if(c.indexOf(nn)===0){return c.substring(nn.length,c.length);}}return null;}
function dls(n){return delete localStorage[n];}
function dc(n){return wc(n,"",-1);}

jQuery.extend({	Storage: {
	set: isLS ? wls : wc,
	get: isLS ? rls : rc,
	remove: isLS ? dls :dc
}
});
})(jQuery); 

function cmePr(o,i,t){
	jQuery(o).animate({ opacity: i }, t);
} 
// opacity animate

function cmeMsg(c,t){
	jQuery("#callme_result").html("<div class='"+c+"'>"+t+"</div>");
}
// set status

function cmeClr(){ 
	jQuery(".cme_txt").val("");
	jQuery("#cphone").val("+7");
} 
// clear form

function cmeShw(e){ 
	jQuery("#cme_form").css("position","absolute");
	if(jQuery("#cme_form").is(":visible")) {
		jQuery("#cme_form").fadeOut("fast");
		jQuery("#cme_back").fadeOut("fast");
		return false;
	}
	
	var dh = jQuery(document).height(); // считаем отступ сверху
	tp_cr = e.pageY + 20;
	tp = dh - e.pageY;
	if (tp < 300) { 
		tp_cr = dh - 280; 
	} // если близко к низу
	
	var dw = jQuery(window).width(); // считаем отступ слева
	lf_cr = e.pageX - 150;
	lf = dw - e.pageX;
		
	if (lf < 300) { 
		lf_cr = dw - 350; 
	} // если близко к правому
	
	if (e.pageX < 300) { 
		lf_cr = e.pageX + 20; 
	} // если близко к левому
	
	jQuery("#cme_form").css("left", lf_cr);
	jQuery("#cme_form").css("top", tp_cr);
	jQuery("#cme_back").css("height", jQuery(document).height());
	jQuery("#cme_back").fadeToggle("fast");
	jQuery("#cme_form").fadeToggle("fast");
	cmeClr();
} 
// show/hide

jQuery(document).on("mouseover", ".cme_btn", function(){ 
	cmePr(".cme_btn", 0.8, 150);
}).on("mouseleave", ".cme_btn", function(){
	cmePr(".cme_btn", 1, 100);
}); 
// button opacity

function cmeS() {
	var cnt = jQuery.Storage.get('callme-sent'); // load sent time
	if (!cnt) { cnt = 0; }
	
	jQuery.getJSON("/callme/index.php", {
		cname: jQuery("#cname").val(), 
		cphone: jQuery("#cphone").val(), 
		ccmnt: jQuery("#ccmnt").val(), 
		ctime: cnt, 
		url: location.href 
	}, function(i) {	
		cmeMsg(i.cls,i.message);		
		if (i.result == "success") {
			jQuery.Storage.set("callme-sent", i.time);
			setTimeout( function(){ cmeShw(); }, 4000);
			setTimeout( function(){ cmeClr(); }, 5000);
		}
	});
}
// send data

jQuery(document).on("click",".callme_viewform", function(e){ 
	cmeShw(e);
	return false;
}); 
// click show form link

jQuery(document).on("mouseover", "#viewform", function(){
	cmePr("#viewform", 0.8, 100);
}).on("mouseout", "#viewform", function(){
	cmePr("#viewform", 1, 100);
}); 
// change right btn class

jQuery(document).on("click", "#cme_cls", function(e){
	cmeShw(e);
	return false;
}).on("mouseover", "#cme_cls", function(){
	cmePr("#cme_cls", 0.6, 100);
}).on("mouseleave", "#cme_cls", function(){
	cmePr("#cme_cls", 1, 100);
});
// close button

jQuery(function(){
});

jQuery(document).on("click", "#cme_back", function(){
	cmeShw();
}); 
// bg click

jQuery(document).ready(function(){

/* загрузка формы */

jQuery.ajaxSetup({'beforeSend' : function(xhr) {
		xhr.overrideMimeType('text/html; charset=UTF-8');
	},
}); 
// выбираем правильную кодировку

jQuery.get("/callme/form.html", function(data) {
	jQuery("body").append(data);
//	if (cm_bt==0) {
//		jQuery("#viewform").hide();
//	}
}); 
// вставка формы

jQuery(document).on("click",".cme_btn", function(){
	cmeMsg("sending","Идёт отправка...");
	setTimeout( function(){ cmeS(); }, 2000);
	return false;
});	
}); 
// отправка уведомления

jQuery(document).keyup(function(a) {
	if (a.keyCode == 27) {
		if(jQuery("#cme_form").is(":visible")) {
			cmeShw();
		}
} 
//обработка esc

});