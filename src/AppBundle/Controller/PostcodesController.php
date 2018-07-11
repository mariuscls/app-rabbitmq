<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostcodesController extends Controller
{

    public function postPostcodesAction(Request $request)
    {

        $postcodesProducer = $this->get('old_sound_rabbit_mq.postcodes_producer');
        $msg = json_decode($request->getContent(),true);
        $postcodesProducer->setContentType('application/json');
        $result = $postcodesProducer->publish(serialize($msg));

        return $result;
    }

}