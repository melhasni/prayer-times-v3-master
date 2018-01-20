<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin/stats")
 */
class StatsController extends Controller
{
    /**
     * @Route(name="stats")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $mosqueRepository = $em->getRepository("AppBundle:Mosque");
        $userRepository = $em->getRepository("AppBundle:User");

        $totalMosquesAndHomes = $mosqueRepository->count();
        $totalMosques = $mosqueRepository->countMosques();

        $totalUsers = $userRepository->count();
        $totalUsersEnabled = $userRepository->countEnabled();

        $stats = [
            "mosques" => [
                "total" => $totalMosquesAndHomes,
                "totalMosques" => $totalMosques,
                "totalHomes" => $totalMosquesAndHomes - $totalMosques,
                "byCountry" => $mosqueRepository->getNumberByCountry(),
            ],
            "users" => [
                "total" => $totalUsers,
                "enabled" => $totalUsersEnabled,
                "disabled" => $totalUsers - $totalUsersEnabled,
            ]
        ];

        return $this->render('stats/index.html.twig', $stats);
    }

}
