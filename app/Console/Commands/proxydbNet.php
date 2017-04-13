<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpQuery;

class proxydbNet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proxydbNet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'http://proxydb.net';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cookiefile = public_path('cookie.txt');
        file_put_contents( $cookiefile, '');

        $html = $this->requestCurl('http://proxydb.net/?protocol=socks4&anonlvl=3&anonlvl=4');

        //xprint($html);

        phpQuery::newDocument($html);

        $ips = pq('.table-sm > tbody > tr > td > a');

        foreach ($ips as $ip)
        {
            $ip = pq($ip);
            xprint($ip->text());
        }

        phpQuery::unloadDocuments();
    }


    /**
     * @param $url
     * @param null $postdata
     * @return mixed
     */
    public function requestCurl( $url, $postdata = null)
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

        //curl_setopt($ch, CURLOPT_PROXY, '61.141.21.34:1080');
        //curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);

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
}
