<div id="clockntp" style="font-size:16px; text-align:center; border:1px solid #ccc; background:#eee; margin:20px 50px; padding:10px;"></div>
<script type="text/javascript">
var request;
var DateServer = new Date("<?php echo date('r',time())?>");
function clock() 
{
	var DateClient = new Date();
	if (!document.layers && !document.getElementById) return;
	var ServerSec = DateServer.getSeconds();
	var ServerMin = DateServer.getMinutes();
	var ServerHours = DateServer.getHours();
	DateServer.setSeconds(ServerSec+1);
	var ClientTimeZone = DateClient.getTimezoneOffset() / 60;
	var ClientSec = DateClient.getSeconds();
	var ClientMin = DateClient.getMinutes();
	var ClientHours = DateClient.getHours() + ClientTimeZone;	
	ServerHours = convertHour(ServerHours, ClientTimeZone);
	if (ServerMin < 10) ServerMin = "0" + ServerMin;
	if (ServerSec < 10) ServerSec = "0" + ServerSec;
	ClientHours = convertHour(ClientHours, ClientTimeZone);
	if (ClientMin < 10) ClientMin = "0" + ClientMin;
	if (ClientSec < 10) ClientSec = "0" + ClientSec;
	CurrentTimeNTP = (ServerHours + ClientTimeZone) + ":" + ServerMin + ":" + ServerSec;
	if (document.layers) {
		document.layers.clockntp.document.write(CurrentTimeNTP);
		document.layers.clockntp.document.close();
	}else if (document.getElementById) {
		document.getElementById("clockntp").innerHTML = CurrentTimeNTP;}
	setTimeout("clock()", 1000);
}

function convertHour(Hour,Zone){
	Hour = Hour - Zone;
	if (Hour >= 24) Hour = Hour - 24;
	if (Hour < 0) Hour = Hour + 24;
	if (Hour < 10) Hour = "0" + Hour;
	return Hour;
}

clock();
</script>