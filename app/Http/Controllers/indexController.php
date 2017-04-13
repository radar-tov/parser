<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use phpQuery;

class indexController extends Controller
{
    public function index()
    {
        $html = file_get_contents('http://pogoda.yandex.ru');

        phpQuery::newDocument($html);


       /* $temperature = pq('.current-weather__thermometer_type_now')->text();
        xprint($temperature);

        $wind = pq('body > div.content > div.content__top.clearfix > div.current-weather > span.current-weather__col.current-weather__info > div.current-weather__info-row.current-weather__info-row_type_wind > abbr')->attr('title');
        xd($wind);*/

       $forecast = pq('ul.forecast-brief')->children('li.forecast-brief__item:not(.forecast-brief__item_gap)');

       foreach ($forecast as $li)
       {
           $li = pq($li);
           $li->find('.icon')->remove();
           $li->append('ppoipoipeoritpeoritb;cxvbm');
           xprint($li->html());
       }

        phpQuery::unloadDocuments();

    }
}
