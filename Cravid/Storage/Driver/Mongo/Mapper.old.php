<?php

namespace Cravid\Storage\Driver\Mongo;

class Mapper
{
    /**
     */
    private $connectorMap = array(
        'and'   => '$and',
        'or'    => '$or',
    );

    /**
     */
    private $expressionMap = array(
        'eq'    => '$eq',
        'gt'    => '$gt',
        'gte'   => '$gte',
        'lt'    => '$lt',
        'lte'   => '$lte',
        'ne'    => '$ne',
        'like'  => '$like',
    );

    /**
     */
    private $fieldMap = array();
    

    /**
     */
    public function mapSelect(array $select)
    {
        $return = array();
        foreach ($select as $alias => $field)
        {
            $return[$field] = 1;
        }
        return $return;
    }

    /**
     * Converts the storage where syntax to the driver specific syntax.
     *
     * @param array $where The dependencies.
     *
     * @return array
     */
    public function mapFrom($from)
    {
        $from = preg_split('/(\.)/i', $from);
        if (count($from) !== 2) {
            throw new \Cravid\Storage\DriverInterfaceException();
        }
        return $from;
    }

    /**
     * Converts the storage where syntax to the driver specific syntax.
     *
     * @param array $where The dependencies.
     *
     * @return array
     */
    public function mapWhere(array $where)
    {
        $return = array();
        foreach ($where as $key => $set)
        {
            $key = strtolower($key);

            if (isset($this->connectorMap[$key])) {
                $return[] = array(
                    $this->connectorMap[$key] => $this->mapWhere($set)
                );
            } else {
                foreach ($set as $expr => $value)
                {
                    if (!isset($this->expressionMap[$expr])) {
                        throw new \UnexpectedValueException('Unexpected expression \'' . $expr . '\'');
                    }
                    $expr = $this->expressionMap[$expr];

                    $value = $this->mapField($key, $value);

                    switch ($expr)
                    {
                        case '$eq':
                            $return[] = array(
                                $key => $value
                            );
                            break;
                        case '$like':
                            $return[] = array(
                                $key => new \MongoRegex("/$value/")
                            );
                            break;
                        default:
                            $return[] = array(
                                $key => array(
                                    $expr => $value
                                )
                            );
                    }
                }
            }
        }
        return $return;
    }

    /**
     */
    private function mapField($key, $value)
    {
        if (isset($this->fieldMap[$key])) return $this->fieldMap[$key]($value);
        if (is_numeric($value)) return (float)$value;
        if (is_null($value)) return null;
        if ($value === 'true') return true;
        if ($value === 'false') return false;
        if ($value instanceof \MongoId) return (string)$value;
        if (\MongoId::isValid($value)) return new \MongoId($value);
        if (is_string($value)) return (string)$value;

        return $value;
    }

    /**
     */
    public function mapOrder(array $order)
    {
        array_walk($order, function (&$direction, $key) {
            if (strtolower($direction) === 'asc') $direction = 1;
            else if (strtolower($direction) === 'desc') $direction = -1;
            else
                throw new \UnexpectedValueException('Unexpected order value \'' . $key . ' => ' . $direction . '\'');
        });
        return $order;
    }

    /**
     */
    public function mapValue(array $values)
    {
        array_walk($values, function (&$value, $key) {
            $value = $this->mapField($key, $value);
        });
        return $values;
    }

    /**
     * Transforms an array to MongoDB dot-notation.
     *
     * @param array  $array  The array.
     * @param string $prefix The current dot-notation prefix.
     *
     * @return array
     */
    public function arrayToDot(array $array, $prefix = '')
    {
        foreach ($array as $key => $value)
        {
            unset($array[$key]);
            if (!is_array($value)) {
                $array[$prefix . $key] = $value;
            } else {
                foreach ($this->arrayToDot($value, $prefix . $key . '.') as $k => $v)
                {
                    $array[$k] = $v;
                }
            }
        }

        return $array;
    }
}