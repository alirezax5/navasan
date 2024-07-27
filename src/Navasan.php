<?php

namespace Alirezax5\Navasan;

class Navasan
{
    public $url = 'http://api.navasan.tech/{PATH}/?api_key={KEY}';
    private $api_token;

    public function __construct($api_token)
    {
        $this->api_token = $api_token;
        return $this;
    }

    private function getUrl($path)
    {
        return strtr($this->url, ['{PATH}' => $path, '{KEY}' => $this->api_token]);

    }

    protected function request($path, $body = [], $httpMetHod = 'GET')
    {
        $data = $httpMetHod == 'POST' || $httpMetHod == 'PUT' ? json_encode($body) : http_build_query($body);
        $ch = curl_init();
        $options = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->getUrl($path),
            CURLOPT_POST => $httpMetHod == 'POST' ? true : false,
            CURLOPT_CUSTOMREQUEST => $httpMetHod,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ];
        curl_setopt_array($ch, $options);
        $res = curl_exec($ch);
        return json_decode($res, true);
    }

    public function latestRates($item = null, $dollar_rate = null, $dirham_rate = null)
    {
        return $this->request('latest', compact('item', 'dollar_rate', 'dirham_rate'));
    }

    public function dailyCurrency($item = null, $date = null)
    {
        return $this->request('dailyCurrency', compact('item', 'date'));
    }

    public function ohlcSearch($item = null, $start = null, $end = null)
    {
        return $this->request('ohlcSearch', compact('item', 'start', 'end'));
    }
}