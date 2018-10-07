<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PostcodesController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postPostcodesAction(Request $request)
    {
        $postcodesProducer = $this->get('old_sound_rabbit_mq.postcodes_producer');
        $data = json_decode($request->getContent(),true);
        $postcodesProducer->setContentType('application/json');
        $postcodesProducer->publish(serialize($data));

        return new JsonResponse(array('Status' => 'OK'));
    }

}