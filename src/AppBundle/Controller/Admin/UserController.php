<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\EmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin/user")
 */
class UserController extends Controller
{

    /**
     * @Route(name="user_index")
     */
    public function indexAction(Request $request)
    {
        $search = $request->query->all();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository("AppBundle:User")->search($search);
        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate($qb, $request->query->getInt('page', 1), 10);
        return $this->render('user/index.html.twig', [
            "users" => $users
        ]);
    }

    /**
     * @Route("/{id}/delete", name="user_delete")
     */
    public function deleteAction(Request $request, User $user)
    {
        if (!$this->getUser()->isAdmin() || $this->getUser() === $user) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', "form.delete.success");
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}/show", name="user_show")
     */
    public function showAction(Request $request, User $user)
    {
        return $this->render('user/show.html.twig', [
            "user" => $user
        ]);
    }

    /**
     * @Route("/{id}/enable/{status}", name="ajax_user_enable")
     */
    public function enableAction(Request $request, User $user, $status)
    {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled($status === "true");
        $em->persist($user);
        $em->flush();
        return new Response();
    }

    /**
     * send email to all users
     * @Route("/send-email", name="users_send_email")
     */
    public function sendEmailAction(Request $request)
    {

        $form = $this->createForm(EmailType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get("app.user_service")->sendEmailToAllUsers($form->getData());
            $this->addFlash('success', "email.send.success");

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/send_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * get users by search term
     * @Route("/search-ajax", name="users_search_ajax")
     */
    public function searchAjaxAction(Request $request)
    {
        $term = $request->query->get("term");
        if (empty($term)) {
            return new JsonResponse();
        }

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")
            ->search($term)
            ->select("u.id, u.email as label")
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($users);
    }

}
