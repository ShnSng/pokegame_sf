<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $isConnected = $session->get("isConnected", false);
        if ($isConnected) {
            return $this->forward("AppBundle:RefPokemon:index");
        } else {
            return $this->render("default/index.html.twig");
        }
    }
}
