function getClientWidth()
{
  return (document.compatMode=='CSS1Compat' && !window.opera) ?document.documentElement.clientWidthocument.body.clientWidth : document.body.clientWidth;
}

function getClientHeight()
{
  return (document.compatMode=='CSS1Compat' && !window.opera) ?document.documentElement.clientHeightocument.body.clientHeight : document.body.clientHeight; 
}

function getBodyScrollTop()
{
  return self.pageYOffset || (document.documentElement && document.documentElement.scrollTop) || (document.body && document.body.scrollTop);
}

function getBodyScrollLeft()
{
  return self.pageXOffset || (document.documentElement && document.documentElement.scrollLeft) || (document.body && document.body.scrollLeft);
}

function getClientCenterX()
{
    return parseInt(getClientWidth()/2)+getBodyScrollLeft();
}

function getClientCenterY()
{
    return parseInt(getClientHeight()/2)+getBodyScrollTop();
}

//Enter "frombottom" or "fromtop"
var verticalpos="fromtop"
var MaxTopScroll=$(".lci").height()+110;
function JSFX_FloatTopDiv()
{
    var startX = 0;
    var startY = 140;
    var ns = (navigator.appName.indexOf("Netscape") != -1);
    var d = document;
    function ml(id)
    {
	var el=d.getElementById?d.getElementById(id):d.all?d.all[id]:d.layers[id];
	if(d.layers)el.style=el;
	
	el.sP=function(x,y){
		$(this).css({'left' : x, 'top' : y});
	};
	
	el.x = startX;
	if (verticalpos=="fromtop")
	el.y = startY;
	else{
	el.y = ns ? getBodyScrollTop() + innerHeight : getBodyScrollTop() + document.body.clientHeight;
	el.y -= startY;
	}
	return el;
    }
    window.stayTopLeft=function()
    {
	if (verticalpos=="fromtop"){
	var pY = ns ? getBodyScrollTop() : getBodyScrollTop();
        pY = (getBodyScrollTop()>MaxTopScroll) ? pY : MaxTopScroll;
	ftlObj.y += (pY + startY - ftlObj.y)/15;
	}
	else{
	var pY = ns ? getBodyScrollTop() + innerHeight : getBodyScrollTop() + document.body.clientHeight;
	ftlObj.y += (pY - startY - ftlObj.y)/15;
	}
	ftlObj.sP(ftlObj.x, ftlObj.y);
	setTimeout("stayTopLeft()", 10);
    }
    ftlObj = ml("divStayTopLeft");
    stayTopLeft();
}
JSFX_FloatTopDiv();