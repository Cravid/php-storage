<?php

namespace Cravid\Storage\Driver\Mongo;

class Driver
{
    /**
     * The database connection instance.
     * 
     * @var mixed
     */
    protected $conn = null;

    /**
     * The expression mapper.
     * 
     * @var \Cravid\Storage\Mapper
     */
    protected $mapper = null;

    
    /**
     * {@inheritdoc}
     */
    public function __construct(array $params)
    {
        if (!class_exists('MongoClient')) {
            throw new \Cravid\Storage\MissingDependencyException('The MongoDB PECL extension has not been installed or enabled.');
        }

        $defaultParams = array(
            'host'          => '127.0.0.1',
            'port'          => 27017,
            'dbname'        => '',
            'user'          => 'root',
            'password'      => '',
        );
        $params = array_merge($defaultParams, $params);

        try {
            $this->conn = new \MongoClient('mongodb://' . $params['host'] . ':' . $params['port'], array(
                'connect'   => true,
                'db'        => $params['dbname'],
                'username'  => $params['user'],
                'password'  => $params['password'],
            ));
        }
        catch (\MongoException $e) {
            throw new \Cravid\Storage\DriverInterfaceException($e->getMessage(), $e->getCode(), $e);
        }

        $this->mapper = new Mapper();
    }

    /**
     * {@inheritdoc}
     */
    public function create($source, array $set)
    {
        $source = $this->mapper->mapFrom($source);
        $collection = $this->conn->selectCollection($source[0], $source[1]);
        
        $set = $this->mapper->mapValue($set);

        return $collection->insert($set);
    }

    /**
     * {@inheritdoc}
     */
    public function read($source, array $fields, array $criteria, array $group = array(), array $order = array(), $offset = 0, $limit = 0)
    {
        $fields = $this->mapper->mapSelect($fields);

        $source = $this->mapper->mapFrom($source);
        $collection = $this->conn->selectCollection($source[0], $source[1]);
        
        $criteria = $this->mapper->mapWhere($criteria);
        if (!empty($criteria)) {
            $criteria = array_shift($criteria);
        }

        $order = $this->mapper->mapOrder($order);
        if (!empty($order)) {
            $collection->ensureIndex($order);
        }
        
        $data = $collection->find($criteria, $fields)->sort($order)->skip($offset);
        if ($limit > 0) {
            $data = $data->limit($limit);
        }
        $data = iterator_to_array($data);

        foreach ($data as &$d)
        {
            $d = $this->mapper->mapValue($d);
        }

        $fieldMap = array_flip($fields);
        foreach ($data as &$d)
        {
            foreach ($d as $key => $value)
            {
                if (isset($fieldMap[$key]) && is_string($fieldMap[$key])) {
                    $d[$fieldMap[$key]] = $value;
                    unset($d[$key]);
                }
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function update($source, array $criteria, array $set)
    {
        $source = $this->mapper->mapFrom($source);
        $collection = $this->conn->selectCollection($source[0], $source[1]);

        $criteria = $this->mapper->mapWhere($criteria);
        if (!empty($criteria)) {
            $criteria = array_shift($criteria);
        }
        
        $set = $this->mapper->mapValue($set);
        $set = array('$set' => $this->mapper->arrayToDot($set));
        
        return $collection->update($criteria, $set, array(
            'multiple' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function delete($source, array $criteria)
    {
        $source = $this->mapper->mapFrom($source);
        $collection = $this->conn->selectCollection($source[0], $source[1]);

        $criteria = $this->mapper->mapWhere($criteria);
        if (!empty($criteria)) {
            $criteria = array_shift($criteria);
        }
        
        return $collection->remove($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function count($source, array $criteria)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId($source)
    {
    }
}