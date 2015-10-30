<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class RestController extends Controller
{
    protected $defaultTasks = [
        ['id' => 1, 'name' => 'Create Fleet Commander', 'description' => 'Create the demo application', 'complete' => true],
        ['id' => 2, 'name' => 'Create mission log', 'description' => 'Create the mission log interface', 'complete' => false]
    ];

    public function listAction($type, Request $request)
    {
        return new JsonResponse([$type => $this->defaultTasks], 200);
    }

    public function createAction($type, Request $request)
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

    public function retrieveAction($type, $id, Request $request)
    {
        $session = $request->getSession();

        if (null === $session->get('tasks', null)) {
            $session->set('tasks', $this->defaultTasks);
        }

        return $session->get('tasks', []);
    }

    public function updateAction($type, $id, Request $request, $id)
    {
        $tasks = $this->retrieveTasks($request);
        $tasks[$id]['complete'] = !$tasks[$id]['complete'];
        $request->getSession()->set('tasks', $tasks);
        return $this->redirectToRoute('homepage');
    }

    public function deleteAction($type, $id, Request $request, $id)
    {
        $tasks = $this->retrieveTasks($request);
        unset($tasks[$id]);
        $tasks = array_values($tasks);
        $request->getSession()->set('tasks', $tasks);
        return $this->redirectToRoute('homepage');
    }
}
