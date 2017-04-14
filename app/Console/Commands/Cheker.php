<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

    }

    /**
     * @param $url
     * @param null $postdata
     * @return mixed
     */
    public function requestCurlCheker( $ip, $port, $anonymi_level)
    {
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

        switch ($anonymi_level){
            case 1:
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            case 2:
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            case 3:
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
            case 4:
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 9);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);

        $html = curl_exec($ch);

        curl_close($ch);

        return $html;
    }
}
