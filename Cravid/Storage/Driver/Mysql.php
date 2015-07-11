<?php

namespace Cravid\Storage\Driver;

class Mysql implements \Cravid\Storage\DriverInterface
{
    /**
     * @var \Pdo
     */
    private $conn = null;

    
    /**
     * {@inheritDoc}
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
            throw new \Cravid\Storage\($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function execute(\Cravid\Storage\Query $query, \Cravid\Storage\ResultInterface $result = null)
    {
        if (null === $result) {
            $result = new \Cravid\Storage\Result();
        }

        // TODO: query translation

        return $result;
    }
}