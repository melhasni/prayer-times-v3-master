<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/messages")
 */
class MessageController extends Controller {

    /**
     * @Route("/{slug}", name="messages")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function indexAction(Request $request, Mosque $mosque) {
        return $this->render("message/show_message_slider.html.twig", [
                    'mosque' => $mosque
        ]);
    }

    /**
     * @Route("/{id}/get-messages", name="ajax_get_messages")
     */
    public function getMessagesAjaxAction(Request $request, Mosque $mosque) {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository("AppBundle:Message")->getMessagesByMosque($mosque);
        
        $res = [
          "messages"  => $messages,
          "timeToDisplayMessage"  => $mosque->getConfiguration()->getTimeToDisplayMessage(),
        ];
        return new Response($this->get("serializer")->serialize($res, "json"), 200, [
            'content-type' => 'application/json'
        ]);
    }

}
