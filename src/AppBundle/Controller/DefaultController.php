<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Default Controller.
 * Handles core/basic requests.
 *
 * @author Jacob Bare  <jbare@southcomm.com>
 * @author Josh Worden <jworden@southcomm.com>
 */
class DefaultController extends Controller
{
    /**
     * Default application route.
     * Loads the home page or the application, depending on user permissions.
     *
     * @param   Request     $request
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $session->start();
        if ($this->isGranted('ROLE_USER')) {
            return $this->render('@AppBundle/Resources/views/default/app.html.twig');
        }
        return $this->render('@AppBundle/Resources/views/default/index.html.twig');
    }

    /**
     * Handles requests to create new developer environment via GH fork web hook.
     *
     * @param   Request     $request
     */
    public function forkAction(Request $request)
    {
        $username = json_decode($request->getContent(), false)->forkee->owner->login;
        exec('sudo createuser '. escapeshellarg($username));
        return new JsonResponse([], 200);
    }
}
