<?php

namespace AppBundle\Services;

/**
 * The database service.
 * Acts as a mock persistence layer.
 * In "real-life," this would communicate with a database such as MongoDB.
 * For our purposes, the Symfony (PHP) Session is used.
 *
 * @author Jacob Bare  <jbare@southcomm.com>
 * @author Josh Worden <jworden@southcomm.com>
 */
class Database
{
    /**
     * The Symfony session wrapper object.
     *
     * @var Session
     */
    protected $session;

    /**
     * The default ToDo model data.
     *
     * @var array
     */
    protected $defaultTasks = [
        ['id' => 1, 'name' => 'Create Fleet Commander', 'description' => 'Create the demo application', 'complete' => true],
        ['id' => 2, 'name' => 'Create mission log', 'description' => 'Create the mission log interface', 'complete' => false]
    ];

    /**
     * Constructor/DI.
     *
     * @param   Session     $session The Symfony Session.
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->loadRecordsIntoSession();
    }

    /**
     * Retrieves all ToDo records from the "database."
     *
     * @return  array
     */
    public function all()
    {
        return $this->getFromSesssion();
    }

    /**
     * Creates a new ToDo record and persists it to the "database."
     *
     * @param   array   $data   The todo record payload.
     * @return  array
     */
    public function create(array $data)
    {
        $data['id'] = $this->generateId();
        $tasks = $this->getFromSesssion();
        $tasks[] = $data;
        $this->session->set('tasks', $tasks);
        return $data;
    }

    /**
     * Retrieves a single ToDo record from the "database."
     *
     * @param   string  $id     The record identifier.
     * @return  array
     */
    public function retrieve($id)
    {
        $index = $this->getRecordIndex($id);
        return $this->getFromSesssion()[$index];
    }

    /**
     * Updates a single ToDo record in the "database" with the provided data.
     *
     * @param   string  $id     The record identifier.
     * @param   array   $data   The record data to apply.
     * @return  array
     */
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

    /**
     * Deletes a single ToDo record from the "database."
     *
     * @param   string  $id     The record identifier.
     * @return  null
     */
    public function delete($id)
    {
        $index = $this->getRecordIndex($id);
        $tasks = $this->getFromSesssion();
        unset($tasks[$index]);
        $tasks = array_values($tasks);
        $this->session->set('tasks', $tasks);
        return null;
    }

    /**
     * Gets the array index of a record, by id.
     *
     * @param   int     $id     The record identifier.
     * @return  int
     * @throws  InvalidArgumentException    If the record was not found.
     */
    protected function getRecordIndex($id)
    {
        foreach ($this->getFromSesssion() as $index => $record) {
            if ($record['id'] === (Integer) $id) {
                return $index;
            }
        }
        throw new InvalidArgumentException(sprintf('No record was found using id "%s"', $id));
    }

    /**
     * Generates (auto-increments) a new record id.
     *
     * @return  int
     */
    protected function generateId()
    {
        $id = $this->session->get('currentId');
        $this->session->set('currentId', ++$id);
        return $id;
    }

    /**
     * Gets all todo records from the session.
     *
     * @return  array
     */
    protected function getFromSesssion()
    {
        return $this->session->get('tasks', []);
    }

    /**
     * Loads the default records into the session.
     *
     * @return  self
     */
    protected function loadRecordsIntoSession()
    {
        if (null === $this->session->get('tasks', null)) {
            $this->session->set('tasks', $this->defaultTasks);
        }

        if (null === $this->session->get('currentId', null)) {
            $this->session->set('currentId', 2);
        }
        return $this;
    }
}
