<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Default application route.
     *
     * @param   Request     $request
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $session->start();
        if ($this->isGranted('ROLE_USER')) {
            return $this->render('default/app.html.twig');
        }
        return $this->render('default/index.html.twig');
    }
}
