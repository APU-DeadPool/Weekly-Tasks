<?php

require __DIR__ . "/vendor/autoload.php";

use GuzzleHttp\ClientInterface;

class ExchangeRateService
{   
    private const BASE_URI = 'https://api.apilayer.com/fixer';

    protected ClientInterface $_client;

    public function __construct(ClientInterface $_client)
    {
        $this->_client = $_client;
    }

    /**
     * @return mixed|string
     */
    public function exchangeRate($amount)
    {
        $response = $this->_client->request('GET', self::BASE_URI . '/' . 'convert', [
            'query' => [
                'to' => 'INR',
                'from' => 'EUR',
                'amount' => $amount,
                'date' => new DateTime(),
            ],
            'headers' => [
                'apikey' => 'zJHKIrKqpFSMz5TuR6IS6OeRjss7jWzM',
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
        $amount = 1000;
        $exchangeRate = $this->exchangeRate($amount);
        return $exchangeRate * $amount;
    }
}

