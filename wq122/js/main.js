c_url = location.href;

/*@cc_on
   /*@if (@_win32)

	function check_url()
	{
	
		if(c_url != location.href && js_start == 1)
		{
			if($('editor1'))
			{
				tinyMCEDeinit('editor1');
			}
			
			if($('editor2'))
			{
				tinyMCEDeinit('editor2');		
			}
		
			run_href(location.href);
			c_url = location.href;
		}
		setTimeout("check_url()", 25);
	}
	
	function gotourl(url)
	{
		location.href = url;
		document.title = url;
		
		var g = url.split("#");
		document.getElementById('cframe').src = '<?php echo SA_URL; ?>ie_url.php?' + g['0'] + '_and_' + g['1'] + '';
	}
	
@else @*/
function check_url()
{
	if(c_url != location.href && js_start == 1)
	{
		if($('editor1'))
		{
			tinyMCEDeinit('editor1');
		}
		
		if($('editor2'))
		{
			tinyMCEDeinit('editor2');		
		}
		
		run_href(location.href, 1);
		c_url = location.href;
	}
	setTimeout("check_url()", 25);
}


function gotourl(url)
{
		if($('editor1'))
		{
			tinyMCEDeinit('editor1');
		}
		
		if($('editor2'))
		{
			tinyMCEDeinit('editor2');		
		}
		
	location.href = url;
}
	
   /*@end
@*/

function run_href(url, value)
{
	var g = url.split("#");
	eval(unescape(g[1]));
}

var start_e = 0;
function get_e(e)
{
	if(start_e == 1)
	{
		var lm1 = document.getElementById('lm1');
		//alert(e.clientX);
		//alert(lm1.clientWidth);
		lm1.style.width = e.clientX - 3;
	}
}
function start_ev()
{
	start_e = 1;
	var lmt = document.getElementById('mtbl');
	lmt.style.cursor="move";
} 

function stop_ev()
{
	start_e = 0;
	var lmt = document.getElementById('mtbl');
	lmt.style.cursor="default";
} 

jQuery.preloadImages = function()
{
  for(var i = 0; i<arguments.length; i++)
  {
    jQuery("<img>").attr("src", arguments[i]);
  }
}

stp = 0;
timer = 0;
js_start = 0;
function tinyMCEInit(id){
    tinyMCE.execCommand( 'mceAddControl', true, id);
}

function tinyMCEDeinit(id){
    tinyMCE.execCommand( 'mceRemoveControl', true, id);  
}


function setup()
{

}

old_s = 0;

function rec_spl(vid)
{
	var g1 = document.getElementById('cat_s_' + old_s);
	var g2 = document.getElementById('cat_s_' + vid);
	if(g1) g1.style.display = "none";
	if(g2) g2.style.display = "block";
	old_s = vid;
}

var isCtrl = false;

function test(val)
{
	document.getElementById($("iframe[id^='mce']:first").attr('id')).contentWindow.document.getElementById('href').value = val;
}

$(document).keyup(function (e) {
	if(e.which == 17) isCtrl=false;
}).keydown(function (e) {
	if(e.which == 17) {
		isCtrl=true;
		
	}

	if(e.which == 65 && isCtrl == true) {
		if($("#markp").attr('checked') == false)
		{
			$("#markp").attr({'checked' : true })
		}
		else
		{
			$("#markp").attr({'checked' : false })
			
		}
		$("input[name^='box']").attr({'checked' : $("#markp").attr('checked') });
		return false; 
	}
	else if(e.which == 46 && isCtrl == true)
	{
		if (confirm('Вы действительно хотите удалить выделенные категории/товары?')) { Deleting();}
	}	
}); 

$(document).ready(function() {
	$("#loader").css({'display' : 'none' });
});


