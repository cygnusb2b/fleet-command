<?php

namespace AppBundle\Services;

interface StorageEngine
{
    /**
     * Retrieves all models
     *
     * @return  array
     */
    public function all();

    /**
     * Creates a model
     *
     * @param   array      $payload
     * @return  array
     */
    public function create(array $payload);

    /**
     * Retrieves a model
     *
     * @param   int         $id
     * @return  array
     */
    public function retrieve($id);

    /**
     * Modifies a model
     *
     * @param   int         $id
     * @param   array       $payload
     * @return  array
     */
    public function update($id, array $payload);

    /**
     * Deletes a model
     *
     * @param   int         $id
     * @param   array       $payload
     * @return  array
     */
    public function delete($id);
}
