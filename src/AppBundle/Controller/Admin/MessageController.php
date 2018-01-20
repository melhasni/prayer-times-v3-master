<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Message;
use AppBundle\Entity\Mosque;
use AppBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin")
 */
class MessageController extends Controller
{

    /**
     * @Route("/{mosque}/message/", name="message_index")
     */
    public function indexAction(Request $request, Mosque $mosque)
    {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository("AppBundle:Message")->getBySortableGroupsQuery(array('mosque' => $mosque))->getResult();

        return $this->render('message/index.html.twig', [
            "mosque" => $mosque,
            "messages" => $messages
        ]);
    }

    /**
     * @Route("/{mosque}/message/create", name="message_create")
     */
    public function createAction(Request $request, Mosque $mosque)
    {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }

        $message = new Message();
        $message->setMosque($mosque);

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $this->addFlash('success', "form.create.success");

            return $this->redirectToRoute('message_index', ['mosque' => $mosque->getId()]);
        }

        return $this->render('message/create.html.twig', [
            "mosque" => $mosque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{mosque}/message/{message}/edit", name="message_edit")
     */
    public function editAction(Request $request, Mosque $mosque, Message $message)
    {

        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $mosque->getUser()) {
            throw new AccessDeniedException;
        }

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $this->addFlash('success', "form.edit.success");

            return $this->redirectToRoute('message_index', ['mosque' => $mosque->getId()]);
        }

        return $this->render('message/edit.html.twig', [
            "message" => $message,
            "mosque" => $mosque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/message/{id}/delete", name="message_delete")
     */
    public function deleteAction(Request $request, Message $message)
    {
        $user = $this->getUser();
        if (!$user->isAdmin() && $user !== $message->getMosque()->getUser()) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();
        $this->addFlash('success', "form.delete.success");
        return $this->redirectToRoute('message_index', ['mosque' => $message->getMosque()->getId()]);
    }


    /**
     * Resorts an item using it's doctrine sortable property
     * @param Request $request
     * @Route("/message/sort", name="message_sort")
     * @Method("PUT")
     * @return JsonResponse
     */
    public function sortAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository('AppBundle:Message')->find((int)$request->request->get("id"));
        $message->setPosition((int)$request->request->get("position"));
        $em->persist($message);
        $em->flush();

        return new JsonResponse();
    }
}
