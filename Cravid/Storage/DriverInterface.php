<?php

namespace Cravid\Storage;

interface DriverInterface
{
    /**
     * Configures the driver connection.
     * 
     * @param array $params
     */
    public function __construct(array $params);

    /**
     * Translates a query to the driver specific syntax and executes it.
     *
     * @param \Cravid\Storage\Query  $query  The query object.
     * @param \Cravid\Storage\ResultInterface $result The result object (optional).
     *
     * @return \Cravid\Storage\ResultInterface
     *
     * @throws \Cravid\Storage\Driver\ExecutionException if an error occurs.
     */
    public function execute(\Cravid\Storage\Query $query, \Cravid\Storage\ResultInterface $result = null);
}