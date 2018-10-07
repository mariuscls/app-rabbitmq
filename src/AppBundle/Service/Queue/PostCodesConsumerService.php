<?php

namespace AppBundle\Service\Queue;

use GuzzleHttp\Client;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PostCodesConsumerService implements ConsumerInterface
{
    /**
     * @var $client Client
     */
    private $client;

    /**
     * @var $logger LoggerInterface
     */
    private $logger;

    function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param AMQPMessage $msg
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        $data = unserialize($msg->getBody());

        $result = $this->run($data);

        $latitude = null;
        $longitude = null;

        if( !empty($result) ) {
            $body = json_decode($result,true);
            $latitude = !empty($body['result'][0]['query']['latitude']) ? $body['result'][0]['query']['latitude'] : null;
            $longitude = !empty($body['result'][0]['query']['longitude']) ? $body['result'][0]['query']['longitude'] : null;
        }

        if( empty($latitude) && empty($longitude) ){
            return false;
        }

        return true;
    }

    /**
     * @param $body
     * @return string
     * @throws \Exception|BadRequestHttpException|$response
     */
    private function run($body){

        $response = $this->client->post('postcodes', [
            'form_params' => $body
        ]);

        $response->withHeader('content-type','application/x-www-form-urlencoded');

        if ($response->getStatusCode() == 200){
            $this->logger->info('Registry processed correctly.');
            return $response->getBody()->getContents();
        }else if ($response->getStatusCode() == 400){
            $this->logger->error('It has not been possible to process the request correctly.');
            throw new BadRequestHttpException('Invalid JSON submitted. You need to submit a JSON object with an array of postcodes or geolocation objects.',null, 400 );
        }else{
            $this->logger->critical('Unexpected error when processing the request.');
            throw new \Exception('Unexpected error, please check the sent content.',500);
        }

    }


}