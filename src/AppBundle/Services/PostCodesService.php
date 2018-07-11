<?php

namespace AppBundle\Services;

use GuzzleHttp\Client;

class PostCodesService
{
    /**
     * @var $client Client
     */
    protected $client;

    public function __constructor(Client $client){
        $this->client = $client;
    }

    public function run(array $body)
    {
        $response = $this->client->post('postcodes', $body);
        $result  = (string)$response->getBody();
        return $result;
    }
}