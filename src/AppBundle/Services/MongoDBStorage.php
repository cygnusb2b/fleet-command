<?php

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\MongoDB\Connection;
use Doctrine\MongoDB\Collection;
use Doctrine\MongoDB\Cursor;
use MongoId;

/**
 * The MongoDB Storage engine service.
 *
 * @author Jacob Bare  <jbare@southcomm.com>
 * @author Josh Worden <jworden@southcomm.com>
 */
class MongoDBStorage implements StorageEngine
{
    /**
     * The MongoDB Connection
     *
     * @var Connection
     */
    protected $connection;

    /**
     * The MongoDB Collection
     *
     * @var Collection
     */
    protected $collection;

    /**
     * Constructor/DI.
     *
     * @param   string     $dbName
     */
    public function __construct($dbName)
    {
        $this->connection = $this->initConnection();
        $this->collection = $this->connection->selectCollection($dbName, 'Todo');
    }

    /**
     * Initializes the MongoDB connection.
     * For lack of the ODM and symfony configuration, hardcoded to localhost.
     *
     * @return Connection
     */
    private function initConnection()
    {
        return new Connection('mongodb://localhost:27017');
    }

    /**
     * Selects and returns the specified database and collection
     *
     * @param   string $collection
     * @param   string $database
     * @return  Collection
     */
    private function selectCollection($collection = 'Todo', $database = 'test_sandbox')
    {
        return $this->connection->selectCollection($database, $collection);
    }

    /**
     * Handles serializing responses into a standard API format.
     *
     * @param  array $response  An array of MongoDB result data
     * @return array
     */
    private function formatResponse(array $response)
    {
        foreach ($response as $key => &$value) {
            if (is_array($value)) {
                $value = $this->formatResponse($value);
            }
            if ($key === '_id') {
                unset($response[$key]);
                $response['id'] = $this->convertId($value);
            }
        }
        return $response;
    }

    /**
     * Returns an array containing the formatted MongoId
     *
     * @param  mixed $id
     * @return array        An array containing the MongoId
     */
    private function convertId($id)
    {
        if ($id instanceof MongoId) {
            return $id;
        }
        return new MongoId($id);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->formatResponse(array_values($this->collection->find()->toArray()));
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($id)
    {
        return $this->formatResponse($this->selectCollection()->findOne(['_id' => $this->convertId($id)]));
    }
}
