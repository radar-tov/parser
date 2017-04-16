<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proxy;

class checkerProxyNet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkerProxyNet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'https://checkerproxy.net';

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
        $cookiefile = public_path('cookies/checkerProxyNet.txt');
        file_put_contents( $cookiefile, '');

        $url = 'https://checkerproxy.net/api/archive/' . date('Y-m-d');

        $proxys = json_decode($this->requestCurl($url), true);

        foreach ($proxys as $newProxy)
        {
            $ipPort = explode(':', $newProxy['addr']);

            $proxy = Proxy::where('ip', $ipPort[0])->first();

            if(!$proxy)
            {
                $proxy = new Proxy();

                switch ($newProxy['kind']) {
                    case 0:
                        $anonymi_level_id = 1;
                        break;
                    case 2:
                        $anonymi_level_id = 2;
                        break;
                    default:
                        $anonymi_level_id = 1;
                }

                $proxy->ip = $ipPort[0];
                $proxy->port = $ipPort[1];
                $proxy->type_id = $newProxy['type'];
                $proxy->anonymi_level_id = $anonymi_level_id;
                $proxy->status_id = 3;

                if($proxy->type_id == 4){
                    $proxy->save();
                }
            }

        }

    }


    /**
     * @param $url
     * @param null $postdata
     * @return mixed
     */
    public function requestCurl( $url, $postdata = null)
    {
        $cookiefile = public_path('cookies/checkerProxyNet.txt');

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
