<?php

namespace App\Libraries;

use Exception;
use GuzzleHttp\Exception\RequestException;

class Bybit
{
    protected $key;
    protected $secret;
    protected $host;
    protected $options = [];

    public function __construct(string $key = '', string $secret = '', string $host = 'https://api-testnet.bybit.com')
    {
        $this->key = $key = 'oNsWbCwKUDAhS1ou3D';
        $this->secret = $secret = 'sRoEorumn1nxLWeEMxPUuPSyo0qTpffx40ET';
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

    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    public function account()
    {
        return new Account($this->init());
    }

    public function trade()
    {
        return new Trade($this->init());
    }
}

class Account extends Request
{
    //Default required HMAC SHA256
    protected $signature = true;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->data['timestamp'] = time() . '000';
    }

    public function getInfo(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/account';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getBalance(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/balance';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getCoinNetwork(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/sapi/v1/capital/config/getall';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function postTranferFutures(array $data = [])
    {
        $this->type = 'post';
        $this->path = '/sapi/v1/futures/transfer';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function postWithdraw(array $data = [])
    {
        $this->type = 'post';
        $this->path = '/sapi/v1/capital/withdraw/apply';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }
}

class Trade extends Request
{
    //Default required HMAC SHA256
    protected $signature = true;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->data['timestamp'] = time() . '000';
    }

    public function postMarginType(array $data = [])
    {
        $this->type = 'POST';
        $this->path = '/fapi/marginType';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function postLeverage(array $data = [])
    {
        $this->type = 'post';
        $this->path = '/fapi/leverage';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function postOrder(array $data = [])
    {
        $this->type = 'POST';
        $this->path = '/fapi/order';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getOpenOrder(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/openOrder';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getAllOpenOrders(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/openOrders';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getOrder(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/private/linear/order/list';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getOrders(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/allOrders';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getPosition(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/private/linear/position/list';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getTrades(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/userTrades';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getMarkPrice(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/premiumIndex';
        $this->data = array_merge($this->data, $data);
        return $this->exec();
    }

    public function getExchangeInfo(array $data = [])
    {
        $this->type = 'get';
        $this->path = '/fapi/exchangeInfo';
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

    public function __construct(array $data)
    {
        $this->key = $data['key'] ?? '';
        $this->secret = $data['secret'] ?? '';
        $this->host = $data['host'] ?? 'https://api.bybit.com';
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
            // must be ordered alphabetically
            ksort($this->data);

            foreach ($this->data as $k1 => $v1) {
                if (is_array($v1)) {
                    $this->query .= $k1 . '=' . urlencode(json_encode($v1)) . '&';
                } else {
                    $this->query .= $k1 . '=' . $v1 . '&';
                }
            }
            $this->query = 'api_key=' . $this->key . '&' . substr($this->query, 0, -1);

            if ($this->signature === true) {
                $this->signature = $this->query . '&sign=' . hash_hmac('sha256', $this->query, $this->secret);
            } else {
                $this->signature = $this->query;
            }
        }
    }

    protected function headers()
    {
        $this->headers = [];
    }

    public function getResponseHeaders()
    {
        return $this->response_headers;
    }

    protected function options()
    {
        if (isset($this->options['headers'])) {
            $this->headers = array_merge($this->headers, $this->options['headers']);
        }

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
