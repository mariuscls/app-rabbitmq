<?php

namespace AppBundle\Services\Queue;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use AppBundle\Services\PostCodesService;

class PostCodesQueueService implements ConsumerInterface
{
    /**
     * @var $postcodesService PostCodesService
     */
    protected $postcodesService;

    public function __constructor(PostCodesService $postcodesService){
        $this->postcodesService = $postcodesService;
    }

    public function execute(AMQPMessage $msg)
    {

        $body = unserialize($msg->getBody());

        $result = $this->postcodesService->run( $body);

        $latitude = !empty($result['result'][0]['result'][0]['latitude']) ? $result['result'][0]['result'][0]['latitude'] : null;
        $longitude = !empty($result['result'][0]['result'][0]['longitude']) ? $result['result'][0]['result'][0]['longitude'] : null;

        if( empty($latitude) && empty($longitude) ){
            return false;
        }

        return true;
    }
}