<?php

namespace Cravid\Storage\Validator\Assertion;

interface AssertionInterface
{
    /**
     * Confirms if the given value matches the assertion type.
     * 
     * @param mixed $value
     *
     * @return bool
     */
    public function assert($value);

    /**
     * Implements the magic method to use the class like a function.
     * 
     * @param mixed $value
     *
     * @return mixed
     */
    public function __invoke($value);
}