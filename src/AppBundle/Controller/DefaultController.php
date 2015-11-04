<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

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
        return $this->render(
            '@AppBundle/Resources/views/default/provisioning.html.twig',
            ['username' => $this->getUsername($request)]
        );
    }

    private function getUsername(Request $request)
    {
        $cookies = $request->cookies;
        if ($cookies->has('username') && null !== $username = $cookies->get('username')) {
            return $username;
        }
    }

    /**
     * Handles requests to create new developer environment via GH fork web hook.
     *
     * @param   Request     $request
     */
    public function forkAction(Request $request)
    {
        if (null !== $this->getUsername($request)) {
            // Already set username -- do not reprovision.
            return $this->redirectToRoute('app_default_index');
        }

        // Required fields
        if (!$request->request->get('username') || !$request->request->get('email') || !$request->request->get('name')) {
            return $this->redirectToRoute('app_default_index');
        }

        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $name = $request->request->get('name');
        exec(sprintf('sudo createuser %s %s %s', escapeshellarg($username), escapeshellarg($email), escapeshellarg($name)));

        $cookie = new Cookie('username', $username);
        $response = new RedirectResponse($this->generateUrl('app_default_index'));
        $response->headers->setCookie($cookie);
        return $response;
    }
}
