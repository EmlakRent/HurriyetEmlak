<?php
header('Content-Type: text/html; charset=utf-8');
include("functions.php");


function cmp($a,$b)
{
    return strcmp($a['location']['distance'],$b['location']['distance']);
}
//$street = $_POST["street"];
//$latitude = $_POST["latitude"];
//$longitude = $_POST["longitude"];

$latitude = "37.740013";
$longitude = "29.09372899999994";

$sonuc = array();

$ilan_sayisi = 0;

# todo : mbdef class'ına sahip divi bul ve kaç sayfa olduğunu tespit et && infoSearchResults


    $url = file_get_contents("http://www.hurriyetemlak.com/emlak?p44=denizli+çamlaraltı&pageSize=50");
    preg_match_all('@<span id="ctl00_cphContent_lblTotalRealtyCountNew">(.*?)</span>@si',$url,$ilan_sayisi);

        $sayfa_sayisi = $ilan_sayisi[1][0]/50;
        $sayfa_sayisi=round($sayfa_sayisi);
        if(($ilan_sayisi%50) !=0 )
            $sayfa_sayisi++;
//echo $sayfa_sayisi;

for ( $i = 1 ; $i < $sayfa_sayisi ; $i++)
{
   $url = file_get_contents("http://www.hurriyetemlak.com/emlak?p44=denizli+çamlaraltı&pageSize=50&page=$i");

//echo $url;
    //echo "<a href='$url'>$url</a><br>";

  preg_match_all('@<a id="ctl00_cphContent_ctlRealtyListNew1_rptRealtyList_lnkOverlay(.*?) href="(.*?)">@si',$url,$detay_icin_link);

    //echo $detay_icin_link[2][1];

    $ilan_sayisi = count($detay_icin_link[0]);

 //   echo $ilan_sayisi;

    for ( $j = 0; $j < $ilan_sayisi; $j++ )
    {
        # Current url
        $url ="http://www.hurriyetemlak.com".$detay_icin_link[2][$j];
    //echo $url;
       $sonuc[$j] = getDetail($url,$latitude,$longitude);

    }

}


//for ( $j = 0; $j < 4 ; $j++ )
//{

    //if ( $sonuc[$j]['location']['distance'] > 1 )
    //{
    //    unset($sonuc[$j]);
  //  }
//}

# todo php nesting array sort
//$data = [];

foreach(array_values($sonuc) as $r)
{
    $data[] = $r;
}

usort($data,'cmp');


$return =  json_encode($data);
echo $return;
//echo "<pre>";
//print_r($sonuc);
//echo "</pre>";


