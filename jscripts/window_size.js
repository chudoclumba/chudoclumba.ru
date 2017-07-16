function getClientWidth()
{
  return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth;
}

function getClientHeight()
{
  return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight;
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

function getClientFullHeight()
{
	return parseInt(getClientHeight())+getBodyScrollTop();
}

function getElHeight(id)
{
	if(document.getElementById(id))
	{
		return parseInt(document.getElementById(id).offsetHeight);
	}
}

