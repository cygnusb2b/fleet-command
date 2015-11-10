<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * ToDo Controller.
 * Serves as the RESTful API layer for the ToDo Ember application.
 *
 * @author Jacob Bare  <jbare@southcomm.com>
 * @author Josh Worden <jworden@southcomm.com>
 */
class ToDoController extends Controller
{
    /**
     * Lists all ToDo models currently in the database.
     *
     * @param   Request     $request
     * @return  JsonResponse
     */
    public function listAction(Request $request)
    {
        $records = $this->get('app_bundle_storage_session')->all();
        return new JsonResponse(['todos' => $records], 200);
    }

    /**
     * Handles a ToDo model payload and passes it to the database service for creation.
     *
     * @param   Request     $request
     * @return  JsonResponse
     */
    public function createAction(Request $request)
    {
        $payload = json_decode($request->getContent())['todo'];
        $record = $this->get('app_bundle_storage_session')->create($payload);
        return new JsonResponse(['todo' => $record], 201);
    }

    /**
     * Retrieves a single ToDo model by id.
     *
     * @param   int         $id
     * @return  JsonResponse
     */
    public function retrieveAction($id)
    {
        $record = $this->get('app_bundle_storage_session')->retrieve($id);
        return new JsonResponse(['todo' => $record], 200);
    }

    /**
     * Handles a ToDo model payload and passes it to the database service for updating.
     *
     * @param   int         $id
     * @param   Request     $request
     * @return  JsonResponse
     */
    public function updateAction($id, Request $request)
    {
        $payload = json_decode($request->getContent(), true)['todo'];
        $record = $this->get('app_bundle_storage_session')->update($id, $payload);
        return new JsonResponse(['todo' => $record], 200);
    }

    /**
     * Deletes a single ToDo model by id.
     *
     * @todo    Implement this functionality.
     * @param   int         $id
     * @param   Request     $request
     * @return  JsonResponse
     * @throws  \InvalidArgumentException   Not yet implemented.
     */
    public function deleteAction($id, Request $request)
    {
        throw new \InvalidArgumentException('Not yet implemented');
    }
}
