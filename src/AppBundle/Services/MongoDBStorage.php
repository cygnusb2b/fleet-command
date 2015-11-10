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
        $connection = new Connection('mongodb://localhost:27017');
        $this->connection = $connection->selectCollection($dbName, 'Todo');
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
                $response['id'] = (string) $value;
            }
        }
        return $response;
    }

    /**
     * Returns the MongoId for the supplied id value.
     *
     * @param  mixed    $id
     * @return MongoId
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
        return $this->formatResponse($this->collection->findOne(['_id' => $this->convertId($id)]));
    }
}
