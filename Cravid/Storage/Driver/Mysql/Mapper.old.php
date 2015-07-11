<?php

namespace Cravid\Storage\Driver\Mysql;

class Mapper implements \Cravid\Storage\Mapper
{
    /**
     */
    private $connectorMap = array(
        'and'   => 'AND',
        'or'    => 'OR',
    );

    /**
     */
    private $expressionMap = array(
        'eq'    => '=',
        'gt'    => '>',
        'gte'   => '>=',
        'lt'    => '<',
        'lte'   => '<=',
        'ne'    => '!=',
        'like'  => 'like',
    );

    /**
     */
    protected $fieldMap = array();


    /**
     */
    public function mapSelect(array $select)
    {
        if (empty($select)) return '*';
        
        $return = array();
        foreach ($select as $key => $value)
        {
            $value = $this->escape($value);
            $return[] = $value . (is_string($key) && !empty($key) ? ' AS ' . $key : '');
        }
        return implode(', ', $return);
    }

    /**
     */
    public function escape($value)
    {
        $regex = '/.+\((.*?)\)/';
        $result = preg_match($regex, $value, $matches);
        if (false === $result) throw new \Exception();
        elseif (1 === $result) return $value;

        $parts = explode('.', $value);
        foreach ($parts as &$part)
        {
            $part = '`' . $part . '`';
        }
        return implode('.', $parts);
    }

    /**
     */
    public function mapFrom($from)
    {
        $from = preg_split('/(\s|\sas\s)/i', $from);
        if (count($from) === 2) {
            return $from[0] . ' AS ' . $from[1];
        } else if (count($from) === 3) {
            return $from[0] . ' AS ' . $from[2];
        }
        return $from[0];
    }

    /**
     */
    public function mapWhere(array $where)
    {
        $f = function ($where) use (&$f) {
            $return = array();
            foreach ($where as $key => $set)
            {
                $key = strtolower($key);

                if (isset($this->connectorMap[$key])) {
                    $return[] = '(' . implode(' ' . $this->connectorMap[$key] . ' ', $f($set)) . ')';
                } else {
                    $tmp = array();
                    foreach ($set as $expr => $value)
                    {
                        if (!isset($this->expressionMap[$expr])) {
                            throw new \UnexpectedValueException('Unexpected expression \'' . $expr . '\'');
                        }
                        $expr = $this->expressionMap[$expr];
                        
                        $value = $this->mapField($key, $value);
                        
                        if (is_null($value)) {
                            if ($expr === '=') $expr = 'IS';
                            if ($expr === '!=') $expr = 'IS NOT';
                        }
                        
                        $tmp[] = '(`' . $key . '` ' . $expr . ' ' . $value . ')';
                    }
                    $return[] = '(' . implode(' AND ', $tmp) . ')';
                }
            }
            return $return;
        };
        $result = $f($where);
        return empty($result) ? '' : 'WHERE ' . array_shift($result);
    }

    /**
     */
    private function mapField($key, $value)
    {
        if (isset($this->fieldMap[$key])) return $this->fieldMap[$key]($value);
        if (is_numeric($value)) return (float)$value;
        if (is_null($value)) return 'null';
        if ($value === 'true') return 'true';
        if ($value === 'false') return 'false';
        if (is_string($value)) return '\'' . $value . '\'';

        return $value;
    }

    /**
     */
    public function mapGroup(array $group)
    {
        $return = array();
        foreach ($group as $key => $value)
        {
            $return[] = '`' . $value . '`';
        }
        return empty($return) ? '' : 'GROUP BY ' . implode(', ', $return);
    }

    /**
     */
    public function mapOrder(array $order)
    {
        foreach ($order as $key => $value)
        {
            $return[] = $key . ' ' . $value;
        }
        return empty($return) ? '' : 'ORDER BY ' . implode(', ', $return);
    }

    /**
     */
    public function mapLimit($offset, $limit)
    {
        if ($limit > 0) {
            return 'LIMIT ' . $offset . ',' . $limit;
        }
        return '';
    }

    /**
     */
    public function mapValue(array $values)
    {
        foreach ($values as $key => $value)
        {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $set[] = $key . ' = ' . $this->mapField($key, $value);
        }

        return implode(', ', $set);
    }
}