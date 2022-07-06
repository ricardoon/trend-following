<?php

namespace App\Libraries;

use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class Binance
{
    protected $key;
    protected $secret;
    protected $host;
    protected $options = [];

    public function __construct(string $key = '', string $secret = '', string $host = 'https://fapi.binance.com')
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->host = $host;
    }

    private function init()
    {
        return [
            'key' => $this->key,
            'secret' => $this->secret,
            'host' => $this->host,

            'options' => $this->options,
        ];
    }

    function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    public function trade()
    {
        return new Trade($this->init());
    }
}

class Trade extends Request
{
    //Default required HMAC SHA256
    protected $signature = true;

    //Default seting
    protected $version = 'v1';

    function __construct(array $data)
    {
        parent::__construct($data);

        $this->data['timestamp'] = time() . '000';
    }

    public function postMarginType(array $data = [])
    {
        $this->type = 'POST';
        $this->path = '/fapi/' . $this->version . '/marginType';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function postLeverage(array $data = [])
    {
        $this->type = 'post';
        $this->path = '/fapi/' . $this->version . '/leverage';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function postOrder(array $data = [])
    {
        $this->type = 'POST';
        $this->path = '/fapi/' . $this->version . '/order';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getOpenOrder(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/' . $this->version . '/openOrder';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getAllOpenOrders(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/' . $this->version . '/openOrders';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getOrder(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/' . $this->version . '/order';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getAllOrders(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/' . $this->version . '/allOrders';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getPosition(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/' . $this->version . '/positionRisk';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getMarkPrice(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/' . $this->version . '/premiumIndex';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getExchangeInfo(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/' . $this->version . '/exchangeInfo';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }
}

class Request
{
    protected $key = '';
    protected $secret = '';
    protected $host = '';
    protected $nonce = '';
    protected $signature = ''; //bool | string
    protected $headers = [];
    protected $type = '';
    protected $path = '';
    protected $query = '';
    protected $data = [];
    protected $options = [];
    protected $response_headers = [];
    protected $version = '';

    public function __construct(array $data)
    {
        $this->key = $data['key'] ?? '';
        $this->secret = $data['secret'] ?? '';
        $this->host = $data['host'] ?? 'https://api.binance.com';

        if (isset($data['options']['version'])) {
            $this->version = strtolower($data['options']['version']);
            unset($data['options']['version']);
        }

        $this->options = $data['options'] ?? [];
    }

    protected function auth()
    {
        $this->nonce();

        $this->signature();

        $this->headers();

        $this->options();
    }

    protected function nonce()
    {
        $this->nonce = '';
    }

    protected function signature()
    {
        if (!empty($this->data)) {
            foreach ($this->data as $k1 => $v1) {
                if (is_array($v1)) $this->query .= $k1 . '=' . urlencode(json_encode($v1)) . '&';
                else $this->query .= $k1 . '=' . $v1 . '&';
            }
            $this->query = substr($this->query, 0, -1);

            if ($this->signature === true) {
                $this->signature = $this->query . '&signature=' . hash_hmac('sha256', $this->query, $this->secret);
            } else {
                $this->signature = $this->query;
            }
        }
    }

    protected function headers()
    {
        $this->headers = [
            'X-MBX-APIKEY' => $this->key,
        ];
    }

    public function getResponseHeaders()
    {
        return $this->response_headers;
    }

    protected function options()
    {
        if (isset($this->options['headers'])) $this->headers = array_merge($this->headers, $this->options['headers']);

        $this->options['headers'] = $this->headers;
        $this->options['timeout'] = $this->options['timeout'] ?? 60;
    }

    protected function send()
    {
        $client = new \GuzzleHttp\Client();

        $query = $this->signature === true ? '' : '?' . $this->signature;

        $response = $client->request($this->type, $this->host . $this->path . $query, $this->options);

        $this->signature = '';

        $this->response_headers = $response->getHeaders();

        return $response->getBody()->getContents();
    }

    protected function exec()
    {
        $this->auth();
        try {
            return json_decode($this->send(), true);
        } catch (RequestException $e) {
            if (method_exists($e->getResponse(), 'getBody')) {
                $contents = $e->getResponse()->getBody()->getContents();

                $temp = json_decode($contents, true);
                if (!empty($temp)) {
                    $temp['_method'] = $this->type;
                    $temp['_url'] = $this->host . $this->path . '?' . $this->query;
                } else {
                    $temp['_message'] = $e->getMessage();
                }
            } else {
                $temp['_message'] = $e->getMessage();
            }

            $temp['_httpcode'] = $e->getCode();

            throw new Exception(json_encode($temp));
        }
    }
}
