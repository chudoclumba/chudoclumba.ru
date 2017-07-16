<div class="orders_btnm">За последних суток <input id="dinp" type="text" class="fbinp" style="width:50px" value="4" name="dinp"/>
 <button name="reload" type="button" value="anc" onclick="LoadGr(1);" class="albutton alorange"><span><span><span class="sync">Обновить</span></span></span></button>
</div>

<div id="chart1" style="height:400px; "></div>
<div id="chart2" style="height:400px; "></div>
<div id="chart3" style="height:400px; "></div>
<div id="chart4" style="height:400px; "></div>
<div id="chart5" style="height:400px; "></div>
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="<?=SITE_URL?>jscripts/excanvas.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="<?=SITE_URL?>jscripts/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=SITE_URL?>jscripts/jquery.jqplot.min.css" />
<script type="text/javascript" src="<?=SITE_URL?>jscripts/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/plugins/jqplot.logAxisRenderer.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>jscripts/plugins/jqplot.json2.min.js"></script>
<script type="text/javascript">
var plot1=null;
var plot2=null;
var plot3=null;
var plot4=null;
var plot5=null;
var d1=null;
var d2=null;
var d3=null;
var d4=null;
var d5=null;
function LoadGr(id){
  var ymin=2;
  var ymax=7;
  $.ajax({async: false,url:  'ajax?ttype=1&long='+$("#dinp").val(),dataType:"json",success: function(data) {d1=data;}})
  if (d1[2].min<ymin) ymin=d1[2].min;
  if (d1[2].avg>=ymax) ymax=d1[2].max;
  plot1 = $.jqplot('chart1', [d1[0],d1[1]], {title:'Камера 1 Min='+d1[2].min+' Max='+d1[2].max+' Cредняя='+d1[2].avg, axesDefaults: {tickRenderer: $.jqplot.CanvasAxisTickRenderer ,tickOptions: {fontSize: '8pt'}},  axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer, tickOptions:{angle: 45,formatString:'%d.%m.%y %H:%M'},tickInterval:'2 hour'},yaxis:{min:ymin,max:ymax,tickOptions:{angle: 0,formatString:'%.1f'},tickInterval:1.0}}, series:[{lineWidth:1,showMarker:false,color:"#fd543c",label:"Верх"},{lineWidth:1,showMarker:false,color:"#4bb2c5",label:"Низ"}], highlighter: {show: true, sizeAdjust: 3, formatString:'%s, Температура: %.1f'},cursor: {show: true, zoom:true, showTooltip:false}, legend: {show: true, location: "ne", xoffset: 12, yoffset: 12 }});
  $.ajax({async: false,url:  'ajax?ttype=2&long='+$("#dinp").val(),dataType:"json",success: function(data) {d2=data;}})
  ymin=2;
  ymax=7;
  if (d2[2].min<ymin) ymin=d2[2].min;
  if (d2[2].avg>=ymax) ymax=d2[2].max;
  plot2 = $.jqplot('chart2', [d2[0],d2[1]], {title:'Камера 2 Min='+d2[2].min+' Max='+d2[2].max+' Cредняя='+d2[2].avg, axesDefaults: {tickRenderer: $.jqplot.CanvasAxisTickRenderer, tickOptions: {fontSize: '8pt'}}, axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer, tickOptions:{angle: 45,formatString:'%d.%m.%y %H:%M'},tickInterval:'2 hour'}, yaxis:{min:ymin,max:ymax,tickOptions:{angle: 0,formatString:'%.1f'}, tickInterval:1.0}}, series:[{lineWidth:1,showMarker:false,color:"#fd543c",label:"Верх"},{lineWidth:1,showMarker:false,color:"#4bb2c5",label:"Низ"}], highlighter: {show: true,sizeAdjust: 3,formatString:'%s, Температура: %.1f'},cursor: {show: true,zoom:true, showTooltip:false},legend: {show: true,location: "ne",xoffset: 12,yoffset: 12 }});
  $.ajax({async: false,url:  'ajax?ttype=3&long='+$("#dinp").val(),dataType:"json",success: function(data) {d3=data;}})
  ymin=0;
  ymax=5;
  if (d3[2].min<ymin) ymin=d3[2].min;
  if (d3[2].avg>=ymax) ymax=d3[2].max;
  plot3 = $.jqplot('chart3', [d3[0],d3[1]], {title:'Камера 3 Min='+d3[2].min+' Max='+d3[2].max+' Cредняя='+d3[2].avg, axesDefaults: {tickRenderer: $.jqplot.CanvasAxisTickRenderer, tickOptions: {fontSize: '8pt'}}, axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer, tickOptions:{angle: 45,formatString:'%d.%m.%y %H:%M'},tickInterval:'2 hour'}, yaxis:{min:ymin,max:ymax,tickOptions:{angle: 0,formatString:'%.1f'}, tickInterval:1.0}}, series:[{lineWidth:1,showMarker:false,color:"#fd543c",label:"Верх"},{lineWidth:1,showMarker:false,color:"#4bb2c5",label:"Низ"}], highlighter: {show: true,sizeAdjust: 3,formatString:'%s, Температура: %.1f'},cursor: {show: true,zoom:true, showTooltip:false},legend: {show: true,location: "ne",xoffset: 12,yoffset: 12 }});
  $.ajax({async: false,url:  'ajax?ttype=4&long='+$("#dinp").val(),dataType:"json",success: function(data) {d4=data;}})
  
	plot4 = $.jqplot('chart4', [d4[0]], {title:'Офис Min='+d4[1].min+' Max='+d4[1].max+' Cредняя='+d4[1].avg,axesDefaults: {tickRenderer: $.jqplot.CanvasAxisTickRenderer ,tickOptions: {fontSize: '8pt'}},axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer,tickOptions:{angle: 45,formatString:'%d.%m.%y %H:%M'},tickInterval:'2 hour'},yaxis:{tickOptions:{angle: 0,formatString:'%.1f'},tickInterval:1.0,min:18,max:30}},series:[{lineWidth:1,showMarker:false,color:"#fd543c"}],highlighter: {show: true, sizeAdjust: 3,formatString:'%s, Температура: %.1f'},cursor: {show: true,zoom:true, showTooltip:false},legend: {show: false,location: "ne",xoffset: 12,yoffset: 12 }});
  var ymin=14;
  var ymax=27;
  $.ajax({async: false,url:  'ajax?ttype=5&long='+$("#dinp").val(),dataType:"json",success: function(data) {d5=data;}})
  if (d5[2].min<ymin) ymin=d5[2].min;
  if (d5[2].avg>=ymax) ymax=d5[2].max;
  plot5 = $.jqplot('chart5', [d5[0],d5[1]], {title:'CЦ зал1 Min='+d5[2].min+' Max='+d5[2].max+' Cредняя='+d5[2].avg, axesDefaults: {tickRenderer: $.jqplot.CanvasAxisTickRenderer ,tickOptions: {fontSize: '8pt'}},  axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer, tickOptions:{angle: 45,formatString:'%d.%m.%y %H:%M'},tickInterval:'2 hour'},yaxis:{min:ymin,max:ymax,tickOptions:{angle: 0,formatString:'%.1f'},tickInterval:1.0}}, series:[{lineWidth:1,showMarker:false,color:"#fd543c",label:"Зал 1"},{lineWidth:1,showMarker:false,color:"#4bb2c5",label:"Зал 2"}], highlighter: {show: true, sizeAdjust: 3, formatString:'%s, Температура: %.1f'},cursor: {show: true, zoom:true, showTooltip:false}, legend: {show: true, location: "ne", xoffset: 12, yoffset: 12 }});
	
    if (id==1){plot1.replot();plot2.replot();	plot3.replot();plot4.replot();plot5.replot();}
}
$(document).ready(function(){LoadGr(0)});
</script>
