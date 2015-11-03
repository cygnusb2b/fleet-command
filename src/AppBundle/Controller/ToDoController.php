<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ToDoController extends Controller
{
    public function listAction(Request $request)
    {
        $records = $this->get('app_bundle_database')->all();
        return new JsonResponse(['todos' => $records], 200);
    }

    public function createAction(Request $request)
    {
        $payload = json_decode($request->getContent())['todo'];
        $record = $this->get('app_bundle_database')->create($payload);
        return new JsonResponse(['todo' => $record], 201);
    }

    public function retrieveAction($id, Request $request)
    {
        $session = $request->getSession();

        if (null === $session->get('tasks', null)) {
            $session->set('tasks', $this->defaultTasks);
        }

        return $session->get('tasks', []);
    }

    public function updateAction($id, Request $request)
    {
        $payload = json_decode($request->getContent(), true)['todo'];
        $record = $this->get('app_bundle_database')->update($id, $payload);
        return new JsonResponse(['todo' => $record], 200);
    }

    public function deleteAction($id, Request $request)
    {
        throw new \InvalidArgumentException('Not yet implemented');
    }
}
