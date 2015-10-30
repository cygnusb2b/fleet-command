<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RestController extends Controller
{
    protected $defaultTasks = [
        ['name' => 'Create Fleet Commander', 'description' => 'Create the demo application', 'complete' => true],
        ['name' => 'Create mission log', 'description' => 'Create the mission log interface', 'complete' => false]
    ];

    public function listAction(Request $request)
    {

    }

    public function createAction(Request $request)
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

    public function retrieveAction($id, Request $request)
    {
        $session = $request->getSession();

        if (null === $session->get('tasks', null)) {
            $session->set('tasks', $this->defaultTasks);
        }

        return $session->get('tasks', []);
    }

    public function updateAction($id, Request $request, $id)
    {
        $tasks = $this->retrieveTasks($request);
        $tasks[$id]['complete'] = !$tasks[$id]['complete'];
        $request->getSession()->set('tasks', $tasks);
        return $this->redirectToRoute('homepage');
    }

    public function deleteAction($id, Request $request, $id)
    {
        $tasks = $this->retrieveTasks($request);
        unset($tasks[$id]);
        $tasks = array_values($tasks);
        $request->getSession()->set('tasks', $tasks);
        return $this->redirectToRoute('homepage');
    }
}
