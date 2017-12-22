<?php
error_reporting(E_ERROR);
set_time_limit(0);
$doc=new DOMDocument(1.0);
$i=1;
$p=1;
echo "<table>";
do{
    $str=fetch("https://search.jd.com/Search?keyword=手机&enc=utf-8&qrst=1&rt=1&stop=1&vt=2&suggest=1.def.0.V16&wq=shouji&cid2=653&cid3=655&page=".(($p-1)*2+1)."&s=58&click=0");
    $doc->loadHTML($str);
    $xpath=new DOMXPath($doc);
    $thumbs=$xpath->query("//div[@class='gl-i-wrap']/div[@class='p-img']/a/img/@src | //div[@class='gl-i-wrap']/div[@class='p-img']/a/img/@data-lazy-img");
    $pics=$xpath->query("//div[@class='gl-i-wrap']/div[@class='p-scroll']");
    $prices=$xpath->query("//div[@class='gl-i-wrap']/div[@class='p-price']");
    $titles=$xpath->query("//div[@class='gl-i-wrap']/div[@class='p-name p-name-type-2']/a/em");
    for($x=0;$x<$titles->length;$x++){
        $pics->item($x);
        echo "<tr>";
        echo "<td>".($i)."</td>";
        echo "<td>".$titles->item($x)->textContent."</td>";
        echo "<td>".$prices->item($x)->textContent."</td>";
        echo "<td><img width='200' src='".$thumbs->item($x)->textContent."'/></td>";
        echo "<td>";
        $picTitleList=$xpath->query(".//li[@class='ps-item']/a/@title",$pics->item($x));
        $picList=$xpath->query(".//li[@class='ps-item']/a/img/@src | .//li[@class='ps-item']/a/img/@data-lazy-img",$pics->item($x));
        for($y=0;$y<$picList->length;$y++){
            echo "<img title='".$picTitleList->item($y)->textContent."' width='30' src='".$picList->item($y)->textContent."' />";
        }
        echo "</td>";
        echo "</tr>";
        $i++;
    }
    $p++;
}while($p<100);
echo "</table>";



function fetch($url){
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_AUTOREFERER,true);
    curl_setopt($ch,CURLOPT_REFERER,$url);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36 OPR/49.0.2725.64");
    $result=curl_exec($ch);
    curl_close($ch);
    return $result;
}
?>
