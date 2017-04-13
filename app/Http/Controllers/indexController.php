<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpQuery;

class indexController extends Controller
{
    public function index()
    {

        function request( $url, $postdata = null)
        {
            $cookiefile = public_path('cookie.txt');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36');
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_PROXY, '83.219.142.133:3128');
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

            curl_setopt($ch, CURLOPT_TIMEOUT, 9);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);

            if( $postdata )
            {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            }

            $html = curl_exec($ch);

            curl_close($ch);

            return $html;
        }

        $cookiefile = public_path('cookie.txt');
        file_put_contents( $cookiefile, '');

        $html = request('http://httpbin.org/ip');
        xprint($html);

        /*$html = request('https://radar.com.ua/login');

        phpQuery::newDocument($html);

        $token = pq('input[name=_token]')->attr('value');

        phpQuery::unloadDocuments();

        $post = [
            'phone' => '(048)798-51-06',
            'password' => 'te0203373269',
            '_token'=> $token
        ];

        $html = request('https://radar.com.ua/login', $post);

        echo $html;*/





       /* $cookiefile = public_path('cookie.txt');

        //$ch = curl_init('http://laravel.loc/cook');
        $ch = curl_init('https://radar.com.ua/nasosi/tsirkulyatsionnie/halm-hupa-25-60-u-130');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        //curl_setopt($ch, CURLOPT_NOBODY, true);

        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36');
        //curl_setopt($ch, CURLOPT_COOKIESESSION, true);

        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $html = curl_exec($ch);

        curl_close($ch);

        xprint($html);*/

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
        $message1 = '';
        $message2 = '';

        if( isset( $_COOKIE[ 'curl_session_coockie' ] ))
        {
            $cook = true;
            $message1 =  "Сессионная кука есть\r\n";
        }
        if( isset( $_COOKIE[ 'curl_normal_coockie' ] ))
        {
            $cook = true;
            $message2 = "Нормальная кука есть\r\n";
        }

        setcookie('curl_session_coockie', 1);
        setcookie('curl_normal_coockie', 1, microtime( true ) + 10000);

        echo $message1;
        echo $message2;
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
