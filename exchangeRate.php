<?php

require __DIR__ . "/vendor/autoload.php";

use GuzzleHttp\ClientInterface;

class ExchangeRateService
{   
    private const BASE_URI = 'https://api.apilayer.com/fixer';

    private int $_amount;

    private string $_apiKey;

    protected ClientInterface $_client;


    public function __construct(ClientInterface $_client, int $_amount, string $_apiKey)
    {
        $this->_apiKey = $_apiKey;
        $this->_client = $_client;
        $this->_amount = $_amount;
    }

    /**
     * @return mixed|string
     */
    public function exchangeRate()
    {
        $response = $this->_client->request('GET', self::BASE_URI . '/' . 'convert', [
            'query' => [
                'to' => 'INR',
                'from' => 'EUR',
                'amount' => $this->_amount,
                'date' => new DateTime(),
            ],
            'headers' => [
                'apikey' => $this->_apiKey,
            ],
        ]);
        $results = json_decode($response->getBody()->getContents(), true);
        if ($response->getStatusCode() === 200)
        {
            return $results['info']['rate'];
        }
        else
        {
            $errorMessage = 'Server did not return a JSON response.';
            return ($errorMessage);
        }
    }

    /**
     * @return mixed|string
     */
    public function foreignAmount()
    {
        $exchangeRate = $this->exchangeRate();
        return $exchangeRate * $this->_amount;
    }
}