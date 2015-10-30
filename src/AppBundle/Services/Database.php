<?php

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

class Database
{
    protected $session;

    protected $defaultTasks = [
        ['id' => 1, 'name' => 'Create Fleet Commander', 'description' => 'Create the demo application', 'complete' => true],
        ['id' => 2, 'name' => 'Create mission log', 'description' => 'Create the mission log interface', 'complete' => false]
    ];

    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->loadRecordsIntoSession();
    }

    public function all()
    {
        return $this->getFromSesssion();
    }

    public function create(array $data)
    {
        $data['id'] = $this->generateId();
        $tasks = $this->getFromSesssion();
        $tasks[] = $data;
        $this->session->set('tasks', $tasks);
        return $data;
    }

    public function retrieve($id)
    {
        $index = $this->getRecordIndex($id);
        return $this->getFromSesssion()[$index];
    }

    public function update($id, array $data = [])
    {
        $record = $this->retrieve($id);
        foreach ($data as $key => $value) {
            $record[$key] = $value;
        }
        $index = $this->getRecordIndex($id);
        $tasks = $this->getFromSesssion();
        $tasks[$index] = $record;
        $this->session->set('tasks', $tasks);
        return $record;
    }

    public function delete($id)
    {
        $index = $this->getRecordIndex($id);
        $tasks = $this->getFromSesssion();
        unset($tasks[$index]);
        $tasks = array_values($tasks);
        $this->session->set('tasks', $tasks);
        return null;
    }

    protected function getRecordIndex($id)
    {
        foreach ($this->getFromSesssion() as $index => $record) {
            if ($record['id'] === (Integer) $id) {
                return $index;
            }
        }
        throw new InvalidArgumentException(sprintf('No record was found using id "%s"', $id));
    }

    protected function generateId()
    {
        $id = $this->session->get('currentId');
        $this->session->set('currentId', ++$id);
        return $id;
    }

    protected function getFromSesssion()
    {
        return $this->session->get('tasks', []);
    }

    protected function loadRecordsIntoSession()
    {
        if (null === $this->session->get('tasks', null)) {
            $this->session->set('tasks', $this->defaultTasks);
        }

        if (null === $this->session->get('currentId', null)) {
            $this->session->set('currentId', 2);
        }
    }
}
