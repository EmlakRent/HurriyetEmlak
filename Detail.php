<?php
/**
 * Created by PhpStorm.
 * User: cagatay
 * Date: 04/04/16
 * Time: 23:45
 */

class Detail
{

    /**
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @param $unit
     * @return float
     */
    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    /**
     * @param $latitude
     * @param $longitude
     */
    public function getStreet($latitude, $longitude)
    {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&sensor=true";
        $data = @file_get_contents($url);
        $jsondata = json_decode($data, true);


        return $jsondata['results']['0']['address_components']['2']['long_name'];
    }


    /**
     * @param $site
     * @return array
     */
    public function getLocation($site,$latitude,$longitude) # Current locations
    {
        preg_match_all('@<div id="gmap" data-lat="(.*?)" data-lon="(.*?)" data-lang="tr"></div>@si', $site, $lokasyon);

        unset($lokasyon[0]);

        $lokasyon = array_values($lokasyon);

        /**
         * Detail locations
         */
        $lat = $lokasyon[0][0];
        $lon = $lokasyon[1][0];

        $konum = array();

        $konum["latitude"] = $lat;
        $konum["longitude"] = $lon;
        $konum["distance"] = distance($lat,$lon,$latitude,$longitude,"K");

        return $konum;
    }

    /**
     * @param $site
     * @return mixed
     */
    public function getPrice($site)
    {
        preg_match_all('@<h3>(.*?)</h3>@si', $site, $fiyat);

        $string = trim(preg_replace('/\s\s+/', ' ', strip_tags($fiyat[0][0])));
        return $string;
    }

    /**
     * @param $site
     * @return mixed
     */
    public function getDescription($site)
    {
        preg_match_all('@<div id="classifiedDescription" class="uiBoxContainer">(.*?)</div>@si', $site, $aciklama);

        //$aciklama = validate($aciklama[0][0]);

        return strip_tags($aciklama);
    }

    /**
     * @param $site
     * @return mixed
     */
    public function getPhoto($site)
    {
        preg_match_all('@<img width="480" height="360" src="(.*?)" alt="(.*?)">@si', $site, $resimler);
        return $resimler;
    }

    /**
     * @param $aciklama
     * @return mixed|string
     */
    public function validate($aciklama)
    {
        $temizle = strip_tags($aciklama[0][0]);
        $temizle = str_replace('<div', '', $temizle);
        $temizle = htmlentities($temizle);
        $temizle = str_replace('<font', '', $temizle);
        $temizle = str_replace('<span', '', $temizle);
        $temizle = str_replace('<p', '', $temizle);
        $temizle = html_entity_decode($temizle);
        return $temizle;
    }

    /**
     * @param $url
     * @return array
     */
    public function getDetail($url,$latitude,$longitude)
    {
        $result = array();

        $site = file_get_contents($url);

        $result["url"]   = $url;
        $result["location"] = getLocation($site,$latitude,$longitude);
        $result["price"]  = getPrice($site);

        return $result;
    }
}