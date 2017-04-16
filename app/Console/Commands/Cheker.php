<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proxy;
use Curl\MultiCurl;

class Cheker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cheker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking proxy';

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
        //$this->multiCurl();
        $proxys = Proxy::where('status_id', 3)->get();//->take(10)

        foreach ($proxys as $proxy)
        {
            $request = $this->requestCurlCheker($proxy->ip, $proxy->port, $proxy->anonymi_level_id);

            if(!$request)
            {
                $proxy->status_id = 1;
                $proxy->save();
                echo "Error " . $proxy->ip . "\n\r\n\r";
            }
            else
            {
                $proxy->status_id = 2;
                $proxy->save();
                echo "Ok " . $proxy->ip . "\n\r\n\r";
                xprint($request);
            }
        }
    }

    /**
     * @param $url
     * @param null $postdata
     * @return mixed
     */
    public function requestCurlCheker( $ip, $port, $anonymi_level_id)
    {
        echo $ip . ':' . $port . "\n";
        $cookiefile = public_path('cookies/cheker.txt');

        $ch = curl_init('http://httpbin.org/ip');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_PROXY, $ip . ':' . $port );

        switch ($anonymi_level_id){
            case 1:
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                echo 'Level 1' . "\n";
                break;
            case 2:
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                echo 'Level 2' . "\n";
                break;
            case 3:
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
                echo 'Level 3' . "\n";
                break;
            case 4:
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
                echo 'Level 4' . "\n";
                break;
            default:
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                echo 'Level default' . "\n";

        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $html = curl_exec($ch);

        curl_close($ch);

        return $html;
    }






    public function multiCurl()
    {
        // Requests in parallel with callback functions.
        $multi_curl = new MultiCurl();
        $multi_curl->windowSize = 5;

        $cookiefile = public_path('cookies/cheker.txt');

        $multi_curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $multi_curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
        $multi_curl->setUserAgent('Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36');
        $multi_curl->setReferer('https://www.google.com/');
        $multi_curl->setCookieJar($cookiefile);
        $multi_curl->setCookieFile($cookiefile);
        $multi_curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $multi_curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $multi_curl->setTimeout(15);
        $multi_curl->setConnectTimeout(10);

        $proxys = Proxy::where('status_id', '>', 0)->take(10)->get();//

        foreach ($proxys as $proxy)
        {
            $multi_curl->addGet('http://httpbin.org/ip');
            $multi_curl->setOpt(CURLOPT_PROXY, $proxy->ip . ':' . $proxy->port);
            switch ($proxy->type_id){
                case 1:
                    $multi_curl->setOpt(CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                    break;
                case 2:
                    $multi_curl->setOpt(CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                    break;
                case 3:
                    $multi_curl->setOpt(CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
                    break;
                case 4:
                    $multi_curl->setOpt(CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
                    break;
                default:
                    $multi_curl->setOpt(CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

            }
        }


        $multi_curl->success(function($instance) use ($proxy) {
            echo 'ip: ' . $proxy->ip . "\n";
            echo 'port: ' . $proxy->port . "\n";
            echo 'type_id: ' . $proxy->type_id . "\n";
            echo 'call to "' . $instance->url . '" was successful.' . "\n";
            echo 'response:' . "\n";
            var_dump($instance->response);
            echo "\n\r\n\r";
        });

        $multi_curl->error(function($instance) use ($proxy) {
            echo 'ip: ' . $proxy->ip . "\n";
            echo 'port: ' . $proxy->port . "\n";
            echo 'type_id: ' . $proxy->type_id . "\n";
            echo 'call to "' . $instance->url . '" was unsuccessful.' . "\n";
            echo 'error code: ' . $instance->errorCode . "\n";
            echo 'error message: ' . $instance->errorMessage . "\n\r\n\r";
        });


        $multi_curl->complete(function($instance) {
            echo 'call completed' . "\n";
        });


        $multi_curl->start(); // Blocks until all items in the queue have been processed.
    }
}
