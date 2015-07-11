<?php

namespace Cravid\Storage\Validator\Assertion;

abstract class Query extends Assertion
{
    /**
     * {@inheritdoc}
     */
    public function assert($value)
    {
        if (!is_array($value)) return false;
        if (!isset($value['database']) || empty($value['resource'])) return false;
        if (!isset($value['fields'])) return false;
        if (!$this->validateFilter($filter)) return false;
    }

    /**
     * Validates the query filter part.
     *
     * @param array $filter The filter.
     *
     * @return bool
     */
    protected function validateFilter(array $filter)
    {

    }
}