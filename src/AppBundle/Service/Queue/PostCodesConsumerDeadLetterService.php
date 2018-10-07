<?php

namespace AppBundle\Service\Queue;

use GuzzleHttp\Client;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class PostCodesConsumerDeadLetterService implements ConsumerInterface
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
        $message  = unserialize($msg->getBody());
        $this->logger->info('Corrupt message goes into Dead Letter Exchange');
    }

}