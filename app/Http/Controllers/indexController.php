<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpQuery;

class indexController extends Controller
{
    public function index()
    {
        $cookiefile = 'storage/cookie.txt';

        $ch = curl_init('http://laravel.loc/cook');
        //$ch = curl_init('https://radar.com.ua/nasosi/tsirkulyatsionnie/halm-hupa-25-60-u-130');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        //curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        //curl_setopt($ch, CURLOPT_COOKIE, 'curl_normal_coockie=1; curl_session_coockie=1');
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $html = curl_exec($ch);

        curl_close($ch);

        xprint($html);

        //$html = file_get_contents('http://www.wizard.ua/index.php?route=product/product&product_id=3423');

        //phpQuery::newDocument($html);

        /*$price = pq('#product_price')->text();
        xd($price);*/


        /* $temperature = pq('.current-weather__thermometer_type_now')->text();
        xprint($temperature);

        $wind = pq('body > div.content > div.content__top.clearfix > div.current-weather > span.current-weather__col.current-weather__info > div.current-weather__info-row.current-weather__info-row_type_wind > abbr')->attr('title');
        xd($wind);*/

       /*$forecast = pq('ul.forecast-brief')->children('li.forecast-brief__item:not(.forecast-brief__item_gap)');

       foreach ($forecast as $li)
       {
           $li = pq($li);
           $li->find('.icon')->remove();
           xprint($li->html());
       }*/

        //phpQuery::unloadDocuments();

    }

    public function cook(){
        $cook = false;

        if( isset( $_COOKIE[ 'curl_session_coockie' ] ))
        {
            $cook = true;
            echo "Сессионная кука есть\r\n";
        }
        if( isset( $_COOKIE[ 'curl_normal_coockie' ] ))
        {
            $cook = true;
            echo "Нормальная кука есть\r\n";
        }

        setcookie('curl_session_coockie', 1);
        setcookie('curl_normal_coockie', 1);
        /*if(!$cook)
        {
            setcookie('curl_session_coockie', 1);
            setcookie('curl_normal_coockie', 1);
        }*/


        if($cook)
        {
            echo "Я тебя знаю!";
        }
        else
        {
            echo "Вы тут новенький!";
        }
    }
}
