<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpQuery;
use App\Models\Proxy;

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
        $cookiefile = public_path('cookies/proxyNet.txt');
        file_put_contents( $cookiefile, '');

        $html = $this->requestCurl('http://proxydb.net/?protocol=socks5&anonlvl=4&offset=50');

        phpQuery::newDocument($html);

        $trs = pq('.table-sm > tbody > tr');

        foreach ($trs as $tr) {

            $tr = pq($tr);
            $ipPort = explode(':', $tr->find('a')->text());
            $ip = $ipPort[0];
            $port = $ipPort[1];

            $type = trim($tr->find('td:nth-child(2)')->text());
            switch ($type) {
                case 'HTTP':
                    $type_id = 1;
                    break;
                case 'HTTPS':
                    $type_id = 2;
                    break;
                case 'SOCKS4':
                    $type_id = 3;
                    break;
                case 'SOCKS5':
                    $type_id = 4;
                    break;
                default:
                    $type_id = 1;
            }

            $anonymi_level = trim($tr->find('td:nth-child(3)')->text());
            switch ($anonymi_level) {
                case 'Transparent':
                    $anonymi_level_id = 1;
                    break;
                case 'Anonymous':
                    $anonymi_level_id = 2;
                    break;
                case 'Distorting':
                    $anonymi_level_id = 3;
                    break;
                case 'High Anonymous':
                    $anonymi_level_id = 4;
                    break;
                default:
                    $anonymi_level_id = 1;
            }

            //echo 'IP: ' . $ip . ':' . $port . ' - Type: ' . $type . ' - Anonymous Level: ' . $anonymi_level . "\n";

            $proxy = Proxy::where('ip', $ip)->first();

            if(!$proxy)
            {
                $proxy = new Proxy();
            }

            $proxy->ip = $ip;
            $proxy->port = $port;
            $proxy->type_id = $type_id;
            $proxy->anonymi_level_id = $anonymi_level_id;
            $proxy->status_id = 2;
            $proxy->save();

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
