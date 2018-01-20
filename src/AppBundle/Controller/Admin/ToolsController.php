<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin/tools")
 */
class ToolsController extends Controller
{
    /**
     * @Route("/force-update-all", name="mosque_force_update_all")
     */
    public function forceUpdateAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository("AppBundle:Mosque")->forceUpdateAll();
        $this->addFlash('success', $this->get("translator")->trans("mosque.force_update_all.success"));
        return $this->redirectToRoute('mosque_index');
    }

}
