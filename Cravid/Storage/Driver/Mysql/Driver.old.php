<?php

namespace Cravid\Storage\Driver\Mysql;

class Driver implements \Cravid\Storage\DriverInterface
{
    /**
     * The database connection instance.
     * 
     * @var \Pdo
     */
    protected $conn = null;

    /**
     * @var \Cravid\Storage\Mapper
     */
    protected $mapper = null;


    /**
     * {@inheritdoc}
     */
    public function __construct(array $params)
    {
        $defaultParams = array(
            'host'          => '127.0.0.1',
            'port'          => 3306,
            'dbname'        => '',
            'user'          => 'root',
            'password'      => '',
        );
        $params = array_merge($defaultParams, $params);

        try {
            $dsn = 'mysql:host=' . $params['host'] . ';';
            if (!empty($params['dbname'])) {
                $dsn .= 'dbname=' . $params['dbname'] . ';';
            }
            $this->conn = new \PDO($dsn, $params['user'], $params['password'], array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ));
        }
        catch (\PDOException $e) {
            throw new \Cravid\Storage\DriverInterfaceException($e->getMessage(), $e->getCode(), $e);
        }

        $this->mapper = new Mapper();
    }

    /**
     * {@inheritdoc}
     */
    public function create($source, array $set)
    {
        $query['type'] = 'INSERT';
        $query['from'] = 'INTO ' . $this->mapper->mapFrom($source);
        $query['value'] = 'SET ' . $this->mapper->mapValue($set);

        $query = implode(' ', $query);

        return (bool)$this->conn->exec($query);
    }

    /**
     * {@inheritdoc}
     */
    public function read($source, array $fields, array $criteria, array $group = array(), array $order = array(), $offset = 0, $limit = 0)
    {
        $query['type'] = 'SELECT';
        $query['select'] = $this->mapper->mapSelect($fields);
        $query['from'] = 'FROM ' . $this->mapper->mapFrom($source);
        $query['where'] = $this->mapper->mapWhere($criteria);
        $query['group'] = $this->mapper->mapGroup($group);
        $query['order'] = $this->mapper->mapOrder($order);
        $query['limit'] = $this->mapper->mapLimit($offset, $limit);

        $query = implode(' ', array_filter($query));
        
        $sth = $this->conn->query($query);
        $data = $sth->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($data as &$d)
        {
            foreach ($d as $key => $value)
            {
                $tmp = json_decode($value, true);
                if (in_array(substr($value, 0, 1), array('{', '[')) && json_last_error() == JSON_ERROR_NONE) {
                    $d[$key] = $tmp;
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
        $query['type'] = 'UPDATE';
        $query['from'] = $this->mapper->mapFrom($source);
        $query['value'] = 'SET ' . $this->mapper->mapValue($set);
        $query['where'] = $this->mapper->mapWhere($criteria);

        $query = implode(' ', array_filter($query));

        return $this->conn->exec($query);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($source, array $criteria)
    {
    }
}