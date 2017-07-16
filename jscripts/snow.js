var no = 30; //
var speed = 100; //
var snowflake = "images/snow.gif";
var ns4up = (document.style) ? 1 : 0;
var ie4up = (document.getElementById) ? 1 : 0;
var dx, xp, yp;
var am, stx, sty;
var i, doc_width = 1000, doc_height = 600;

 if (ie4up)
	 {
	
		doc_width = document.body.clientWidth;
		doc_height = document.body.clientHeight;
	}

dx = new Array();
xp = new Array();
yp = new Array();
am = new Array();
stx = new Array();
sty = new Array();

for (i = 0; i < no; ++ i)
 {
	dx[i] = 0;
	xp[i] = Math.random()*(doc_width-50);
	yp[i] = Math.random()*doc_height;
	am[i] = Math.random()*20;
	stx[i] = 0.02 + Math.random()/10;
	sty[i] = 0.7 + Math.random();
 if (ie4up)
		 {
			if (i == 0)
			 {
				document.write("<div id=\"dot"+ i +"\" style=\"POSITION: ");
				document.write("absolute; Z-INDEX: "+ i +"; VISIBILITY: ");
				document.write("visible; TOP: 25px; LEFT: 25px;\"><img src=\"");
				document.write(snowflake + "\" border=\"0\"></div>");
			}
			 else
			 {
				document.write("<div id=\"dot"+ i +"\" style=\"POSITION: ");
				document.write("absolute; Z-INDEX: "+ i +"; VISIBILITY: ");
				document.write("visible; TOP: 25px; LEFT: 25px;\"><img src=\"");
				document.write(snowflake + "\" border=\"0\"></div>");
			}
		}
 }



function snowIE()
 {
	for (i = 0; i < no; ++ i)
	 {
		yp[i] += sty[i];
		if (yp[i] > doc_height-50)
		 {
			xp[i] = Math.random()*(doc_width-am[i]-30);
			yp[i] = 0;
			stx[i] = 0.02 + Math.random()/10;
			sty[i] = 0.7 + Math.random();
			doc_width = document.body.clientWidth;
			doc_height = document.body.clientHeight;
		}
		
		dx[i] += stx[i];
		document.getElementById("dot"+i).style.top = yp[i];
		document.getElementById("dot"+i).style.left = xp[i] + am[i]*Math.sin(dx[i]);
	}
setTimeout("snowIE()", speed);
 }

if (ie4up)
 {
  	snowIE();
 }