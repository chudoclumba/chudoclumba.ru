// Incomedia WebSite X5 2007. All rights reserved.
var imBrw_op=window.opera;
var imBrw_ie=document.all && !imBrw_op;
var imBrw_ns=document.getElementById && !imBrw_ie;
var imEffectEnabled = /MSIE [678]/.test(navigator.userAgent) && navigator.platform == "Win32";
var imHoverToEnable = /MSIE (5\.5)|[6]/.test(navigator.userAgent) && navigator.platform == "Win32";
var mbTipOk = false;
function imGetLayer(sName) {return document.all?document.all[sName]:document.getElementById?document.getElementById(sName) : "";}
function imIEBody(){return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body}
function imOpenLocation(sNewLocation){
document.location = sNewLocation;
}
function imGetParameter(sParamName) {
var sQueryString = "";
var iStart = 0;
var iEnd = 0;
if (window.top.location.search != 0)
sQueryString = unescape(window.top.location.search);
sParamName = sParamName + "=";
if (sQueryString.length > 0) {
iStart = sQueryString.indexOf(sParamName);
if ( iStart != -1 ) {
iStart += sParamName.length;
iEnd = sQueryString.indexOf("&",iStart);
if (iEnd == -1)
iEnd = sQueryString.length;
return sQueryString.substring(iStart,iEnd);
}
return null;
}
}
document.onmouseover=imTipMove;
function imTipShow(oLink,sTitle,iWidth,sImage,sBackColor,sForeColor,sBorderdColor,iTextSize,bDoFade) {
if (imBrw_ns||imBrw_ie){
var oTip=imGetLayer("imToolTip");
oLink.title = '';
if (sImage != "") sImage = "<img src=\"" + sImage + "\" /><br />";
sStyle = (sImage == "") ? "text-align: left; white-space: nowrap;": "text-align: center; width: "+iWidth+"px;";
oTip.innerHTML = "<div><div style=\"padding: 3px; background-color: " +sBackColor+ "; color: " +sForeColor+ "; border: 1px solid " +sBorderdColor+"; font: "+(iTextSize*2+6)+"pt Tahoma, Arial; "+sStyle+"\">" + sImage + sTitle +"</div></div>";
mbTipOk=true;
if (imBrw_ie || imBrw_ns || imBrw_op) {
iFadeStep=0;
if (bDoFade) imTipDoFade();
}
}
return false;
}
function imTipHide(){
if (imBrw_ns||imBrw_ie){
var oTip=imGetLayer("imToolTip");
mbTipOk=false;
oTip.style.visibility='hidden';
oTip.style.left='-1000px';
}
}
function imTipMove(e){
if (mbTipOk) {
var oTip=imGetLayer("imToolTip");
var offsetxpoint=-60;
var offsetypoint=20;
var curX=(imBrw_ns)? e.pageX : event.clientX + imIEBody().scrollLeft;
var curY=(imBrw_ns)? e.pageY : event.clientY + imIEBody().scrollTop;
var rightedge=imBrw_ie&&!imBrw_op? imIEBody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20;
var bottomedge=imBrw_ie&&!imBrw_op? imIEBody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20;
var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000;
if (rightedge<oTip.offsetWidth)
oTip.style.left=imBrw_ie? imIEBody().scrollLeft+event.clientX-oTip.offsetWidth+"px" : window.pageXOffset+e.clientX-oTip.offsetWidth+"px";
else if (curX<leftedge)
oTip.style.left="5px";
else
oTip.style.left=curX+offsetxpoint+"px";
if (bottomedge<oTip.offsetHeight)
oTip.style.top=imBrw_ie? imIEBody().scrollTop+event.clientY-oTip.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-oTip.offsetHeight-offsetypoint+"px";
else
oTip.style.top=curY+offsetypoint+"px";
oTip.style.visibility="visible";
}
}
function imTipDoFade(){
if (iFadeStep<=100){
var oTip=imGetLayer("imToolTip");
iFadeStep+=15;
if(imBrw_ie)
oTip.style.filter = "alpha(opacity=" + iFadeStep + ")";
else
oTip.style.opacity = iFadeStep/100;
oTime=setTimeout('imTipDoFade()', 50);
}
}
function imOnload() {
if (document.getElementById("imMnMn") != null) {
if (document.getElementsByTagName) {
if (imHoverToEnable) {
var oList = document.getElementById("imMnMn").getElementsByTagName("LI");
for (var i=0; i<oList.length; i++) {
oList[i].onmouseover=function() {this.className+=" iehover";}
oList[i].onmouseout=function() {this.className=this.className.replace(new RegExp(" iehover\\b"), "");}
}
}
var objs = document.getElementsByTagName("object");
for (i=0; i<objs.length; i++) {
if (objs[i].name == 'imFlash') {objs[i].outerHTML = objs[i].outerHTML;}
}
}
}
}
if (window.attachEvent) window.attachEvent("onload", imOnload);
function imPreloadImages(sImgNames) {
sNames = sImgNames.split(",");
for(iList = 0 ; iList < sNames.length ; iList++) {
var oImg = new Image();
oImg.src = sNames[iList];
}
}
function imFormatInt(i) {
if (i<10) i='0'+i;
return i;
}
function imShowHour() {
var now=new Date();
imGetLayer("imHour").innerHTML = now.getHours()+':'+imFormatInt(now.getMinutes())+':'+imFormatInt(now.getSeconds())+' ';
setTimeout(imShowHour,1000);
}
function imShowDate(sDay,sMonth,iMode) {
var now=new Date();
if (iMode == 0)
document.write(sDay.substr(now.getDay()*3,3)+' '+now.getDate()+' '+sMonth.substr(now.getMonth()*3,3)+', '+now.getFullYear());
else
document.write(sDay.substr(now.getDay()*3,3)+', '+sMonth.substr(now.getMonth()*3,3)+' '+now.getDate()+' '+now.getFullYear());
}
function imPopUpWin(sUrl,w,h,cb,sb){
if (cb=='yes')
sProp='';
else {
if ((w==-1) || (h==-1)) {
sProp= 'width='+screen.width+',height='+screen.height+',top=0,left=0,scrollbars=no,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no';
} else {
l=(screen.width)?(screen.width-w)/2:100;
t=(screen.height)?(screen.height-h)/2:100;
sProp='width='+ w +',height='+ h +',top='+ t +',left='+ l +',scrollbars='+ sb +',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no';
}
}
oWin=window.open(sUrl,'',sProp);
oWin.focus();
}
var msSSTrans = new Array(50);
msSSTrans[0] = "BasicImage(grayscale=0, xray=0, mirror=0, invert=0, opacity=1, rotation=0)";
msSSTrans[1] = "rnd";
msSSTrans[2] = "Barn(motion='out',orientation='vertical')";
msSSTrans[3] = "Barn(motion='out',orientation='horizontal')";
msSSTrans[4] = "Barn(motion='in',orientation='vertical')";
msSSTrans[5] = "Barn(motion='in',orientation='horizontal')";
msSSTrans[6] = "Blinds(Bands=2,direction='up')";
msSSTrans[7] = "Blinds(Bands=2,direction='down')";
msSSTrans[8] = "Blinds(Bands=2,direction='left')";
msSSTrans[9] = "Blinds(Bands=2,direction='right')";
msSSTrans[10] = "Blinds(Bands=15,direction='up')";
msSSTrans[11] = "Blinds(Bands=15,direction='down')";
msSSTrans[12] = "Blinds(Bands=15,direction='left')";
msSSTrans[13] = "Blinds(Bands=15,direction='right')";
msSSTrans[14] = "Checkerboard(Direction='up',SquaresX=4,SquaresY=4)";
msSSTrans[15] = "Checkerboard(Direction='down',SquaresX=4,SquaresY=4)";
msSSTrans[16] = "Checkerboard(Direction='left',SquaresX=4,SquaresY=4)";
msSSTrans[17] = "Checkerboard(Direction='right',SquaresX=4,SquaresY=4)";
msSSTrans[18] = "Checkerboard(Direction='up',SquaresX=50,SquaresY=12)";
msSSTrans[19] = "Checkerboard(Direction='down',SquaresX=50,SquaresY=12)";
msSSTrans[20] = "Checkerboard(Direction='left',SquaresX=50,SquaresY=12)";
msSSTrans[21] = "Checkerboard(Direction='right',SquaresX=50,SquaresY=12)";
msSSTrans[22] = "Fade(Overlap=1.00)";
msSSTrans[23] = "Fade(Overlap=0.00)";
msSSTrans[24] = "GradientWipe(GradientSize=0.00,wipestyle=0,motion='forward')";
msSSTrans[25] = "GradientWipe(GradientSize=0.00,wipestyle=0,motion='reverse')";
msSSTrans[26] = "GradientWipe(GradientSize=0.00,wipestyle=1,motion='forward')";
msSSTrans[27] = "GradientWipe(GradientSize=0.00,wipestyle=1,motion='reverse')";
msSSTrans[28] = "GradientWipe(GradientSize=0.75,wipestyle=0,motion='forward')";
msSSTrans[29] = "GradientWipe(GradientSize=0.75,wipestyle=0,motion='reverse')";
msSSTrans[30] = "GradientWipe(GradientSize=0.75,wipestyle=1,motion='forward')";
msSSTrans[31] = "GradientWipe(GradientSize=0.75,wipestyle=1,motion='reverse')";
msSSTrans[32] = "Iris(irisstyle='PLUS',motion='out')";
msSSTrans[33] = "Iris(irisstyle='PLUS',motion='in')";
msSSTrans[34] = "Iris(irisstyle='DIAMOND',motion='out')";
msSSTrans[35] = "Iris(irisstyle='DIAMOND',motion='in')";
msSSTrans[36] = "Iris(irisstyle='CIRCLE',motion='out')";
msSSTrans[37] = "Iris(irisstyle='CIRCLE',motion='in')";
msSSTrans[38] = "Iris(irisstyle='CROSS',motion='out')";
msSSTrans[39] = "Iris(irisstyle='CROSS',motion='in')";
msSSTrans[40] = "Iris(irisstyle='SQUARE',motion='out')";
msSSTrans[41] = "Iris(irisstyle='SQUARE',motion='in')";
msSSTrans[42] = "Iris(irisstyle='STAR',motion='out')";
msSSTrans[43] = "Iris(irisstyle='STAR',motion='in')";
msSSTrans[44] = "RadialWipe(wipestyle='CLOCK')";
msSSTrans[45] = "RadialWipe(wipestyle='WEDGE')";
msSSTrans[46] = "RadialWipe(wipestyle='RADIAL')";
msSSTrans[47] = "Wheel(spokes=2)";
msSSTrans[48] = "Wheel(spokes=4)";
msSSTrans[49] = "Wheel(spokes=10)";
msSSTrans[50] = "RandomBars(orientation='horizontal')";
msSSTrans[51] = "RandomBars(orientation='vertical')";
msSSTrans[52] = "RandomDissolve(duration=1)";
msSSTrans[53] = "Slide(slidestyle='HIDE',Bands=1)";
msSSTrans[54] = "Slide(slidestyle='SWAP',Bands=1)";
msSSTrans[55] = "Slide(slidestyle='PUSH',Bands=1)";
msSSTrans[56] = "Slide(slidestyle='HIDE',Bands=2)";
msSSTrans[57] = "Slide(slidestyle='SWAP',Bands=2)";
msSSTrans[58] = "Slide(slidestyle='PUSH',Bands=2)";
msSSTrans[59] = "Slide(slidestyle='HIDE',Bands=10)";
msSSTrans[60] = "Slide(slidestyle='SWAP',Bands=10)";
msSSTrans[61] = "Slide(slidestyle='PUSH',Bands=10)";
msSSTrans[62] = "Spiral(GridSizeX=8,GridSizeY=8)";
msSSTrans[63] = "Spiral(GridSizeX=16,GridSizeY=16)";
msSSTrans[64] = "Zigzag(GridSizeX=6,GridSizeY=6)";
msSSTrans[65] = "Zigzag(GridSizeX=12,GridSizeY=12)";
msSSTrans[66] = "Stretch(stretchstyle='HIDE')";
msSSTrans[67] = "Stretch(stretchstyle='PUSH')";
msSSTrans[68] = "Stretch(stretchstyle='SPIN')";
msSSTrans[69] = "Strips(motion='rightdown')";
msSSTrans[70] = "Strips(motion='leftdown')";
msSSTrans[71] = "Strips(motion='rightup')";
msSSTrans[72] = "Strips(motion='leftup')";
msSSTrans[73] = "Pixelate(MaxSquare=5)";
msSSTrans[74] = "Pixelate(MaxSquare=50)";
msSSTrans[75] = "Inset()";
var msImgList = new Array();
var miImgW = new Array();
var miImgH = new Array();
var miSSDelay = new Array();
var miSSEffect = new Array();
var msSSDescr = new Array();
var msSSLink = new Array();
var miSSCount = new Array();
var moSSTime = new Array();
function imSSLoad(iID,oImgData) {
msImgList[iID] = new Array();
miImgW[iID] = new Array();
miImgH[iID] = new Array();
miSSEffect[iID] = new Array();
miSSDelay[iID] = new Array();
msSSDescr[iID] = new Array();
msSSLink[iID] = new Array();
for(i=0;i<oImgData.length;i++){
msImgList[iID][i+1] = ""+oImgData[i][0];
miImgW[iID][i+1] = oImgData[i][1];
miImgH[iID][i+1] = oImgData[i][2];
miSSDelay[iID][i+1] = oImgData[i][3]*1000;
miSSEffect[iID][i+1] = oImgData[i][4];
msSSDescr[iID][i+1] = oImgData[i][5];
msSSLink[iID][i+1] = oImgData[i][6];
}
if(!miSSCount[iID]) miSSCount[iID]=1;
}
function imDoTrans(iID,iStep) {
iLast = msImgList[iID].length-1;
miSSCount[iID]=(miSSCount[iID]+iStep);
if (miSSCount[iID] == iLast + 1) miSSCount[iID] = 1;
if (miSSCount[iID] == 0) miSSCount[iID] = iLast;
var div_Descr=imGetLayer("imSSDescr_"+iID);
var div_Main=imGetLayer("imSSBackg_"+iID);
var div_Image=imGetLayer("imSSImage_"+iID);
if (imEffectEnabled) {
if (miSSEffect[iID][miSSCount[iID]] == 1)
iSSEffectType = Math.floor(Math.random()*73) + 2;
else
iSSEffectType = miSSEffect[iID][miSSCount[iID]];
div_Main.style.filter="progid:DXImageTransform.Microsoft."+msSSTrans[iSSEffectType];
div_Main.filters.item(0).Apply();
}
div_Descr.innerHTML=msSSDescr[iID][miSSCount[iID]];
div_Image.src = msImgList[iID][miSSCount[iID]];
iHeight=parseInt(msSSDescr[iID][miSSCount[iID]]==''?0:div_Descr.offsetHeight);
iTop=parseInt((div_Main.offsetHeight-miImgH[iID][miSSCount[iID]]-iHeight)/2);
div_Image.style.top=iTop+'px';
div_Descr.style.top=iTop+miImgH[iID][miSSCount[iID]]+'px';
div_Image.style.left=parseInt((div_Main.offsetWidth-miImgW[iID][miSSCount[iID]])/2)+'px';
if (imEffectEnabled) div_Main.filters.item(0).Play();
if(msSSLink[iID][miSSCount[iID]] != "#")
div_Image.style.cursor = 'pointer';
else
div_Image.style.cursor = 'default';
iNext = miSSCount[iID]+1
if (iNext <= iLast) {
oImg = new Image();
oImg.src = msImgList[iID][iNext];
}
}
function imLink(iID){
if(msSSLink[iID][miSSCount[iID]] != "#")
location = msSSLink[iID][miSSCount[iID]];
}
function imDoAuto(iID) {
imDoTrans(iID,1);
iAutoDelay=miSSDelay[iID][miSSCount[iID]];
moSSTime[iID]=setTimeout("imDoAuto("+iID+")", iAutoDelay);
}
function imSSPlay(iID,Auto,iBtnType) {
if (Auto == 1) {
miSSCount[iID]=1;
iAutoDelay=miSSDelay[iID][miSSCount[iID]];
moSSTime[iID]=setTimeout("imDoAuto("+iID+")", iAutoDelay);
}
else {
cmd_Auto = imGetLayer('imssPlay_' + iID);
if (cmd_Auto.alt == 'Pause') {
cmd_Auto.alt='Play';
cmd_Auto.src='jscripts/gallery/res/ss_play'+iBtnType+'.gif';
clearTimeout(moSSTime[iID]);
}
else {
cmd_Auto.alt='Pause';
cmd_Auto.src='jscripts/gallery/res/ss_pause'+iBtnType+'.gif';
imDoTrans(iID, 1);
iAutoDelay=miSSDelay[iID][miSSCount[iID]];
moSSTime[iID]=setTimeout("imDoAuto("+iID+")", iAutoDelay);
}
}
}
function imFilterCheck(oEvent,expr){
if (imEffectEnabled)
iKey = oEvent.keyCode;
else
iKey = oEvent.which;
sKey = String.fromCharCode(iKey);
if (expr.test(sKey))
return true;
else
return false;
}
function imKeyFilter(iType, oEvent){
if (iType == 0)
expr = /[\d\n\b]/;
else if (iType == 1)
expr = /[\d\n\b\- ]/;
else if (iType == 2)
expr = /[\d\n\b\/\.]/;
return imFilterCheck(oEvent,expr);
}
var iMMCurPos=0;
var iMMEnd = 0;
var iMMEndDisplace = 0;
var oMMTime = null;
var imMMVel=0;
var iMMHeaderSize = 0;
var iMMFooterSize = 0;
var iMMTimerInt = 0;
function imGetOffset(sName) {return imGetLayer(sName).offsetHeight ?
imGetLayer(sName).offsetHeight :
imGetLayer(sName).style.pixelHeight ?
imGetLayer(sName).style.pixelHeight : 0;
}
function imMMScrollMenu(){
if (document.documentElement && document.documentElement.scrollTop)
iMMEnd = document.documentElement.scrollTop > iMMHeaderSize ?
document.documentElement.scrollTop - iMMHeaderSize :
0;
else if (document.body && document.body.scrollTop)
iMMEnd = document.body.scrollTop > iMMHeaderSize ?
document.body.scrollTop - iMMHeaderSize :
0;
else
iMMEnd = 0;
if(iMMCurPos > iMMEnd)
iMMEndDisplace = -(imMMVel-1);
else if (iMMCurPos < iMMEnd)
iMMEndDisplace = (imMMVel-1);
iMMCurPos += ((iMMEnd - iMMCurPos + iMMEndDisplace)/imMMVel);
iMMCurPos = parseInt(iMMCurPos);
imGetLayer("imMnMn").style.paddingTop = iMMCurPos + 'px';
if (iMMCurPos == iMMEnd){
clearTimeout(oMMTime);
oMMTime = null;
}
else{
clearTimeout(oMMTime);
oMMTime = setTimeout("imMMScrollMenu()", iMMTimerInt);
}
}
function imMMMenu(){
if(!oMMTime)
oMMTime = setTimeout("imMMScrollMenu()", iMMTimerInt);
}
function imMMInit(iMMVel){
iMMHeaderSize = imGetLayer("imMenuMain").offsetTop;
iMMFooterSize = imGetLayer("imFooter").offsetTop;
imMMVel = iMMVel;
iTimerInt = 5;
iMMCurPos = 0;
imGetLayer("imMnMn").style.paddingTop = iMMCurPos + 'px';
window.onscroll = imMMMenu;
}
function imZIZoom(sImage,iHeight,iWidth,sDescr) {
var imZIdiv_Backg = imGetLayer("imZIBackg");
var imZIdiv_Image = imGetLayer("imZIImage");
var imZIyScroll;
var imZIwindowHeight;
if (window.innerHeight && window.scrollMaxY)
imZIyScroll = window.innerHeight + window.scrollMaxY;
else if (document.body.scrollHeight > document.body.offsetHeight)
imZIyScroll = document.body.scrollHeight;
else
imZIyScroll = document.body.offsetHeight;
if (self.innerHeight)
imZIwindowHeight = self.innerHeight;
else if (document.documentElement && document.documentElement.clientHeight)
imZIwindowHeight = document.documentElement.clientHeight;
else if (document.body)
imZIwindowHeight = document.body.clientHeight;
imZIpageHeight = imZIyScroll < imZIwindowHeight ? imZIwindowHeight : imZIyScroll;
if (self.pageYOffset)
imZIyScroll = self.pageYOffset;
else if (document.documentElement && document.documentElement.scrollTop)
imZIyScroll = document.documentElement.scrollTop;
else if (document.body)
imZIyScroll = document.body.scrollTop;
imZIdiv_Backg.style.top = '0px';
imZIdiv_Backg.style.height = (imZIpageHeight + 'px');
imZIdiv_Backg.style.zIndex = '2000';
var imZIImageTop = imZIyScroll + ((imZIwindowHeight - 35 - iHeight) / 2);
var div_Descr = "";
if (sDescr!="") div_Descr = "<div id=\"imZICaption\">" + sDescr + "</div>";
imZIdiv_Backg.innerHTML = "<div id=\"imZIImage\" style=\"margin-top:" + ((imZIImageTop < 0) ? "0px" : imZIImageTop) + "px" + "; width: " + (iWidth + 14) + "px\"><img src=\"" + sImage + "\" width=\"" + iWidth + "\" height=\"" + iHeight + "\" />" + div_Descr + "</div>";
imZIdiv_Backg.style.display = "block";
}
function imZIHide(){
imGetLayer("imZIBackg").style.display = "none";
imGetLayer("imZIImage").innerHtml = "";
}
var moFGTime = null;
var mFGMoving = new Array();
function imFGClickLR(iFGIndex,iFGBkSize,iFGEndBlock,iFGMaxBlock,iFGSpeed,iFGDir){
var oFGObj = imGetLayer("imFGImgList_" + iFGIndex);
var imiLeft = parseInt(oFGObj.style.left);
iFGEndBlock = iFGEndBlock <= iFGMaxBlock ? iFGEndBlock < 0 ? 0 : iFGEndBlock : iFGMaxBlock;
if(Math.abs(imiLeft) == iFGEndBlock*iFGBkSize){
mFGMoving[iFGIndex] = null;
return;
}
if(mFGMoving[iFGIndex] && mFGMoving[iFGIndex] != iFGDir)
return;
else
mFGMoving[iFGIndex] = iFGDir;
if(Math.abs(imiLeft) < iFGEndBlock*iFGBkSize && iFGDir==1){
if(Math.abs(imiLeft - iFGSpeed) > iFGEndBlock*iFGBkSize )
iFGSpeed = imiLeft+iFGEndBlock*iFGBkSize;
oFGObj.style.left = imiLeft - iFGSpeed + "px";
setTimeout("imFGClickLR(" + iFGIndex + "," + iFGBkSize + "," + iFGEndBlock + "," + iFGMaxBlock + "," + iFGSpeed + "," + iFGDir + ")",40);
}
else if(Math.abs(imiLeft) > iFGEndBlock*iFGBkSize && imiLeft <= 0 && iFGDir==2){
if(Math.abs(imiLeft + iFGSpeed) < iFGEndBlock*iFGBkSize || (imiLeft + iFGSpeed) > iFGEndBlock*iFGBkSize)
iFGSpeed = Math.abs(imiLeft)-iFGEndBlock*iFGBkSize;
oFGObj.style.left = imiLeft + iFGSpeed + "px";
setTimeout("imFGClickLR(" + iFGIndex + "," + iFGBkSize + "," + iFGEndBlock + "," + iFGMaxBlock + "," + iFGSpeed + "," + iFGDir + ")",40);
}
}
function imFGClickUD(iFGIndex,iFGBkSize,iFGEndBlock,iFGMaxBlock,iFGSpeed,iFGDir){
var oFGObj = imGetLayer("imFGImgList_" + iFGIndex);
var imiTop = parseInt(oFGObj.style.top);
//set bound
iFGEndBlock = iFGEndBlock <= iFGMaxBlock ? iFGEndBlock < 0 ? 0 : iFGEndBlock : iFGMaxBlock;
if(Math.abs(imiTop) == iFGEndBlock*iFGBkSize){
mFGMoving[iFGIndex] = null;
return;
}
if(mFGMoving[iFGIndex] && mFGMoving[iFGIndex] != iFGDir)
return;
else
mFGMoving[iFGIndex] = iFGDir;
if(Math.abs(imiTop) < iFGEndBlock*iFGBkSize && iFGDir==2){
if(Math.abs(imiTop - iFGSpeed) > iFGEndBlock*iFGBkSize )
iFGSpeed = parseInt((imiTop+iFGEndBlock*iFGBkSize));
oFGObj.style.top = imiTop - iFGSpeed + "px";
setTimeout("imFGClickUD(" + iFGIndex + "," + iFGBkSize + "," + iFGEndBlock + "," + iFGMaxBlock + "," + iFGSpeed + "," + iFGDir + ")",40);
}
else if(Math.abs(imiTop) > iFGEndBlock*iFGBkSize && imiTop <= 0 && iFGDir==1){
if(Math.abs(imiTop + iFGSpeed) < iFGEndBlock*iFGBkSize || (imiTop + iFGSpeed) > iFGEndBlock*iFGBkSize)
iFGSpeed = parseInt((Math.abs(imiTop)-iFGEndBlock*iFGBkSize));
oFGObj.style.top = imiTop + iFGSpeed + "px";
setTimeout("imFGClickUD(" + iFGIndex + "," + iFGBkSize + "," + iFGEndBlock + "," + iFGMaxBlock + "," + iFGSpeed + "," + iFGDir + ")",40);
}
}
function imFGMove(iFGIndex,iFGSpeed,iFGType,iFGCmd){
clearTimeout(moFGTime);
if(iFGType < 1 || iFGType > 4)
return;
var imDataObj = imGetLayer("imFGImgList_" + iFGIndex);
var imDataCont = imGetLayer("imFGImgCont_" + iFGIndex);
var imiLeft = parseInt(imDataObj.style.left);
var imiTop = parseInt(imDataObj.style.top);
var imiSize = imGetLayer("imFGItem_" + iFGIndex + "_" + 1 ).offsetHeight;
var imiTWBlock = parseInt(imDataObj.offsetWidth/imiSize);
var imiTHBlock = parseInt(imDataObj.offsetHeight/imiSize);
switch(iFGType){
case 1:{
if (imiLeft - iFGSpeed >= imDataCont.offsetWidth - imDataObj.offsetWidth)
switch(iFGCmd){
case 0:
if(mFGMoving[iFGIndex])
return;
imDataObj.style.left = imiLeft - iFGSpeed + "px";
break;
case 1:
imFGClickLR(iFGIndex,imiSize,parseInt(Math.abs(imiLeft/imiSize)+1),
imiTWBlock-parseInt(imDataCont.offsetWidth/imiSize),
iFGSpeed,1);
return;
default:
imFGClickLR(iFGIndex,imiSize,
parseInt(Math.abs(imiLeft/imiSize)+parseInt(imDataCont.offsetWidth/imiSize)),
imiTWBlock-parseInt(imDataCont.offsetWidth/imiSize),
iFGSpeed,
1);
return;
}
break;
}
case 2 :{
if (imiLeft + iFGSpeed < 0)
switch(iFGCmd){
case 0:
if(mFGMoving[iFGIndex])
return;
imDataObj.style.left = imiLeft + iFGSpeed + "px";
break;
case 1 :
imFGClickLR(iFGIndex,imiSize,parseInt(Math.abs(imiLeft/imiSize)),
imiTWBlock-parseInt(imDataCont.offsetWidth/imiSize),
iFGSpeed,2);
return;
default:
imFGClickLR(iFGIndex,imiSize,
parseInt(Math.abs(imiLeft/imiSize)-parseInt(imDataCont.offsetWidth/imiSize)),
imiTWBlock-parseInt(imDataCont.offsetWidth/imiSize),
iFGSpeed,
2);
return;
}
else
imDataObj.style.left = 0;
break;
}
case 3 :{
if (imiTop + iFGSpeed < 0)
switch(iFGCmd){
case 0 :
if(mFGMoving[iFGIndex])
return;
imDataObj.style.top = imiTop + iFGSpeed + "px";
break;
case 1 :
imFGClickUD(iFGIndex,imiSize,parseInt(Math.abs(imiTop/imiSize)),
imiTHBlock-parseInt(imDataCont.offsetHeight/imiSize),
iFGSpeed,1);
return;
default :
imFGClickUD(iFGIndex,imiSize,
parseInt(Math.abs(imiTop/imiSize)-parseInt(imDataCont.offsetHeight/imiSize)),
imiTHBlock-parseInt(imDataCont.offsetHeight/imiSize),
iFGSpeed,
1);
return;
}
else{
imDataObj.style.top = 0;
return;
}
break;
}
case 4 :{
if (imiTop - iFGSpeed >= imDataCont.offsetHeight - imDataObj.offsetHeight)
switch(iFGCmd){
case 0 :
if(mFGMoving[iFGIndex])
return;
imDataObj.style.top = imiTop - iFGSpeed + "px";
break;
case 1 :
imFGClickUD(iFGIndex,imiSize,parseInt(Math.abs(imiTop/imiSize))+1,
imiTHBlock-parseInt(imDataCont.offsetHeight/imiSize),
iFGSpeed,2);
return;
default:
imFGClickUD(iFGIndex,imiSize,
parseInt(Math.abs(imiTop/imiSize)+parseInt(imDataCont.offsetHeight/imiSize))+1,
imiTHBlock-parseInt(imDataCont.offsetHeight/imiSize),
iFGSpeed,
2);
return;
}
}
}
moFGTime = setTimeout("imFGMove(" + iFGIndex + "," + iFGSpeed + "," + iFGType + "," + iFGCmd + ")", 50);
}
function imFGShow(iIndex,sImageSrc,iImageH,iDescH,sDescr,sLink,iEffect){
var div_FGMain = imGetLayer("imFGMain_" + iIndex);
if (imEffectEnabled && iEffect != 0) {
if (iEffect == 1) iEffect = Math.floor(Math.random()*73) + 2;
div_FGMain.style.filter="progid:DXImageTransform.Microsoft."+msSSTrans[iEffect];
div_FGMain.filters.item(0).Apply();
}
var iTop = parseInt((div_FGMain.offsetHeight-iImageH-iDescH)/2);
var div_Descr = (iDescH == 0) ? "" : "<div id=\"imFGDescr_" + iIndex + "\" style=\"top: " + (iTop + iImageH) + "px\">" + sDescr + "</div>";
div_FGMain.innerHTML = "<img src=\"imagebrowser/"+sImageSrc+"\" style=\"margin-top: " + iTop + "px\"/>" + div_Descr;
if (sLink!="#") {
div_FGMain.onclick= function onclick(event) {location = sLink};
div_FGMain.style.cursor="pointer";
} else {
div_FGMain.onclick="";
div_FGMain.style.cursor="default";
}
if (imEffectEnabled && iEffect != 0) div_FGMain.filters.item(0).Play();
}
function imMapSwap(oLI) {
if(oLI.className == 'imMap_closed')
oLI.className = 'imMap_open';
else
oLI.className = 'imMap_closed';
}
function imMapExpAll() {
var a = document.getElementsByTagName('li');
for(var i = 0; i < a.length; i++)
if(a[i].className == 'imMap_closed')
a[i].className = 'imMap_open';
}
function imMapCmpAll() {
var a = document.getElementsByTagName('li');
for(var i = 0; i < a.length; i++)
if(a[i].className == 'imMap_open')
a[i].className = 'imMap_closed';
}
