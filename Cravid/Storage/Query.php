<?php

namespace Cravid\Storage;

class Query
{
    /**
     * The array of query parts collected.
     *
     * @var array
     */
    protected $queryParts = array(
        'database'      => '',
        'resource'      => '',
        'fields'        => array(),
        'filter'        => array(),
        'grouping'      => array(),
        'sorting'       => array(),
        'limit'         => false,
        'offset'        => 0,
    );

    /**
     * The type of the specific query.
     *
     * @var int
     */
    private $type = null;


    /**
     * Class may not be instantiated via constructor.
     */
    private function __construct() {}

    /**
     * Creates a create query instance.
     *
     * @return \Cravid\Storage\Query
     */
    public static function create()
    {
        $query = new self();
        $query->setType(QueryType::CREATE);

        return $query;
    }

    /**
     * Creates a read query instance.
     *
     * @return \Cravid\Storage\Query
     */
    public static function read()
    {
        $query = new self();
        $query->setType(QueryType::READ);

        return $query;
    }

    /**
     * Creates a update query instance.
     *
     * @return \Cravid\Storage\Query
     */
    public static function update()
    {
        $query = new self();
        $query->setType(QueryType::UPDATE);

        return $query;
    }

    /**
     * Creates a delete query instance.
     *
     * @return \Cravid\Storage\Query
     */
    public static function delete()
    {
        $query = new self();
        $query->setType(QueryType::DELETE);

        return $query;
    }

    /**
     * Sets the query type.
     *
     * @param int $type The query type.
     */
    public function setType($type = QueryType::READ)
    {
        $this->type = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function origin($database, $resource)
    {
        $this->queryParts['database'] = $database;
        $this->queryParts['resource'] = $resource;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function field($field, $alias)
    {
        $this->queryParts['fields'][] = array($alias => $fields);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function filter($connector, array $filter)
    {
        $this->queryParts['filter'][][$connector] = $filter;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function groupBy($field)
    {
        $this->queryParts['grouping'][] = $field;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function sortBy($field, $direction = SortType::ASC)
    {
        $this->sorting[] = array($field => $direction);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function limit($limit)
    {
        $this->queryParts['limit'] = (int)$limit;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function offset($offset)
    {
        $this->queryParts['offset'] = (int)$offset;

        return $this;
    }

    /**
     *
     */
    protected function validateQuery(Validator\ValidatorResolver $assertion = null)
    {
        if ()
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryParts()
    {
        return $this->queryParts;
    }
}