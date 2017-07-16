<?php
    // определим Ip пользователя
$ip_user = ip(); 

    // функция по выводу данных об $ip
function whois($server,$ip){
$fp = @fsockopen ($server, 43, &$errno, &$errstr, 30); 
if (!$fp){ return false; }
else {
$servers .= $server."<br>\n";
@fputs ($fp, $ip."\r\n");
$text = "";
while ( !feof ($fp)) { $text .= @fgets ($fp, 128)."<br>\n"; }
@fclose ($fp);
$search = "~". preg_quote ("ReferralServer: whois://","~")."([?\n<:]+)~i";
preg_match ($search, $text, $out);
if (! empty ($out[1])) { return whois($out[1], $ip); }
else return $servers.$text;
}
}

    // пытаемся извлечь информацию об Ip
$content = whois ( "whois.arin.net", $ip_user );
    // если ничего не вышло
if ($content == ''){
print "Невозможно вывести данные об Ip: $ip_user";
}
else { print $content; }
?>

<?php 
    // функция читающая инфу о домене с Whois-сервера
function Domain ($domain){
    set_time_limit (0);
    $servers = array (
    array ("ru","whois.nic.ru","No entries found"),
    array ("ac","whois.nic.ac","No match"),
    array ("com","whois.verisign-grs.net","No match"),
    array ("com.au","whois.aunic.net","No Data Found"),
    array ("com.br","whois.nic.br","No match"),
    array ("com.cn","whois.cnnic.net.cn","No entries found"),
    array ("com.eg","whois.ripe.net","No entries found"),
    array ("com.hk","whois.hknic.net.hk","No Match for"),
    array ("com.mx","whois.nic.mx","Nombre del Dominio"),
    array ("com.ru","whois.ripn.ru","No entries found"),
    array ("com.tw","whois.twnic.net","NO MATCH TIP"),
    array ("zj.cn","whois.cnnic.net.cn","No entries found")
   );

    $first_dom = substr ($domain, strpos ($domain, ".") + 1);
    for ($i = 0; $i < count ($servers); $i++) {
        if ($servers[$i][0] == $first_dom) {
        $whois = $servers[$i][1];
        $not_found_string = $servers[$i][2];
        break;
    }
}

if ( empty ($whois)){ return ; }
    $fp = fsockopen ($whois, 43 , &$errno , &$errstr , 30 );
    fputs ($fp, $domain."\r\n");
    while ( !feof ($fp)) { $str .= str_replace ("\n", "\n<br>", fgets ($fp,128)); }
    fclose ($fp);
    if ( !preg_match ("/".$not_found_string."/is", $str)){
        return "<h3>Домен " . strtoupper ($domain) . " уже зарегистрирован</h3>" . $str;
    }
    else {
        return "<h3>Домен " . strtoupper ($domain) . " не зарегистрирован</h3>" . $str;
    }
}
    // подставляем домен
$domain = 'omsk777.ru';
$content = Domain ($domain); 
    // если нет Whois-сервера
if ($content==''){
    print "<h3>К сожалению, не найден соответствующий Whois-сервер</h3>";
}
    // если есть Whois-сервера печатаем, что он выдал
else {  print $content;  }
?>