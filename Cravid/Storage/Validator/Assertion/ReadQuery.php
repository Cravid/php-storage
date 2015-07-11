<?php

namespace Cravid\Storage\Validator\Assertion;

class ReadQuery extends Assertion
{
    /**
     * {@inheritdoc}
     */
    public function assert($value)
    {
        if (!is_array($value)) return false;
        if (!isset($value['database']) || empty($value['resource'])) return false;
        if (!isset($value['fields'])) return false;
    }
}