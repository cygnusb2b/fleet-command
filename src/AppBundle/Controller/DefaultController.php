<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    protected $defaultTasks = [
        ['name' => 'Create Fleet Commander', 'description' => 'Create the demo application', 'complete' => true],
        ['name' => 'Create mission log', 'description' => 'Create the mission log interface', 'complete' => false]
    ];

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        if ($this->isGranted('ROLE_USER')) {
            return $this->render('default/todo.html.twig', ['tasks' => $this->retrieveTasks($request)]);
        }
        return $this->render('default/index.html.twig');
    }

    protected function retrieveTasks(Request $request)
    {
        $session = $request->getSession();

        if (null === $session->get('tasks', null)) {
            $session->set('tasks', $this->defaultTasks);
        }

        return $session->get('tasks', []);
    }

    /**
     * @Route("/create", name="create-task")
     */
    public function createTaskAction(Request $request)
    {
        $tasks = $this->retrieveTasks($request);
        $tasks[] = [
            'name'          => $request->request->get('name', 'New Mission Log'),
            'description'   => $request->request->get('description', ''),
            'complete'      => false
        ];
        $request->getSession()->set('tasks', $tasks);
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/toggle/{id}", name="toggle-task")
     */
    public function toggleTaskAction(Request $request, $id)
    {
        $tasks = $this->retrieveTasks($request);
        $tasks[$id]['complete'] = !$tasks[$id]['complete'];
        $request->getSession()->set('tasks', $tasks);
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/remove/{id}", name="remove-task")
     */
    public function removeTaskAction(Request $request, $id)
    {
        $tasks = $this->retrieveTasks($request);
        unset($tasks[$id]);
        $tasks = array_values($tasks);
        $request->getSession()->set('tasks', $tasks);
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/sign-in", name="sign-in")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'default/sign-in.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );

        return $this->render('default/sign-in.html.twig');
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }
}
