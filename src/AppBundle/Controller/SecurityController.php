<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Security Controller.
 * Handles requests related to security actions.
 *
 * @author Jacob Bare  <jbare@southcomm.com>
 * @author Josh Worden <jworden@southcomm.com>
 */
class SecurityController extends Controller
{
    /**
     * Displays the login page.
     *
     * @param   Request     $request
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            '@AppBundle/Resources/views/default/sign-in.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * Built-in login check.
     * This controller method will not be executed directly, as the route is handled by the Security system.
     */
    public function loginCheckAction()
    {
    }
}
